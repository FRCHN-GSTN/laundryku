<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOrderBusinessFields extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // Add business fields to orders table
        $db->query('ALTER TABLE orders ADD COLUMN estimated_date DATE NULL AFTER notes');
        $db->query('ALTER TABLE orders ADD COLUMN confirmed_weight DECIMAL(5,2) NULL AFTER total_weight');
        $db->query('ALTER TABLE orders ADD COLUMN confirmed_price DECIMAL(10,2) NULL AFTER total_price');
        $db->query('ALTER TABLE orders ADD COLUMN admin_notes TEXT NULL AFTER notes');

        // Create order_status_history table for audit trail
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'order_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'old_status' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'new_status' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'note' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'changed_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('order_id');
        $this->forge->addKey('created_at');
        $this->forge->createTable('order_status_history');
    }

    public function down()
    {
        $db = \Config\Database::connect();

        $db->query('ALTER TABLE orders DROP COLUMN estimated_date');
        $db->query('ALTER TABLE orders DROP COLUMN confirmed_weight');
        $db->query('ALTER TABLE orders DROP COLUMN confirmed_price');
        $db->query('ALTER TABLE orders DROP COLUMN admin_notes');

        $this->forge->dropTable('order_status_history');
    }
}
