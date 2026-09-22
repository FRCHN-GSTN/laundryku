<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="card rounded-lg p-4">
        <p class="text-gray-400 text-xs mb-1">Pesanan Hari Ini</p>
        <p class="text-2xl font-bold text-primary"><?= esc($stats['total_today'] ?? 0) ?></p>
    </div>
    <div class="card rounded-lg p-4">
        <p class="text-gray-400 text-xs mb-1">Menunggu</p>
        <p class="text-2xl font-bold text-amber-400"><?= esc($stats['pending'] ?? 0) ?></p>
    </div>
    <div class="card rounded-lg p-4">
        <p class="text-gray-400 text-xs mb-1">Sedang Diproses</p>
        <p class="text-2xl font-bold text-blue-400"><?= esc($stats['processing'] ?? 0) ?></p>
    </div>
    <div class="card rounded-lg p-4">
        <p class="text-gray-400 text-xs mb-1">Selesai</p>
        <p class="text-2xl font-bold text-green-400"><?= esc($stats['completed'] ?? 0) ?></p>
    </div>
</div>

<div class="card rounded-lg p-6 mb-8">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-gray-400 text-sm">Pendapatan Hari Ini</p>
            <p class="text-3xl font-bold text-primary">
                Rp <?= number_format($todayRevenue['revenue'] ?? 0, 0, ',', '.') ?>
            </p>
        </div>
    </div>
</div>

<div class="card rounded-lg p-6 mb-8">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Pesanan Menunggu Konfirmasi</h3>
        <a href="/admin/orders?status=pending" class="text-primary text-sm hover:underline">Lihat Semua</a>
    </div>

    <?php if (empty($pendingOrders)): ?>
        <div class="text-center py-8 text-gray-400">
            <p>Tidak ada pesanan baru</p>
        </div>
    <?php else: ?>
        <div class="space-y-3">
            <?php foreach ($pendingOrders as $order): ?>
                <div class="flex items-center justify-between p-4 rounded-lg bg-white/5">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center mr-4">
                            <span class="text-primary font-semibold text-sm"><?= esc(substr($order['order_code'], -4)) ?></span>
                        </div>
                        <div>
                            <p class="font-medium"><?= esc($order['order_code']) ?></p>
                            <p class="text-sm text-gray-400">Rp <?= number_format($order['total_price'], 0, ',', '.') ?></p>
                        </div>
                    </div>
                    <a href="/admin/orders/<?= $order['id'] ?>" class="btn-primary px-4 py-2 rounded-lg text-sm font-medium text-white">
                        Proses
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div class="card rounded-lg p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Pesanan Hari Ini</h3>
        <a href="/admin/orders" class="text-primary text-sm hover:underline">Lihat Semua</a>
    </div>

    <?php if (empty($todayOrders)): ?>
        <div class="text-center py-8 text-gray-400">
            <p>Belum ada pesanan hari ini</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-400 text-sm">
                        <th class="pb-3">Kode Order</th>
                        <th class="pb-3">Pelanggan</th>
                        <th class="pb-3">Total</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($todayOrders, 0, 5) as $order): ?>
                        <tr class="border-t border-white/10">
                            <td class="py-3 font-medium"><?= esc($order['order_code']) ?></td>
                            <td class="py-3 text-gray-400">-</td>
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
                                    <?= esc($statusLabels[$order['status']] ?? $order['status']) ?>
                                </span>
                            </td>
                            <td class="py-3">
                                <a href="/admin/orders/<?= $order['id'] ?>" class="text-primary hover:underline">
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
