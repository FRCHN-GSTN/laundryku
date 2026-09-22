<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="card rounded-lg p-6 max-w-2xl">
    <h3 class="text-lg font-semibold mb-6"><?= isset($faq) ? 'Edit FAQ' : 'Tambah FAQ Baru' ?></h3>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <p><?= esc($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= isset($faq) ? '/admin/faqs/' . $faq['id'] . '/edit' : '/admin/faqs/create' ?>">
        <?= csrf_field() ?>
        <div class="mb-4">
            <label class="block text-sm text-gray-400 mb-1">Pertanyaan *</label>
            <input type="text" name="question" value="<?= esc($faq['question'] ?? old('question')) ?>" class="input-field w-full px-4 py-2.5 rounded-lg text-white" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm text-gray-400 mb-1">Jawaban *</label>
            <textarea name="answer" rows="5" class="input-field w-full px-4 py-2.5 rounded-lg text-white" required><?= esc($faq['answer'] ?? old('answer')) ?></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm text-gray-400 mb-1">Kategori *</label>
                <select name="category" class="input-field w-full px-4 py-2.5 rounded-lg text-white" required>
                    <option value="umum" <?= ($faq['category'] ?? old('category')) === 'umum' ? 'selected' : '' ?>>Umum</option>
                    <option value="layanan" <?= ($faq['category'] ?? old('category')) === 'layanan' ? 'selected' : '' ?>>Layanan</option>
                    <option value="biaya" <?= ($faq['category'] ?? old('category')) === 'biaya' ? 'selected' : '' ?>>Biaya</option>
                    <option value="garansi" <?= ($faq['category'] ?? old('category')) === 'garansi' ? 'selected' : '' ?>>Garansi</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Urutan *</label>
                <input type="number" name="sort_order" value="<?= $faq['sort_order'] ?? old('sort_order', 0) ?>" class="input-field w-full px-4 py-2.5 rounded-lg text-white" min="0" required>
            </div>
        </div>

        <?php if (isset($faq)): ?>
        <div class="mb-4">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" <?= $faq['is_active'] ? 'checked' : '' ?> class="rounded">
                <span class="text-sm text-gray-400">Aktif</span>
            </label>
        </div>
        <?php endif; ?>

        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary px-6 py-2.5 rounded-lg text-sm font-medium text-white">
                <?= isset($faq) ? 'Simpan Perubahan' : 'Tambah FAQ' ?>
            </button>
            <a href="/admin/faqs" class="px-6 py-2.5 rounded-lg text-sm text-gray-400 hover:text-white">Batal</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
