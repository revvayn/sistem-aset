<?php

/**
 * @var array $assets
 */
?>

<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Daftar Aset</h2>
    <a href="<?= base_url('asset/create') ?>" class="btn btn-primary">+ Tambah Aset Baru</a>
</div>

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

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="50">No</th>
                        <th>No. Aset</th>
                        <th>Nama Aset</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th width="200" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($assets)) : ?>
                        <?php $i = 1;
                        foreach ($assets as $ast) : ?>
                            <tr>
                                <td><?= $i++ ?></td>
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
                                    <!-- Tombol Detail -->
                                    <?php
                                    // Pastikan specifications diparsing ke array lebih dulu
                                    $specsData = $ast['specifications'] ?? [];
                                    if (is_string($specsData)) {
                                        $specsData = json_decode($specsData, true) ?? [];
                                    }
                                    ?>
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

                                    <!-- Tombol Edit -->
                                    <a href="<?= base_url('asset/edit/' . $ast['id']) ?>" class="btn btn-sm btn-outline-warning me-1">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <a href="<?= base_url('asset/delete/' . $ast['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus aset ini?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data aset.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
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

                    // Jika terparsing dua kali (stringified JSON)
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

                            // 1. Cek apakah value merupakan nama file gambar
                            let displayValue = val;
                            if (typeof val === 'string' && val !== '-') {
                                const isImage = /\.(jpg|jpeg|png|webp|gif|svg)$/i.test(val);

                                if (isImage) {
                                    // Render sebagai tag IMG jika file berupa gambar
                                    const imgUrl = `<?= base_url('uploads/specs/') ?>/${val}`;
                                    displayValue = `
                                        <div class="my-1">
                                            <a href="${imgUrl}" target="_blank">
                                                <img src="${imgUrl}" alt="${formattedKey}" class="img-thumbnail shadow-sm" style="max-height: 150px; max-width: 200px; object-fit: cover;">
                                            </a>
                                            <small class="d-block text-muted mt-1"><i class="bi bi-box-arrow-up-right"></i> Klik untuk memperbesar</small>
                                        </div>
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