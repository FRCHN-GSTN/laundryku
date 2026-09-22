<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\ServiceModel;
use App\Models\PromotionsModel;
use App\Models\PaymentModel;

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
        $promoCode = trim($this->request->getPost('promo_code') ?? '');

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

        // Apply promo code
        $discountAmount = 0;
        $promoId = null;
        $finalPrice = $totalPrice;

        if (!empty($promoCode)) {
            $promoModel = new PromotionsModel();
            $promoResult = $promoModel->validateCode($promoCode, $totalPrice);

            if ($promoResult['valid']) {
                $discountAmount = $promoResult['discount'];
                $promoId = $promoResult['promo']['id'];
                $finalPrice = $totalPrice - $discountAmount;
            } else {
                return redirect()->back()->withInput()->with('error', $promoResult['message']);
            }
        }

        $orderData = [
            'order_code' => $this->orderModel->generateOrderCode(),
            'user_id' => $userId,
            'total_weight' => $totalWeight > 0 ? $totalWeight : null,
            'total_price' => $totalPrice,
            'discount_amount' => $discountAmount,
            'final_price' => $finalPrice,
            'promo_id' => $promoId,
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

            // Increment promo usage
            if ($promoId) {
                $promoModel->incrementUsage($promoId);
            }

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
            'statusHistory' => $this->orderModel->getStatusHistory($orderId),
            'pageTitle' => 'Detail Pesanan',
        ];

        return view('customer/order_detail', $data);
    }

    public function cancelOrder($orderId)
    {
        $userId = session()->get('user_id');
        $order = $this->orderModel->where('id', $orderId)->where('user_id', $userId)->first();

        if (!$order || !in_array($order['status'], ['pending', 'confirmed'])) {
            return redirect()->back()->with('error', 'Tidak bisa membatalkan pesanan');
        }

        $userName = session()->get('user_name') ?: 'Pelanggan';
        $this->orderModel->updateStatus($orderId, 'cancelled', $userName, 'Dibatalkan oleh pelanggan');

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

    public function uploadPaymentProof($orderId)
    {
        $userId = session()->get('user_id');
        $order = $this->orderModel->where('id', $orderId)->where('user_id', $userId)->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan');
        }

        $proofFile = $this->request->getFile('proof_image');

        if (!$proofFile || !$proofFile->isValid() || $proofFile->hasMoved()) {
            return redirect()->back()->with('error', 'File bukti pembayaran tidak valid');
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($proofFile->getMimeType(), $allowedTypes)) {
            return redirect()->back()->with('error', 'Format file harus JPG, PNG, atau WebP');
        }

        if ($proofFile->getSize() > 5 * 1024 * 1024) {
            return redirect()->back()->with('error', 'Ukuran file maksimal 5MB');
        }

        $uploadPath = FCPATH . 'uploads/proofs';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $newName = 'proof_' . $orderId . '_' . time() . '.' . $proofFile->getExtension();
        $proofFile->move($uploadPath, $newName);

        $paymentModel = new PaymentModel();
        $payment = $paymentModel->getPaymentByOrder($orderId);

        if ($payment) {
            $paymentModel->update($payment['id'], ['proof_image' => '/uploads/proofs/' . $newName]);
        } else {
            $paymentModel->insert([
                'order_id' => $orderId,
                'amount' => $order['confirmed_price'] ?? $order['total_price'],
                'payment_method' => 'transfer',
                'payment_date' => date('Y-m-d H:i:s'),
                'status' => 'pending',
                'proof_image' => '/uploads/proofs/' . $newName,
            ]);
        }

        return redirect()->to('/customer/orders/' . $orderId)->with('success', 'Bukti pembayaran berhasil diupload');
    }
}
