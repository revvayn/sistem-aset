<?php

/**
 * @var array $assets
 * @var object $pager
 * @var int $currentPage
 * @var int $perPage
 * @var string|null $keyword
 * @var string|null $category
 * @var array $categories
 */

// Ambil role dari session
$role = session()->get('role');
?>

<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Daftar Aset</h2>

    <!-- Tombol Tambah Aset: HANYA untuk Admin & Staf -->
    <?php if (in_array($role, ['admin', 'staff'])) : ?>
        <a href="<?= base_url('asset/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> + Tambah Aset Baru
        </a>
    <?php endif; ?>
</div>

<!-- Flash Message Notifications -->
<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('message') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- ==================== FORM FILTER & SEARCH ==================== -->
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <form action="<?= base_url('asset') ?>" method="GET" class="row g-3">
            <!-- Input Cari Keyword -->
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="keyword" class="form-control" placeholder="Cari No Asset / Nama Asset..." value="<?= esc($keyword ?? '') ?>">
                </div>
            </div>

            <!-- Dropdown Filter Kategori -->
            <div class="col-md-4">
                <select name="category" class="form-select">
                    <option value="">-- Semua Kategori --</option>
                    <?php if (!empty($categories)) : ?>
                        <?php foreach ($categories as $cat) : ?>
                            <option value="<?= $cat['nama_kategori'] ?>" <?= (($category ?? '') == $cat['nama_kategori']) ? 'selected' : '' ?>>
                                <?= esc($cat['nama_kategori']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Tombol Filter & Reset -->
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                <?php if (!empty($keyword) || !empty($category)) : ?>
                    <a href="<?= base_url('asset') ?>" class="btn btn-outline-danger" title="Reset Filter">
                        <i class="bi bi-x-circle"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- ==================== TABEL DAFTAR ASET ==================== -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th width="50" class="text-center">No</th>
                        <th>No. Aset</th>
                        <th>Nama Aset</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th width="200" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($assets)) : ?>
                        <?php
                        // Perhitungan nomor urut dinamis berdasarkan halaman pagination
                        $i = 1 + (($perPage ?? 10) * (($currentPage ?? 1) - 1));
                        ?>
                        <?php foreach ($assets as $ast) : ?>
                            <tr>
                                <td class="text-center"><?= $i++ ?></td>
                                <td><code><?= esc($ast['no_asset'] ?? '-') ?></code></td>
                                <td><strong><?= esc($ast['nama_aset'] ?? '-') ?></strong></td>
                                <td><span class="badge bg-secondary"><?= esc($ast['nama_kategori'] ?? '-') ?></span></td>
                                <td>
                                    <?php
                                    $status = $ast['status'] ?? 'Aktif';
                                    $badgeClass = match ($status) {
                                        'Aktif'     => 'bg-success',
                                        'Perbaikan' => 'bg-warning text-dark',
                                        'Rusak'     => 'bg-danger',
                                        default     => 'bg-secondary',
                                    };
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= esc($status) ?></span>
                                </td>
                                <td class="text-center">
                                    <!-- Parsing Specifications -->
                                    <?php
                                    $specsData = $ast['specifications'] ?? [];
                                    if (is_string($specsData)) {
                                        $specsData = json_decode($specsData, true) ?? [];
                                    }
                                    ?>

                                    <!-- Tombol Detail: BISA DILIHAT SEMUA ROLE (Admin, Staf, Viewer) -->
                                    <button type="button"
                                        class="btn btn-sm btn-outline-info me-1 btn-detail"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detailModal"
                                        data-no="<?= esc($ast['no_asset'] ?? '-') ?>"
                                        data-nama="<?= esc($ast['nama_aset'] ?? '-') ?>"
                                        data-kategori="<?= esc($ast['nama_kategori'] ?? '-') ?>"
                                        data-status="<?= esc($status) ?>"
                                        data-specs='<?= htmlspecialchars(json_encode($specsData), ENT_QUOTES, 'UTF-8') ?>'>
                                        <i class="bi bi-eye"></i> Detail
                                    </button>

                                    <!-- Tombol Edit: Admin & Staf/Staff -->
                                    <?php if (in_array(strtolower($role ?? ''), ['admin', 'staff'])) : ?>
                                        <a href="<?= base_url('asset/edit/' . $ast['id']) ?>" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    <?php endif; ?>

                                    <!-- Tombol Hapus: Khusus Admin -->
                                    <?php if (strtolower($role ?? '') === 'admin') : ?>
                                        <a href="<?= base_url('asset/delete/' . $ast['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus aset ini?')" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    <?php endif; ?>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Belum ada data aset yang ditemukan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ==================== PAGINATION FOOTER ==================== -->
    <?php if (!empty($assets) && isset($pager)) : ?>
        <div class="card-footer d-flex justify-content-between align-items-center bg-white py-3">
            <div class="small text-muted">
                Menampilkan halaman <strong><?= $currentPage ?? 1 ?></strong>
            </div>
            <div>
                <?= $pager->links('asset', 'default_full') ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Detail Aset -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="detailModalLabel"><i class="bi bi-info-circle me-2"></i>Detail Informasi Aset</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered mb-4">
                    <tr>
                        <th width="30%" class="bg-light">No. Aset</th>
                        <td><code id="modalNoAsset" class="fs-6"></code></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Nama Aset</th>
                        <td id="modalNamaAsset" class="fw-bold"></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Kategori</th>
                        <td id="modalKategori"></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Status</th>
                        <td id="modalStatus"></td>
                    </tr>
                </table>

                <h6 class="text-primary mb-3"><i class="bi bi-cpu me-1"></i> Komponen & Spesifikasi Detail</h6>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle border">
                        <thead class="table-light">
                            <tr>
                                <th>Atribut / Komponen</th>
                                <th>Nilai / Spesifikasi</th>
                            </tr>
                        </thead>
                        <tbody id="modalSpecsContainer">
                            <!-- Komponen akan diisi lewat JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const detailButtons = document.querySelectorAll('.btn-detail');

        detailButtons.forEach(button => {
            button.addEventListener('click', function() {
                const noAsset = this.getAttribute('data-no');
                const namaAsset = this.getAttribute('data-nama');
                const kategori = this.getAttribute('data-kategori');
                const status = this.getAttribute('data-status');
                const rawSpecs = this.getAttribute('data-specs');

                document.getElementById('modalNoAsset').textContent = noAsset;
                document.getElementById('modalNamaAsset').textContent = namaAsset;
                document.getElementById('modalKategori').textContent = kategori;
                document.getElementById('modalStatus').textContent = status;

                const specsContainer = document.getElementById('modalSpecsContainer');
                specsContainer.innerHTML = '';

                try {
                    let specs = typeof rawSpecs === 'string' ? JSON.parse(rawSpecs) : rawSpecs;

                    if (typeof specs === 'string') {
                        specs = JSON.parse(specs);
                    }

                    const keys = Object.keys(specs || {});

                    if (!specs || keys.length === 0) {
                        specsContainer.innerHTML = '<tr><td colspan="2" class="text-center text-muted">Tidak ada komponen / spesifikasi tambahan.</td></tr>';
                    } else {
                        keys.forEach(key => {
                            const val = specs[key] ? specs[key] : '-';
                            const formattedKey = key.replace(/_/g, ' ').toUpperCase();

                            let displayValue = val;

                            if (typeof val === 'string' && val !== '-') {
                                // 1. Cek apakah file berupa Gambar
                                const isImage = /\.(jpg|jpeg|png|webp|gif|svg)$/i.test(val);
                                // 2. Cek apakah file berupa Dokumen (PDF / Word)
                                const isPdf = /\.pdf$/i.test(val);
                                const isDoc = /\.(doc|docx)$/i.test(val);

                                const fileUrl = `<?= base_url('uploads/specs/') ?>/${val}`;

                                if (isImage) {
                                    displayValue = `
                <div class="my-1">
                    <a href="${fileUrl}" target="_blank">
                        <img src="${fileUrl}" alt="${formattedKey}" class="img-thumbnail shadow-sm" style="max-height: 150px; max-width: 200px; object-fit: cover;">
                    </a>
                    <small class="d-block text-muted mt-1"><i class="bi bi-box-arrow-up-right"></i> Klik untuk memperbesar</small>
                </div>
            `;
                                } else if (isPdf) {
                                    displayValue = `
                <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-outline-danger my-1">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Buka Dokumen PDF
                </a>
            `;
                                } else if (isDoc) {
                                    displayValue = `
                <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-outline-primary my-1">
                    <i class="bi bi-file-earmark-word me-1"></i> Unduh Dokumen Word
                </a>
            `;
                                }
                            }

                            const row = `
        <tr>
            <td class="fw-semibold text-secondary" width="40%">${formattedKey}</td>
            <td>${displayValue}</td>
        </tr>
    `;
                            specsContainer.innerHTML += row;
                        });
                    }
                } catch (e) {
                    console.error("Error parsing specs:", e);
                    specsContainer.innerHTML = '<tr><td colspan="2" class="text-center text-muted">Gagal membaca data spesifikasi.</td></tr>';
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>