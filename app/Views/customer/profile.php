<?= $this->extend('layouts/customer') ?>

<?= $this->section('content') ?>

<div class="max-w-2xl">
    <div class="card rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-6">Profil Saya</h3>

        <form action="/customer/profile/update" method="POST">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Nama Lengkap</label>
                <input type="text" name="name" value="<?= esc($user['name']) ?>" required
                       class="input-field w-full px-4 py-3 rounded-lg text-white placeholder-gray-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                <input type="email" value="<?= esc($user['email']) ?>" disabled
                       class="input-field w-full px-4 py-3 rounded-lg text-gray-500 bg-white/5">
                <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">No. Telepon</label>
                <input type="tel" name="phone" value="<?= esc($user['phone']) ?>" required
                       class="input-field w-full px-4 py-3 rounded-lg text-white placeholder-gray-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">Alamat</label>
                <textarea name="address" rows="3"
                          class="input-field w-full px-4 py-3 rounded-lg text-white placeholder-gray-500"
                          placeholder="Masukkan alamat"><?= esc($user['address']) ?></textarea>
            </div>

            <div class="flex gap-4">
                <a href="/customer/dashboard" class="flex-1 py-3 rounded-lg font-semibold text-white border border-white/20 hover:bg-white/10 transition-all text-center">
                    Batal
                </a>
                <button type="submit" class="flex-1 btn-primary py-3 rounded-lg font-semibold text-white">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
