<?php

namespace App\Models;

use CodeIgniter\Model;

class HowItStepsModel extends Model
{
    protected $table = 'how_it_steps';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['step_number', 'title', 'description', 'icon', 'is_active'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getActive(): array
    {
        return $this->where('is_active', 1)
                    ->orderBy('step_number', 'ASC')
                    ->findAll();
    }
}
