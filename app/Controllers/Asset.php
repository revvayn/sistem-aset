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

    // 1. Halaman daftar aset (Filter, Pencarian, JOIN & Pagination)
    public function index()
    {
        $keyword  = $this->request->getGet('keyword');
        $category = $this->request->getGet('category');

        // Query dasar dengan JOIN ke tabel master_data untuk mengambil nama_kategori
        $builder = $this->assetModel
            ->select('assets.*, master_data.nama_kategori')
            ->join('master_data', 'master_data.id = assets.master_data_id', 'left');

        // Filter Keyword (Cari dalam isi spesifikasi / JSON komponen)
        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('assets.specifications', $keyword)
                    ->groupEnd();
        }

        // Filter Kategori berdasarkan nama_kategori
        if (!empty($category)) {
            $builder->where('master_data.nama_kategori', $category);
        }

        $perPage = 10;

        $data = [
            'title'       => 'Daftar Aset',
            'assets'      => $builder->paginate($perPage, 'asset'),
            'pager'       => $this->assetModel->pager,
            'currentPage' => $this->request->getVar('page_asset') ? (int)$this->request->getVar('page_asset') : 1,
            'perPage'     => $perPage,
            'keyword'     => $keyword,
            'category'    => $category,
            'categories'  => $this->masterDataModel->findAll(), // Mengambil list kategori dari master_data
        ];

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
        if (!$this->validate([
            'master_data_id' => 'required|integer',
            'status'         => 'required|in_list[Aktif,Perbaikan,Rusak,Disimpan]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $masterDataId = (int) $this->request->getPost('master_data_id');
        $components   = $this->componentModel->getComponentsByMasterData($masterDataId);

        $specsInput   = $this->request->getPost('specs') ?? [];
        $specsFiles   = $this->request->getFiles()['specs'] ?? [];

        $specifications = [];
        foreach ($components as $comp) {
            $key  = $comp['key_komponen'];
            $type = $comp['tipe_input'] ?? 'text';

            if ($type === 'file' || $type === 'foto') {
                if (isset($specsFiles[$key]) && $specsFiles[$key]->isValid() && !$specsFiles[$key]->hasMoved()) {
                    $file    = $specsFiles[$key];
                    $fileErr = $this->validateSpecFile($file, $comp['nama_komponen']);

                    if ($fileErr) {
                        return redirect()->back()->withInput()->with('error', $fileErr);
                    }

                    $fileName = $file->getRandomName();

                    $file->move(FCPATH . 'uploads/specs', $fileName);
                    $specifications[$key] = $fileName;
                }
            } else {
                if (isset($specsInput[$key]) && $specsInput[$key] !== '') {
                    $specifications[$key] = $specsInput[$key];
                }
            }
        }

        $this->assetModel->save([
            'master_data_id' => $masterDataId,
            'user_id'        => session()->get('id'),
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
        $asset = $this->assetModel->find($id);
        if (!$asset) {
            return redirect()->to('/asset')->with('error', 'Aset tidak ditemukan!');
        }

        if (!$this->validate([
            'master_data_id' => 'required|integer',
            'status'         => 'required|in_list[Aktif,Perbaikan,Rusak,Disimpan]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $oldSpecs = json_decode($asset['specifications'] ?? '{}', true) ?? [];

        $masterDataId = (int) $this->request->getPost('master_data_id');
        $components   = $this->componentModel->getComponentsByMasterData($masterDataId);

        $specsInput = $this->request->getPost('specs') ?? [];
        $specsFiles = $this->request->getFiles()['specs'] ?? [];

        $specifications = [];
        foreach ($components as $comp) {
            $key  = $comp['key_komponen'];
            $type = $comp['tipe_input'] ?? 'text';

            if ($type === 'file' || $type === 'foto') {
                if (isset($specsFiles[$key]) && $specsFiles[$key]->isValid() && !$specsFiles[$key]->hasMoved()) {
                    $file    = $specsFiles[$key];
                    $fileErr = $this->validateSpecFile($file, $comp['nama_komponen']);

                    if ($fileErr) {
                        return redirect()->back()->withInput()->with('error', $fileErr);
                    }

                    $fileName = $file->getRandomName();
                    $file->move(FCPATH . 'uploads/specs', $fileName);

                    if (!empty($oldSpecs[$key]) && file_exists(FCPATH . 'uploads/specs/' . $oldSpecs[$key])) {
                        @unlink(FCPATH . 'uploads/specs/' . $oldSpecs[$key]);
                    }

                    $specifications[$key] = $fileName;
                } else {
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
            'master_data_id' => $masterDataId,
            'status'         => $this->request->getPost('status'),
            'specifications' => json_encode($specifications),
        ]);

        return redirect()->to('/asset')->with('message', 'Aset berhasil diperbarui!');
    }

    // 7. Hapus Aset
    public function delete($id)
    {
        $asset = $this->assetModel->find($id);
        if ($asset) {
            $specs = json_decode($asset['specifications'] ?? '{}', true) ?? [];

            foreach ($specs as $val) {
                if (is_string($val) && file_exists(FCPATH . 'uploads/specs/' . $val)) {
                    @unlink(FCPATH . 'uploads/specs/' . $val);
                }
            }

            $this->assetModel->delete($id);
            return redirect()->to('/asset')->with('message', 'Aset berhasil dihapus!');
        }

        return redirect()->to('/asset')->with('error', 'Aset tidak ditemukan!');
    }

    private function validateSpecFile($file, string $label): ?string
    {
        $allowedExt = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'pdf', 'doc', 'docx'];
        $maxSize    = 5 * 1024 * 1024;
        $ext        = strtolower($file->getClientExtension());

        if (!in_array($ext, $allowedExt, true)) {
            return "File \"" . esc($label) . "\" tidak diizinkan. Gunakan: " . implode(', ', $allowedExt) . '.';
        }

        if ($file->getSize() > $maxSize) {
            return "Ukuran file \"" . esc($label) . "\" melebihi batas maksimal 5MB.";
        }

        return null;
    }
}