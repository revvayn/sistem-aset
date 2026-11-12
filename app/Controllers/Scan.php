<?php

namespace App\Controllers;

use App\Models\AssetModel;

class Scan extends BaseController
{
    protected $assetModel;

    public function __construct()
    {
        $this->assetModel = new AssetModel();
    }

    public function index($code)
    {
        $asset = null;

        if (ctype_digit((string) $code)) {
            $asset = $this->assetModel->getAssetWithDetails((int) $code);
        }

        if (!$asset) {
            $asset = $this->assetModel
                ->select('assets.*, master_data.nama_kategori')
                ->join('master_data', 'master_data.id = assets.master_data_id', 'left')
                ->where('assets.serial_number', $code)
                ->where('assets.deleted_at', null)
                ->first();

            if ($asset && !empty($asset['specifications']) && is_string($asset['specifications'])) {
                $asset['specifications'] = json_decode($asset['specifications'], true);
            }
        }

        if (!$asset) {
            return view('errors/html/error_404', ['title' => 'Aset Tidak Ditemukan']);
        }

        $data = [
            'title' => 'Detail Aset (Scan QR)',
            'asset' => $asset,
        ];

        return view('scan/index', $data);
    }
}