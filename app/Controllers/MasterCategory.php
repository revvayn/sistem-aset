<?php

namespace App\Controllers;

use App\Models\MasterDataModel;
use App\Models\ComponentModel;
use App\Models\MasterDataComponentModel;

class MasterCategory extends BaseController
{
    /** @var MasterDataModel */
    protected MasterDataModel $categoryModel;

    /** @var ComponentModel */
    protected ComponentModel $componentModel;

    /** @var MasterDataComponentModel */
    protected MasterDataComponentModel $relModel;

    public function __construct()
    {
        $this->categoryModel  = new MasterDataModel();
        $this->componentModel = new ComponentModel();
        $this->relModel       = new MasterDataComponentModel();
    }

    // 1. Tampilkan Semua Kategori beserta Komponennya
    public function index()
    {
        $categories = $this->categoryModel->findAll();

        // Ambil komponen untuk semua kategori sekaligus (satu query, hindari N+1)
        if (!empty($categories)) {
            $groupedComponents = $this->componentModel->getComponentsByMasterDataIds(array_column($categories, 'id'));

            foreach ($categories as &$cat) {
                $cat['components'] = $groupedComponents[$cat['id']] ?? [];
            }
        }

        $data = [
            'title'      => 'Master Kategori Aset',
            'categories' => $categories,
        ];

        return view('master/categories/index', $data);
    }

    // 2. Form Tambah Kategori
    public function create()
    {
        $data = [
            'title'      => 'Tambah Kategori Aset Baru',
            'components' => $this->componentModel->findAll(),
        ];
        return view('master/categories/create', $data);
    }

    // 3. Simpan Kategori Baru
    public function store()
    {
        $catId = $this->categoryModel->insert([
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'keterangan'    => $this->request->getPost('keterangan'),
        ]);

        $selectedComponents = $this->request->getPost('components') ?? [];
        $requiredComponents = $this->request->getPost('required') ?? [];

        foreach ($selectedComponents as $compId) {
            $this->relModel->insert([
                'master_data_id' => $catId,
                'component_id'   => $compId,
                'is_required'    => in_array($compId, $requiredComponents) ? 1 : 0,
            ]);
        }

        return redirect()->to('/master/categories')->with('message', 'Kategori baru berhasil ditambahkan!');
    }

    // 4. Form Edit Kategori
    public function edit($id)
    {
        /** @var array $category */
        $category = $this->categoryModel->find($id);
        if (!$category) {
            return redirect()->to('/master/categories')->with('error', 'Kategori tidak ditemukan!');
        }

        // Ambil komponen yang sudah terpasang di kategori ini
        $activeComponents = $this->componentModel->getComponentsByMasterData($id);
        
        $activeCompIds = [];
        $requiredCompIds = [];
        foreach ($activeComponents as $ac) {
            $activeCompIds[] = $ac['id'];
            if ($ac['is_required'] == 1) {
                $requiredCompIds[] = $ac['id'];
            }
        }

        $data = [
            'title'           => 'Edit Kategori Aset',
            'category'        => $category,
            'allComponents'   => $this->componentModel->findAll(),
            'activeCompIds'   => $activeCompIds,
            'requiredCompIds' => $requiredCompIds,
        ];

        return view('master/categories/edit', $data);
    }

    // 5. Update Kategori & Relasi Komponennya
    public function update($id)
    {
        $this->categoryModel->update($id, [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'keterangan'    => $this->request->getPost('keterangan'),
        ]);

        // Hapus relasi lama lalu buat ulang sesuai centangan baru
        $this->relModel->where('master_data_id', $id)->delete();

        $selectedComponents = $this->request->getPost('components') ?? [];
        $requiredComponents = $this->request->getPost('required') ?? [];

        foreach ($selectedComponents as $compId) {
            $this->relModel->insert([
                'master_data_id' => $id,
                'component_id'   => $compId,
                'is_required'    => in_array($compId, $requiredComponents) ? 1 : 0,
            ]);
        }

        return redirect()->to('/master/categories')->with('message', 'Kategori berhasil diperbarui!');
    }

    // 6. Hapus Kategori
    public function delete($id)
    {
        // Cek apakah ada aset yang sedang memakai kategori ini
        $assetModel = new \App\Models\AssetModel();
        $assetCount = (int) $assetModel->where('master_data_id', $id)->countAllResults();

        if ($assetCount > 0) {
            return redirect()->to('/master/categories')->with('error', 'Kategori tidak bisa dihapus karena sedang digunakan oleh ' . $assetCount . ' unit aset!');
        }

        $this->categoryModel->delete($id);
        return redirect()->to('/master/categories')->with('message', 'Kategori berhasil dihapus!');
    }
}