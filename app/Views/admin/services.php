<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="card rounded-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold">Daftar Layanan</h3>
        <a href="/admin/services/create" class="btn-primary px-4 py-2 rounded-lg text-sm font-medium text-white inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Layanan
        </a>
    </div>

    <?php if (empty($services)): ?>
        <div class="text-center py-12 text-gray-400">
            <p>Belum ada layanan</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-400 text-sm">
                        <th class="pb-3">Nama Layanan</th>
                        <th class="pb-3">Deskripsi</th>
                        <th class="pb-3">Harga</th>
                        <th class="pb-3">Satuan</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                        <tr class="border-t border-white/10 hover:bg-white/5 transition-colors">
                            <td class="py-3 font-medium"><?= $service['name'] ?></td>
                            <td class="py-3 text-gray-400"><?= $service['description'] ?: '-' ?></td>
                            <td class="py-3">Rp <?= number_format($service['price'], 0, ',', '.') ?></td>
                            <td class="py-3 text-gray-400">/<?= $service['unit'] ?></td>
                            <td class="py-3">
                                <span class="status-badge <?= $service['is_active'] ? 'bg-green-500/20 text-green-400' : 'bg-gray-500/20 text-gray-400' ?>">
                                    <?= $service['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                </span>
                            </td>
                            <td class="py-3">
                                <div class="flex gap-3">
                                    <a href="/admin/services/<?= $service['id'] ?>/edit" class="text-primary hover:underline">
                                        Edit
                                    </a>
                                    <form action="/admin/services/<?= $service['id'] ?>/delete" method="POST" 
                                          onsubmit="return confirm('Yakin ingin menghapus layanan ini?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="text-red-400 hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
