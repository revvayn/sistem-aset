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

        // 5. Statistik status aset
        $statusStats = [];
        foreach (['Aktif', 'Perbaikan', 'Rusak', 'Disimpan'] as $st) {
            $statusStats[$st] = (int) $this->db->table('assets')
                ->where('status', $st)
                ->where('deleted_at', null)
                ->countAllResults();
        }

        // 6. Notifikasi: garansi akan habis dalam 30 hari
        $warrantyAlerts = $this->db->table('assets')
            ->select('assets.*, master_data.nama_kategori')
            ->join('master_data', 'master_data.id = assets.master_data_id', 'left')
            ->where('assets.deleted_at', null)
            ->where('assets.warranty_expiry IS NOT NULL', null, false)
            ->where('assets.warranty_expiry <=', date('Y-m-d', strtotime('+30 days')))
            ->orderBy('assets.warranty_expiry', 'ASC')
            ->limit(5)
            ->get()
            ->getResultArray();

        // 7. Notifikasi: aset Perbaikan lebih dari 14 hari
        $staleMaintenance = $this->db->table('assets')
            ->select('assets.*, master_data.nama_kategori')
            ->join('master_data', 'master_data.id = assets.master_data_id', 'left')
            ->where('assets.deleted_at', null)
            ->where('assets.status', 'Perbaikan')
            ->where('assets.updated_at <=', date('Y-m-d H:i:s', strtotime('-14 days')))
            ->limit(5)
            ->get()
            ->getResultArray();

        // 8. Aset yang perlu perhatian (Perbaikan / Rusak)
        $attentionAssets = $this->db->table('assets')
            ->select('assets.*, master_data.nama_kategori')
            ->join('master_data', 'master_data.id = assets.master_data_id', 'left')
            ->whereIn('assets.status', ['Perbaikan', 'Rusak'])
            ->where('assets.deleted_at', null)
            ->orderBy('assets.updated_at', 'ASC')
            ->limit(5)
            ->get()
            ->getResultArray();

        // 7. Aktivitas terbaru (audit log)
        $recentActivities = $this->db->table('activity_logs')
            ->select('activity_logs.*, users.nama as nama_user')
            ->join('users', 'users.id = activity_logs.user_id', 'left')
            ->orderBy('activity_logs.id', 'DESC')
            ->limit(8)
            ->get()
            ->getResultArray();

        // 7. Mengirimkan data ke View
        $data = [
            'title'             => 'Dashboard Utama',
            'totalAssets'       => (int) $totalAssets,
            'totalCategories'   => (int) $totalCategories,
            'totalComponents'   => (int) $totalComponents,
            'totalMaintenance'  => (int) $totalMaintenance,
            'recentAssets'      => $recentAssets,
            'categoryStats'     => $categoryStats,
            'statusStats'       => $statusStats,
            'attentionAssets'   => $attentionAssets,
            'warrantyAlerts'    => $warrantyAlerts,
            'staleMaintenance'  => $staleMaintenance,
            'recentActivities'  => $recentActivities,
        ];

        return view('dashboard/index', $data);
    }
}