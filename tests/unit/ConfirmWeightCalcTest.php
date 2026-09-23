<?php

use App\Models\OrderModel;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class ConfirmWeightCalcTest extends CIUnitTestCase
{
    /**
     * Mirror of Admin::calculateConfirmedPrice for pure unit coverage.
     *
     * @param array<int, array{unit?: string, quantity: float|string, subtotal: float|string}> $items
     */
    private function calculate(array $order, array $items, float $confirmedWeight): array
    {
        $weightPart = 0.0;
        $fixedPart  = 0.0;
        $estWeight  = 0.0;

        foreach ($items as $item) {
            $subtotal = (float) $item['subtotal'];
            $quantity = (float) $item['quantity'];

            if (($item['unit'] ?? '') === 'kg') {
                $weightPart += $subtotal;
                $estWeight  += $quantity;
            } else {
                $fixedPart += $subtotal;
            }
        }

        if ($estWeight <= 0 && ! empty($order['total_weight'])) {
            $estWeight  = (float) $order['total_weight'];
            $weightPart = (float) $order['total_price'];
            $fixedPart  = 0.0;
        }

        if ($estWeight > 0) {
            $gross = round($weightPart * ($confirmedWeight / $estWeight) + $fixedPart);
        } else {
            $gross = round((float) $order['total_price']);
        }

        $discount = min((float) ($order['discount_amount'] ?? 0), $gross);
        $net = max(0, $gross - $discount);

        return [
            'gross'     => $gross,
            'discount'  => $discount,
            'net'       => $net,
            'estWeight' => $estWeight,
            'weightPart'=> $weightPart,
        ];
    }

    public function testScalesKgAndKeepsPcs(): void
    {
        $order = [
            'total_price'     => 65000,
            'total_weight'    => 10,
            'discount_amount' => 0,
        ];
        $items = [
            ['unit' => 'kg', 'quantity' => 10, 'subtotal' => 50000],
            ['unit' => 'pcs', 'quantity' => 5, 'subtotal' => 15000],
        ];

        $calc = $this->calculate($order, $items, 20.0);

        // 50000 * (20/10) + 15000 = 115000
        $this->assertSame(115000.0, $calc['gross']);
        $this->assertSame(115000.0, $calc['net']);
        $this->assertSame(0.0, $calc['discount']);
    }

    public function testAppliesDiscountToConfirmedNet(): void
    {
        $order = [
            'total_price'     => 65000,
            'total_weight'    => 10,
            'discount_amount' => 10000,
        ];
        $items = [
            ['unit' => 'kg', 'quantity' => 10, 'subtotal' => 50000],
            ['unit' => 'pcs', 'quantity' => 5, 'subtotal' => 15000],
        ];

        $calc = $this->calculate($order, $items, 20.0);

        $this->assertSame(115000.0, $calc['gross']);
        $this->assertSame(10000.0, $calc['discount']);
        $this->assertSame(105000.0, $calc['net']);
    }

    public function testDiscountCannotExceedGross(): void
    {
        $order = [
            'total_price'     => 5000,
            'total_weight'    => 5,
            'discount_amount' => 100000,
        ];
        $items = [
            ['unit' => 'kg', 'quantity' => 5, 'subtotal' => 5000],
        ];

        $calc = $this->calculate($order, $items, 5.0);

        $this->assertSame(5000.0, $calc['gross']);
        $this->assertSame(5000.0, $calc['discount']);
        $this->assertSame(0.0, (float) $calc['net']);
    }

    public function testRoundsSubtotalsConsistently(): void
    {
        $order = [
            'total_price'     => 33333,
            'total_weight'    => 3,
            'discount_amount' => 0,
        ];
        $items = [
            ['unit' => 'kg', 'quantity' => 3, 'subtotal' => 33333],
        ];

        $calc = $this->calculate($order, $items, 4.5);

        // 33333 * 1.5 = 49999.5 → 50000
        $this->assertSame(50000.0, $calc['gross']);
        $this->assertSame(50000.0, $calc['net']);
    }

    public function testFallsBackToTotalPriceWithoutKgItems(): void
    {
        $order = [
            'total_price'     => 40000,
            'total_weight'    => 8,
            'discount_amount' => 5000,
        ];
        $items = [
            ['unit' => 'pcs', 'quantity' => 2, 'subtotal' => 40000],
        ];

        $calc = $this->calculate($order, $items, 8.0);

        // no kg items → estWeight from order total_weight, weightPart = total_price
        // 40000 * (8/8) + 0 = 40000
        $this->assertSame(40000.0, $calc['gross']);
        $this->assertSame(35000.0, $calc['net']);
    }

    public function testBillableAmountMatchesConfirmedNetAfterConfirm(): void
    {
        $order = [
            'total_price'     => 65000,
            'final_price'     => 55000,
            'discount_amount' => 10000,
            'confirmed_price' => null,
        ];
        $items = [
            ['unit' => 'kg', 'quantity' => 10, 'subtotal' => 50000],
            ['unit' => 'pcs', 'quantity' => 5, 'subtotal' => 15000],
        ];

        $calc = $this->calculate($order, $items, 20.0);
        $order['confirmed_price'] = $calc['net'];

        $this->assertSame(105000.0, OrderModel::billableAmount($order));
    }
}
