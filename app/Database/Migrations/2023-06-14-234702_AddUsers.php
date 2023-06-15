<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUsers extends Migration
{
    public function up()
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'name' => [
                'type'              => 'VARCHAR',
                'constraint'        => '150',
            ],
            'email' => [
                'type'              => 'VARCHAR',
                'constraint'        => '150',
            ],
            'email_hash' => [
                'type'              => 'VARCHAR',
                'constraint'        => '255',
            ],
            'email_verified_at' => [
                'type'              => 'DATETIME',
                'null'              => true,
            ],
            'password' => [
                'type'              => 'VARCHAR',
                'constraint'        => '255',
            ],
            'created_at' => [
                'type'              => 'DATETIME',
                'null'              => true,
            ],
            'updated_at' => [
                'type'              => 'DATETIME',
                'null'              => true,
            ],
        ]);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
