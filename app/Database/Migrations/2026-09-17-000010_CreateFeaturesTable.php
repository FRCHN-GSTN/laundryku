<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFeaturesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'icon' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'badge_text' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'badge_color' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'default' => 'purple',
            ],
            'sort_order' => [
                'type' => 'INT',
                'constraint' => 5,
                'default' => 0,
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('features');
    }

    public function down()
    {
        $this->forge->dropTable('features');
    }
}
