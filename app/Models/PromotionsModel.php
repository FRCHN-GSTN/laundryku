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
        'end_date', 'is_active',
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
}
