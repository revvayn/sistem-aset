<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAssetStandardFields extends Migration
{
    public function up()
    {
        $fields = [
            'serial_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
                'after'      => 'status',
            ],
            'location' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
                'after'      => 'serial_number',
            ],
            'department' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
                'after'      => 'location',
            ],
            'purchase_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'department',
            ],
            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'null'       => true,
                'after'      => 'purchase_date',
            ],
            'warranty_expiry' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'price',
            ],
        ];

        $this->forge->addColumn('assets', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('assets', ['serial_number', 'location', 'department', 'purchase_date', 'price', 'warranty_expiry']);
    }
}