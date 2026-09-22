<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;

class Track extends BaseController
{
    public function index()
    {
        return view('track/search');
    }

    public function lookup()
    {
        $code = trim($this->request->getPost('order_code'));

        if (empty($code)) {
            return redirect()->back()->with('error', 'Masukkan kode pesanan');
        }

        $orderModel = new OrderModel();
        $order = $orderModel->where('order_code', $code)->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan');
        }

        $items = (new OrderItemModel())->getOrderItems($order['id']);

        $data = [
            'order' => $order,
            'items' => $items,
            'statusHistory' => $orderModel->getStatusHistory($order['id']),
        ];

        return view('track/result', $data);
    }
}
