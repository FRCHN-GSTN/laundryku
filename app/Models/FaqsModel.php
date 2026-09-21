<?php

namespace App\Models;

use CodeIgniter\Model;

class FaqsModel extends Model
{
    protected $table = 'faqs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['question', 'answer', 'category', 'sort_order', 'is_active'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getActive(): array
    {
        return $this->where('is_active', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    }

    public function getByCategory(string $category): array
    {
        return $this->where('is_active', 1)
                    ->where('category', $category)
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    }
}
