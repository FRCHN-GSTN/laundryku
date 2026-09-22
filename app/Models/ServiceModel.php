<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceModel extends Model
{
    protected $table = 'services';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['name', 'description', 'price', 'unit', 'category', 'is_active', 'image'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = null;

    public function getActiveServices(): array
    {
        return $this->where('is_active', 1)->findAll();
    }

    public function getByCategory(string $category): array
    {
        return $this->where('is_active', 1)
                    ->where('category', $category)
                    ->findAll();
    }

    public function getFeatured(): array
    {
        return $this->where('is_active', 1)
                    ->whereIn('category', ['kiloan', 'express', 'spa'])
                    ->limit(4)
                    ->findAll();
    }

    public function getPricingCards(): array
    {
        return $this->where('is_active', 1)
                    ->groupBy('category')
                    ->findAll();
    }
}
