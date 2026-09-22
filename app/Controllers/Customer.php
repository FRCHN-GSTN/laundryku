<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\ServiceModel;

class Customer extends BaseController
{
    protected $orderModel;
    protected $orderItemModel;
    protected $serviceModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->serviceModel = new ServiceModel();
    }

    public function dashboard()
    {
        $userId = session()->get('user_id');
        $data = [
            'orders' => $this->orderModel->getUserOrders($userId),
            'stats' => $this->orderModel->getStats($userId),
            'pageTitle' => 'Dashboard',
        ];

        return view('customer/dashboard', $data);
    }

    public function newOrder()
    {
        $data = [
            'services' => $this->serviceModel->getActiveServices(),
            'pageTitle' => 'Order Baru',
        ];

        return view('customer/new_order', $data);
    }

    public function createOrder()
    {
        $userId = session()->get('user_id');
        $services = $this->request->getPost('services');
        $quantities = $this->request->getPost('quantities');
        $deliveryType = $this->request->getPost('delivery_type');
        $deliveryAddress = $this->request->getPost('delivery_address');
        $notes = $this->request->getPost('notes');

        if (empty($services) || !is_array($services)) {
            return redirect()->back()->withInput()->with('error', 'Pilih minimal satu layanan');
        }

        $totalPrice = 0;
        $totalWeight = 0;
        $orderItems = [];

        foreach ($services as $serviceId) {
            $service = $this->serviceModel->find($serviceId);
            if (!$service || !$service['is_active']) {
                return redirect()->back()->withInput()->with('error', 'Layanan tidak valid atau sudah tidak tersedia');
            }

            $quantity = (float) ($quantities[$serviceId] ?? 1);
            if ($quantity < 0.5) {
                return redirect()->back()->withInput()->with('error', 'Jumlah minimal 0.5 untuk layanan: ' . $service['name']);
            }

            $subtotal = $service['price'] * $quantity;
            $totalPrice += $subtotal;
            if ($service['unit'] === 'kg') {
                $totalWeight += $quantity;
            }

            $orderItems[] = [
                'service_id' => $serviceId,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];
        }

        if ($totalPrice <= 0) {
            return redirect()->back()->withInput()->with('error', 'Total harga harus lebih dari 0');
        }

        $orderData = [
            'order_code' => $this->orderModel->generateOrderCode(),
            'user_id' => $userId,
            'total_weight' => $totalWeight > 0 ? $totalWeight : null,
            'total_price' => $totalPrice,
            'delivery_type' => $deliveryType,
            'delivery_address' => $deliveryType === 'delivery' ? $deliveryAddress : null,
            'notes' => $notes,
            'status' => 'pending',
        ];

        $orderId = $this->orderModel->insert($orderData);

        if ($orderId) {
            foreach ($orderItems as &$item) {
                $item['order_id'] = $orderId;
            }
            $this->orderItemModel->insertBatch($orderItems);

            return redirect()->to('/customer/orders/' . $orderId)->with('success', 'Pesanan berhasil dibuat!');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal membuat pesanan');
    }

    public function orders()
    {
        $userId = session()->get('user_id');
        $data = [
            'orders' => $this->orderModel->getUserOrders($userId),
            'pageTitle' => 'Riwayat Pesanan',
        ];

        return view('customer/orders', $data);
    }

    public function orderDetail($orderId)
    {
        $userId = session()->get('user_id');
        $order = $this->orderModel->where('id', $orderId)->where('user_id', $userId)->first();

        if (!$order) {
            return redirect()->to('/customer/orders')->with('error', 'Pesanan tidak ditemukan');
        }

        $data = [
            'order' => $order,
            'items' => $this->orderItemModel->getOrderItems($orderId),
            'pageTitle' => 'Detail Pesanan',
        ];

        return view('customer/order_detail', $data);
    }

    public function cancelOrder($orderId)
    {
        $userId = session()->get('user_id');
        $order = $this->orderModel->where('id', $orderId)->where('user_id', $userId)->first();

        if (!$order || $order['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Tidak bisa membatalkan pesanan');
        }

        $this->orderModel->update($orderId, ['status' => 'cancelled']);

        return redirect()->to('/customer/orders/' . $orderId)->with('success', 'Pesanan dibatalkan');
    }

    public function profile()
    {
        $userId = session()->get('user_id');
        $userModel = new \App\Models\UserModel();
        $data = [
            'user' => $userModel->find($userId),
            'pageTitle' => 'Profil',
        ];

        return view('customer/profile', $data);
    }

    public function updateProfile()
    {
        $userId = session()->get('user_id');
        $userModel = new \App\Models\UserModel();

        $rules = [
            'name' => 'required|min_length[3]',
            'phone' => 'required|min_length[10]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userData = [
            'name' => $this->request->getPost('name'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
        ];

        $userModel->update($userId, $userData);
        session()->set('user_name', $userData['name']);

        return redirect()->to('/customer/profile')->with('success', 'Profil berhasil diupdate');
    }
}
