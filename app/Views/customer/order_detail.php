<?= $this->extend('layouts/customer') ?>

<?= $this->section('content') ?>

<div class="max-w-3xl">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-2xl font-bold"><?= esc($order['order_code']) ?></h3>
            <p class="text-gray-400">Dipesan pada <?= date('d M Y H:i', strtotime($order['created_at'])) ?></p>
            <?php if ($order['estimated_date']): ?>
                <?php
                $estDate = strtotime($order['estimated_date']);
                $now = time();
                $isOverdue = $estDate < $now && !in_array($order['status'], ['completed', 'cancelled']);
                ?>
                <p class="text-sm <?= $isOverdue ? 'text-red-400' : 'text-primary' ?> mt-1">
                    Estimasi selesai: <?= date('d M Y', $estDate) ?>
                    <?php if ($isOverdue): ?>
                        (melewati estimasi)
                    <?php endif; ?>
                </p>
            <?php endif; ?>
        </div>
        <?php if (in_array($order['status'], ['pending', 'confirmed'])): ?>
            <form action="/customer/orders/<?= $order['id'] ?>/cancel" method="POST"
                  onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                <?= csrf_field() ?>
                <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-red-500 hover:bg-red-600 transition-all">
                    Batalkan Pesanan
                </button>
            </form>
        <?php endif; ?>
    </div>

    <!-- Status Timeline -->
    <div class="card rounded-lg p-6 mb-6">
        <h4 class="text-lg font-semibold mb-4">Status Pesanan</h4>
        <div class="flex items-center justify-between">
            <?php
            $steps = ['pending', 'confirmed', 'washing', 'drying', 'ironing', 'ready', 'completed'];
            $stepLabels = ['Pesanan', 'Dikonfirmasi', 'Dicuci', 'Dijemur', 'Disetrika', 'Siap', 'Selesai'];
            $currentIndex = array_search($order['status'], $steps);
            if ($currentIndex === false) $currentIndex = -1;
            ?>
            <?php foreach ($steps as $index => $step): ?>
                <div class="flex flex-col items-center flex-1">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center mb-2
                        <?= $index <= $currentIndex ? 'bg-primary text-white' : 'bg-white/10 text-gray-500' ?>">
                        <?php if ($index < $currentIndex): ?>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        <?php else: ?>
                            <?= $index + 1 ?>
                        <?php endif; ?>
                    </div>
                    <p class="text-xs text-center <?= $index <= $currentIndex ? 'text-primary' : 'text-gray-500' ?>">
                        <?= $stepLabels[$index] ?>
                    </p>
                </div>
                <?php if ($index < count($steps) - 1): ?>
                    <div class="flex-1 h-1 mx-1 <?= $index < $currentIndex ? 'bg-primary' : 'bg-white/10' ?>"></div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <?php if ($order['status'] === 'cancelled'): ?>
            <div class="mt-4 p-3 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 text-center">
                Pesanan dibatalkan
            </div>
        <?php endif; ?>
    </div>

    <!-- Detail Pesanan -->
    <div class="card rounded-lg p-6 mb-6">
        <h4 class="text-lg font-semibold mb-4">Detail Pesanan</h4>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-400">Kode Pesanan</span>
                <span class="font-medium"><?= esc($order['order_code']) ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Pengiriman</span>
                <span class="font-medium"><?= $order['delivery_type'] === 'pickup' ? 'Ambil Sendiri' : 'Dijemput' ?></span>
            </div>
            <?php if ($order['delivery_address']): ?>
                <div class="flex justify-between">
                    <span class="text-gray-400">Alamat</span>
                    <span class="font-medium text-right max-w-xs"><?= esc($order['delivery_address']) ?></span>
                </div>
            <?php endif; ?>
            <?php if ($order['notes']): ?>
                <div class="flex justify-between">
                    <span class="text-gray-400">Catatan</span>
                    <span class="font-medium text-right max-w-xs"><?= esc($order['notes']) ?></span>
                </div>
            <?php endif; ?>
            <div class="flex justify-between">
                <span class="text-gray-400">Total Berat</span>
                <span class="font-medium">
                    <?php if ($order['confirmed_weight']): ?>
                        <?= esc($order['confirmed_weight']) ?> kg (dikonfirmasi)
                    <?php elseif ($order['total_weight']): ?>
                        <?= esc($order['total_weight']) ?> kg (estimasi)
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Item Pesanan -->
    <div class="card rounded-lg p-6 mb-6">
        <h4 class="text-lg font-semibold mb-4">Item Pesanan</h4>
        <div class="space-y-3">
            <?php foreach ($items as $item): ?>
                <div class="flex justify-between items-center py-3 border-b border-white/10 last:border-0">
                    <div>
                        <p class="font-medium"><?= esc($item['service_name']) ?></p>
                        <p class="text-sm text-gray-400">
                            <?= esc($item['quantity']) ?> <?= esc($item['unit']) ?> × Rp <?= number_format($item['subtotal'] / $item['quantity'], 0, ',', '.') ?>
                        </p>
                    </div>
                    <p class="font-semibold">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="mt-4 pt-4 border-t border-white/10">
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Subtotal</span>
                    <span>Rp <?= number_format($order['total_price'], 0, ',', '.') ?></span>
                </div>
                <?php if (($order['discount_amount'] ?? 0) > 0): ?>
                    <div class="flex justify-between text-sm">
                        <span class="text-green-400">Diskon</span>
                        <span class="text-green-400">- Rp <?= number_format($order['discount_amount'], 0, ',', '.') ?></span>
                    </div>
                <?php endif; ?>
                <div class="flex justify-between items-center border-t border-white/10 pt-2">
                    <span class="text-lg font-semibold">Total Bayar</span>
                    <span class="text-2xl font-bold text-primary">
                        Rp <?= number_format($order['confirmed_price'] ?? $order['final_price'] ?? $order['total_price'], 0, ',', '.') ?>
                    </span>
                </div>
                <?php if ($order['confirmed_price'] && $order['confirmed_price'] != $order['total_price']): ?>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Estimasi awal</span>
                        <span class="text-gray-500 line-through">Rp <?= number_format($order['total_price'], 0, ',', '.') ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Invoice -->
    <?php if (in_array($order['status'], ['confirmed', 'washing', 'drying', 'ironing', 'ready', 'delivered', 'completed'])): ?>
        <div class="card rounded-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-semibold">Invoice</h4>
                    <p class="text-sm text-gray-400">Cetak bukti pemesanan</p>
                </div>
                <a href="/invoice/<?= $order['id'] ?>" target="_blank"
                   class="px-4 py-2 rounded-lg text-sm font-semibold text-white border border-white/20 hover:bg-white/10 transition-all">
                    Cetak Invoice
                </a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Payment Proof Upload -->
    <?php if (in_array($order['status'], ['pending', 'confirmed']) && ($order['delivery_type'] === 'delivery')): ?>
        <div class="card rounded-lg p-6 mb-6">
            <h4 class="font-semibold mb-3">Upload Bukti Pembayaran</h4>
            <p class="text-sm text-gray-400 mb-4">Upload bukti transfer/QRIS setelah melakukan pembayaran</p>
            <form action="/customer/orders/<?= $order['id'] ?>/proof" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="mb-4">
                    <input type="file" name="proof_image" accept="image/jpeg,image/png,image/webp" required
                           class="input-field w-full px-4 py-3 rounded-lg text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/80">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, WebP. Maks 5MB.</p>
                </div>
                <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold text-white btn-primary">
                    Upload Bukti
                </button>
            </form>
        </div>
    <?php endif; ?>

    <!-- Rating -->
    <?php if ($order['status'] === 'completed'): ?>
        <div class="card rounded-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-semibold">Beri Penilaian</h4>
                    <p class="text-sm text-gray-400">Bagaimana pengalaman Anda?</p>
                </div>
                <a href="/customer/orders/<?= $order['id'] ?>/rate"
                   class="px-4 py-2 rounded-lg text-sm font-semibold text-white btn-primary">
                    Beri Rating
                </a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Status History -->
    <?php if (!empty($statusHistory)): ?>
        <div class="card rounded-lg p-6 mb-6">
            <h4 class="text-lg font-semibold mb-4">Riwayat Status</h4>
            <div class="space-y-4">
                <?php foreach ($statusHistory as $index => $history): ?>
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-3 h-3 rounded-full <?= $index === count($statusHistory) - 1 ? 'bg-primary' : 'bg-white/20' ?>"></div>
                            <?php if ($index < count($statusHistory) - 1): ?>
                                <div class="w-0.5 flex-1 bg-white/10 mt-1"></div>
                            <?php endif; ?>
                        </div>
                        <div class="pb-4">
                            <p class="font-medium text-sm">
                                <?php if ($history['old_status'] && $history['old_status'] !== $history['new_status']): ?>
                                    <?= esc(\App\Models\OrderModel::$statusLabels[$history['old_status']] ?? $history['old_status']) ?>
                                    → <?= esc(\App\Models\OrderModel::$statusLabels[$history['new_status']] ?? $history['new_status']) ?>
                                <?php else: ?>
                                    <?= esc(\App\Models\OrderModel::$statusLabels[$history['new_status']] ?? $history['new_status']) ?>
                                <?php endif; ?>
                            </p>
                            <?php if ($history['note']): ?>
                                <p class="text-xs text-gray-400 mt-1"><?= esc($history['note']) ?></p>
                            <?php endif; ?>
                            <p class="text-xs text-gray-500 mt-1">
                                <?= date('d M Y H:i', strtotime($history['created_at'])) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <a href="/customer/orders" class="inline-flex items-center text-primary hover:underline transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Riwayat Pesanan
    </a>
</div>

<?= $this->endSection() ?>
