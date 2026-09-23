<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\ServiceModel;
use App\Models\UserModel;

class Admin extends BaseController
{
    protected $orderModel;
    protected $orderItemModel;
    protected $serviceModel;
    protected $userModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->serviceModel = new ServiceModel();
        $this->userModel = new UserModel();
    }

    public function dashboard()
    {
        $data = [
            'stats' => $this->orderModel->getStats(),
            'todayOrders' => $this->orderModel->getTodayOrders(),
            'todayRevenue' => $this->orderModel->getTodayRevenue(),
            'pendingOrders' => $this->orderModel->where('status', 'pending')->orderBy('created_at', 'ASC')->findAll(),
            'pageTitle' => 'Dashboard',
        ];

        return view('admin/dashboard', $data);
    }

    public function orders()
    {
        $status = $this->request->getGet('status');
        $search = $this->request->getGet('search');

        $builder = $this->orderModel->builder();
        $builder->select('orders.*, users.name as user_name, users.phone as user_phone, payments.status as payment_status, payments.payment_method');
        $builder->join('users', 'users.id = orders.user_id');
        $builder->join('payments', 'payments.order_id = orders.id', 'left');

        if ($status) {
            $builder->where('orders.status', $status);
        }

        if ($search) {
            $builder->groupStart();
            $builder->like('orders.order_code', $search);
            $builder->orLike('users.name', $search);
            $builder->orLike('users.phone', $search);
            $builder->groupEnd();
        }

        $builder->orderBy('orders.created_at', 'DESC');
        $orders = $builder->get()->getResultArray();

        $data = [
            'orders' => $orders,
            'currentStatus' => $status,
            'search' => $search,
            'pageTitle' => 'Pesanan',
        ];

        return view('admin/orders', $data);
    }

    public function orderDetail($orderId)
    {
        $order = $this->orderModel->find($orderId);

        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan');
        }

        $data = [
            'order' => $order,
            'items' => $this->orderItemModel->getOrderItems($orderId),
            'user' => $this->userModel->find($order['user_id']),
            'statusHistory' => $this->orderModel->getStatusHistory($orderId),
            'payment' => (new \App\Models\PaymentModel())->getPaymentByOrder($orderId),
            'validNextStatuses' => $this->orderModel->isValidTransition($order['status'], '')
                ? array_keys(array_filter(
                    \ReflectionClass::getConstantValue($this->orderModel, 'validTransitions')[$order['status']] ?? [],
                    fn($v) => $v !== null
                ))
                : [],
            'pageTitle' => 'Detail Pesanan #' . $order['order_code'],
        ];

        // Get allowed next statuses from the model's valid transitions
        $ref = new \ReflectionClass($this->orderModel);
        $transitions = $ref->getProperty('validTransitions');
        $transitions->setAccessible(true);
        $allTransitions = $transitions->getValue($this->orderModel);
        $data['validNextStatuses'] = $allTransitions[$order['status']] ?? [];

        return view('admin/order_detail', $data);
    }

    public function updateStatus($orderId)
    {
        $status = $this->request->getPost('status');
        $note = $this->request->getPost('status_note');

        $userName = session()->get('user_name') ?: 'Admin';

        if (!$this->orderModel->updateStatus($orderId, $status, $userName, $note)) {
            $order = $this->orderModel->find($orderId);
            if (!$order) {
                return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan');
            }
            return redirect()->back()->with('error', 'Transisi status tidak valid dari "' .
                (OrderModel::$statusLabels[$order['status']] ?? $order['status']) . '" ke "' .
                (OrderModel::$statusLabels[$status] ?? $status) . '"');
        }

        return redirect()->to('/admin/orders/' . $orderId)->with('success', 'Status pesanan berhasil diupdate');
    }

    public function confirmWeight($orderId)
    {
        $order = $this->orderModel->find($orderId);

        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan');
        }

        if (!in_array($order['status'], ['pending', 'confirmed'], true)) {
            return redirect()->back()->with('error', 'Konfirmasi berat hanya untuk pesanan menunggu/dikonfirmasi');
        }

        $confirmedWeight = (float) $this->request->getPost('confirmed_weight');

        if ($confirmedWeight <= 0) {
            return redirect()->back()->with('error', 'Berat harus lebih dari 0');
        }

        if ($confirmedWeight > 500) {
            return redirect()->back()->with('error', 'Berat maksimal 500 kg');
        }

        $calc = $this->calculateConfirmedPrice($order, $confirmedWeight);

        if ($calc['gross'] <= 0) {
            return redirect()->back()->with('error', 'Harga final harus lebih dari 0');
        }

        $postPrice = $this->request->getPost('confirmed_price');
        $autoNet = $calc['net'];
        $confirmedPrice = $autoNet;

        if ($postPrice !== null && $postPrice !== '') {
            $manual = (float) $postPrice;
            $discount = (float) ($order['discount_amount'] ?? 0);
            // Manual price = net (setelah diskon), valid 1..gross
            if ($manual > 0 && $manual <= $calc['gross']) {
                $confirmedPrice = $manual;
            } elseif ($manual <= 0) {
                return redirect()->back()->with('error', 'Harga final setelah diskon harus lebih dari 0');
            }
        }

        if ($confirmedPrice <= 0) {
            return redirect()->back()->with('error', 'Harga final setelah diskon harus lebih dari 0');
        }

        $this->syncWeightItems($order, $confirmedWeight, $calc['estWeight'], $calc['weightPart']);

        $this->orderModel->confirmWeight($orderId, $confirmedWeight, $confirmedPrice);

        $userName = session()->get('user_name') ?: 'Admin';
        $historyModel = new \App\Models\OrderStatusHistoryModel();
        $priceNote = abs($confirmedPrice - $autoNet) > 0.01
            ? sprintf('Harga manual Rp %s (otomatis Rp %s)', number_format($confirmedPrice, 0, ',', '.'), number_format($autoNet, 0, ',', '.'))
            : sprintf('Harga: Rp %s%s', number_format($confirmedPrice, 0, ',', '.'), $calc['discount'] > 0 ? ' (setelah diskon Rp ' . number_format($calc['discount'], 0, ',', '.') . ')' : '');
        $note = sprintf('Konfirmasi berat: %s kg, %s', $confirmedWeight, $priceNote);
        $historyModel->insert([
            'order_id'   => $orderId,
            'old_status' => $order['status'],
            'new_status' => $order['status'],
            'note'       => $note,
            'changed_by' => $userName,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/orders/' . $orderId)->with('success', 'Berat dan harga berhasil dikonfirmasi');
    }

    /**
     * Hitung harga dari berat aktual.
     * - Item kg diskalakan ke berat aktual
     * - Item pcs tetap
     * - Diskon promo dipotong dari gross → net = yang ditagihkan
     *
     * @return array{gross: float, discount: float, net: float, estWeight: float, weightPart: float}
     */
    private function calculateConfirmedPrice(array $order, float $confirmedWeight): array
    {
        $items = $this->orderItemModel->getOrderItems($order['id']);

        $weightPart = 0.0;
        $fixedPart  = 0.0;
        $estWeight  = 0.0;

        foreach ($items as $item) {
            $subtotal = (float) $item['subtotal'];
            $quantity = (float) $item['quantity'];

            if (($item['unit'] ?? '') === 'kg') {
                $weightPart += $subtotal;
                $estWeight  += $quantity;
            } else {
                $fixedPart += $subtotal;
            }
        }

        if ($estWeight <= 0 && ! empty($order['total_weight'])) {
            $estWeight  = (float) $order['total_weight'];
            $weightPart = (float) $order['total_price'];
            $fixedPart  = 0.0;
        }

        if ($estWeight > 0) {
            $gross = round($weightPart * ($confirmedWeight / $estWeight) + $fixedPart);
        } else {
            $gross = round((float) $order['total_price']);
        }

        // Terapkan diskon yang sudah disetujui saat order (dibatasi gross)
        $discount = min((float) ($order['discount_amount'] ?? 0), $gross);
        $net = max(0, $gross - $discount);

        return [
            'gross'     => $gross,
            'discount'  => $discount,
            'net'       => $net,
            'estWeight' => $estWeight,
            'weightPart'=> $weightPart,
        ];
    }

    /**
     * Update quantity & subtotal item kg agar konsisten dengan berat aktual.
     */
    private function syncWeightItems(array $order, float $confirmedWeight, float $estWeight, float $weightPart): void
    {
        if ($estWeight <= 0) {
            return;
        }

        $scale = $confirmedWeight / $estWeight;
        $items = $this->orderItemModel->getOrderItems($order['id']);

        foreach ($items as $item) {
            if (($item['unit'] ?? '') !== 'kg') {
                continue;
            }

            $newQty = round((float) $item['quantity'] * $scale, 2);
            $newSub = round((float) $item['subtotal'] * $scale);

            $this->orderItemModel->update($item['id'], [
                'quantity' => $newQty,
                'subtotal' => $newSub,
            ]);
        }
    }

    public function updateAdminNotes($orderId)
    {
        $adminNotes = $this->request->getPost('admin_notes');

        $this->orderModel->update($orderId, ['admin_notes' => $adminNotes]);

        return redirect()->to('/admin/orders/' . $orderId)->with('success', 'Catatan admin berhasil disimpan');
    }

    public function services()
    {
        $data = [
            'services' => $this->serviceModel->findAll(),
            'pageTitle' => 'Layanan',
        ];

        return view('admin/services', $data);
    }

    public function createService()
    {
        if ($this->request->is('post')) {
            $rules = [
                'name' => 'required',
                'price' => 'required|numeric',
                'unit' => 'required|in_list[kg,pcs]',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $serviceData = [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'price' => $this->request->getPost('price'),
                'unit' => $this->request->getPost('unit'),
                'is_active' => true,
            ];

            $imageFile = $this->request->getFile('image');
            if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
                $uploadPath = FCPATH . 'uploads/services';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $newName = 'service_' . time() . '.' . $imageFile->getExtension();
                $imageFile->move($uploadPath, $newName);
                $serviceData['image'] = '/uploads/services/' . $newName;
            }

            $this->serviceModel->insert($serviceData);

            return redirect()->to('/admin/services')->with('success', 'Layanan berhasil ditambahkan');
        }

        return view('admin/service_form', ['pageTitle' => 'Tambah Layanan']);
    }

    public function editService($serviceId)
    {
        $service = $this->serviceModel->find($serviceId);

        if (!$service) {
            return redirect()->to('/admin/services')->with('error', 'Layanan tidak ditemukan');
        }

        if ($this->request->is('post')) {
            $rules = [
                'name' => 'required',
                'price' => 'required|numeric',
                'unit' => 'required|in_list[kg,pcs]',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $serviceData = [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'price' => $this->request->getPost('price'),
                'unit' => $this->request->getPost('unit'),
                'is_active' => $this->request->getPost('is_active') === '1',
            ];

            $imageFile = $this->request->getFile('image');
            if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
                $uploadPath = FCPATH . 'uploads/services';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $newName = 'service_' . time() . '.' . $imageFile->getExtension();
                $imageFile->move($uploadPath, $newName);
                $serviceData['image'] = '/uploads/services/' . $newName;
            }

            $this->serviceModel->update($serviceId, $serviceData);

            return redirect()->to('/admin/services')->with('success', 'Layanan berhasil diupdate');
        }

        $data = [
            'service' => $service,
            'pageTitle' => 'Edit Layanan',
        ];

        return view('admin/service_form', $data);
    }

    public function deleteService($serviceId)
    {
        $this->serviceModel->delete($serviceId);
        return redirect()->to('/admin/services')->with('success', 'Layanan berhasil dihapus');
    }

    public function customers()
    {
        $data = [
            'customers' => $this->userModel->getCustomers(),
            'pageTitle' => 'Pelanggan',
        ];

        return view('admin/customers', $data);
    }

    public function customerDetail($customerId)
    {
        $customer = $this->userModel->find($customerId);

        if (!$customer) {
            return redirect()->to('/admin/customers')->with('error', 'Pelanggan tidak ditemukan');
        }

        $data = [
            'customer' => $customer,
            'orders' => $this->orderModel->where('user_id', $customerId)->orderBy('created_at', 'DESC')->findAll(),
            'pageTitle' => 'Detail Pelanggan',
        ];

        return view('admin/customer_detail', $data);
    }

    public function reports()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');

        $data = [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'orders' => $this->orderModel->where('DATE(orders.created_at) >=', $startDate)
                                         ->where('DATE(orders.created_at) <=', $endDate)
                                         ->where('orders.status', 'completed')
                                         ->getOrdersWithUser(),
            'totalRevenue' => $this->orderModel->where('DATE(orders.created_at) >=', $startDate)
                                                ->where('DATE(orders.created_at) <=', $endDate)
                                                ->where('orders.status', 'completed')
                                                ->select('SUM(COALESCE(orders.confirmed_price, orders.final_price, orders.total_price)) as total')
                                                ->first(),
            'pageTitle' => 'Laporan',
        ];

        return view('admin/reports', $data);
    }

    public function revenueChart()
    {
        $data = $this->orderModel->getRevenueChart();
        return $this->response->setJSON($data);
    }

    public function statusChart()
    {
        $result = $this->orderModel->select('status, COUNT(*) as count')
                                   ->where('status !=', 'cancelled')
                                   ->groupBy('status')
                                   ->findAll();

        $labels = [];
        $values = [];
        $statusLabels = \App\Models\OrderModel::$statusLabels;

        foreach ($result as $row) {
            $labels[] = $statusLabels[$row['status']] ?? $row['status'];
            $values[] = (int) $row['count'];
        }

        return $this->response->setJSON(['labels' => $labels, 'values' => $values]);
    }
}
