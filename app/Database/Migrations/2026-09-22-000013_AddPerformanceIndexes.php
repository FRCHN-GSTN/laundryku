<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPerformanceIndexes extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // orders: status (used in WHERE for filtering/stats)
        $db->query('ALTER TABLE orders ADD INDEX idx_orders_status (status)');

        // orders: created_at (used in WHERE DATE(created_at) for today queries)
        $db->query('ALTER TABLE orders ADD INDEX idx_orders_created_at (created_at)');

        // orders: composite status + created_at (used in getStats, getTodayOrders)
        $db->query('ALTER TABLE orders ADD INDEX idx_orders_status_created (status, created_at)');

        // order_items: service_id (FK, used in joins)
        $db->query('ALTER TABLE order_items ADD INDEX idx_order_items_service_id (service_id)');

        // users: role (used in WHERE role = 'customer' for customer listing)
        $db->query('ALTER TABLE users ADD INDEX idx_users_role (role)');

        // payments: status (used in WHERE for payment checks)
        $db->query('ALTER TABLE payments ADD INDEX idx_payments_status (status)');
    }

    public function down()
    {
        $db = \Config\Database::connect();

        $db->query('ALTER TABLE orders DROP INDEX idx_orders_status');
        $db->query('ALTER TABLE orders DROP INDEX idx_orders_created_at');
        $db->query('ALTER TABLE orders DROP INDEX idx_orders_status_created');
        $db->query('ALTER TABLE order_items DROP INDEX idx_order_items_service_id');
        $db->query('ALTER TABLE users DROP INDEX idx_users_role');
        $db->query('ALTER TABLE payments DROP INDEX idx_payments_status');
    }
}
