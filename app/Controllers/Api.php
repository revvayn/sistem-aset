<?php

namespace App\Controllers;

use App\Models\AssetModel;

class Api extends BaseController
{
    protected $assetModel;

    public function __construct()
    {
        $this->assetModel = new AssetModel();
    }

    public function assets()
    {
        $assets = $this->assetModel
            ->select('assets.*, master_data.nama_kategori')
            ->join('master_data', 'master_data.id = assets.master_data_id', 'left')
            ->where('assets.deleted_at', null)
            ->orderBy('assets.id', 'DESC')
            ->findAll();

        foreach ($assets as &$ast) {
            if (is_string($ast['specifications'])) {
                $ast['specifications'] = json_decode($ast['specifications'], true);
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $assets,
        ]);
    }

    public function asset($id)
    {
        $asset = $this->assetModel->getAssetWithDetails((int) $id);
        if (!$asset) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Aset tidak ditemukan'])->setStatusCode(404);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $asset,
        ]);
    }
}