<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="card rounded-lg p-4">
        <p class="text-gray-400 text-xs mb-1">Pesanan Hari Ini</p>
        <p class="text-2xl font-bold text-primary"><?= esc($stats['total_today'] ?? 0) ?></p>
    </div>
    <div class="card rounded-lg p-4">
        <p class="text-gray-400 text-xs mb-1">Menunggu (hari ini)</p>
        <p class="text-2xl font-bold text-amber-400"><?= esc($stats['pending'] ?? 0) ?></p>
    </div>
    <div class="card rounded-lg p-4">
        <p class="text-gray-400 text-xs mb-1">Sedang Diproses (hari ini)</p>
        <p class="text-2xl font-bold text-blue-400"><?= esc($stats['processing'] ?? 0) ?></p>
    </div>
    <div class="card rounded-lg p-4">
        <p class="text-gray-400 text-xs mb-1">Selesai (hari ini)</p>
        <p class="text-2xl font-bold text-green-400"><?= esc($stats['completed'] ?? 0) ?></p>
    </div>
</div>

<div class="card rounded-lg p-6 mb-8">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-gray-400 text-sm">Pendapatan Hari Ini</p>
            <p class="text-3xl font-bold text-primary">
                Rp <?= number_format($todayRevenue['revenue'] ?? 0, 0, ',', '.') ?>
            </p>
        </div>
    </div>
</div>

<!-- Revenue Chart -->
<div class="card rounded-lg p-6 mb-8">
    <h3 class="text-lg font-semibold mb-4">Pendapatan 7 Hari Terakhir</h3>
    <div class="relative" style="height: 250px;">
        <canvas id="revenueChart"></canvas>
    </div>
</div>

<!-- Status Distribution -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="card rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-4">Distribusi Status Pesanan</h3>
        <div class="relative" style="height: 200px;">
            <canvas id="statusChart"></canvas>
        </div>
    </div>
    <div class="card rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Pesanan Menunggu Konfirmasi</h3>
            <a href="/admin/orders?status=pending" class="text-primary text-sm hover:underline">Lihat Semua</a>
        </div>
        <?php if (empty($pendingOrders)): ?>
            <div class="text-center py-8 text-gray-400">
                <p>Tidak ada pesanan baru</p>
            </div>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach (array_slice($pendingOrders, 0, 5) as $order): ?>
                    <div class="flex items-center justify-between p-4 rounded-lg bg-white/5">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center mr-4">
                                <span class="text-primary font-semibold text-sm"><?= esc(substr($order['order_code'], -4)) ?></span>
                            </div>
                            <div>
                                <p class="font-medium"><?= esc($order['order_code']) ?></p>
                                <p class="text-sm text-gray-400">Rp <?= number_format(\App\Models\OrderModel::billableAmount($order), 0, ',', '.') ?></p>
                            </div>
                        </div>
                        <a href="/admin/orders/<?= $order['id'] ?>" class="btn-primary px-4 py-2 rounded-lg text-sm font-medium text-white">
                            Detail
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="card rounded-lg p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Pesanan Hari Ini</h3>
        <a href="/admin/orders" class="text-primary text-sm hover:underline">Lihat Semua</a>
    </div>

    <?php if (empty($todayOrders)): ?>
        <div class="text-center py-8 text-gray-400">
            <p>Belum ada pesanan hari ini</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-400 text-sm">
                        <th class="pb-3">Kode Order</th>
                        <th class="pb-3">Pelanggan</th>
                        <th class="pb-3">Total</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($todayOrders, 0, 5) as $order): ?>
                        <tr class="border-t border-white/10">
                            <td class="py-3 font-medium"><?= esc($order['order_code']) ?></td>
                            <td class="py-3 text-gray-400"><?= esc($order['user_name'] ?? '-') ?></td>
                            <td class="py-3">Rp <?= number_format(\App\Models\OrderModel::billableAmount($order), 0, ',', '.') ?></td>
                            <td class="py-3">
                                <span class="status-badge <?= \App\Models\OrderModel::$statusColors[$order['status']] ?? '' ?>">
                                    <?= esc(\App\Models\OrderModel::$statusLabels[$order['status']] ?? $order['status']) ?>
                                </span>
                            </td>
                            <td class="py-3">
                                <a href="/admin/orders/<?= $order['id'] ?>" class="text-primary hover:underline">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
fetch('/admin/chart/revenue')
    .then(r => r.json())
    .then(data => {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: data.values,
                    backgroundColor: 'rgba(134, 93, 255, 0.5)',
                    borderColor: '#865DFF',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#9ca3af', callback: v => 'Rp ' + v.toLocaleString('id-ID') },
                        grid: { color: 'rgba(255,255,255,0.05)' }
                    },
                    x: {
                        ticks: { color: '#9ca3af' },
                        grid: { display: false }
                    }
                }
            }
        });
    });

fetch('/admin/chart/status')
    .then(r => r.json())
    .then(data => {
        const ctx = document.getElementById('statusChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    data: data.values,
                    backgroundColor: ['#6b7280', '#865DFF', '#3b82f6', '#f59e0b', '#22c55e', '#ef4444'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'right', labels: { color: '#9ca3af', boxWidth: 12, padding: 10 } } },
                cutout: '60%',
            }
        });
    });
</script>

<?= $this->endSection() ?>
