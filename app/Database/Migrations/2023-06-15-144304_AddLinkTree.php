<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLinkTree extends Migration
{
    public function up()
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'user_id' => [
                'type' => 'INT',
                'constraint' => 9,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'url' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'visits' => [
                'type' => 'INT',
                'constraint' => 9,
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
            ],
        ]);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('linktrees');
    }

    public function down()
    {
        $this->forge->dropTable('linktrees');
    }
}
