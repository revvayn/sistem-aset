<?php
/**
 * @var array $category
 * @var array $allComponents
 * @var array $activeCompIds
 * @var array $requiredCompIds
 */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="card shadow-sm">
    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Kategori Aset</h5>
        <a href="<?= base_url('master/categories') ?>" class="btn btn-sm btn-dark">Kembali</a>
    </div>
    <div class="card-body">
        <form action="<?= base_url('master/categories/update/' . $category['id']) ?>" method="POST">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Nama Kategori Aset <span class="text-danger">*</span></label>
                <input type="text" name="nama_kategori" class="form-control" value="<?= esc($category['nama_kategori']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="2"><?= esc($category['keterangan'] ?? '') ?></textarea>
            </div>

            <h6 class="mt-4 mb-3">Atur Komponen/Atribut untuk Kategori Ini:</h6>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="50" class="text-center">Pilih</th>
                            <th>Nama Komponen</th>
                            <th>Tipe Input</th>
                            <th width="120" class="text-center">Wajib Diisi?</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($allComponents as $comp): ?>
                            <?php 
                                $isChecked = in_array($comp['id'], $activeCompIds);
                                $isRequired = in_array($comp['id'], $requiredCompIds);
                            ?>
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" name="components[]" value="<?= $comp['id'] ?>" class="form-check-input" <?= $isChecked ? 'checked' : '' ?>>
                                </td>
                                <td><?= esc($comp['nama_komponen']) ?></td>
                                <td><span class="badge bg-info text-dark"><?= esc($comp['tipe_input']) ?></span></td>
                                <td class="text-center">
                                    <input type="checkbox" name="required[]" value="<?= $comp['id'] ?>" class="form-check-input" <?= $isRequired ? 'checked' : '' ?>>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-warning mt-3">Update Master Kategori</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>