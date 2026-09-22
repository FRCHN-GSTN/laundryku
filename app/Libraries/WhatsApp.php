<?php

namespace App\Libraries;

class WhatsApp
{
    /**
     * Generate WhatsApp URL with pre-filled message
     */
    public static function sendMessage(string $phone, string $message): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Ensure Indonesian format
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        $encoded = urlencode($message);
        return "https://wa.me/{$phone}?text={$encoded}";
    }

    /**
     * Generate order status notification message
     */
    public static function orderStatusMessage(array $order, string $newStatus, string $shopName = 'Laundryku'): string
    {
        $statusLabels = [
            'pending'   => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'washing'   => 'Sedang Dicuci',
            'drying'    => 'Sedang Dijemur',
            'ironing'   => 'Sedang Disetrika',
            'ready'     => 'Siap Diambil/Diantar',
            'delivered' => 'Sudah Diantar/Diambil',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        $status = $statusLabels[$newStatus] ?? $newStatus;
        $price = number_format($order['confirmed_price'] ?? $order['total_price'], 0, ',', '.');
        $estDate = $order['estimated_date'] ? date('d/m/Y', strtotime($order['estimated_date'])) : '-';

        $msg = "Halo! 👋\n";
        $msg .= "Pesanan Anda di *{$shopName}*\n\n";
        $msg .= "📦 Kode: *{$order['order_code']}*\n";
        $msg .= "📋 Status: *{$status}*\n";

        if ($newStatus === 'ready') {
            $msg .= "💰 Total: *Rp {$price}*\n";
            $msg .= "📅 Estimasi: {$estDate}\n\n";
            $msg .= "Silakan ambil pesanan Anda atau tunggu diantar. Terima kasih! 🙏";
        } elseif ($newStatus === 'completed') {
            $msg .= "💰 Total: *Rp {$price}*\n\n";
            $msg .= "Pesanan sudah selesai! Terima kasih telah menggunakan layanan kami. ⭐\n";
            $msg .= "Silakan beri penilaian di aplikasi ya!";
        } elseif ($newStatus === 'cancelled') {
            $msg .= "\nPesanan Anda telah dibatalkan.";
        } else {
            $msg .= "📅 Estimasi selesai: {$estDate}\n\n";
            $msg .= "Terima kasih! 🙏";
        }

        return $msg;
    }

    /**
     * Generate tracking message for sharing
     */
    public static function trackingMessage(array $order, string $baseUrl): string
    {
        $trackUrl = $baseUrl . '/track/' . $order['order_code'];
        return "Lacak pesanan Anda: {$trackUrl}";
    }
}
