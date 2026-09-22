<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-2xl font-bold"><?= esc($customer['name']) ?></h3>
            <p class="text-gray-400">Pelanggan sejak <?= date('d M Y', strtotime($customer['created_at'])) ?></p>
        </div>
        <a href="/admin/customers" class="text-primary hover:underline flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="card rounded-lg p-6">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-4">
                        <span class="text-primary font-bold text-2xl">
                            <?= esc(strtoupper(substr($customer['name'], 0, 1))) ?>
                        </span>
                    </div>
                    <h4 class="text-xl font-semibold"><?= esc($customer['name']) ?></h4>
                    <p class="text-gray-400"><?= esc($customer['email']) ?></p>
                </div>

                <div class="space-y-4">
                    <?php if ($customer['phone']): ?>
                        <div class="flex items-center text-gray-400">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <?= esc($customer['phone']) ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($customer['address']): ?>
                        <div class="flex items-start text-gray-400">
                            <svg class="w-5 h-5 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span><?= esc($customer['address']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="card rounded-lg p-6">
                <h4 class="text-lg font-semibold mb-4">Riwayat Pesanan</h4>

                <?php if (empty($orders)): ?>
                    <div class="text-center py-8 text-gray-400">
                        <p>Belum ada pesanan</p>
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
                                <?php foreach ($orders as $order): ?>
                                    <tr class="border-t border-white/10">
                                        <td class="py-3 font-medium"><?= esc($order['order_code']) ?></td>
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
        </div>
    </div>
</div>

<?= $this->endSection() ?>
