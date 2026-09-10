<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetModel extends Model
{
    protected $table            = 'assets';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'master_data_id', 
        'user_id', 
        'status', 
        'specifications'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getAssetWithDetails(int $id = null)
    {
        $builder = $this->select('assets.*, master_data.nama_kategori, users.nama as nama_pengguna')
                        ->join('master_data', 'master_data.id = assets.master_data_id')
                        ->join('users', 'users.id = assets.user_id', 'left');

        if ($id !== null) {
            $asset = $builder->where('assets.id', $id)->first();
            if ($asset && !empty($asset['specifications'])) {
                $asset['specifications'] = json_decode($asset['specifications'], true);
            }
            return $asset;
        }

        return $builder->findAll();
    }
}