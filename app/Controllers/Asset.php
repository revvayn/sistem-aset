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
        $status   = $this->request->getGet('status');

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

        // Filter Status
        if (!empty($status)) {
            $builder->where('assets.status', $status);
        }

        $perPage = 10;

        $assets = $builder->paginate($perPage, 'asset');
        $this->assetModel->pager->only(['keyword', 'category', 'status']);

        $data = [
            'title'       => 'Daftar Aset',
            'assets'      => $assets,
            'pager'       => $this->assetModel->pager,
            'currentPage' => $this->request->getVar('page_asset') ? (int)$this->request->getVar('page_asset') : 1,
            'perPage'     => $perPage,
            'keyword'     => $keyword,
            'category'    => $category,
            'status'      => $status,
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

        // Validasi server-side komponen wajib & tipe data
        $specError = $this->validateSpecs($components, $specsInput, $specsFiles, false, []);
        if ($specError) {
            return redirect()->back()->withInput()->with('error', $specError);
        }

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
                    $value = $specsInput[$key];

                    if ($type === 'password') {
                        $value = password_hash((string) $value, PASSWORD_DEFAULT);
                    }

                    $specifications[$key] = $value;
                }
            }
        }

        $this->assetModel->save([
            'master_data_id'  => $masterDataId,
            'user_id'         => session()->get('id'),
            'status'          => $this->request->getPost('status'),
            'serial_number'   => $this->request->getPost('serial_number'),
            'location'        => $this->request->getPost('location'),
            'department'      => $this->request->getPost('department'),
            'purchase_date'   => $this->request->getPost('purchase_date') ?: null,
            'price'           => $this->request->getPost('price') ?: null,
            'warranty_expiry' => $this->request->getPost('warranty_expiry') ?: null,
            'specifications'  => json_encode($specifications),
        ]);

        log_activity('create', 'asset', $this->assetModel->getInsertID(), 'Tambah aset kategori ID ' . $masterDataId);

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

        $specError = $this->validateSpecs($components, $specsInput, $specsFiles, true, $oldSpecs);
        if ($specError) {
            return redirect()->back()->withInput()->with('error', $specError);
        }

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
                    $value = $specsInput[$key];

                    if ($type === 'password') {
                        $value = password_hash((string) $value, PASSWORD_DEFAULT);
                    }

                    $specifications[$key] = $value;
                } elseif ($type === 'password' && isset($oldSpecs[$key])) {
                    // Password: pertahankan nilai lama jika input dikosongkan
                    $specifications[$key] = $oldSpecs[$key];
                }
            }
        }

        $this->assetModel->update($id, [
            'master_data_id'  => $masterDataId,
            'status'          => $this->request->getPost('status'),
            'serial_number'   => $this->request->getPost('serial_number'),
            'location'        => $this->request->getPost('location'),
            'department'      => $this->request->getPost('department'),
            'purchase_date'   => $this->request->getPost('purchase_date') ?: null,
            'price'           => $this->request->getPost('price') ?: null,
            'warranty_expiry' => $this->request->getPost('warranty_expiry') ?: null,
            'specifications'  => json_encode($specifications),
        ]);

        log_activity('update', 'asset', $id, 'Update aset');

        return redirect()->to('/asset')->with('message', 'Aset berhasil diperbarui!');
    }

    // 7. Hapus Aset
    public function delete($id)
    {
        $asset = $this->assetModel->find($id);
        if ($asset) {
            $specs = json_decode($asset['specifications'] ?? '{}', true) ?? [];

            foreach ($specs as $val) {
                if (is_string($val) && preg_match('/\.(jpg|jpeg|png|webp|gif|pdf|doc|docx)$/i', $val) && file_exists(FCPATH . 'uploads/specs/' . $val)) {
                    @unlink(FCPATH . 'uploads/specs/' . $val);
                }
            }

            $this->assetModel->delete($id);
            log_activity('delete', 'asset', $id, 'Soft delete aset');
            return redirect()->to('/asset')->with('message', 'Aset berhasil dihapus!');
        }

        return redirect()->to('/asset')->with('error', 'Aset tidak ditemukan!');
    }

    // Validasi server-side komponen wajib & tipe data
    // Aksi massal pada daftar aset
    public function bulk()
    {
        $role   = strtolower((string) session()->get('role'));
        $action = (string) $this->request->getPost('bulk_action');
        $ids    = $this->request->getPost('ids');

        if (!is_array($ids) || empty($ids)) {
            return redirect()->to('/asset')->with('error', 'Tidak ada aset yang dipilih!');
        }

        $ids = array_values(array_unique(array_map('intval', $ids)));

        if ($action === 'delete') {
            if ($role !== 'admin') {
                return redirect()->to('/asset')->with('error', 'Anda tidak memiliki hak akses untuk menghapus aset!');
            }

            foreach ($ids as $id) {
                $asset = $this->assetModel->find($id);
                if (!$asset) {
                    continue;
                }

                $specs = json_decode($asset['specifications'] ?? '{}', true) ?? [];
                foreach ($specs as $val) {
                    if (is_string($val) && preg_match('/\.(jpg|jpeg|png|webp|gif|pdf|doc|docx)$/i', $val) && file_exists(FCPATH . 'uploads/specs/' . $val)) {
                        @unlink(FCPATH . 'uploads/specs/' . $val);
                    }
                }

                $this->assetModel->delete($id);
            }

            log_activity('bulk_delete', 'asset', null, 'Hapus massal ' . count($ids) . ' aset');
            return redirect()->to('/asset')->with('message', count($ids) . ' aset berhasil dihapus!');
        }

        if ($action === 'status') {
            $status = (string) $this->request->getPost('bulk_status');
            if (!in_array($status, ['Aktif', 'Perbaikan', 'Rusak', 'Disimpan'], true)) {
                return redirect()->to('/asset')->with('error', 'Status tidak valid!');
            }

            foreach ($ids as $id) {
                $asset = $this->assetModel->find($id);
                if (!$asset) {
                    continue;
                }
                $this->assetModel->update($id, ['status' => $status]);
            }

            log_activity('bulk_status', 'asset', null, 'Ubah status massal ' . count($ids) . ' aset menjadi ' . $status);
            return redirect()->to('/asset')->with('message', count($ids) . ' aset diubah statusnya menjadi ' . $status . '!');
        }

        return redirect()->to('/asset')->with('error', 'Aksi massal tidak dikenali!');
    }

    private function validateSpecs(array $components, array $specsInput, array $specsFiles, bool $isUpdate, array $oldSpecs): ?string
    {
        foreach ($components as $comp) {
            $key      = $comp['key_komponen'];
            $type     = $comp['tipe_input'] ?? 'text';
            $required = (int) ($comp['is_required'] ?? 0) === 1;
            $label    = $comp['nama_komponen'] ?? $key;

            if ($type === 'file' || $type === 'foto') {
                $hasNewFile = isset($specsFiles[$key]) && $specsFiles[$key]->isValid() && !$specsFiles[$key]->hasMoved();
                $hasOldFile = $isUpdate && !empty($oldSpecs[$key]);

                if ($required && !$hasNewFile && !$hasOldFile) {
                    return 'File \"' . esc($label) . '\" wajib diunggah.';
                }

                if ($hasNewFile) {
                    $fileErr = $this->validateSpecFile($specsFiles[$key], $label);
                    if ($fileErr) {
                        return $fileErr;
                    }
                }
            } else {
                $value = $specsInput[$key] ?? '';

                if ($required && ($value === '' || $value === null)) {
                    // Pada update, nilai lama boleh dipertahankan
                    if (!($isUpdate && isset($oldSpecs[$key]) && $oldSpecs[$key] !== '')) {
                        return 'Field \"' . esc($label) . '\" wajib diisi.';
                    }
                }

                if ($value !== '' && $value !== null) {
                    if ($type === 'number' && !is_numeric($value)) {
                        return 'Field \"' . esc($label) . '\" harus berupa angka.';
                    }

                    if ($type === 'date') {
                        $dt = \DateTime::createFromFormat('Y-m-d', (string) $value);
                        if ($dt === false || $dt->format('Y-m-d') !== $value) {
                            return 'Field \"' . esc($label) . '\" harus berupa tanggal yang valid (YYYY-MM-DD).';
                        }
                    }
                }
            }
        }

        return null;
    }

    // Halaman detail aset
    public function show($id)
    {
        $asset = $this->assetModel->getAssetWithDetails((int) $id);
        if (!$asset) {
            return redirect()->to('/asset')->with('error', 'Aset tidak ditemukan!');
        }

        $components = $this->componentModel->getComponentsByMasterData((int) $asset['master_data_id']);

        $data = [
            'title'      => 'Detail Aset',
            'asset'      => $asset,
            'components' => $components,
        ];

        return view('asset/show', $data);
    }

    // Halaman aset terhapus (soft delete)
    public function trashed()
    {
        $assets = $this->assetModel
            ->withDeleted()
            ->where('assets.deleted_at IS NOT NULL', null, false)
            ->select('assets.*, master_data.nama_kategori')
            ->join('master_data', 'master_data.id = assets.master_data_id', 'left')
            ->orderBy('assets.deleted_at', 'DESC')
            ->findAll();

        $data = [
            'title'  => 'Aset Terhapus',
            'assets' => $assets,
        ];

        return view('asset/trashed', $data);
    }

    // Pulihkan aset terhapus
    public function restore($id)
    {
        $asset = $this->assetModel->withDeleted()->find($id);
        if (!$asset || empty($asset['deleted_at'])) {
            return redirect()->to('/asset/trashed')->with('error', 'Aset tidak ditemukan!');
        }

        $db = \Config\Database::connect();
        $db->table('assets')->where('id', $id)->update(['deleted_at' => null]);

        log_activity('restore', 'asset', $id, 'Pulihkan aset');
        return redirect()->to('/asset/trashed')->with('message', 'Aset berhasil dipulihkan!');
    }

    // Hapus permanen
    public function forceDelete($id)
    {
        $asset = $this->assetModel->withDeleted()->find($id);
        if (!$asset) {
            return redirect()->to('/asset/trashed')->with('error', 'Aset tidak ditemukan!');
        }

        $specs = json_decode($asset['specifications'] ?? '{}', true) ?? [];
        foreach ($specs as $val) {
            if (is_string($val) && file_exists(FCPATH . 'uploads/specs/' . $val)) {
                @unlink(FCPATH . 'uploads/specs/' . $val);
            }
        }

        $this->assetModel->delete($id, true);

        log_activity('force_delete', 'asset', $id, 'Hapus permanen aset');
        return redirect()->to('/asset/trashed')->with('message', 'Aset berhasil dihapus permanen!');
    }

    // Ekspor CSV aset aktif
    public function export()
    {
        $assets = $this->assetModel
            ->select('assets.*, master_data.nama_kategori')
            ->join('master_data', 'master_data.id = assets.master_data_id', 'left')
            ->where('assets.deleted_at', null)
            ->orderBy('assets.id', 'DESC')
            ->findAll();

        $filename = 'aset_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $out = fopen('php://output', 'w');
        // BOM for Excel UTF-8
        fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($out, ['ID', 'Kategori', 'Status', 'Spesifikasi (JSON)', 'Dibuat', 'Diubah']);

        foreach ($assets as $ast) {
            fputcsv($out, [
                $ast['id'],
                $ast['nama_kategori'] ?? '-',
                $ast['status'] ?? '',
                $ast['specifications'] ?? '',
                $ast['created_at'] ?? '',
                $ast['updated_at'] ?? '',
            ]);
        }

        fclose($out);
        exit;
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