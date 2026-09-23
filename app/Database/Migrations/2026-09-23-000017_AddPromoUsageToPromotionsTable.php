<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPromoUsageToPromotionsTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('promotions', [
            'usage_limit' => [
                'type'     => 'INT',
                'null'     => true,
                'default'  => null,
            ],
            'used_count' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'default'    => 0,
                'null'       => false,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('promotions', 'usage_limit');
        $this->forge->dropColumn('promotions', 'used_count');
    }
}
