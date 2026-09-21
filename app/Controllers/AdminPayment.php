<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\OrderItemModel;
use App\Models\UserModel;
use App\Models\SettingsModel;
use App\Libraries\Qris;

class AdminPayment extends BaseController
{
    protected $orderModel;
    protected $paymentModel;
    protected $orderItemModel;
    protected $userModel;
    protected $settingsModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->paymentModel = new PaymentModel();
        $this->orderItemModel = new OrderItemModel();
        $this->userModel = new UserModel();
        $this->settingsModel = new SettingsModel();
    }

    public function showPayment($orderId)
    {
        $order = $this->orderModel->find($orderId);

        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan');
        }

        $payment = $this->paymentModel->getPaymentByOrder($orderId);

        // Load QRIS settings
        $qrisString = $this->settingsModel->getValue('qris_static_string');
        $qrisImage = $this->settingsModel->getValue('qris_image');

        $data = [
            'order' => $order,
            'payment' => $payment,
            'items' => $this->orderItemModel->getOrderItems($orderId),
            'user' => $this->userModel->find($order['user_id']),
            'qris_string' => $qrisString,
            'qris_image' => $qrisImage,
            'pageTitle' => 'Pembayaran Order #' . $order['order_code'],
        ];

        return view('admin/payment', $data);
    }

    public function processPayment($orderId)
    {
        $order = $this->orderModel->find($orderId);

        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan');
        }

        $paymentMethod = $this->request->getPost('payment_method');

        if (!in_array($paymentMethod, ['cash', 'qris'])) {
            return redirect()->back()->with('error', 'Metode pembayaran tidak valid');
        }

        $paymentData = [
            'order_id' => $orderId,
            'amount' => $order['total_price'],
            'payment_method' => $paymentMethod,
            'payment_date' => date('Y-m-d H:i:s'),
            'status' => $paymentMethod === 'cash' ? 'paid' : 'pending',
        ];

        // Check if payment already exists
        $existingPayment = $this->paymentModel->getPaymentByOrder($orderId);

        if ($existingPayment) {
            $this->paymentModel->update($existingPayment['id'], $paymentData);
        } else {
            $this->paymentModel->insert($paymentData);
        }

        // Update order status if cash payment
        if ($paymentMethod === 'cash') {
            $this->orderModel->update($orderId, ['status' => 'completed']);
        }

        return redirect()->to('/admin/orders/' . $orderId . '/payment')->with('success', 'Pembayaran berhasil diproses');
    }
}
