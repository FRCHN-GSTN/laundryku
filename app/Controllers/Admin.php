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
        $builder->select('orders.*, users.name as user_name, users.phone as user_phone');
        $builder->join('users', 'users.id = orders.user_id');

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

        $confirmedWeight = (float) $this->request->getPost('confirmed_weight');
        $confirmedPrice = (float) $this->request->getPost('confirmed_price');

        if ($confirmedWeight <= 0 || $confirmedPrice <= 0) {
            return redirect()->back()->with('error', 'Berat dan harga harus lebih dari 0');
        }

        $this->orderModel->confirmWeight($orderId, $confirmedWeight, $confirmedPrice);

        // Log the weight confirmation
        $userName = session()->get('user_name') ?: 'Admin';
        $historyModel = new \App\Models\OrderStatusHistoryModel();
        $historyModel->insert([
            'order_id'   => $orderId,
            'old_status' => $order['status'],
            'new_status' => $order['status'],
            'note'       => "Konfirmasi berat: {$confirmedWeight}kg, Harga: Rp " . number_format($confirmedPrice, 0, ',', '.'),
            'changed_by' => $userName,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/orders/' . $orderId)->with('success', 'Berat dan harga berhasil dikonfirmasi');
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
        if ($this->request->getMethod() === 'post') {
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

        if ($this->request->getMethod() === 'post') {
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
                                                ->select('SUM(orders.total_price) as total')
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
