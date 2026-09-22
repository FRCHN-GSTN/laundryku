<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['order_id', 'amount', 'payment_method', 'payment_date', 'status', 'proof_image'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = null;

    public function getPaymentByOrder($orderId)
    {
        return $this->where('order_id', $orderId)->first();
    }
}
