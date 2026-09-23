<?php

use App\Models\PaymentModel;
use App\Models\RatingModel;
use App\Models\ServiceModel;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * CI4 setUpdatedField only skips when updatedField === ''.
 * Using null makes $row[null] => $row[''] and breaks INSERT with empty column.
 *
 * @internal
 */
final class PaymentModelTimestampTest extends CIUnitTestCase
{
    public function testUpdatedFieldIsEmptyStringNotNull(): void
    {
        $payment = new PaymentModel();
        $rating  = new RatingModel();
        $service = new ServiceModel();

        $ref = static fn (object $model): string => (function () {
            return $this->updatedField;
        })->call($model);

        foreach ([$payment, $rating, $service] as $model) {
            $value = $ref($model);
            $this->assertSame('', $value, get_class($model) . ' updatedField must be empty string, got ' . var_export($value, true));
            $this->assertNotNull($value);
        }
    }

    public function testInsertPayloadHasNoEmptyKey(): void
    {
        // Simulate what setUpdatedField would do with '' vs null
        $payload = [
            'order_id'       => 2,
            'amount'         => 60000,
            'payment_method' => 'qris',
            'status'         => 'pending',
            'payment_date'   => '2026-09-23 03:23:11',
            'created_at'     => '2026-09-23 03:23:11',
        ];

        $updatedField = '';
        if ($updatedField !== '' && ! array_key_exists($updatedField, $payload)) {
            $payload[$updatedField] = 'now';
        }

        $this->assertArrayNotHasKey('', $payload);
        $this->assertCount(6, $payload);
        foreach (array_keys($payload) as $key) {
            $this->assertNotSame('', (string) $key);
        }
    }
}
