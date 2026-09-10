<?php

namespace App\Models;

use CodeIgniter\Model;

class ComponentModel extends Model
{
    protected $table            = 'components';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama_komponen', 'key_komponen', 'tipe_input'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Mengambil komponen berdasarkan ID Kategori
    public function getComponentsByMasterData(int $masterDataId)
    {
        return $this->db->table('master_data_components')
            ->select('components.*, master_data_components.is_required')
            ->join('components', 'components.id = master_data_components.component_id')
            ->where('master_data_components.master_data_id', $masterDataId)
            ->get()
            ->getResultArray();
    }

    // Mengambil komponen untuk banyak kategori sekaligus (hindari N+1), dikelompokkan per master_data_id
    public function getComponentsByMasterDataIds(array $masterDataIds): array
    {
        if (empty($masterDataIds)) {
            return [];
        }

        $rows = $this->db->table('master_data_components')
            ->select('master_data_components.master_data_id, components.*, master_data_components.is_required')
            ->join('components', 'components.id = master_data_components.component_id')
            ->whereIn('master_data_components.master_data_id', $masterDataIds)
            ->get()
            ->getResultArray();

        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row['master_data_id']][] = $row;
        }

        return $grouped;
    }
}