<?php

namespace App\Controllers;

use App\Models\AssetModel;
use App\Models\AssetLocationLogModel;

class AssetLocation extends BaseController
{
    protected $assetModel;
    protected $logModel;

    public function __construct()
    {
        $this->assetModel = new AssetModel();
        $this->logModel   = new AssetLocationLogModel();
    }

    public function index($assetId)
    {
        $asset = $this->assetModel->getAssetWithDetails((int) $assetId);
        if (!$asset) {
            return redirect()->to('/asset')->with('error', 'Aset tidak ditemukan!');
        }

        $logs = $this->logModel
            ->where('asset_id', $assetId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Riwayat Lokasi Aset',
            'asset' => $asset,
            'logs'  => $logs,
        ];

        return view('asset/location', $data);
    }

    public function store($assetId)
    {
        $asset = $this->assetModel->find((int) $assetId);
        if (!$asset) {
            return redirect()->to('/asset')->with('error', 'Aset tidak ditemukan!');
        }

        $locationTo   = (string) $this->request->getPost('location_to');
        $departmentTo = (string) $this->request->getPost('department_to');

        $this->logModel->insert([
            'asset_id'        => (int) $assetId,
            'location_from'   => $asset['location'] ?? null,
            'location_to'     => $locationTo ?: ($asset['location'] ?? null),
            'department_from' => $asset['department'] ?? null,
            'department_to'   => $departmentTo ?: ($asset['department'] ?? null),
            'note'            => $this->request->getPost('note'),
            'user_id'         => session()->get('id'),
        ]);

        $this->assetModel->update($assetId, [
            'location'   => $locationTo ?: $asset['location'],
            'department' => $departmentTo ?: $asset['department'],
        ]);

        log_activity('location_change', 'asset', (int) $assetId, 'Perubahan lokasi/departemen');

        return redirect()->to('/asset/' . $assetId . '/locations')->with('message', 'Lokasi aset berhasil diperbarui!');
    }
}