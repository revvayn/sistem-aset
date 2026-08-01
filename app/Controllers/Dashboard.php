<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // 1. Mengambil ringkasan statistik utama
        $totalAssets      = $this->db->table('assets')->where('deleted_at', null)->countAllResults();
        
        // Menggunakan tabel 'master_data' sesuai skema Migration
        $totalCategories  = $this->db->table('master_data')->countAllResults();
        
        // Mengambil aset non-aktif berdasarkan status di migration ('Perbaikan', 'Rusak', 'Disimpan')
        $totalMaintenance = $this->db->table('assets')
                                    ->whereIn('status', ['Perbaikan', 'Rusak'])
                                    ->where('deleted_at', null)
                                    ->countAllResults();

        // 2. Mengambil 5 Aset Terbaru (Join ke tabel 'master_data')
        $recentAssets = $this->db->table('assets')
            ->select('assets.*, master_data.nama_kategori')
            ->join('master_data', 'master_data.id = assets.master_data_id', 'left')
            ->where('assets.deleted_at', null)
            ->orderBy('assets.id', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        // 3. Mengambil Data Statistik Aset per Kategori (master_data)
        $categoryStats = $this->db->table('master_data')
            ->select('master_data.nama_kategori, COUNT(assets.id) as total')
            ->join('assets', 'assets.master_data_id = master_data.id AND assets.deleted_at IS NULL', 'left')
            ->groupBy('master_data.id')
            ->get()
            ->getResultArray();

        // 4. Hitung Total Jenis Komponen dari Master Atribut 'components'
        $totalComponents = $this->db->table('components')->countAllResults();

        // 5. Mengirimkan data ke View
        $data = [
            'title'            => 'Dashboard Utama',
            'totalAssets'      => (int) $totalAssets,
            'totalCategories'  => (int) $totalCategories,
            'totalComponents'  => (int) $totalComponents,
            'totalMaintenance' => (int) $totalMaintenance,
            'recentAssets'     => $recentAssets,
            'categoryStats'    => $categoryStats,
        ];

        return view('dashboard/index', $data);
    }
}