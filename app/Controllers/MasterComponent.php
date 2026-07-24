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
        /** @var array $components */
        $components = $this->componentModel->findAll();

        $data = [
            'title'      => 'Master Komponen Aset',
            'components' => $components,
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
        
        // Auto-generate key database dari nama komponen (contoh: "IP Address" -> "ip_address")
        $keyKomponen = url_title(strtolower($namaKomponen), '_', true);

        // Validasi kustom agar key_komponen tidak terduplikasi
        if ($this->componentModel->where('key_komponen', $keyKomponen)->first()) {
            return redirect()->back()->withInput()->with('error', 'Komponen dengan nama atau key serupa sudah ada!');
        }

        $this->componentModel->insert([
            'nama_komponen' => $namaKomponen,
            'key_komponen'  => $keyKomponen,
            'tipe_input'    => $this->request->getPost('tipe_input'),
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
        $keyKomponen  = url_title(strtolower($namaKomponen), '_', true);

        // Cek jika key terduplikasi dengan komponen lain
        $existing = $this->componentModel->where('key_komponen', $keyKomponen)->where('id !=', $id)->first();
        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'Komponen dengan nama serupa sudah ada!');
        }

        $this->componentModel->update($id, [
            'nama_komponen' => $namaKomponen,
            'key_komponen'  => $keyKomponen,
            'tipe_input'    => $this->request->getPost('tipe_input'),
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