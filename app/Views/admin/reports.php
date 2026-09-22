<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="card rounded-lg p-6 mb-6">
    <h3 class="text-lg font-semibold mb-4">Filter Laporan</h3>
    <form action="/admin/reports" method="GET" class="flex flex-wrap gap-4">
        <?= csrf_field() ?>
        <div>
            <label class="block text-sm text-gray-400 mb-2">Tanggal Mulai</label>
            <input type="date" name="start_date" value="<?= esc($startDate, 'attr') ?>"
                   class="input-field px-4 py-3 rounded-lg text-white">
        </div>
        <div>
            <label class="block text-sm text-gray-400 mb-2">Tanggal Akhir</label>
            <input type="date" name="end_date" value="<?= esc($endDate, 'attr') ?>"
                   class="input-field px-4 py-3 rounded-lg text-white">
        </div>
        <div class="flex items-end">
            <button type="submit" class="btn-primary px-6 py-3 rounded-lg font-semibold text-white">
                Filter
            </button>
        </div>
    </form>
</div>

<div class="card rounded-lg p-6 mb-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-gray-400">Total Pendapatan</p>
            <p class="text-4xl font-bold text-primary">
                Rp <?= number_format($totalRevenue['total'] ?? 0, 0, ',', '.') ?>
            </p>
        </div>
        <div class="text-right">
            <p class="text-gray-400">Total Pesanan Selesai</p>
            <p class="text-4xl font-bold text-green-400"><?= count($orders) ?></p>
        </div>
    </div>
</div>

<div class="card rounded-lg p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold">Detail Pesanan Selesai</h3>
        <a href="/admin/export/orders?start_date=<?= esc($startDate, 'attr') ?>&end_date=<?= esc($endDate, 'attr') ?>"
           class="px-4 py-2 rounded-lg text-sm font-semibold text-white border border-white/20 hover:bg-white/10 transition-all inline-flex items-center gap-2"
           target="_blank">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Export CSV
        </a>
    </div>

    <?php if (empty($orders)): ?>
        <div class="text-center py-12 text-gray-400">
            <p>Tidak ada pesanan selesai pada periode ini</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-400 text-sm">
                        <th class="pb-3">Kode Order</th>
                        <th class="pb-3">Pelanggan</th>
                        <th class="pb-3">Tanggal</th>
                        <th class="pb-3">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr class="border-t border-white/10">
                            <td class="py-3 font-medium"><?= esc($order['order_code']) ?></td>
                            <td class="py-3 text-gray-400"><?= esc($order['user_name'] ?? '-') ?></td>
                            <td class="py-3 text-gray-400"><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                            <td class="py-3">Rp <?= number_format($order['total_price'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="border-t border-white/20">
                        <td colspan="3" class="py-4 font-semibold text-right">Total Pendapatan:</td>
                        <td class="py-4 font-bold text-primary text-lg">Rp <?= number_format($totalRevenue['total'] ?? 0, 0, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
