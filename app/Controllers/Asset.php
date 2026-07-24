<?php

namespace App\Controllers;

use App\Models\AssetModel;
use App\Models\MasterDataModel;
use App\Models\ComponentModel;

class Asset extends BaseController
{
    /** @var AssetModel */
    protected AssetModel $assetModel;

    /** @var MasterDataModel */
    protected MasterDataModel $masterDataModel;

    /** @var ComponentModel */
    protected ComponentModel $componentModel;

    public function __construct()
    {
        $this->assetModel      = new AssetModel();
        $this->masterDataModel = new MasterDataModel();
        $this->componentModel  = new ComponentModel();
    }

    // 1. Halaman daftar aset
    public function index()
    {
        // Gunakan $this->assetModel yang sudah diinisialisasi
        $data['assets'] = $this->assetModel
            ->select('assets.*, master_data.nama_kategori')
            ->join('master_data', 'master_data.id = assets.master_data_id', 'left')
            ->findAll();

        return view('asset/index', $data);
    }

    // 2. Form Tambah Aset
    public function create()
    {
        $data['categories'] = $this->masterDataModel->findAll();
        return view('asset/create', $data);
    }

    // 3. Endpoint AJAX komponen dinamis
    public function getComponents($masterDataId)
    {
        $components = $this->componentModel->getComponentsByMasterData($masterDataId);
        return $this->response->setJSON($components);
    }

    // 4. Simpan Data Aset Baru
    public function store()
    {
        $masterDataId = $this->request->getPost('master_data_id');
        $components   = $this->componentModel->getComponentsByMasterData($masterDataId);

        // PERBAIKAN: Ambil seluruh array 'specs' dari Form POST
        $specsInput   = $this->request->getPost('specs') ?? [];

        $specifications = [];
        foreach ($components as $comp) {
            $key = $comp['key_komponen'];
            // Ambil dari array specsInput
            if (isset($specsInput[$key]) && $specsInput[$key] !== '') {
                $specifications[$key] = $specsInput[$key];
            }
        }

        $this->assetModel->save([
            'no_asset'       => $this->request->getPost('no_asset'),
            'nama_aset'      => $this->request->getPost('nama_aset'),
            'master_data_id' => $masterDataId,
            'status'         => $this->request->getPost('status'),
            'specifications' => json_encode($specifications),
        ]);

        return redirect()->to('/asset')->with('message', 'Aset berhasil disimpan!');
    }

    // 5. Form Edit Aset
    public function edit($id)
    {
        $asset = $this->assetModel->find($id);
        if (!$asset) {
            return redirect()->to('/asset')->with('error', 'Aset tidak ditemukan!');
        }

        $data = [
            'asset'      => $asset,
            'categories' => $this->masterDataModel->findAll(),
            'components' => $this->componentModel->getComponentsByMasterData($asset['master_data_id']),
            'specs'      => json_decode($asset['specifications'] ?? '{}', true) ?? [],
        ];

        return view('asset/edit', $data);
    }

    // 6. Update Data Aset
    public function update($id)
    {
        $masterDataId = $this->request->getPost('master_data_id');
        $components   = $this->componentModel->getComponentsByMasterData($masterDataId);

        // PERBAIKAN: Ambil seluruh array 'specs' dari Form POST
        $specsInput   = $this->request->getPost('specs') ?? [];

        $specifications = [];
        foreach ($components as $comp) {
            $key = $comp['key_komponen'];
            if (isset($specsInput[$key]) && $specsInput[$key] !== '') {
                $specifications[$key] = $specsInput[$key];
            }
        }

        $this->assetModel->update($id, [
            'no_asset'       => $this->request->getPost('no_asset'),
            'nama_aset'      => $this->request->getPost('nama_aset'),
            'master_data_id' => $masterDataId,
            'status'         => $this->request->getPost('status'),
            'specifications' => json_encode($specifications),
        ]);

        return redirect()->to('/asset')->with('message', 'Aset berhasil diperbarui!');
    }

    // 7. Hapus Aset
    public function delete($id)
    {
        $this->assetModel->delete($id);
        return redirect()->to('/asset')->with('message', 'Aset berhasil dihapus!');
    }
}