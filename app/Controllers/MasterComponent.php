<?php

namespace App\Controllers;

use App\Models\ComponentModel;

class MasterComponent extends BaseController
{
    /** @var ComponentModel */
    protected ComponentModel $componentModel;

    public function __construct()
    {
        $this->componentModel = new ComponentModel();
    }

    // 1. Tampilkan Daftar Komponen
    public function index()
    {
        $keyword = $this->request->getGet('keyword');

        $builder = $this->componentModel;
        if (!empty($keyword)) {
            $builder = $builder->groupStart()
                ->like('nama_komponen', $keyword)
                ->orLike('key_komponen', $keyword)
                ->orLike('tipe_input', $keyword)
                ->groupEnd();
        }

        $perPage = 10;
        $components = $builder->paginate($perPage, 'component');
        $this->componentModel->pager->only(['keyword']);

        $data = [
            'title'       => 'Master Komponen Aset',
            'components'  => $components,
            'pager'       => $this->componentModel->pager,
            'currentPage' => $this->request->getVar('page_component') ? (int) $this->request->getVar('page_component') : 1,
            'perPage'     => $perPage,
            'keyword'     => $keyword,
        ];

        return view('master/components/index', $data);
    }

    // 2. Form Tambah Komponen
    public function create()
    {
        $data = [
            'title' => 'Tambah Master Komponen Baru',
        ];
        return view('master/components/create', $data);
    }

    // 3. Proses Simpan Komponen Baru
    public function store()
    {
        $namaKomponen = $this->request->getPost('nama_komponen');
        $tipeInput    = $this->request->getPost('tipe_input');
        
        // List tipe input yang diizinkan
        $allowedTypes = ['text', 'number', 'password', 'date', 'file', 'qr_code'];
        if (!in_array($tipeInput, $allowedTypes)) {
            return redirect()->back()->withInput()->with('error', 'Tipe input tidak valid!');
        }

        // Auto-generate key database dari nama komponen (contoh: "Foto Aset" -> "foto_aset")
        $keyKomponen = url_title(strtolower($namaKomponen), '_', true);

        // Validasi agar key_komponen tidak terduplikasi
        if ($this->componentModel->where('key_komponen', $keyKomponen)->first()) {
            return redirect()->back()->withInput()->with('error', 'Komponen dengan nama atau key serupa sudah ada!');
        }

        $this->componentModel->insert([
            'nama_komponen' => $namaKomponen,
            'key_komponen'  => $keyKomponen,
            'tipe_input'    => $tipeInput,
        ]);

        return redirect()->to('/master/components')->with('message', 'Komponen baru berhasil ditambahkan!');
    }

    // 4. Form Edit Komponen
    public function edit($id)
    {
        /** @var array $component */
        $component = $this->componentModel->find($id);
        if (!$component) {
            return redirect()->to('/master/components')->with('error', 'Komponen tidak ditemukan!');
        }

        $data = [
            'title'     => 'Edit Master Komponen',
            'component' => $component,
        ];

        return view('master/components/edit', $data);
    }

    // 5. Proses Update Komponen
    public function update($id)
    {
        $namaKomponen = $this->request->getPost('nama_komponen');
        $tipeInput    = $this->request->getPost('tipe_input');

        // Validasi pilihan tipe input
        $allowedTypes = ['text', 'number', 'password', 'date', 'file', 'qr_code'];
        if (!in_array($tipeInput, $allowedTypes)) {
            return redirect()->back()->withInput()->with('error', 'Tipe input tidak valid!');
        }

        $keyKomponen  = url_title(strtolower($namaKomponen), '_', true);

        // Cek jika key terduplikasi dengan komponen lain
        $existing = $this->componentModel->where('key_komponen', $keyKomponen)->where('id !=', $id)->first();
        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'Komponen dengan nama serupa sudah ada!');
        }

        $this->componentModel->update($id, [
            'nama_komponen' => $namaKomponen,
            'key_komponen'  => $keyKomponen,
            'tipe_input'    => $tipeInput,
        ]);

        return redirect()->to('/master/components')->with('message', 'Master komponen berhasil diperbarui!');
    }

    // 6. Hapus Komponen
    public function delete($id)
    {
        // Cek apakah komponen sedang digunakan di relasi kategori (master_data_components)
        $db = \Config\Database::connect();
        $isUsed = $db->table('master_data_components')->where('component_id', $id)->get()->getRow();

        if ($isUsed) {
            return redirect()->to('/master/components')->with('error', 'Komponen tidak bisa dihapus karena masih terhubung dengan Kategori Aset!');
        }

        $this->componentModel->delete($id);
        return redirect()->to('/master/components')->with('message', 'Komponen berhasil dihapus!');
    }
}