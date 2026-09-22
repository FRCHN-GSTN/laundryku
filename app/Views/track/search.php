<!DOCTYPE html>
<html class="dark" lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Pesanan — Laundryku</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: { extend: { colors: { 'dark': '#191825', 'dark-secondary': '#060047', 'primary': '#865DFF', 'primary-light': '#E384FF' } } }
        }
    </script>
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: #191825; }
        .card { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); }
        .input-field { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); }
        .input-field:focus { border-color: #865DFF; outline: none; box-shadow: 0 0 0 2px rgba(134, 93, 255, 0.3); }
    </style>
</head>
<body class="text-white min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md px-6">
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2 mb-6">
                <div class="w-10 h-10 rounded-full bg-[#060047] border border-[#865DFF]/50 flex items-center justify-center text-[#FFA3FD]">
                    🧺
                </div>
                <span class="text-xl font-bold text-primary">Laundryku</span>
            </a>
            <h1 class="text-2xl font-bold mb-2">Lacak Pesanan</h1>
            <p class="text-gray-400 text-sm">Masukkan kode pesanan Anda untuk melacak status</p>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 text-sm">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <div class="card rounded-2xl p-6">
            <form action="/track" method="POST">
                <?= csrf_field() ?>
                <label class="block text-sm text-gray-400 mb-2">Kode Pesanan</label>
                <div class="flex gap-2">
                    <input type="text" name="order_code" required
                           class="input-field flex-1 px-4 py-3 rounded-lg text-white placeholder-gray-500"
                           placeholder="ORD-20260922-0001">
                    <button type="submit" class="px-6 py-3 rounded-lg font-semibold text-white" style="background: #865DFF;">
                        Lacak
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center text-gray-500 text-xs mt-6">
            <a href="/" class="text-primary hover:underline">← Kembali ke beranda</a>
        </p>
    </div>
</body>
</html>
