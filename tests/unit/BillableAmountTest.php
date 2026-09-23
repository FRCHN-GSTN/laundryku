<?php

use App\Models\OrderModel;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class BillableAmountTest extends CIUnitTestCase
{
    public function testPrefersConfirmedPrice(): void
    {
        $order = [
            'confirmed_price' => 90000.0,
            'final_price'     => 100000.0,
            'total_price'     => 110000.0,
        ];

        $this->assertSame(90000.0, OrderModel::billableAmount($order));
    }

    public function testFallsBackToFinalPriceWhenConfirmedNull(): void
    {
        $order = [
            'confirmed_price' => null,
            'final_price'     => 100000.0,
            'total_price'     => 110000.0,
        ];

        $this->assertSame(100000.0, OrderModel::billableAmount($order));
    }

    public function testFallsBackToTotalPriceWhenNoConfirmedOrFinal(): void
    {
        $order = [
            'confirmed_price' => null,
            'final_price'     => null,
            'total_price'     => 110000.0,
        ];

        $this->assertSame(110000.0, OrderModel::billableAmount($order));
    }

    public function testTreatsEmptyStringAsMissing(): void
    {
        $order = [
            'confirmed_price' => '',
            'final_price'     => '',
            'total_price'     => 50000.0,
        ];

        $this->assertSame(50000.0, OrderModel::billableAmount($order));
    }

    public function testReturnsZeroWhenNoPriceFields(): void
    {
        $this->assertSame(0.0, OrderModel::billableAmount([]));
    }

    public function testBillableSqlUsesCoalesce(): void
    {
        $sql = OrderModel::billableSql('revenue');

        $this->assertStringContainsString('COALESCE(confirmed_price, final_price, total_price)', $sql);
        $this->assertStringEndsWith(' as revenue', $sql);
    }

    public function testConfirmedPriceZeroIsRespected(): void
    {
        $order = [
            'confirmed_price' => 0,
            'final_price'     => 100000.0,
            'total_price'     => 110000.0,
        ];

        $this->assertSame(0.0, OrderModel::billableAmount($order));
    }
}
