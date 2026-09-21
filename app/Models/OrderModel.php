<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['order_code', 'user_id', 'total_weight', 'total_price', 'delivery_type', 'delivery_address', 'notes', 'status'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function generateOrderCode()
    {
        $date = date('Ymd');
        $lastOrder = $this->where('order_code LIKE', "ORD-{$date}-%")
                           ->orderBy('id', 'DESC')
                           ->first();

        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder['order_code'], -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "ORD-{$date}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function getUserOrders($userId)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getOrdersWithUser()
    {
        return $this->select('orders.*, users.name as user_name, users.phone as user_phone')
                     ->join('users', 'users.id = orders.user_id')
                     ->orderBy('orders.created_at', 'DESC')
                     ->findAll();
    }

    public function getTodayOrders()
    {
        return $this->where('DATE(created_at)', date('Y-m-d'))
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getTodayRevenue()
    {
        return $this->select('SUM(total_price) as revenue')
                     ->where('DATE(created_at)', date('Y-m-d'))
                     ->where('status', 'completed')
                     ->first();
    }

    public function getStats()
    {
        $today = date('Y-m-d');
        return [
            'total_today' => $this->where('DATE(created_at)', $today)->countAllResults(),
            'pending' => $this->where('status', 'pending')->countAllResults(),
            'processing' => $this->where('status', 'washing')->orWhere('status', 'drying')->orWhere('status', 'ironing')->countAllResults(),
            'completed' => $this->where('status', 'completed')->countAllResults(),
        ];
    }
}
