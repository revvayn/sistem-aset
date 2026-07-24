<?php

namespace App\Models;

use CodeIgniter\Model;

class MasterDataComponentModel extends Model
{
    protected $table            = 'master_data_components';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['master_data_id', 'component_id', 'is_required'];

    protected $useTimestamps    = false;
}