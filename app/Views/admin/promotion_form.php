<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="card rounded-lg p-6 max-w-2xl">
    <h3 class="text-lg font-semibold mb-6"><?= isset($promotion) ? 'Edit Promo' : 'Tambah Promo Baru' ?></h3>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= isset($promotion) ? '/admin/promotions/' . $promotion['id'] . '/edit' : '/admin/promotions/create' ?>">
        <?= csrf_field() ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm text-gray-400 mb-1">Judul Promo *</label>
                <input type="text" name="title" value="<?= esc($promotion['title'] ?? old('title')) ?>" class="input-field w-full px-4 py-2.5 rounded-lg text-white" required>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Kode Promo</label>
                <input type="text" name="promo_code" value="<?= esc($promotion['promo_code'] ?? old('promo_code')) ?>" class="input-field w-full px-4 py-2.5 rounded-lg text-white" placeholder="Contoh: HEMAT10">
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm text-gray-400 mb-1">Deskripsi</label>
            <textarea name="description" rows="3" class="input-field w-full px-4 py-2.5 rounded-lg text-white"><?= esc($promotion['description'] ?? old('description')) ?></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-sm text-gray-400 mb-1">Tipe Diskon *</label>
                <select name="discount_type" class="input-field w-full px-4 py-2.5 rounded-lg text-white" required>
                    <option value="percentage" <?= ($promotion['discount_type'] ?? old('discount_type')) === 'percentage' ? 'selected' : '' ?>>Persen (%)</option>
                    <option value="fixed" <?= ($promotion['discount_type'] ?? old('discount_type')) === 'fixed' ? 'selected' : '' ?>>Nominal (Rp)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Nilai Diskon *</label>
                <input type="number" name="discount_value" value="<?= $promotion['discount_value'] ?? old('discount_value') ?>" class="input-field w-full px-4 py-2.5 rounded-lg text-white" min="0" required>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Diskon Maksimum</label>
                <input type="number" name="max_discount" value="<?= $promotion['max_discount'] ?? old('max_discount') ?>" class="input-field w-full px-4 py-2.5 rounded-lg text-white" min="0" placeholder="Opsional">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-sm text-gray-400 mb-1">Minimum Order</label>
                <input type="number" name="min_order" value="<?= $promotion['min_order'] ?? old('min_order') ?>" class="input-field w-full px-4 py-2.5 rounded-lg text-white" min="0" placeholder="Opsional">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Tanggal Mulai *</label>
                <input type="date" name="start_date" value="<?= $promotion['start_date'] ?? old('start_date') ?>" class="input-field w-full px-4 py-2.5 rounded-lg text-white" required>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Tanggal Selesai *</label>
                <input type="date" name="end_date" value="<?= $promotion['end_date'] ?? old('end_date') ?>" class="input-field w-full px-4 py-2.5 rounded-lg text-white" required>
            </div>
        </div>

        <?php if (isset($promotion)): ?>
        <div class="mb-4">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" <?= $promotion['is_active'] ? 'checked' : '' ?> class="rounded">
                <span class="text-sm text-gray-400">Aktif</span>
            </label>
        </div>
        <?php endif; ?>

        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary px-6 py-2.5 rounded-lg text-sm font-medium text-white">
                <?= isset($promotion) ? 'Simpan Perubahan' : 'Tambah Promo' ?>
            </button>
            <a href="/admin/promotions" class="px-6 py-2.5 rounded-lg text-sm text-gray-400 hover:text-white">Batal</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
