<?php
/**
 * @var array $categories
 */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Master Kategori Aset</h2>
    <a href="<?= base_url('master/categories/create') ?>" class="btn btn-primary">+ Tambah Kategori Baru</a>
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
                        <th>Nama Kategori</th>
                        <th>Keterangan</th>
                        <th>Komponen Terpasang</th>
                        <th width="150" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($categories)) : ?>
                        <?php $i = 1; foreach ($categories as $cat) : ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><strong><?= esc($cat['nama_kategori']) ?></strong></td>
                                <td><?= esc($cat['keterangan'] ?? '-') ?></td>
                                <td>
                                    <?php if (!empty($cat['components'])) : ?>
                                        <div class="d-flex flex-wrap gap-1">
                                            <?php foreach ($cat['components'] as $comp) : ?>
                                                <span class="badge bg-<?= $comp['is_required'] ? 'danger' : 'info text-dark' ?>">
                                                    <?= esc($comp['nama_komponen']) ?> <?= $comp['is_required'] ? '*' : '' ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else : ?>
                                        <em class="text-muted small">Belum ada komponen terhubung</em>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('master/categories/edit/' . $cat['id']) ?>" class="btn btn-sm btn-outline-warning me-1">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="<?= base_url('master/categories/delete/' . $cat['id']) ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada kategori aset tersimpan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>