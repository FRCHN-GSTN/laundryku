<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['order_id', 'service_id', 'quantity', 'subtotal'];

    protected $useTimestamps = false;

    public function getOrderItems($orderId)
    {
        return $this->select('order_items.*, services.name as service_name, services.unit')
                     ->join('services', 'services.id = order_items.service_id')
                     ->where('order_id', $orderId)
                     ->findAll();
    }
}
