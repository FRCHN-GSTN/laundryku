<?php

namespace App\Models;

use CodeIgniter\Model;

class PromotionsModel extends Model
{
    protected $table = 'promotions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'title', 'description', 'discount_type', 'discount_value',
        'min_order', 'max_discount', 'promo_code', 'start_date',
        'end_date', 'is_active', 'usage_limit', 'used_count',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getActive(): array
    {
        $today = date('Y-m-d');
        return $this->where('is_active', 1)
                    ->where('start_date <=', $today)
                    ->where('end_date >=', $today)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getHeroPromo(): ?array
    {
        $today = date('Y-m-d');
        return $this->where('is_active', 1)
                    ->where('start_date <=', $today)
                    ->where('end_date >=', $today)
                    ->orderBy('created_at', 'DESC')
                    ->first();
    }

    /**
     * Validate and apply promo code
     * Returns ['valid' => bool, 'discount' => float, 'message' => string, 'promo' => array|null]
     */
    public function validateCode(string $code, float $orderTotal): array
    {
        $promo = $this->where('promo_code', $code)
                      ->where('is_active', 1)
                      ->first();

        if (!$promo) {
            return ['valid' => false, 'discount' => 0, 'message' => 'Kode promo tidak valid'];
        }

        $today = date('Y-m-d');
        if ($today < $promo['start_date'] || $today > $promo['end_date']) {
            return ['valid' => false, 'discount' => 0, 'message' => 'Kode promo sudah tidak berlaku'];
        }

        if (!empty($promo['usage_limit']) && (int) $promo['used_count'] >= (int) $promo['usage_limit']) {
            return ['valid' => false, 'discount' => 0, 'message' => 'Kode promo sudah habis digunakan'];
        }

        $minOrder = (float) ($promo['min_order'] ?? 0);
        if ($minOrder > 0 && $orderTotal < $minOrder) {
            return ['valid' => false, 'discount' => 0, 'message' => 'Minimum order Rp ' . number_format($minOrder, 0, ',', '.')];
        }

        $discount = 0;
        if ($promo['discount_type'] === 'percentage') {
            $discount = $orderTotal * ((float) $promo['discount_value'] / 100);
            $maxDiscount = (float) ($promo['max_discount'] ?? 0);
            if ($maxDiscount > 0) {
                $discount = min($discount, $maxDiscount);
            }
        } else {
            $discount = (float) $promo['discount_value'];
        }

        $discount = min($discount, $orderTotal);
        $discount = (float) round($discount);

        if ($discount <= 0) {
            return ['valid' => false, 'discount' => 0, 'message' => 'Diskon tidak valid'];
        }

        return [
            'valid'    => true,
            'discount' => $discount,
            'message'  => "Diskon {$promo['title']}: Rp " . number_format($discount, 0, ',', '.'),
            'promo'    => $promo,
        ];
    }

    /**
     * Increment usage count
     */
    public function incrementUsage(int $promoId): void
    {
        $this->db->table($this->table)
                  ->where('id', $promoId)
                  ->set('used_count', 'COALESCE(used_count, 0) + 1', false)
                  ->update();
    }
}
