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

    // 4. Simpan Data Aset Baru (UPDATE HANDLE UPLOAD FILE)
    public function store()
    {
        $masterDataId = $this->request->getPost('master_data_id');
        $components   = $this->componentModel->getComponentsByMasterData($masterDataId);

        $specsInput   = $this->request->getPost('specs') ?? [];
        $specsFiles   = $this->request->getFiles()['specs'] ?? []; // Ambil file ter-upload dalam array specs

        $specifications = [];
        foreach ($components as $comp) {
            $key  = $comp['key_komponen'];
            $type = $comp['tipe_input'] ?? 'text';

            // Jika tipe komponen adalah file/foto
            if ($type === 'file' || $type === 'foto') {
                if (isset($specsFiles[$key]) && $specsFiles[$key]->isValid() && !$specsFiles[$key]->hasMoved()) {
                    $file     = $specsFiles[$key];
                    $fileName = $file->getRandomName(); // Generate nama unik
                    
                    // Simpan file ke folder public/uploads/specs
                    $file->move(FCPATH . 'uploads/specs', $fileName);
                    
                    $specifications[$key] = $fileName;
                }
            } else {
                // Untuk tipe input biasa (text, number, date, dsb)
                if (isset($specsInput[$key]) && $specsInput[$key] !== '') {
                    $specifications[$key] = $specsInput[$key];
                }
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

    // 6. Update Data Aset (UPDATE HANDLE UPDATE/KEEP/DELETE FILE)
    public function update($id)
    {
        $asset    = $this->assetModel->find($id);
        $oldSpecs = json_decode($asset['specifications'] ?? '{}', true) ?? [];

        $masterDataId = $this->request->getPost('master_data_id');
        $components   = $this->componentModel->getComponentsByMasterData($masterDataId);

        $specsInput = $this->request->getPost('specs') ?? [];
        $specsFiles = $this->request->getFiles()['specs'] ?? [];

        $specifications = [];
        foreach ($components as $comp) {
            $key  = $comp['key_komponen'];
            $type = $comp['tipe_input'] ?? 'text';

            if ($type === 'file' || $type === 'foto') {
                // Jika user mengunggah foto BARU
                if (isset($specsFiles[$key]) && $specsFiles[$key]->isValid() && !$specsFiles[$key]->hasMoved()) {
                    $file     = $specsFiles[$key];
                    $fileName = $file->getRandomName();
                    $file->move(FCPATH . 'uploads/specs', $fileName);

                    // Hapus foto lama jika ada di server
                    if (!empty($oldSpecs[$key]) && file_exists(FCPATH . 'uploads/specs/' . $oldSpecs[$key])) {
                        unlink(FCPATH . 'uploads/specs/' . $oldSpecs[$key]);
                    }

                    $specifications[$key] = $fileName;
                } else {
                    // Jika tidak mengunggah foto baru, pakai nama foto LAMA
                    if (isset($oldSpecs[$key])) {
                        $specifications[$key] = $oldSpecs[$key];
                    }
                }
            } else {
                if (isset($specsInput[$key]) && $specsInput[$key] !== '') {
                    $specifications[$key] = $specsInput[$key];
                }
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

    // 7. Hapus Aset (UPDATE HAPUS FILE GAMBAR JIKA ASET DIHAPUS)
    public function delete($id)
    {
        $asset = $this->assetModel->find($id);
        if ($asset) {
            $specs = json_decode($asset['specifications'] ?? '{}', true) ?? [];
            
            // Hapus semua file gambar aset ini dari direktori server
            foreach ($specs as $val) {
                if (is_string($val) && file_exists(FCPATH . 'uploads/specs/' . $val)) {
                    @unlink(FCPATH . 'uploads/specs/' . $val);
                }
            }

            $this->assetModel->delete($id);
        }

        return redirect()->to('/asset')->with('message', 'Aset berhasil dihapus!');
    }
}