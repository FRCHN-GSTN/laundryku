<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="card rounded-lg p-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h3 class="text-lg font-semibold">Daftar User</h3>

        <form method="GET" action="/admin/users" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
            <input type="text"
                   name="search"
                   value="<?= esc($search) ?>"
                   placeholder="Cari nama/email/telepon..."
                   class="input-field px-3 py-2 rounded-lg text-sm text-white placeholder-gray-500 w-full sm:w-56">

            <select name="role" class="input-field px-3 py-2 rounded-lg text-sm text-white w-full sm:w-36">
                <option value="">Semua Role</option>
                <?php foreach ($roles as $roleKey => $roleLabel): ?>
                    <option value="<?= esc($roleKey) ?>" <?= $currentRole === $roleKey ? 'selected' : '' ?>>
                        <?= esc($roleLabel) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn-primary px-4 py-2 rounded-lg text-sm font-medium text-white">
                Filter
            </button>
        </form>
    </div>

    <?php if (empty($users)): ?>
        <div class="text-center py-12 text-gray-400">
            <p>Tidak ada user ditemukan</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-400 text-sm border-b border-white/10">
                        <th class="p-4">User</th>
                        <th class="p-4">Telepon</th>
                        <th class="p-4">Role</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Terdaftar</th>
                        <th class="p-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <?php $isSelf = (int) $user['id'] === (int) session()->get('user_id'); ?>
                        <tr class="border-t border-white/10 hover:bg-white/5 transition-colors">
                            <td class="p-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center mr-3 shrink-0">
                                        <span class="text-primary font-semibold">
                                            <?= esc(strtoupper(substr($user['name'], 0, 1))) ?>
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-medium">
                                            <?= esc($user['name']) ?>
                                            <?php if ($isSelf): ?>
                                                <span class="text-xs text-primary">(Anda)</span>
                                            <?php endif; ?>
                                        </p>
                                        <p class="text-sm text-gray-400"><?= esc($user['email']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-gray-400"><?= esc($user['phone'] ?? '-') ?></td>
                            <td class="p-4">
                                <form action="/admin/users/<?= $user['id'] ?>" method="POST" class="flex items-center gap-2">
                                    <?= csrf_field() ?>
                                    <select name="role" class="input-field px-2 py-1 rounded text-sm text-white" <?= $isSelf ? 'disabled' : '' ?>>
                                        <?php foreach ($roles as $roleKey => $roleLabel): ?>
                                            <option value="<?= esc($roleKey) ?>" <?= $user['role'] === $roleKey ? 'selected' : '' ?>>
                                                <?= esc($roleLabel) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" name="is_active" value="<?= (int) ($user['is_active'] ?? 1) ?>">
                                    <?php if (! $isSelf): ?>
                                        <button type="submit" class="text-primary hover:underline text-sm">Simpan</button>
                                    <?php endif; ?>
                                </form>
                            </td>
                            <td class="p-4">
                                <?php if (! empty($user['is_active'])): ?>
                                    <span class="status-badge bg-green-500/20 text-green-400">Aktif</span>
                                <?php else: ?>
                                    <span class="status-badge bg-red-500/20 text-red-400">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-gray-400">
                                <?= $user['created_at'] ? date('d M Y', strtotime($user['created_at'])) : '-' ?>
                            </td>
                            <td class="p-4">
                                <?php if (! $isSelf): ?>
                                    <form action="/admin/users/<?= $user['id'] ?>/toggle" method="POST" class="inline"
                                          onsubmit="return confirm('Yakin ubah status user ini?')">
                                        <?= csrf_field() ?>
                                        <?php if (! empty($user['is_active'])): ?>
                                            <button type="submit" class="text-red-400 hover:underline text-sm">Nonaktifkan</button>
                                        <?php else: ?>
                                            <button type="submit" class="text-green-400 hover:underline text-sm">Aktifkan</button>
                                        <?php endif; ?>
                                    </form>
                                <?php else: ?>
                                    <span class="text-gray-500 text-sm">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
