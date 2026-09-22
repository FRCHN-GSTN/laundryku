<?= $this->extend('layouts/customer') ?>

<?= $this->section('content') ?>

<div class="max-w-3xl">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-2xl font-bold"><?= esc($order['order_code']) ?></h3>
            <p class="text-gray-400">Dipesan pada <?= date('d M Y H:i', strtotime($order['created_at'])) ?></p>
        </div>
        <?php if ($order['status'] === 'pending'): ?>
            <form action="/customer/orders/<?= $order['id'] ?>/cancel" method="POST" 
                  onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                <?= csrf_field() ?>
                <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-red-500 hover:bg-red-600 transition-all">
                    Batalkan Pesanan
                </button>
            </form>
        <?php endif; ?>
    </div>

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
            <?php if ($order['total_weight']): ?>
                <div class="flex justify-between">
                    <span class="text-gray-400">Total Berat</span>
                    <span class="font-medium"><?= esc($order['total_weight']) ?> kg</span>
                </div>
            <?php endif; ?>
        </div>
    </div>

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
            <div class="flex justify-between items-center">
                <span class="text-lg font-semibold">Total Bayar</span>
                <span class="text-2xl font-bold text-primary">Rp <?= number_format($order['total_price'], 0, ',', '.') ?></span>
            </div>
        </div>
    </div>

    <a href="/customer/orders" class="inline-flex items-center text-primary hover:underline transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Riwayat Pesanan
    </a>
</div>

<?= $this->endSection() ?>
