<?= $this->extend('layouts/customer') ?>

<?= $this->section('content') ?>

<div class="max-w-3xl">
    <div class="card rounded-lg p-6">
        <form action="/customer/order/create" method="POST">
            <?= csrf_field() ?>
            
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">Pilih Layanan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($services as $service): ?>
                        <div class="card rounded-lg p-4 hover:border-primary/30 transition-colors">
                            <label class="flex items-start cursor-pointer">
                                <input type="checkbox" name="services[]" value="<?= esc($service['id'], 'attr') ?>" 
                                       class="mt-1 mr-3 w-5 h-5 rounded border-gray-600 text-primary focus:ring-primary"
                                       onchange="updateTotal()">
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                             <p class="font-medium"><?= esc($service['name']) ?></p>
                                             <p class="text-sm text-gray-400"><?= esc($service['description']) ?></p>
                                        </div>
                                        <p class="text-primary font-semibold">
                                            Rp <?= number_format($service['price'], 0, ',', '.') ?>
                                             <span class="text-xs text-gray-400">/<?= esc($service['unit']) ?></span>
                                        </p>
                                    </div>
                                    <div class="mt-3">
                                        <label class="text-sm text-gray-400">Jumlah:</label>
                                         <input type="number" name="quantities[<?= esc($service['id'], 'attr') ?>]" 
                                               value="1" min="0.5" step="0.5"
                                               class="input-field w-24 px-3 py-2 rounded-lg text-white ml-2"
                                               data-price="<?= esc($service['price'], 'attr') ?>"
                                               data-unit="<?= esc($service['unit'], 'attr') ?>"
                                               onchange="updateTotal()">
                                         <span class="text-sm text-gray-400 ml-1"><?= esc($service['unit']) ?></span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">Pengiriman</h3>
                <div class="flex flex-wrap gap-4">
                    <label class="flex items-center card rounded-lg px-6 py-4 cursor-pointer hover:border-primary/30 transition-colors">
                        <input type="radio" name="delivery_type" value="pickup" checked 
                               class="mr-3 w-5 h-5 text-primary" onchange="toggleDelivery()">
                        <div>
                            <p class="font-medium">Ambil Sendiri</p>
                            <p class="text-sm text-gray-400">Datang ke toko</p>
                        </div>
                    </label>
                    <label class="flex items-center card rounded-lg px-6 py-4 cursor-pointer hover:border-primary/30 transition-colors">
                        <input type="radio" name="delivery_type" value="delivery" 
                               class="mr-3 w-5 h-5 text-primary" onchange="toggleDelivery()">
                        <div>
                            <p class="font-medium">Dijemput</p>
                            <p class="text-sm text-gray-400">Kami jemput ke lokasi</p>
                        </div>
                    </label>
                </div>
            </div>

            <div id="deliveryAddress" class="mb-8 hidden">
                <h3 class="text-lg font-semibold mb-4">Alamat Pengiriman</h3>
                <textarea name="delivery_address" rows="3"
                          class="input-field w-full px-4 py-3 rounded-lg text-white placeholder-gray-500"
                          placeholder="Masukkan alamat lengkap pengiriman"></textarea>
            </div>

            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">Catatan</h3>
                <textarea name="notes" rows="3"
                          class="input-field w-full px-4 py-3 rounded-lg text-white placeholder-gray-500"
                          placeholder="Contoh: ada noda di baju"></textarea>
            </div>

            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">Kode Promo</h3>
                <div class="flex gap-2">
                    <input type="text" name="promo_code" id="promoCode"
                           class="input-field flex-1 px-4 py-3 rounded-lg text-white placeholder-gray-500"
                           placeholder="Masukkan kode promo (opsional)">
                    <button type="button" onclick="validatePromo()" id="promoBtn"
                            class="px-6 py-3 rounded-lg font-semibold text-white border border-white/20 hover:bg-white/10 transition-all">
                        Cek
                    </button>
                </div>
                <div id="promoMessage" class="mt-2 text-sm hidden"></div>
                <input type="hidden" name="discount_amount" id="discountAmount" value="0">
            </div>

            <div class="card rounded-lg p-4 mb-6">
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Subtotal</span>
                        <span id="subtotalDisplay" class="font-medium">Rp 0</span>
                    </div>
                    <div id="discountRow" class="flex justify-between items-center hidden">
                        <span class="text-green-400">Diskon</span>
                        <span id="discountDisplay" class="font-medium text-green-400">- Rp 0</span>
                    </div>
                    <div class="flex justify-between items-center border-t border-white/10 pt-2">
                        <span class="text-lg font-semibold">Total Bayar</span>
                        <span id="totalPrice" class="text-2xl font-bold text-primary">Rp 0</span>
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <a href="/customer/dashboard" class="flex-1 py-3 rounded-lg font-semibold text-white border border-white/20 hover:bg-white/10 transition-all text-center">
                    Batal
                </a>
                <button type="submit" class="flex-1 btn-primary py-3 rounded-lg font-semibold text-white">
                    Pesan Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function updateTotal() {
    let total = 0;
    const checkboxes = document.querySelectorAll('input[name="services[]"]');
    
    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            const serviceId = checkbox.value;
            const quantityInput = document.querySelector(`input[name="quantities[${serviceId}]"]`);
            const price = parseFloat(quantityInput.dataset.price);
            const quantity = parseFloat(quantityInput.value) || 0;
            total += Math.round(price * quantity);
        }
    });

    document.getElementById('subtotalDisplay').textContent = 'Rp ' + total.toLocaleString('id-ID');

    const discount = parseFloat(document.getElementById('discountAmount').value) || 0;
    const finalTotal = Math.max(0, total - Math.round(discount));
    document.getElementById('totalPrice').textContent = 'Rp ' + finalTotal.toLocaleString('id-ID');
}

function toggleDelivery() {
    const deliveryType = document.querySelector('input[name="delivery_type"]:checked').value;
    const addressField = document.getElementById('deliveryAddress');
    
    if (deliveryType === 'delivery') {
        addressField.classList.remove('hidden');
    } else {
        addressField.classList.add('hidden');
    }
}

function validatePromo() {
    const code = document.getElementById('promoCode').value.trim();
    if (!code) return;

    let total = 0;
    document.querySelectorAll('input[name="services[]"]').forEach(checkbox => {
        if (checkbox.checked) {
            const serviceId = checkbox.value;
            const quantityInput = document.querySelector(`input[name="quantities[${serviceId}]"]`);
            const price = parseFloat(quantityInput.dataset.price);
            const quantity = parseFloat(quantityInput.value) || 0;
            total += Math.round(price * quantity);
        }
    });

    const msgEl = document.getElementById('promoMessage');
    const discountRow = document.getElementById('discountRow');
    const discountDisplay = document.getElementById('discountDisplay');
    const discountAmount = document.getElementById('discountAmount');

    const csrfInput = document.querySelector('input[name="<?= csrf_token_name() ?>"]');
    fetch('/api/promo/validate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfInput ? csrfInput.value : ''
        },
        body: `promo_code=${encodeURIComponent(code)}&order_total=${total}`
    })
    .then(r => r.json())
    .then(data => {
        msgEl.classList.remove('hidden');
        if (data.valid) {
            msgEl.className = 'mt-2 text-sm text-green-400';
            msgEl.textContent = data.message;
            discountAmount.value = data.discount;
            discountRow.classList.remove('hidden');
            discountDisplay.textContent = '- Rp ' + data.discount.toLocaleString('id-ID');
        } else {
            msgEl.className = 'mt-2 text-sm text-red-400';
            msgEl.textContent = data.message;
            discountAmount.value = 0;
            discountRow.classList.add('hidden');
        }
        updateTotal();
    })
    .catch(() => {
        msgEl.className = 'mt-2 text-sm text-red-400';
        msgEl.textContent = 'Gagal memvalidasi promo';
        msgEl.classList.remove('hidden');
    });
}
</script>

<?= $this->endSection() ?>
