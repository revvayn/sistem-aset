<?php
/**
 * @var array $components
 */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Master Komponen / Atribut Aset</h2>
    <a href="<?= base_url('master/components/create') ?>" class="btn btn-primary">+ Tambah Komponen Baru</a>
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
                        <th>Nama Komponen</th>
                        <th>Key Identifier</th>
                        <th>Tipe Field Form</th>
                        <th width="150" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($components)) : ?>
                        <?php $i = 1; foreach ($components as $comp) : ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><strong><?= esc($comp['nama_komponen']) ?></strong></td>
                                <td><code><?= esc($comp['key_komponen']) ?></code></td>
                                <td><span class="badge bg-info text-dark"><?= esc($comp['tipe_input']) ?></span></td>
                                <td class="text-center">
                                    <a href="<?= base_url('master/components/edit/' . $comp['id']) ?>" class="btn btn-sm btn-outline-warning me-1">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="<?= base_url('master/components/delete/' . $comp['id']) ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('Yakin ingin menghapus komponen ini?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada master komponen. Silakan tambahkan komponen baru.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>