<?= $this->extend('layouts/customer') ?>

<?= $this->section('content') ?>

<div class="grid grid-cols-3 gap-4 mb-8">
    <div class="card rounded-lg p-4">
        <p class="text-gray-400 text-xs mb-1">Total Pesanan</p>
        <p class="text-2xl font-bold text-primary"><?= $stats['total_today'] ?? 0 ?></p>
    </div>
    <div class="card rounded-lg p-4">
        <p class="text-gray-400 text-xs mb-1">Sedang Diproses</p>
        <p class="text-2xl font-bold text-blue-400"><?= $stats['processing'] ?? 0 ?></p>
    </div>
    <div class="card rounded-lg p-4">
        <p class="text-gray-400 text-xs mb-1">Selesai</p>
        <p class="text-2xl font-bold text-green-400"><?= $stats['completed'] ?? 0 ?></p>
    </div>
</div>

<div class="card rounded-lg p-6 mb-8">
    <h3 class="text-lg font-semibold mb-4">Aksi Cepat</h3>
    <div class="flex flex-wrap gap-3">
        <a href="/customer/order/new" class="btn-primary px-6 py-3 rounded-lg font-semibold text-white inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Order Baru
        </a>
        <a href="/customer/orders" class="px-6 py-3 rounded-lg font-semibold text-white border border-primary hover:bg-primary/10 transition-all inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            Lihat Riwayat
        </a>
    </div>
</div>

<div class="card rounded-lg p-6">
    <h3 class="text-lg font-semibold mb-4">Pesanan Terbaru</h3>

    <?php if (empty($orders)): ?>
        <div class="text-center py-8 text-gray-400">
            <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <p>Belum ada pesanan</p>
            <a href="/customer/order/new" class="inline-block mt-4 text-primary hover:underline">Buat pesanan pertama</a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-400 text-sm">
                        <th class="pb-3">Kode Order</th>
                        <th class="pb-3">Tanggal</th>
                        <th class="pb-3">Total</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($orders, 0, 5) as $order): ?>
                        <tr class="border-t border-white/10">
                            <td class="py-3 font-medium"><?= $order['order_code'] ?></td>
                            <td class="py-3 text-gray-400"><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                            <td class="py-3">Rp <?= number_format($order['total_price'], 0, ',', '.') ?></td>
                            <td class="py-3">
                                <?php
                                $statusColors = [
                                    'pending' => 'bg-gray-500/20 text-gray-400',
                                    'confirmed' => 'bg-primary/20 text-primary',
                                    'washing' => 'bg-blue-500/20 text-blue-400',
                                    'drying' => 'bg-blue-500/20 text-blue-400',
                                    'ironing' => 'bg-blue-500/20 text-blue-400',
                                    'ready' => 'bg-amber-500/20 text-amber-400',
                                    'delivered' => 'bg-amber-500/20 text-amber-400',
                                    'completed' => 'bg-green-500/20 text-green-400',
                                    'cancelled' => 'bg-red-500/20 text-red-400',
                                ];
                                $statusLabels = [
                                    'pending' => 'Menunggu',
                                    'confirmed' => 'Dikonfirmasi',
                                    'washing' => 'Dicuci',
                                    'drying' => 'Dijemur',
                                    'ironing' => 'Disetrika',
                                    'ready' => 'Siap Diambil',
                                    'delivered' => 'Diantar',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                ];
                                ?>
                                <span class="status-badge <?= $statusColors[$order['status']] ?? '' ?>">
                                    <?= $statusLabels[$order['status']] ?? $order['status'] ?>
                                </span>
                            </td>
                            <td class="py-3">
                                <a href="/customer/orders/<?= $order['id'] ?>" class="text-primary hover:underline">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
