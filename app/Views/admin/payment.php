<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Order Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Order Details -->
        <div class="card rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Detail Pesanan #<?= esc($order['order_code']) ?></h3>
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
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-400">Pelanggan</p>
                    <p class="font-medium"><?= esc($user['name'] ?? '-') ?></p>
                </div>
                <div>
                    <p class="text-gray-400">Telepon</p>
                    <p class="font-medium"><?= esc($user['phone'] ?? '-') ?></p>
                </div>
                <div>
                    <p class="text-gray-400">Alamat</p>
                    <p class="font-medium"><?= esc($order['delivery_address'] ?? '-') ?></p>
                </div>
                <div>
                    <p class="text-gray-400">Tanggal Pesan</p>
                    <p class="font-medium"><?= date('d M Y H:i', strtotime($order['created_at'])) ?></p>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="card rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Item Pesanan</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-400 border-b border-white/10">
                            <th class="pb-3">Layanan</th>
                            <th class="pb-3">Qty</th>
                            <th class="pb-3">Harga</th>
                            <th class="pb-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($items)): ?>
                            <?php foreach ($items as $item): ?>
                                <tr class="border-t border-white/10">
                                    <td class="py-3"><?= esc($item['service_name'] ?? $item['name'] ?? '-') ?></td>
                                    <td class="py-3"><?= esc($item['quantity']) ?> <?= esc($item['unit'] ?? 'kg') ?></td>
                                    <td class="py-3">Rp <?= number_format($item['quantity'] > 0 ? $item['subtotal'] / $item['quantity'] : 0, 0, ',', '.') ?></td>
                                    <td class="py-3 text-right">Rp <?= number_format($item['subtotal'] ?? 0, 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-400">Tidak ada item</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr class="border-t border-white/20">
                            <td colspan="3" class="py-3 font-semibold">Total</td>
                            <td class="py-3 text-right font-bold text-primary text-lg">Rp <?= number_format(\App\Models\OrderModel::billableAmount($order), 0, ',', '.') ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Payment Section -->
    <div class="space-y-6">
        <!-- Payment Status -->
        <div class="card rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Status Pembayaran</h3>
            <?php if ($payment): ?>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Status</span>
                        <?php if ($payment['status'] === 'paid'): ?>
                            <span class="status-badge bg-green-500/20 text-green-400">Lunas</span>
                        <?php elseif (($payment['payment_method'] ?? '') === 'cash'): ?>
                            <span class="status-badge bg-amber-500/20 text-amber-400">Bayar di Tempat</span>
                        <?php else: ?>
                            <span class="status-badge bg-amber-500/20 text-amber-400">Menunggu Verifikasi</span>
                        <?php endif; ?>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Metode</span>
                        <span class="font-medium uppercase"><?= esc($payment['payment_method'] ?? '-') ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Tanggal Bayar</span>
                        <span class="font-medium"><?= (($payment['payment_date'] ?? null) && ($payment['status'] ?? '') === 'paid') ? date('d M Y H:i', strtotime($payment['payment_date'])) : '-' ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Tagihan</span>
                        <span class="font-semibold text-primary">Rp <?= number_format(\App\Models\OrderModel::billableAmount($order), 0, ',', '.') ?></span>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-gray-400 text-sm text-center py-4">Pelanggan belum memilih metode pembayaran.</p>
            <?php endif; ?>
        </div>

        <?php if (!empty($payment['proof_image'])): ?>
            <div class="card rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-3">
                    <?= ($payment['status'] ?? '') === 'paid' ? 'Bukti Pembayaran' : 'Bukti dari Pelanggan' ?>
                </h3>
                <a href="<?= esc($payment['proof_image']) ?>" target="_blank">
                    <img src="<?= esc($payment['proof_image']) ?>" alt="Bukti" class="w-full max-h-64 object-contain rounded-lg bg-white/5">
                </a>
            </div>
        <?php endif; ?>

        <!-- Verifikasi (aksi utama admin) -->
        <?php if ($payment && $payment['status'] === 'pending' && $order['status'] !== 'cancelled'): ?>
            <div class="card rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-2">Verifikasi</h3>
                <p class="text-sm text-gray-400 mb-4">
                    <?= ($payment['payment_method'] ?? '') === 'qris'
                        ? 'Pastikan bukti/transfer sesuai, lalu tandai lunas.'
                        : 'Uang diterima di tempat? Tandai lunas.' ?>
                </p>
                <form method="POST" action="/admin/orders/<?= $order['id'] ?>/payment/mark-paid"
                      onsubmit="return confirm('Tandai pembayaran ini lunas?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="w-full py-3 rounded-lg font-semibold text-white bg-green-600 hover:bg-green-700 transition-all">
                        Tandai Lunas
                    </button>
                </form>
            </div>
        <?php endif; ?>

        <!-- QRIS Display (jika QRIS pending) -->
        <?php if ($payment && $payment['payment_method'] === 'qris' && $payment['status'] === 'pending' && $order['status'] !== 'cancelled'): ?>
            <div class="card rounded-lg p-6 text-center">
                <h3 class="text-lg font-semibold mb-4">QRIS</h3>

                <?php if (!empty($qris_image)): ?>
                    <div class="bg-white p-4 rounded-lg inline-block mb-4">
                        <img src="<?= esc($qris_image) ?>" alt="QRIS Static" class="w-48 h-48 object-contain">
                    </div>
                <?php elseif (!empty($qris_string)): ?>
                    <?php
                    $payAmount = (int) round(\App\Models\OrderModel::billableAmount($order));
                    $dynamicQris = \App\Libraries\Qris::convertToDynamic($qris_string, $payAmount);
                    $qrUrl = \App\Libraries\Qris::getQrUrl($dynamicQris);
                    ?>
                    <div class="bg-white p-4 rounded-lg inline-block mb-4">
                        <img src="<?= esc($qrUrl, 'attr') ?>" alt="QRIS Code" class="w-48 h-48">
                    </div>
                <?php else: ?>
                    <div class="bg-white/5 p-4 rounded-lg inline-block mb-4">
                        <div class="w-48 h-48 flex items-center justify-center text-gray-500 text-sm">
                            QRIS belum dikonfigurasi
                        </div>
                    </div>
                    <p class="text-xs text-amber-400">Silakan atur QRIS di menu Pengaturan</p>
                <?php endif; ?>

                <p class="text-sm text-gray-400 mb-2">Total Pembayaran</p>
                <p class="text-2xl font-bold text-primary">Rp <?= number_format(\App\Models\OrderModel::billableAmount($order), 0, ',', '.') ?></p>
                <p class="text-xs text-gray-400 mt-2">Pelanggan scan & upload bukti di halaman pesanan mereka</p>
            </div>
        <?php endif; ?>

        <!-- Override metode (sekunder — offline / koreksi) -->
        <?php if ($order['status'] !== 'cancelled' && ($payment['status'] ?? '') !== 'paid'): ?>
            <div class="card rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-2">Override Metode</h3>
                <p class="text-xs text-gray-400 mb-4">
                    <?php if (!$payment): ?>
                        Untuk pesanan offline / kasir — pelanggan belum memilih metode.
                    <?php else: ?>
                        Hanya bila perlu koreksi atau ganti metode.
                    <?php endif; ?>
                </p>

                <form method="POST" action="/admin/orders/<?= $order['id'] ?>/payment">
                    <?= csrf_field() ?>

                    <div class="space-y-3 mb-4">
                        <label class="flex items-center p-4 rounded-lg border border-white/10 cursor-pointer hover:border-primary/50 transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/10">
                            <input type="radio" name="payment_method" value="cash" class="hidden" required
                                   <?= ($payment['payment_method'] ?? '') === 'cash' ? 'checked' : '' ?>>
                            <div class="w-5 h-5 rounded-full border-2 border-gray-400 mr-3 flex items-center justify-center shrink-0 has-[:checked]:border-primary has-[:checked]:bg-primary">
                                <svg class="w-3 h-3 text-white hidden has-[:checked]:block" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">Cash / Bayar di Tempat</p>
                                <p class="text-xs text-gray-400">Catat lunas — tanpa mengubah status pesanan</p>
                            </div>
                        </label>

                        <label class="flex items-center p-4 rounded-lg border border-white/10 cursor-pointer hover:border-primary/50 transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/10">
                            <input type="radio" name="payment_method" value="qris" class="hidden" required
                                   <?= ($payment['payment_method'] ?? '') === 'qris' ? 'checked' : '' ?>>
                            <div class="w-5 h-5 rounded-full border-2 border-gray-400 mr-3 flex items-center justify-center shrink-0 has-[:checked]:border-primary has-[:checked]:bg-primary">
                                <svg class="w-3 h-3 text-white hidden has-[:checked]:block" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">QRIS</p>
                                <p class="text-xs text-gray-400">Tunggu bukti / dana masuk, lalu Tandai Lunas</p>
                            </div>
                        </label>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-lg text-sm font-semibold text-white border border-white/20 hover:bg-white/10 transition-all">
                        Simpan Override Metode
                    </button>
                </form>
            </div>
        <?php endif; ?>

        <!-- Back to orders -->
        <a href="/admin/orders" class="block text-center py-3 rounded-lg border border-white/10 text-gray-400 hover:text-white hover:border-white/30 transition-all">
            Kembali ke Pesanan
        </a>
    </div>
</div>

<script>
document.querySelectorAll('label:has(input[type="radio"])').forEach(label => {
    label.addEventListener('click', function() {
        const radio = this.querySelector('input[type="radio"]');
        radio.checked = true;
    });
});
</script>

<?= $this->endSection() ?>
