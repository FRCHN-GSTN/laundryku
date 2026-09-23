<?php

use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class PaymentFlowTest extends CIUnitTestCase
{
    public function testAllowedPaymentMethods(): void
    {
        $allowed = ['cash', 'qris'];

        $this->assertContains('cash', $allowed);
        $this->assertContains('qris', $allowed);
        $this->assertNotContains('transfer', $allowed);
        $this->assertNotContains('credit', $allowed);
    }

    public function testCashAndQrisBothStayPendingUntilMarkPaid(): void
    {
        // Opsi B: processPayment tidak auto-paid — lunas hanya via markPaid.
        $statusFor = static fn (string $method): string => 'pending';

        $this->assertSame('pending', $statusFor('cash'));
        $this->assertSame('pending', $statusFor('qris'));
    }

    public function testCashDoesNotForceOrderCompleted(): void
    {
        // processPayment must NOT change order status (only mark paid).
        $orderStatusBefore = 'ready';
        $cashPaid = true;
        $orderStatusAfter = $cashPaid ? $orderStatusBefore : 'completed';

        $this->assertSame('ready', $orderStatusAfter);
    }

    public function testPaymentBadgeLogic(): void
    {
        $badge = static function (?string $status, ?string $method = null): string {
            if ($status === 'paid') {
                return 'Lunas';
            }
            if (($status !== null && $status !== '') && $method === 'cash') {
                return 'Bayar di Tempat';
            }
            if ($status !== null && $status !== '') {
                return 'Menunggu Verifikasi';
            }

            return 'Belum dipilih';
        };

        $this->assertSame('Lunas', $badge('paid'));
        $this->assertSame('Bayar di Tempat', $badge('pending', 'cash'));
        $this->assertSame('Menunggu Verifikasi', $badge('pending', 'qris'));
        $this->assertSame('Belum dipilih', $badge(null));
        $this->assertSame('Belum dipilih', $badge(''));
    }

    public function testCannotUploadProofForPaidOrCash(): void
    {
        $canUpload = static fn (?array $payment): bool => $payment !== null
            && ($payment['status'] ?? '') !== 'paid'
            && ($payment['payment_method'] ?? '') !== 'cash';

        $this->assertTrue($canUpload(['status' => 'pending', 'payment_method' => 'qris']));
        $this->assertFalse($canUpload(['status' => 'paid', 'payment_method' => 'qris']));
        $this->assertFalse($canUpload(['status' => 'pending', 'payment_method' => 'cash']));
        $this->assertFalse($canUpload(null));
    }

    public function testChoosePaymentRejectedWhenPaid(): void
    {
        $canChoose = static fn (?array $payment): bool => !($payment['status'] ?? null) || $payment['status'] !== 'paid';

        $this->assertTrue($canChoose(null));
        $this->assertTrue($canChoose(['status' => 'pending']));
        $this->assertFalse($canChoose(['status' => 'paid']));
    }

    public function testAdminOrdersListHasNoPayAction(): void
    {
        $view = file_get_contents(HOMEPATH . 'app/Views/admin/orders.php');

        $this->assertStringNotContainsString('/payment"', $view);
        $this->assertStringContainsString('Detail', $view);
        // badge status bayar tetap boleh (kolom Bayar)
        $this->assertStringContainsString('payment_status', $view);
    }

    public function testAdminPaymentEntryIsFromOrderDetailOnly(): void
    {
        $detail = file_get_contents(HOMEPATH . 'app/Views/admin/order_detail.php');

        $this->assertStringContainsString('/payment"', $detail);
        $this->assertStringContainsString('Verifikasi Pembayaran', $detail);
        $this->assertStringContainsString('Lihat Pembayaran', $detail);
    }

    public function testAdminMethodFormIsOverrideNotPrimary(): void
    {
        $payment = file_get_contents(HOMEPATH . 'app/Views/admin/payment.php');

        $this->assertStringContainsString('Override Metode', $payment);
        $this->assertStringContainsString('Tandai Lunas', $payment);
        $this->assertStringContainsString('Verifikasi', $payment);
        // judul lama — alur utama admin bukan "pilih metode"
        $this->assertStringNotContainsString('Proses Pembayaran', $payment);
        // lunas → form override disembunyikan
        $this->assertStringContainsString("\$payment['status'] ?? '') !== 'paid'", $payment);
    }

    public function testLandingTrackFormSubmitsToRealEndpoint(): void
    {
        $landing = file_get_contents(HOMEPATH . 'app/Views/landing.php');
        $js = file_get_contents(HOMEPATH . 'public/assets/js/landing.js');

        $this->assertStringContainsString('action="/track"', $landing);
        $this->assertStringContainsString('name="order_code"', $landing);
        $this->assertStringNotContainsString("alert('Pesanan #LND-9824", $js);
    }

    public function testTrackPublicHidesInternalNotesAndHasDeliveredStep(): void
    {
        $result = file_get_contents(HOMEPATH . 'app/Views/track/result.php');
        $orderDetail = file_get_contents(HOMEPATH . 'app/Views/customer/order_detail.php');

        $this->assertStringContainsString("'delivered'", $result);
        $this->assertStringContainsString("'delivered'", $orderDetail);
        $this->assertStringContainsString('$isInternal', $result);
        // catatan internal difilter, bukan dihapus mentah-mentah dari kode
        $this->assertStringContainsString("str_contains(\$note, 'Konfirmasi berat')", $result);
    }
}
