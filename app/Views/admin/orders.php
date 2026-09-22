<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="card rounded-lg p-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h3 class="text-lg font-semibold">Daftar Pesanan</h3>
        <div class="flex flex-wrap gap-2">
            <a href="/admin/orders" class="px-3 py-1.5 rounded text-sm font-medium <?= !$currentStatus ? 'bg-primary text-white' : 'bg-white/5 text-gray-400 hover:bg-white/10' ?> transition-all">
                Semua
            </a>
            <a href="/admin/orders?status=pending" class="px-3 py-1.5 rounded text-sm font-medium <?= $currentStatus === 'pending' ? 'bg-amber-500 text-white' : 'bg-white/5 text-gray-400 hover:bg-white/10' ?> transition-all">
                Menunggu
            </a>
            <a href="/admin/orders?status=confirmed" class="px-3 py-1.5 rounded text-sm font-medium <?= $currentStatus === 'confirmed' ? 'bg-primary text-white' : 'bg-white/5 text-gray-400 hover:bg-white/10' ?> transition-all">
                Dikonfirmasi
            </a>
            <a href="/admin/orders?status=washing" class="px-3 py-1.5 rounded text-sm font-medium <?= $currentStatus === 'washing' ? 'bg-blue-500 text-white' : 'bg-white/5 text-gray-400 hover:bg-white/10' ?> transition-all">
                Dicuci
            </a>
            <a href="/admin/orders?status=ready" class="px-3 py-1.5 rounded text-sm font-medium <?= $currentStatus === 'ready' ? 'bg-amber-500 text-white' : 'bg-white/5 text-gray-400 hover:bg-white/10' ?> transition-all">
                Siap
            </a>
            <a href="/admin/orders?status=completed" class="px-3 py-1.5 rounded text-sm font-medium <?= $currentStatus === 'completed' ? 'bg-green-500 text-white' : 'bg-white/5 text-gray-400 hover:bg-white/10' ?> transition-all">
                Selesai
            </a>
        </div>
    </div>

    <?php if (empty($orders)): ?>
        <div class="text-center py-12 text-gray-400">
            <p>Tidak ada pesanan ditemukan</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-400 text-sm">
                        <th class="pb-3">Kode Order</th>
                        <th class="pb-3">Pelanggan</th>
                        <th class="pb-3">Telepon</th>
                        <th class="pb-3">Tanggal</th>
                        <th class="pb-3">Total</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr class="border-t border-white/10 hover:bg-white/5 transition-colors">
                            <td class="py-3 font-medium"><?= esc($order['order_code']) ?></td>
                            <td class="py-3 text-gray-400"><?= esc($order['user_name'] ?? '-') ?></td>
                            <td class="py-3 text-gray-400"><?= esc($order['user_phone'] ?? '-') ?></td>
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
