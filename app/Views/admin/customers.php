<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="card rounded-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold">Daftar Pelanggan</h3>
    </div>

    <?php if (empty($customers)): ?>
        <div class="text-center py-12 text-gray-400">
            <p>Belum ada pelanggan terdaftar</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-400 text-sm">
                        <th class="pb-3">Nama</th>
                        <th class="pb-3">Email</th>
                        <th class="pb-3">Telepon</th>
                        <th class="pb-3">Alamat</th>
                        <th class="pb-3">Terdaftar</th>
                        <th class="pb-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                        <tr class="border-t border-white/10 hover:bg-white/5 transition-colors">
                            <td class="py-3">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center mr-3">
                                        <span class="text-primary font-semibold">
                                            <?= esc(strtoupper(substr($customer['name'], 0, 1))) ?>
                                        </span>
                                    </div>
                                    <span class="font-medium"><?= esc($customer['name']) ?></span>
                                </div>
                            </td>
                            <td class="py-3 text-gray-400"><?= esc($customer['email']) ?></td>
                            <td class="py-3 text-gray-400"><?= esc($customer['phone'] ?? '-') ?></td>
                            <td class="py-3 text-gray-400 max-w-xs truncate"><?= esc($customer['address'] ?? '-') ?></td>
                            <td class="py-3 text-gray-400"><?= date('d M Y', strtotime($customer['created_at'])) ?></td>
                            <td class="py-3">
                                <a href="/admin/customers/<?= $customer['id'] ?>" class="text-primary hover:underline">
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

<?= $this->endSection() ?>
