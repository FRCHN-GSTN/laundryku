<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="flex justify-between items-center mb-6">
    <div></div>
    <a href="/admin/faqs/create" class="btn-primary px-4 py-2 rounded-lg text-sm font-medium text-white">
        + Tambah FAQ
    </a>
</div>

<div class="card rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-gray-400 text-sm border-b border-white/10">
                    <th class="p-4">Pertanyaan</th>
                    <th class="p-4">Jawaban</th>
                    <th class="p-4">Kategori</th>
                    <th class="p-4">Urutan</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($faqs)): ?>
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-400">Belum ada FAQ</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($faqs as $faq): ?>
                        <tr class="border-t border-white/10">
                            <td class="p-4">
                                <p class="font-medium max-w-xs"><?= esc($faq['question']) ?></p>
                            </td>
                            <td class="p-4">
                                <p class="text-sm text-gray-400 max-w-sm"><?= esc(substr($faq['answer'], 0, 80)) ?>...</p>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded bg-primary/20 text-primary text-xs"><?= esc($faq['category']) ?></span>
                            </td>
                            <td class="p-4 text-gray-400"><?= $faq['sort_order'] ?></td>
                            <td class="p-4">
                                <?php if ($faq['is_active']): ?>
                                    <span class="status-badge bg-green-500/20 text-green-400">Aktif</span>
                                <?php else: ?>
                                    <span class="status-badge bg-gray-500/20 text-gray-400">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <a href="/admin/faqs/<?= $faq['id'] ?>/edit" class="text-primary hover:underline text-sm">Edit</a>
                                    <form action="/admin/faqs/<?= $faq['id'] ?>/delete" method="POST" class="inline" onsubmit="return confirm('Yakin hapus FAQ ini?')">
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
