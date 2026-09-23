<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice <?= esc($order['order_code']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #fff; color: #333; padding: 40px; }
        .invoice { max-width: 800px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; border-bottom: 2px solid #865DFF; padding-bottom: 20px; }
        .brand h1 { font-size: 28px; color: #865DFF; margin-bottom: 5px; }
        .brand p { font-size: 12px; color: #666; }
        .invoice-info { text-align: right; }
        .invoice-info h2 { font-size: 20px; color: #333; }
        .invoice-info p { font-size: 12px; color: #666; }
        .customer { margin-bottom: 30px; }
        .customer h3 { font-size: 14px; color: #865DFF; margin-bottom: 8px; }
        .customer p { font-size: 13px; line-height: 1.6; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background: #f8f9fa; text-align: left; padding: 12px; font-size: 12px; color: #666; border-bottom: 2px solid #dee2e6; }
        td { padding: 12px; font-size: 13px; border-bottom: 1px solid #eee; }
        .text-right { text-align: right; }
        .totals { margin-left: auto; width: 300px; }
        .totals .row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 13px; }
        .totals .row.total { border-top: 2px solid #865DFF; font-weight: 700; font-size: 16px; color: #865DFF; padding-top: 12px; }
        .footer { margin-top: 40px; text-align: center; font-size: 11px; color: #999; border-top: 1px solid #eee; padding-top: 20px; }
        @media print { body { padding: 20px; } .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="invoice">
        <div class="header">
            <div class="brand">
                <h1><?= esc($company['name']) ?></h1>
                <p><?= esc($company['address']) ?></p>
                <p>Telp: <?= esc($company['phone']) ?> | WA: <?= esc($company['whatsapp']) ?></p>
            </div>
            <div class="invoice-info">
                <h2>INVOICE</h2>
                <p><strong><?= esc($order['order_code']) ?></strong></p>
                <p>Tanggal: <?= date('d M Y', strtotime($order['created_at'])) ?></p>
            </div>
        </div>

        <div class="customer">
            <h3>Pelanggan</h3>
            <p>
                <strong><?= esc($user['name']) ?></strong><br>
                <?= esc($user['email']) ?><br>
                <?= esc($user['phone'] ?? '-') ?><br>
                <?= esc($user['address'] ?? '-') ?>
            </p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Layanan</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= esc($item['service_name']) ?></td>
                        <td class="text-right"><?= esc($item['quantity']) ?> <?= esc($item['unit']) ?></td>
                        <td class="text-right">Rp <?= number_format($item['quantity'] > 0 ? $item['subtotal'] / $item['quantity'] : 0, 0, ',', '.') ?></td>
                        <td class="text-right">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="totals">
            <?php
            $itemsSubtotal = 0;
            foreach ($items as $item) {
                $itemsSubtotal += (float) $item['subtotal'];
            }
            $billable = \App\Models\OrderModel::billableAmount($order);
            $gross = ! empty($order['confirmed_weight']) ? $itemsSubtotal : (float) $order['total_price'];
            $discountShow = min((float) ($order['discount_amount'] ?? 0), $gross);
            ?>
            <div class="row">
                <span>Subtotal</span>
                <span>Rp <?= number_format($gross, 0, ',', '.') ?></span>
            </div>
            <?php if ($discountShow > 0): ?>
                <div class="row" style="color: #22c55e;">
                    <span>Diskon</span>
                    <span>- Rp <?= number_format($discountShow, 0, ',', '.') ?></span>
                </div>
            <?php endif; ?>
            <div class="row total">
                <span>Total</span>
                <span>Rp <?= number_format($billable, 0, ',', '.') ?></span>
            </div>
            <?php if (! empty($order['confirmed_weight'])): ?>
                <div class="row">
                    <span>Berat dikonfirmasi</span>
                    <span><?= esc($order['confirmed_weight']) ?> kg</span>
                </div>
            <?php endif; ?>
            <?php if (! empty($payment)): ?>
                <div class="row">
                    <span>Metode</span>
                    <span style="text-transform: uppercase;"><?= esc($payment['payment_method'] ?? '-') ?></span>
                </div>
                <div class="row">
                    <span>Status</span>
                    <span><?= ($payment['status'] ?? '') === 'paid' ? 'Lunas' : 'Belum lunas' ?></span>
                </div>
                <?php if (! empty($payment['payment_date'])): ?>
                    <div class="row">
                        <span>Tanggal bayar</span>
                        <span><?= date('d M Y H:i', strtotime($payment['payment_date'])) ?></span>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="footer">
            <p>Terima kasih atas kunjungan Anda! 🙏</p>
            <p><?= esc($company['name']) ?> — <?= esc($company['address']) ?></p>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="background: #865DFF; color: white; border: none; padding: 12px 30px; border-radius: 8px; font-size: 14px; cursor: pointer;">Cetak Invoice</button>
    </div>
</body>
</html>
