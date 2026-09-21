<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCategoryToServicesTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('services', [
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'kiloan',
                'after' => 'unit',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('services', 'category');
    }
}
