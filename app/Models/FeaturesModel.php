<?php

namespace App\Models;

use CodeIgniter\Model;

class FeaturesModel extends Model
{
    protected $table = 'features';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['title', 'description', 'icon', 'badge_text', 'badge_color', 'sort_order', 'is_active'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getActive(): array
    {
        return $this->where('is_active', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    }
}
