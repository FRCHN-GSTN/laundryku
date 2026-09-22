<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="flex justify-between items-center mb-6">
    <div></div>
    <a href="/admin/promotions/create" class="btn-primary px-4 py-2 rounded-lg text-sm font-medium text-white">
        + Tambah Promo
    </a>
</div>

<div class="card rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-gray-400 text-sm border-b border-white/10">
                    <th class="p-4">Judul</th>
                    <th class="p-4">Kode</th>
                    <th class="p-4">Diskon</th>
                    <th class="p-4">Periode</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($promotions)): ?>
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-400">Belum ada promo</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($promotions as $promo): ?>
                        <tr class="border-t border-white/10">
                            <td class="p-4">
                                <p class="font-medium"><?= esc($promo['title']) ?></p>
                                <?php if ($promo['description']): ?>
                                    <p class="text-xs text-gray-400 mt-1"><?= esc(substr($promo['description'], 0, 50)) ?>...</p>
                                <?php endif; ?>
                            </td>
                            <td class="p-4">
                                <?php if ($promo['promo_code']): ?>
                                    <span class="px-2 py-1 rounded bg-primary/20 text-primary text-xs font-mono"><?= esc($promo['promo_code']) ?></span>
                                <?php else: ?>
                                    <span class="text-gray-500">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4">
                                <?php if ($promo['discount_type'] === 'percentage'): ?>
                                    <span class="text-green-400 font-semibold"><?= esc($promo['discount_value']) ?>%</span>
                                <?php else: ?>
                                    <span class="text-green-400 font-semibold">Rp <?= number_format(esc($promo['discount_value']), 0, ',', '.') ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-sm text-gray-400">
                                <?= date('d M Y', strtotime($promo['start_date'])) ?> - <?= date('d M Y', strtotime($promo['end_date'])) ?>
                            </td>
                            <td class="p-4">
                                <?php if ($promo['is_active']): ?>
                                    <span class="status-badge bg-green-500/20 text-green-400">Aktif</span>
                                <?php else: ?>
                                    <span class="status-badge bg-gray-500/20 text-gray-400">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <a href="/admin/promotions/<?= $promo['id'] ?>/edit" class="text-primary hover:underline text-sm">Edit</a>
                                    <form action="/admin/promotions/<?= $promo['id'] ?>/delete" method="POST" class="inline" onsubmit="return confirm('Yakin hapus promo ini?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="text-red-400 hover:underline text-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
