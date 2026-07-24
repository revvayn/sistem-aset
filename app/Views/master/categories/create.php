<?php
/**
 * @var array $components
 */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tambah Master Kategori Aset Baru</h5>
                <a href="<?= base_url('master/categories') ?>" class="btn btn-sm btn-light">Kembali</a>
            </div>
            <div class="card-body">
                <form action="<?= base_url('master/categories/store') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori Aset <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Switch, Router, Laptop" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan / Deskripsi</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Penjelasan singkat mengenai kategori aset ini..."></textarea>
                    </div>

                    <hr class="my-4">

                    <h6 class="text-primary mb-3"><i class="bi bi-cpu me-1"></i> Pilih Komponen / Atribut yang Dimiliki Kategori Ini:</h6>
                    
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="60" class="text-center">Pilih</th>
                                    <th>Nama Komponen</th>
                                    <th>Key Database</th>
                                    <th>Tipe Input</th>
                                    <th width="120" class="text-center">Wajib Diisi?</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($components)) : ?>
                                    <?php foreach ($components as $comp) : ?>
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" name="components[]" value="<?= $comp['id'] ?>" class="form-check-input">
                                            </td>
                                            <td><strong><?= esc($comp['nama_komponen']) ?></strong></td>
                                            <td><code><?= esc($comp['key_komponen']) ?></code></td>
                                            <td><span class="badge bg-info text-dark"><?= esc($comp['tipe_input']) ?></span></td>
                                            <td class="text-center">
                                                <input type="checkbox" name="required[]" value="<?= $comp['id'] ?>" class="form-check-input">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Belum ada master komponen. Tambahkan komponen terlebih dahulu di menu Master Komponen.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-success btn-lg">Simpan Master Kategori</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>