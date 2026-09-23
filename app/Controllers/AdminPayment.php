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

        if ($order['status'] === 'cancelled') {
            return redirect()->back()->with('error', 'Pesanan dibatalkan');
        }

        $paymentMethod = $this->request->getPost('payment_method');

        if (!in_array($paymentMethod, ['cash', 'qris'], true)) {
            return redirect()->back()->with('error', 'Metode pembayaran tidak valid');
        }

        $amount = \App\Models\OrderModel::billableAmount($order);
        $existingPayment = $this->paymentModel->getPaymentByOrder($orderId);

        if ($existingPayment && ($existingPayment['status'] ?? '') === 'paid') {
            return redirect()->back()->with('error', 'Pembayaran sudah lunas — tidak bisa override metode');
        }

        // Samakan dengan customer: cash & qris sama-sama pending sampai Tandai Lunas.
        $paymentData = [
            'order_id' => $orderId,
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'status' => 'pending',
            'payment_date' => null,
        ];

        if ($existingPayment) {
            $this->paymentModel->update($existingPayment['id'], $paymentData);
        } else {
            $this->paymentModel->insert($paymentData);
        }

        // Status order TIDAK diubah otomatis — dikelola lewat menu Update Status.
        $msg = $paymentMethod === 'cash'
            ? 'Metode cash disimpan. Tandai lunas saat uang diterima.'
            : 'Metode QRIS disimpan. Tandai lunas setelah dana diterima.';

        return redirect()->to('/admin/orders/' . $orderId . '/payment')->with('success', $msg);
    }

    public function markPaid($orderId)
    {
        $order = $this->orderModel->find($orderId);

        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan');
        }

        if ($order['status'] === 'cancelled') {
            return redirect()->back()->with('error', 'Pesanan dibatalkan');
        }

        $payment = $this->paymentModel->getPaymentByOrder($orderId);

        if (!$payment) {
            return redirect()->back()->with('error', 'Belum ada metode pembayaran. Pilih cash atau QRIS dulu.');
        }

        if ($payment['status'] === 'paid') {
            return redirect()->back()->with('error', 'Pembayaran sudah lunas');
        }

        $this->paymentModel->update($payment['id'], [
            'amount' => \App\Models\OrderModel::billableAmount($order),
            'status' => 'paid',
            'payment_date' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/orders/' . $orderId . '/payment')
            ->with('success', 'Pembayaran ditandai lunas');
    }
}
