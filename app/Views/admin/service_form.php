<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="max-w-2xl">
    <div class="card rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-6">
            <?= isset($service) ? 'Edit Layanan' : 'Tambah Layanan Baru' ?>
        </h3>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 text-sm">
                <ul class="list-disc list-inside">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= isset($service) ? '/admin/services/' . $service['id'] . '/edit' : '/admin/services/create' ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Nama Layanan</label>
                <input type="text" name="name" value="<?= esc($service['name'] ?? old('name'), 'attr') ?>" required
                       class="input-field w-full px-4 py-3 rounded-lg text-white placeholder-gray-500"
                       placeholder="Contoh: Cuci Kering Setrika">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Deskripsi</label>
                <textarea name="description" rows="3"
                          class="input-field w-full px-4 py-3 rounded-lg text-white placeholder-gray-500"
                          placeholder="Deskripsi layanan (opsional)"><?= esc($service['description'] ?? old('description')) ?></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Foto Layanan</label>
                <?php if (isset($service) && !empty($service['image'])): ?>
                    <div class="mb-2">
                        <img src="<?= esc($service['image']) ?>" alt="Foto layanan" class="w-24 h-24 object-cover rounded-lg">
                    </div>
                <?php endif; ?>
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp"
                       class="input-field w-full px-4 py-3 rounded-lg text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/80">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, WebP. Maks 2MB.</p>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Harga (Rp)</label>
                    <input type="number" name="price" value="<?= esc($service['price'] ?? old('price'), 'attr') ?>" required min="0"
                           class="input-field w-full px-4 py-3 rounded-lg text-white placeholder-gray-500"
                           placeholder="0">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Satuan</label>
                    <select name="unit" class="input-field w-full px-4 py-3 rounded-lg text-white">
                        <option value="kg" <?= ($service['unit'] ?? old('unit')) === 'kg' ? 'selected' : '' ?>>Per Kilogram (kg)</option>
                        <option value="pcs" <?= ($service['unit'] ?? old('unit')) === 'pcs' ? 'selected' : '' ?>>Per Buah (pcs)</option>
                    </select>
                </div>
            </div>

            <?php if (isset($service)): ?>
                <div class="mb-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" 
                               <?= $service['is_active'] ? 'checked' : '' ?>
                               class="mr-3 w-5 h-5 rounded border-gray-600 text-primary focus:ring-primary">
                        <span class="text-sm font-medium text-gray-300">Aktif</span>
                    </label>
                </div>
            <?php endif; ?>

            <div class="flex gap-4">
                <a href="/admin/services" class="flex-1 py-3 rounded-lg font-semibold text-white border border-white/20 hover:bg-white/10 transition-all text-center">
                    Batal
                </a>
                <button type="submit" class="flex-1 btn-primary py-3 rounded-lg font-semibold text-white">
                    <?= isset($service) ? 'Simpan Perubahan' : 'Tambah Layanan' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
