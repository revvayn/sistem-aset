<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropAssetCodeColumns extends Migration
{
    public function up()
    {
        if ($this->db->fieldExists('no_asset', 'assets')) {
            $this->db->query('ALTER TABLE `assets` DROP INDEX `no_asset`');
            $this->forge->dropColumn('assets', ['no_asset', 'nama_aset']);
        }
    }

    public function down()
    {
        $this->forge->addColumn('assets', [
            'no_asset' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'nama_aset' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
        ]);
    }
}