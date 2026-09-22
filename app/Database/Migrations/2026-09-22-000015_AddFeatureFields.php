<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFeatureFields extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // Orders: promo & payment proof
        $db->query('ALTER TABLE orders ADD COLUMN promo_id INT NULL AFTER notes');
        $db->query('ALTER TABLE orders ADD COLUMN discount_amount DECIMAL(10,2) DEFAULT 0 AFTER total_price');
        $db->query('ALTER TABLE orders ADD COLUMN final_price DECIMAL(10,2) NULL AFTER discount_amount');

        // Payments: proof image
        $db->query('ALTER TABLE payments ADD COLUMN proof_image VARCHAR(255) NULL AFTER status');

        // Services: image
        $db->query('ALTER TABLE services ADD COLUMN image VARCHAR(255) NULL AFTER category');
    }

    public function down()
    {
        $db = \Config\Database::connect();

        $db->query('ALTER TABLE orders DROP COLUMN promo_id');
        $db->query('ALTER TABLE orders DROP COLUMN discount_amount');
        $db->query('ALTER TABLE orders DROP COLUMN final_price');
        $db->query('ALTER TABLE payments DROP COLUMN proof_image');
        $db->query('ALTER TABLE services DROP COLUMN image');
    }
}
