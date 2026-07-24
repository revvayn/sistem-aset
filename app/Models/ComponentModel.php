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
}