<!DOCTYPE html>
<html class="dark" lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak <?= esc($order['order_code']) ?> — Laundryku</title>
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
    </style>
</head>
<body class="text-white min-h-screen py-12 px-6">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2 mb-6">
                <div class="w-10 h-10 rounded-full bg-[#060047] border border-[#865DFF]/50 flex items-center justify-center text-[#FFA3FD]">
                    🧺
                </div>
                <span class="text-xl font-bold text-primary">Laundryku</span>
            </a>
        </div>

        <!-- Order Header -->
        <div class="card rounded-2xl p-6 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold"><?= esc($order['order_code']) ?></h1>
                    <p class="text-gray-400 text-sm mt-1">Dipesan <?= date('d M Y H:i', strtotime($order['created_at'])) ?></p>
                </div>
                <?php
                $statusLabels = \App\Models\OrderModel::$statusLabels;
                $statusColors = \App\Models\OrderModel::$statusColors;
                ?>
                <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $statusColors[$order['status']] ?? '' ?>">
                    <?= $statusLabels[$order['status']] ?? $order['status'] ?>
                </span>
            </div>

            <?php if ($order['estimated_date']): ?>
                <div class="mt-4 p-3 rounded-lg bg-[#865DFF]/10 border border-[#865DFF]/30">
                    <p class="text-sm text-[#E384FF]">📅 Estimasi selesai: <strong><?= date('d M Y', strtotime($order['estimated_date'])) ?></strong></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Status Timeline -->
        <div class="card rounded-2xl p-6 mb-6">
            <h3 class="font-semibold mb-6">Status Pesanan</h3>
            <?php
            $steps = ['pending', 'confirmed', 'washing', 'drying', 'ironing', 'ready', 'delivered', 'completed'];
            $stepLabels = ['Pesanan', 'Dikonfirmasi', 'Dicuci', 'Dijemur', 'Disetrika', 'Siap', 'Diantar', 'Selesai'];
            $currentIndex = array_search($order['status'], $steps);
            if ($currentIndex === false) $currentIndex = -1;
            ?>
            <div class="flex items-center justify-between mb-6">
                <?php foreach ($steps as $index => $step): ?>
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center mb-2 text-xs
                            <?= $index <= $currentIndex ? 'bg-[#865DFF] text-white' : 'bg-white/10 text-gray-500' ?>">
                            <?php if ($index < $currentIndex): ?>✓<?php else: ?><?= $index + 1 ?><?php endif; ?>
                        </div>
                        <p class="text-[10px] text-center <?= $index <= $currentIndex ? 'text-[#865DFF]' : 'text-gray-500' ?>">
                            <?= $stepLabels[$index] ?>
                        </p>
                    </div>
                    <?php if ($index < count($steps) - 1): ?>
                        <div class="flex-1 h-1 mx-1 <?= $index < $currentIndex ? 'bg-[#865DFF]' : 'bg-white/10' ?>"></div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <?php if ($order['status'] === 'cancelled'): ?>
                <div class="p-3 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 text-center text-sm">
                    Pesanan dibatalkan
                </div>
            <?php endif; ?>
        </div>

        <!-- Detail -->
        <div class="card rounded-2xl p-6 mb-6">
            <h3 class="font-semibold mb-4">Detail</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Pengiriman</span>
                    <span><?= $order['delivery_type'] === 'pickup' ? 'Ambil Sendiri' : 'Dijemput' ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Total</span>
                    <span class="font-bold text-[#865DFF]">Rp <?= number_format(\App\Models\OrderModel::billableAmount($order), 0, ',', '.') ?></span>
                </div>
                <?php if (! empty($order['confirmed_weight'])): ?>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Berat</span>
                        <span><?= esc($order['confirmed_weight']) ?> kg</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Status History -->
        <?php if (!empty($statusHistory)): ?>
            <div class="card rounded-2xl p-6 mb-6">
                <h3 class="font-semibold mb-4">Riwayat</h3>
                <div class="space-y-3">
                    <?php foreach (array_reverse($statusHistory) as $i => $h): ?>
                        <div class="flex gap-3 text-sm">
                            <div class="w-2 h-2 rounded-full mt-2 <?= $i === 0 ? 'bg-[#865DFF]' : 'bg-white/20' ?>"></div>
                            <div>
                                <p class="font-medium"><?= esc(\App\Models\OrderModel::$statusLabels[$h['new_status']] ?? $h['new_status']) ?></p>
                                <?php
                                // Sembunyikan catatan internal admin (berat/harga) dari halaman publik
                                $note = (string) ($h['note'] ?? '');
                                $isInternal = str_contains($note, 'Konfirmasi berat') || str_contains($note, 'Harga:') || str_contains($note, 'Harga manual');
                                if ($note !== '' && ! $isInternal): ?>
                                    <p class="text-gray-400 text-xs"><?= esc($note) ?></p>
                                <?php endif; ?>
                                <p class="text-gray-500 text-xs"><?= date('d M H:i', strtotime($h['created_at'])) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <p class="text-center">
            <a href="/track" class="text-primary text-sm hover:underline">← Lacak pesanan lain</a>
        </p>
    </div>
</body>
</html>
