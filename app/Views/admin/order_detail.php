<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-2xl font-bold"><?= $order['order_code'] ?></h3>
            <p class="text-gray-400">Dipesan pada <?= date('d M Y H:i', strtotime($order['created_at'])) ?></p>
        </div>
        <a href="/admin/orders" class="text-primary hover:underline flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="card rounded-lg p-6">
                <h4 class="text-lg font-semibold mb-4">Update Status</h4>
                <form action="/admin/orders/<?= $order['id'] ?>/status" method="POST" class="flex flex-wrap gap-3">
                    <?= csrf_field() ?>
                    <select name="status" class="input-field px-4 py-3 rounded-lg text-white flex-1">
                        <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Menunggu</option>
                        <option value="confirmed" <?= $order['status'] === 'confirmed' ? 'selected' : '' ?>>Dikonfirmasi</option>
                        <option value="washing" <?= $order['status'] === 'washing' ? 'selected' : '' ?>>Dicuci</option>
                        <option value="drying" <?= $order['status'] === 'drying' ? 'selected' : '' ?>>Dijemur</option>
                        <option value="ironing" <?= $order['status'] === 'ironing' ? 'selected' : '' ?>>Disetrika</option>
                        <option value="ready" <?= $order['status'] === 'ready' ? 'selected' : '' ?>>Siap Diambil</option>
                        <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Diantar</option>
                        <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Selesai</option>
                        <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
                    </select>
                    <button type="submit" class="btn-primary px-6 py-3 rounded-lg font-semibold text-white">
                        Update
                    </button>
                </form>
            </div>

            <div class="card rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold">Pembayaran</h4>
                    <a href="/admin/orders/<?= $order['id'] ?>/payment" class="text-primary hover:underline text-sm">
                        Proses Pembayaran →
                    </a>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Total Tagihan</span>
                        <span class="font-bold text-primary text-lg">Rp <?= number_format($order['total_price'], 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>

            <div class="card rounded-lg p-6">
                <h4 class="text-lg font-semibold mb-4">Detail Pesanan</h4>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Kode Pesanan</span>
                        <span class="font-medium"><?= $order['order_code'] ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Status</span>
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
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Pengiriman</span>
                        <span class="font-medium"><?= $order['delivery_type'] === 'pickup' ? 'Ambil Sendiri' : 'Dijemput' ?></span>
                    </div>
                    <?php if ($order['delivery_address']): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Alamat</span>
                            <span class="font-medium text-right max-w-xs"><?= $order['delivery_address'] ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($order['notes']): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Catatan</span>
                            <span class="font-medium text-right max-w-xs"><?= $order['notes'] ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($order['total_weight']): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Total Berat</span>
                            <span class="font-medium"><?= $order['total_weight'] ?> kg</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card rounded-lg p-6">
                <h4 class="text-lg font-semibold mb-4">Item Pesanan</h4>
                <div class="space-y-3">
                    <?php foreach ($items as $item): ?>
                        <div class="flex justify-between items-center py-3 border-b border-white/10 last:border-0">
                            <div>
                                <p class="font-medium"><?= $item['service_name'] ?></p>
                                <p class="text-sm text-gray-400">
                                    <?= $item['quantity'] ?> <?= $item['unit'] ?> × Rp <?= number_format($item['subtotal'] / $item['quantity'], 0, ',', '.') ?>
                                </p>
                            </div>
                            <p class="font-semibold">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="mt-4 pt-4 border-t border-white/10">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold">Total Bayar</span>
                        <span class="text-2xl font-bold text-primary">Rp <?= number_format($order['total_price'], 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="card rounded-lg p-6">
                <h4 class="text-lg font-semibold mb-4">Informasi Pelanggan</h4>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center mr-4">
                            <span class="text-primary font-semibold text-lg">
                                <?= strtoupper(substr($user['name'], 0, 1)) ?>
                            </span>
                        </div>
                        <div>
                            <p class="font-medium"><?= $user['name'] ?></p>
                            <p class="text-sm text-gray-400"><?= $user['email'] ?></p>
                        </div>
                    </div>
                    <?php if ($user['phone']): ?>
                        <div class="flex items-center text-gray-400">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <?= $user['phone'] ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($user['address']): ?>
                        <div class="flex items-start text-gray-400">
                            <svg class="w-5 h-5 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span><?= $user['address'] ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card rounded-lg p-6">
                <h4 class="text-lg font-semibold mb-4">Aksi Cepat</h4>
                <div class="space-y-3">
                    <?php if ($order['status'] === 'pending'): ?>
                        <form action="/admin/orders/<?= $order['id'] ?>/status" method="POST">
                            <?= csrf_field() ?>
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit" class="w-full btn-primary py-3 rounded-lg font-semibold text-white">
                                Konfirmasi Pesanan
                            </button>
                        </form>
                    <?php endif; ?>
                    
                    <?php if ($order['status'] === 'confirmed'): ?>
                        <form action="/admin/orders/<?= $order['id'] ?>/status" method="POST">
                            <?= csrf_field() ?>
                            <input type="hidden" name="status" value="washing">
                            <button type="submit" class="w-full btn-primary py-3 rounded-lg font-semibold text-white">
                                Mulai Dicuci
                            </button>
                        </form>
                    <?php endif; ?>
                    
                    <?php if ($order['status'] === 'washing'): ?>
                        <form action="/admin/orders/<?= $order['id'] ?>/status" method="POST">
                            <?= csrf_field() ?>
                            <input type="hidden" name="status" value="drying">
                            <button type="submit" class="w-full btn-primary py-3 rounded-lg font-semibold text-white">
                                Mulai Dijemur
                            </button>
                        </form>
                    <?php endif; ?>
                    
                    <?php if ($order['status'] === 'drying'): ?>
                        <form action="/admin/orders/<?= $order['id'] ?>/status" method="POST">
                            <?= csrf_field() ?>
                            <input type="hidden" name="status" value="ironing">
                            <button type="submit" class="w-full btn-primary py-3 rounded-lg font-semibold text-white">
                                Mulai Disetrika
                            </button>
                        </form>
                    <?php endif; ?>
                    
                    <?php if ($order['status'] === 'ironing'): ?>
                        <form action="/admin/orders/<?= $order['id'] ?>/status" method="POST">
                            <?= csrf_field() ?>
                            <input type="hidden" name="status" value="ready">
                            <button type="submit" class="w-full btn-primary py-3 rounded-lg font-semibold text-white">
                                Tandai Siap Diambil
                            </button>
                        </form>
                    <?php endif; ?>
                    
                    <?php if ($order['status'] === 'ready'): ?>
                        <form action="/admin/orders/<?= $order['id'] ?>/status" method="POST">
                            <?= csrf_field() ?>
                            <input type="hidden" name="status" value="delivered">
                            <button type="submit" class="w-full btn-primary py-3 rounded-lg font-semibold text-white">
                                Tandai Diantar/Diambil
                            </button>
                        </form>
                    <?php endif; ?>
                    
                    <?php if ($order['status'] === 'delivered'): ?>
                        <form action="/admin/orders/<?= $order['id'] ?>/status" method="POST">
                            <?= csrf_field() ?>
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="w-full btn-primary py-3 rounded-lg font-semibold text-white">
                                Tandai Selesai
                            </button>
                        </form>
                    <?php endif; ?>
                    
                    <?php if (!in_array($order['status'], ['completed', 'cancelled'])): ?>
                        <form action="/admin/orders/<?= $order['id'] ?>/status" method="POST">
                            <?= csrf_field() ?>
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="w-full py-3 rounded-lg font-semibold text-white bg-red-500 hover:bg-red-600 transition-all"
                                    onclick="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                Batalkan Pesanan
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
