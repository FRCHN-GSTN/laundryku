<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="card rounded-lg p-6 max-w-3xl">
    <h3 class="text-lg font-semibold mb-6">Pengaturan Perusahaan</h3>

    <form method="POST" action="/admin/settings">
        <?= csrf_field() ?>
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Nama Perusahaan</label>
                    <input type="text" name="company_name" value="<?= esc($settings['company_name'] ?? '') ?>" class="input-field w-full px-4 py-2.5 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Tagline</label>
                    <input type="text" name="company_tagline" value="<?= esc($settings['company_tagline'] ?? '') ?>" class="input-field w-full px-4 py-2.5 rounded-lg text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Telepon</label>
                    <input type="text" name="company_phone" value="<?= esc($settings['company_phone'] ?? '') ?>" class="input-field w-full px-4 py-2.5 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">WhatsApp</label>
                    <input type="text" name="company_whatsapp" value="<?= esc($settings['company_whatsapp'] ?? '') ?>" class="input-field w-full px-4 py-2.5 rounded-lg text-white" placeholder="628xxxxxxxxxx">
                </div>
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-1">Email</label>
                <input type="email" name="company_email" value="<?= esc($settings['company_email'] ?? '') ?>" class="input-field w-full px-4 py-2.5 rounded-lg text-white">
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-1">Alamat</label>
                <input type="text" name="company_address" value="<?= esc($settings['company_address'] ?? '') ?>" class="input-field w-full px-4 py-2.5 rounded-lg text-white">
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-1">Jam Operasional</label>
                <input type="text" name="operational_hours" value="<?= esc($settings['operational_hours'] ?? '') ?>" class="input-field w-full px-4 py-2.5 rounded-lg text-white">
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-1">Radius Gratis Antar (km)</label>
                <input type="number" name="free_delivery_radius" value="<?= esc($settings['free_delivery_radius'] ?? '5') ?>" class="input-field w-full px-4 py-2.5 rounded-lg text-white" min="0">
            </div>

            <hr class="border-white/10 my-6">

            <h4 class="text-md font-semibold text-gray-300">Konten Hero Landing Page</h4>

            <div>
                <label class="block text-sm text-gray-400 mb-1">Headline Utama</label>
                <input type="text" name="hero_headline" value="<?= esc($settings['hero_headline'] ?? '') ?>" class="input-field w-full px-4 py-2.5 rounded-lg text-white">
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-1">Deskripsi Hero</label>
                <textarea name="hero_description" rows="3" class="input-field w-full px-4 py-2.5 rounded-lg text-white"><?= esc($settings['hero_description'] ?? '') ?></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Rating Rata-rata</label>
                    <input type="number" name="rating_average" value="<?= esc($settings['rating_average'] ?? '4.9') ?>" class="input-field w-full px-4 py-2.5 rounded-lg text-white" step="0.1" min="0" max="5">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Total Ulasan</label>
                    <input type="number" name="total_reviews" value="<?= esc($settings['total_reviews'] ?? '15000') ?>" class="input-field w-full px-4 py-2.5 rounded-lg text-white" min="0">
                </div>
            </div>

            <hr class="border-white/10 my-6">

            <h4 class="text-md font-semibold text-gray-300">Pengaturan QRIS</h4>
            <p class="text-xs text-gray-500 mb-4">Upload gambar QRIS statis dan masukkan string QRIS untuk generate QR dinamis saat pembayaran.</p>

            <div>
                <label class="block text-sm text-gray-400 mb-1">String QRIS Statis</label>
                <textarea name="qris_static_string" rows="3" class="input-field w-full px-4 py-2.5 rounded-lg text-white font-mono text-xs" placeholder="00020101021230600016ID.DRAGONFLY.WWW..."><?= esc($settings['qris_static_string'] ?? '') ?></textarea>
                <p class="text-xs text-gray-500 mt-1">Paste string QRIS statis dari hasil generate / QR generator bank Anda</p>
            </div>

            <div class="mt-4">
                <label class="block text-sm text-gray-400 mb-1">Gambar QRIS Statis</label>
                <?php if (!empty($settings['qris_image'])): ?>
                    <div class="mb-2">
                        <img src="<?= esc($settings['qris_image']) ?>" alt="QRIS" class="w-32 h-32 rounded-lg border border-white/10">
                    </div>
                <?php endif; ?>
                <input type="text" name="qris_image" value="<?= esc($settings['qris_image'] ?? '') ?>" class="input-field w-full px-4 py-2.5 rounded-lg text-white" placeholder="URL gambar QRIS (https://...)">
                <p class="text-xs text-gray-500 mt-1">Upload gambar QRIS ke hosting lalu paste URL-nya, atau gunakan URL dari bank</p>
            </div>
        </div>

        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary px-6 py-2.5 rounded-lg text-sm font-medium text-white">
                Simpan Pengaturan
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
