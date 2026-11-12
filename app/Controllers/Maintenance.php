<?php

namespace App\Controllers;

use App\Models\AssetModel;
use App\Models\AssetMaintenanceModel;

class Maintenance extends BaseController
{
    protected $assetModel;
    protected $maintenanceModel;

    public function __construct()
    {
        $this->assetModel        = new AssetModel();
        $this->maintenanceModel  = new AssetMaintenanceModel();
    }

    public function index($assetId)
    {
        $asset = $this->assetModel->getAssetWithDetails((int) $assetId);
        if (!$asset) {
            return redirect()->to('/asset')->with('error', 'Aset tidak ditemukan!');
        }

        $logs = $this->maintenanceModel
            ->where('asset_id', $assetId)
            ->orderBy('tanggal', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Riwayat Pemeliharaan Aset',
            'asset' => $asset,
            'logs'  => $logs,
        ];

        return view('maintenance/index', $data);
    }

    public function store($assetId)
    {
        $asset = $this->assetModel->find((int) $assetId);
        if (!$asset) {
            return redirect()->to('/asset')->with('error', 'Aset tidak ditemukan!');
        }

        $rules = [
            'tanggal'    => 'required|valid_date',
            'deskripsi'  => 'required|min_length[3]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $statusSesudah = $this->request->getPost('status_sesudah');

        $this->maintenanceModel->insert([
            'asset_id'       => (int) $assetId,
            'tanggal'        => $this->request->getPost('tanggal'),
            'deskripsi'      => $this->request->getPost('deskripsi'),
            'biaya'          => $this->request->getPost('biaya') ?: 0,
            'vendor'         => $this->request->getPost('vendor'),
            'status_sebelum' => $asset['status'],
            'status_sesudah' => $statusSesudah ?: $asset['status'],
            'user_id'        => session()->get('id'),
        ]);

        if ($statusSesudah && $statusSesudah !== $asset['status']) {
            $this->assetModel->update($assetId, ['status' => $statusSesudah]);
        }

        log_activity('maintenance', 'asset', (int) $assetId, 'Tambah riwayat pemeliharaan');

        return redirect()->to('/asset/' . $assetId . '/maintenances')->with('message', 'Riwayat pemeliharaan berhasil ditambahkan!');
    }

    public function delete($id)
    {
        $log = $this->maintenanceModel->find((int) $id);
        if (!$log) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $assetId = $log['asset_id'];
        $this->maintenanceModel->delete((int) $id);

        log_activity('maintenance_delete', 'asset', (int) $assetId, 'Hapus riwayat pemeliharaan');

        return redirect()->to('/asset/' . $assetId . '/maintenances')->with('message', 'Riwayat pemeliharaan berhasil dihapus!');
    }
}