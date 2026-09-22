<?php

namespace App\Controllers;

use App\Models\OrderModel;

class AdminExport extends BaseController
{
    public function orders()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');

        $orderModel = new OrderModel();
        $orders = $orderModel->getOrdersForExport($startDate, $endDate);

        $filename = "laporan_{$startDate}_{$endDate}.csv";

        header('Content-Type: text/csv; charset=utf-8');
        header("Content-Disposition: attachment; filename=\"{$filename}\"");

        $output = fopen('php://output', 'w');

        // BOM for Excel UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Header
        fputcsv($output, [
            'Kode Order', 'Tanggal', 'Pelanggan', 'Telepon', 'Email',
            'Layanan', 'Berat', 'Harga Awal', 'Diskon', 'Harga Final',
            'Status', 'Pembayaran', 'Metode Bayar',
        ]);

        // Rows
        $orderItemModel = new \App\Models\OrderItemModel();
        foreach ($orders as $order) {
            $items = $orderItemModel->getOrderItems($order['id']);
            $serviceNames = implode(', ', array_column($items, 'service_name'));

            fputcsv($output, [
                $order['order_code'],
                date('d/m/Y H:i', strtotime($order['created_at'])),
                $order['user_name'],
                $order['user_phone'] ?? '-',
                $order['user_email'] ?? '-',
                $serviceNames,
                $order['confirmed_weight'] ?? $order['total_weight'] ?? '-',
                number_format($order['total_price'], 0, ',', '.'),
                number_format($order['discount_amount'] ?? 0, 0, ',', '.'),
                number_format($order['confirmed_price'] ?? $order['total_price'], 0, ',', '.'),
                $order['status'],
                '-',
                '-',
            ]);
        }

        fclose($output);
        exit;
    }
}
