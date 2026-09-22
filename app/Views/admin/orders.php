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

    <!-- Search -->
    <form action="/admin/orders" method="GET" class="mb-6">
        <?php if ($currentStatus): ?>
            <input type="hidden" name="status" value="<?= esc($currentStatus, 'attr') ?>">
        <?php endif; ?>
        <div class="flex gap-2">
            <input type="text" name="search" value="<?= esc($search ?? '', 'attr') ?>"
                   class="input-field flex-1 px-4 py-2.5 rounded-lg text-white placeholder-gray-500"
                   placeholder="Cari kode order, nama, atau telepon...">
            <button type="submit" class="btn-primary px-6 py-2.5 rounded-lg font-semibold text-white">
                Cari
            </button>
        </div>
    </form>

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
                        <th class="pb-3">Tanggal</th>
                        <th class="pb-3">Estimasi</th>
                        <th class="pb-3">Total</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr class="border-t border-white/10 hover:bg-white/5 transition-colors">
                            <td class="py-3 font-medium"><?= esc($order['order_code']) ?></td>
                            <td class="py-3 text-gray-400">
                                <div>
                                    <p class="text-white"><?= esc($order['user_name'] ?? '-') ?></p>
                                    <p class="text-xs"><?= esc($order['user_phone'] ?? '-') ?></p>
                                </div>
                            </td>
                            <td class="py-3 text-gray-400 text-sm"><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                            <td class="py-3 text-sm">
                                <?php if ($order['estimated_date']): ?>
                                    <?php
                                    $estDate = strtotime($order['estimated_date']);
                                    $now = time();
                                    $isOverdue = $estDate < $now && !in_array($order['status'], ['completed', 'cancelled']);
                                    ?>
                                    <span class="<?= $isOverdue ? 'text-red-400' : 'text-gray-400' ?>">
                                        <?= date('d M Y', $estDate) ?>
                                        <?php if ($isOverdue): ?>
                                            <span class="text-xs">(terlambat)</span>
                                        <?php endif; ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-500">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3">Rp <?= number_format($order['confirmed_price'] ?? $order['total_price'], 0, ',', '.') ?></td>
                            <td class="py-3">
                                <span class="status-badge <?= \App\Models\OrderModel::$statusColors[$order['status']] ?? '' ?>">
                                    <?= esc(\App\Models\OrderModel::$statusLabels[$order['status']] ?? $order['status']) ?>
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
