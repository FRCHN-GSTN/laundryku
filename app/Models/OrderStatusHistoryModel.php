<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderStatusHistoryModel extends Model
{
    protected $table = 'order_status_history';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['order_id', 'old_status', 'new_status', 'note', 'changed_by', 'created_at'];

    protected $useTimestamps = false;

    public function getHistoryByOrder(int $orderId): array
    {
        return $this->where('order_id', $orderId)
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }
}
