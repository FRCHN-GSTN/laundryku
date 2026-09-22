<?= $this->extend('layouts/customer') ?>

<?= $this->section('content') ?>

<div class="max-w-xl">
    <div class="card rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-2">Beri Penilaian</h3>
        <p class="text-sm text-gray-400 mb-6">Pesanan <?= esc($order['order_code']) ?></p>

        <form action="/customer/orders/<?= $order['id'] ?>/rate" method="POST">
            <?= csrf_field() ?>

            <div class="mb-6">
                <label class="block text-sm text-gray-400 mb-3">Rating</label>
                <div class="flex gap-2" id="ratingStars">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <button type="button" onclick="setRating(<?= $i ?>)"
                                class="text-3xl transition-colors <?= ($rating['rating'] ?? 0) >= $i ? 'text-amber-400' : 'text-gray-600' ?>"
                                data-star="<?= $i ?>">
                            ★
                        </button>
                    <?php endfor; ?>
                </div>
                <input type="hidden" name="rating" id="ratingValue" value="<?= esc($rating['rating'] ?? 0) ?>">
            </div>

            <div class="mb-6">
                <label class="block text-sm text-gray-400 mb-2">Ulasan (opsional)</label>
                <textarea name="review" rows="4"
                          class="input-field w-full px-4 py-3 rounded-lg text-white placeholder-gray-500"
                          placeholder="Ceritakan pengalaman Anda..."><?= esc($rating['review'] ?? '') ?></textarea>
            </div>

            <div class="flex gap-4">
                <a href="/customer/orders/<?= $order['id'] ?>"
                   class="flex-1 py-3 rounded-lg font-semibold text-white border border-white/20 hover:bg-white/10 transition-all text-center">
                    Kembali
                </a>
                <button type="submit" class="flex-1 btn-primary py-3 rounded-lg font-semibold text-white">
                    Kirim Penilaian
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function setRating(value) {
    document.getElementById('ratingValue').value = value;
    document.querySelectorAll('#ratingStars button').forEach((btn, index) => {
        btn.classList.toggle('text-amber-400', index < value);
        btn.classList.toggle('text-gray-600', index >= value);
    });
}
</script>

<?= $this->endSection() ?>
