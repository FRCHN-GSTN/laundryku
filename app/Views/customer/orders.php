<?= $this->extend('layouts/customer') ?>

<?= $this->section('content') ?>

<div class="card rounded-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold">Riwayat Pesanan</h3>
        <a href="/customer/order/new" class="btn-primary px-4 py-2 rounded-lg text-sm font-medium text-white inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Order Baru
        </a>
    </div>

    <?php if (empty($orders)): ?>
        <div class="text-center py-12 text-gray-400">
            <svg class="w-20 h-20 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <p class="text-lg">Belum ada pesanan</p>
            <a href="/customer/order/new" class="inline-block mt-4 text-primary hover:underline">Buat pesanan pertama</a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-400 text-sm">
                        <th class="pb-3">Kode Order</th>
                        <th class="pb-3">Tanggal</th>
                        <th class="pb-3">Estimasi</th>
                        <th class="pb-3">Berat</th>
                        <th class="pb-3">Total</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr class="border-t border-white/10 hover:bg-white/5 transition-colors">
                            <td class="py-3 font-medium"><?= esc($order['order_code']) ?></td>
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
                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-500">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 text-gray-400">
                                <?php if ($order['confirmed_weight']): ?>
                                    <?= esc($order['confirmed_weight']) ?> kg
                                <?php elseif ($order['total_weight']): ?>
                                    <?= esc($order['total_weight']) ?> kg
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="py-3">Rp <?= number_format($order['confirmed_price'] ?? $order['total_price'], 0, ',', '.') ?></td>
                            <td class="py-3">
                                <span class="status-badge <?= \App\Models\OrderModel::$statusColors[$order['status']] ?? '' ?>">
                                    <?= esc(\App\Models\OrderModel::$statusLabels[$order['status']] ?? $order['status']) ?>
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
