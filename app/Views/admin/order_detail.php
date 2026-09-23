<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-2xl font-bold"><?= esc($order['order_code']) ?></h3>
            <p class="text-gray-400">Dipesan pada <?= date('d M Y H:i', strtotime($order['created_at'])) ?></p>
            <?php if ($order['estimated_date']): ?>
                <p class="text-sm text-primary mt-1">Estimasi selesai: <?= date('d M Y', strtotime($order['estimated_date'])) ?></p>
            <?php endif; ?>
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
            <!-- Status Update -->
            <div class="card rounded-lg p-6">
                <h4 class="text-lg font-semibold mb-4">Update Status</h4>
                <?php if (!empty($validNextStatuses)): ?>
                    <form action="/admin/orders/<?= $order['id'] ?>/status" method="POST" class="space-y-3">
                        <?= csrf_field() ?>
                        <div class="flex flex-wrap gap-3">
                            <?php foreach ($validNextStatuses as $nextStatus): ?>
                                <button type="submit" name="status" value="<?= esc($nextStatus) ?>"
                                    class="px-4 py-2 rounded-lg text-sm font-semibold transition-all
                                    <?php if ($nextStatus === 'cancelled'): ?>
                                        bg-red-500/20 text-red-400 hover:bg-red-500/30
                                    <?php else: ?>
                                        btn-primary text-white
                                    <?php endif; ?>"
                                    onclick="<?= $nextStatus === 'cancelled' ? "return confirm('Yakin ingin membatalkan pesanan ini?')" : '' ?>">
                                    <?= esc(\App\Models\OrderModel::$statusLabels[$nextStatus] ?? $nextStatus) ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Catatan Status (opsional)</label>
                            <input type="text" name="status_note" class="input-field w-full px-4 py-2.5 rounded-lg text-white" placeholder="Contoh: Sudah diterima kurir">
                        </div>
                    </form>
                <?php else: ?>
                    <p class="text-gray-400">Pesanan sudah <?= esc(\App\Models\OrderModel::$statusLabels[$order['status']] ?? $order['status']) ?>. Tidak ada aksi tersedia.</p>
                <?php endif; ?>
            </div>

            <!-- Weight & Price Confirmation -->
            <?php if (in_array($order['status'], ['pending', 'confirmed'])): ?>
                <div class="card rounded-lg p-6">
                    <h4 class="text-lg font-semibold mb-4">Konfirmasi Berat & Harga</h4>
                    <form action="/admin/orders/<?= $order['id'] ?>/confirm-weight" method="POST" class="space-y-4">
                        <?= csrf_field() ?>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-400 mb-1">Berat Estimasi (Customer)</label>
                                <p class="font-medium"><?= $order['total_weight'] ? esc($order['total_weight']) . ' kg' : '-' ?></p>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-1">Harga Estimasi (Customer)</label>
                                <p class="font-medium">Rp <?= number_format($order['total_price'], 0, ',', '.') ?></p>
                            </div>
                        </div>
                        <hr class="border-white/10">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-400 mb-1">Berat Aktual (kg) *</label>
                                <input type="number" name="confirmed_weight" id="confirmedWeight" step="0.1" min="0.1"
                                       value="<?= esc($order['confirmed_weight'] ?? $order['total_weight'] ?? '', 'attr') ?>"
                                       class="input-field w-full px-4 py-2.5 rounded-lg text-white" required>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-1">Harga Final (Rp) *</label>
                                <input type="number" name="confirmed_price" id="confirmedPrice" min="0"
                                       value="<?= esc($order['confirmed_price'] ?? $order['final_price'] ?? $order['total_price'] ?? '', 'attr') ?>"
                                       class="input-field w-full px-4 py-2.5 rounded-lg text-white" required>
                                <p class="text-xs text-gray-500 mt-1">Otomatis terhitung dari berat (setelah diskon, bisa diedit manual)</p>
                            </div>
                        </div>
                        <button type="submit" class="btn-primary px-6 py-2.5 rounded-lg text-sm font-semibold text-white">
                            Konfirmasi Berat & Harga
                        </button>
                    </form>
                </div>
                <script>
                (function () {
                    const items = <?= json_encode(array_map(static fn ($item) => [
                        'unit'      => $item['unit'] ?? '',
                        'quantity'  => (float) $item['quantity'],
                        'subtotal'  => (float) $item['subtotal'],
                    ], $items)) ?>;
                    const weightInput = document.getElementById('confirmedWeight');
                    const priceInput = document.getElementById('confirmedPrice');
                    const totalWeight = <?= (float) ($order['total_weight'] ?? 0) ?>;
                    const totalPrice = <?= (float) $order['total_price'] ?>;
                    const discount = <?= max(0, (float) ($order['discount_amount'] ?? 0)) ?>;

                    function calcPrice(weight) {
                        let weightPart = 0;
                        let fixedPart = 0;
                        let estWeight = 0;

                        items.forEach(function (item) {
                            if (item.unit === 'kg') {
                                weightPart += item.subtotal;
                                estWeight += item.quantity;
                            } else {
                                fixedPart += item.subtotal;
                            }
                        });

                        if (estWeight <= 0 && totalWeight > 0) {
                            estWeight = totalWeight;
                            weightPart = totalPrice;
                        }

                        let gross;
                        if (estWeight > 0 && weight > 0) {
                            gross = Math.round(weightPart * (weight / estWeight) + fixedPart);
                        } else {
                            gross = Math.round(totalPrice);
                        }

                        return Math.max(0, gross - discount);
                    }

                    weightInput.addEventListener('input', function () {
                        const weight = parseFloat(this.value);
                        if (!isNaN(weight) && weight > 0) {
                            priceInput.value = calcPrice(weight);
                        }
                    });
                })();
                </script>
            <?php endif; ?>

            <!-- Payment -->
            <?php $paymentInfo = $payment ?? null; ?>
            <div class="card rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold">Pembayaran</h4>
                    <div class="flex items-center gap-2">
                        <?php if (($paymentInfo['status'] ?? '') === 'paid'): ?>
                            <span class="status-badge bg-green-500/20 text-green-400">Lunas</span>
                        <?php elseif (($paymentInfo['payment_method'] ?? '') === 'cash' && $paymentInfo): ?>
                            <span class="status-badge bg-amber-500/20 text-amber-400">Bayar di Tempat</span>
                        <?php elseif ($paymentInfo): ?>
                            <span class="status-badge bg-amber-500/20 text-amber-400">Menunggu Verifikasi</span>
                        <?php else: ?>
                            <span class="status-badge bg-gray-500/20 text-gray-400">Belum dipilih</span>
                        <?php endif; ?>
                        <?php if ($order['status'] !== 'cancelled'): ?>
                            <a href="/admin/orders/<?= $order['id'] ?>/payment" class="text-primary hover:underline text-sm">
                                <?php if (($paymentInfo['status'] ?? '') === 'paid'): ?>
                                    Lihat Pembayaran →
                                <?php elseif ($paymentInfo): ?>
                                    Verifikasi Pembayaran →
                                <?php else: ?>
                                    Atur Pembayaran →
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Total Tagihan</span>
                        <span class="font-bold text-primary text-lg">
                            Rp <?= number_format(\App\Models\OrderModel::billableAmount($order), 0, ',', '.') ?>
                        </span>
                    </div>
                    <?php if (($order['discount_amount'] ?? 0) > 0): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-green-400">Diskon promo</span>
                            <span class="text-green-400">- Rp <?= number_format($order['discount_amount'], 0, ',', '.') ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($order['confirmed_price'] && $order['confirmed_price'] != $order['total_price']): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Estimasi awal</span>
                            <span class="text-gray-500 line-through">Rp <?= number_format($order['total_price'], 0, ',', '.') ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Detail Pesanan -->
            <div class="card rounded-lg p-6">
                <h4 class="text-lg font-semibold mb-4">Detail Pesanan</h4>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Kode Pesanan</span>
                        <span class="font-medium"><?= esc($order['order_code']) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Status</span>
                        <span class="status-badge <?= \App\Models\OrderModel::$statusColors[$order['status']] ?? '' ?>">
                            <?= esc(\App\Models\OrderModel::$statusLabels[$order['status']] ?? $order['status']) ?>
                        </span>
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
                            <span class="text-gray-400">Catatan Customer</span>
                            <span class="font-medium text-right max-w-xs"><?= esc($order['notes']) ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Berat</span>
                        <span class="font-medium">
                            <?php if ($order['confirmed_weight']): ?>
                                <span class="text-green-400"><?= esc($order['confirmed_weight']) ?> kg (dikonfirmasi)</span>
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
            <div class="card rounded-lg p-6">
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
                        <span class="text-2xl font-bold text-primary">
                            Rp <?= number_format(\App\Models\OrderModel::billableAmount($order), 0, ',', '.') ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Admin Notes -->
            <div class="card rounded-lg p-6">
                <h4 class="text-lg font-semibold mb-4">Catatan Admin</h4>
                <form action="/admin/orders/<?= $order['id'] ?>/notes" method="POST" class="space-y-3">
                    <?= csrf_field() ?>
                    <textarea name="admin_notes" rows="3"
                              class="input-field w-full px-4 py-3 rounded-lg text-white placeholder-gray-500"
                              placeholder="Catatan internal untuk pesanan ini..."><?= esc($order['admin_notes'] ?? '') ?></textarea>
                    <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold text-white border border-white/20 hover:bg-white/10 transition-all">
                        Simpan Catatan
                    </button>
                </form>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="card rounded-lg p-6">
                <h4 class="text-lg font-semibold mb-4">Informasi Pelanggan</h4>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center mr-4">
                            <span class="text-primary font-semibold text-lg">
                                <?= esc(strtoupper(substr($user['name'], 0, 1))) ?>
                            </span>
                        </div>
                        <div>
                            <p class="font-medium"><?= esc($user['name']) ?></p>
                            <p class="text-sm text-gray-400"><?= esc($user['email']) ?></p>
                        </div>
                    </div>
                    <?php if ($user['phone']): ?>
                        <div class="flex items-center text-gray-400">
                            <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <?= esc($user['phone']) ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($user['address']): ?>
                        <div class="flex items-start text-gray-400">
                            <svg class="w-5 h-5 mr-3 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span><?= esc($user['address']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Status History -->
            <div class="card rounded-lg p-6">
                <h4 class="text-lg font-semibold mb-4">Riwayat Status</h4>
                <?php if (!empty($statusHistory)): ?>
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
                                        <?= esc($history['changed_by']) ?> · <?= date('d M H:i', strtotime($history['created_at'])) ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-400 text-sm">Belum ada riwayat perubahan status</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
