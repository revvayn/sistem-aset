<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetLocationLogModel extends Model
{
    protected $table            = 'asset_location_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['asset_id', 'location_from', 'location_to', 'department_from', 'department_to', 'note', 'user_id'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}