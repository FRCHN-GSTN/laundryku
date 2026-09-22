<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\PaymentModel;
use App\Models\UserModel;
use App\Models\SettingsModel;

class Invoice extends BaseController
{
    public function print($orderId)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->find($orderId);

        if (!$order) {
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan');
        }

        $items = (new OrderItemModel())->getOrderItems($orderId);
        $user = (new UserModel())->find($order['user_id']);
        $payment = (new PaymentModel())->getPaymentByOrder($orderId);
        $settings = new SettingsModel();

        $data = [
            'order' => $order,
            'items' => $items,
            'user' => $user,
            'payment' => $payment,
            'company' => [
                'name' => $settings->getValue('company_name') ?? 'Laundryku',
                'address' => $settings->getValue('company_address') ?? '',
                'phone' => $settings->getValue('company_phone') ?? '',
                'whatsapp' => $settings->getValue('company_whatsapp') ?? '',
            ],
        ];

        return view('invoice', $data);
    }
}
