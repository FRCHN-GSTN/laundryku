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
    protected $allowedFields = [
        'order_code', 'user_id', 'total_weight', 'total_price',
        'delivery_type', 'delivery_address', 'notes', 'status',
        'estimated_date', 'confirmed_weight', 'confirmed_price', 'admin_notes',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Valid status transitions
     * Key = current status, Value = array of allowed next statuses
     */
    protected array $validTransitions = [
        'pending'   => ['confirmed', 'cancelled'],
        'confirmed' => ['washing', 'cancelled'],
        'washing'   => ['drying'],
        'drying'    => ['ironing'],
        'ironing'   => ['ready'],
        'ready'     => ['delivered'],
        'delivered' => ['completed'],
        'completed' => [],
        'cancelled' => [],
    ];

    /**
     * Status display labels
     */
    public static array $statusLabels = [
        'pending'   => 'Menunggu Konfirmasi',
        'confirmed' => 'Dikonfirmasi',
        'washing'   => 'Dicuci',
        'drying'    => 'Dijemur',
        'ironing'   => 'Disetrika',
        'ready'     => 'Siap Diambil',
        'delivered' => 'Diantar',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    public static array $statusColors = [
        'pending'   => 'bg-gray-500/20 text-gray-400',
        'confirmed' => 'bg-primary/20 text-primary',
        'washing'   => 'bg-blue-500/20 text-blue-400',
        'drying'    => 'bg-blue-500/20 text-blue-400',
        'ironing'   => 'bg-blue-500/20 text-blue-400',
        'ready'     => 'bg-amber-500/20 text-amber-400',
        'delivered' => 'bg-amber-500/20 text-amber-400',
        'completed' => 'bg-green-500/20 text-green-400',
        'cancelled' => 'bg-red-500/20 text-red-400',
    ];

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

    /**
     * Check if a status transition is valid
     */
    public function isValidTransition(string $currentStatus, string $newStatus): bool
    {
        $allowed = $this->validTransitions[$currentStatus] ?? [];
        return in_array($newStatus, $allowed);
    }

    /**
     * Update order status with validation and history logging
     */
    public function updateStatus(int $orderId, string $newStatus, string $changedBy = 'Admin', string $note = null): bool
    {
        $order = $this->find($orderId);
        if (!$order) {
            return false;
        }

        $oldStatus = $order['status'];

        if ($oldStatus === $newStatus) {
            return true; // No change needed
        }

        if (!$this->isValidTransition($oldStatus, $newStatus)) {
            return false; // Invalid transition
        }

        // Update order status
        $this->update($orderId, ['status' => $newStatus]);

        // Log to history
        $historyModel = new \App\Models\OrderStatusHistoryModel();
        $historyModel->insert([
            'order_id'    => $orderId,
            'old_status'  => $oldStatus,
            'new_status'  => $newStatus,
            'note'        => $note,
            'changed_by'  => $changedBy,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        // Calculate estimated date when order is confirmed
        if ($newStatus === 'confirmed' && empty($order['estimated_date'])) {
            $estimatedDate = date('Y-m-d', strtotime('+2 days'));
            $this->update($orderId, ['estimated_date' => $estimatedDate]);
        }

        return true;
    }

    /**
     * Get status transition history for an order
     */
    public function getStatusHistory(int $orderId): array
    {
        $historyModel = new \App\Models\OrderStatusHistoryModel();
        return $historyModel->where('order_id', $orderId)
                            ->orderBy('created_at', 'ASC')
                            ->findAll();
    }

    /**
     * Confirm weight and recalculate price
     */
    public function confirmWeight(int $orderId, float $confirmedWeight, float $confirmedPrice): bool
    {
        return $this->update($orderId, [
            'confirmed_weight' => $confirmedWeight,
            'confirmed_price'  => $confirmedPrice,
        ]);
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

    public function getStats($userId = null)
    {
        $builder = $this->db->table('orders');
        $builder->select('
            COUNT(*) as total_today,
            SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status IN ("washing","drying","ironing") THEN 1 ELSE 0 END) as processing,
            SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed
        ');
        $builder->where('DATE(created_at)', date('Y-m-d'));

        if ($userId) {
            $builder->where('user_id', $userId);
        }

        $result = $builder->get()->getRowArray();

        return [
            'total_today' => (int) ($result['total_today'] ?? 0),
            'pending'     => (int) ($result['pending'] ?? 0),
            'processing'  => (int) ($result['processing'] ?? 0),
            'completed'   => (int) ($result['completed'] ?? 0),
        ];
    }
}
