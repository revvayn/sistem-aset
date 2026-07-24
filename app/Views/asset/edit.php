<?php
/**
 * @var array $asset
 * @var array $categories
 * @var array $components
 * @var array $specs
 */
?>

<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Aset</h5>
                <a href="<?= base_url('asset') ?>" class="btn btn-sm btn-dark">Kembali</a>
            </div>
            <div class="card-body">
                <form action="<?= base_url('asset/update/' . $asset['id']) ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori Aset <span class="text-danger">*</span></label>
                            <select name="master_data_id" class="form-select" required>
                                <?php foreach ($categories as $cat) : ?>
                                    <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $asset['master_data_id']) ? 'selected' : '' ?>>
                                        <?= esc($cat['nama_kategori']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Aset / Kode <span class="text-danger">*</span></label>
                            <input type="text" name="no_asset" class="form-control" value="<?= esc($asset['no_asset'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Nama Aset <span class="text-danger">*</span></label>
                            <input type="text" name="nama_aset" class="form-control" value="<?= esc($asset['nama_aset'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="Aktif" <?= (($asset['status'] ?? '') === 'Aktif') ? 'selected' : '' ?>>Aktif</option>
                                <option value="Perbaikan" <?= (($asset['status'] ?? '') === 'Perbaikan') ? 'selected' : '' ?>>Perbaikan</option>
                                <option value="Rusak" <?= (($asset['status'] ?? '') === 'Rusak') ? 'selected' : '' ?>>Rusak</option>
                                <option value="Non-Aktif" <?= (($asset['status'] ?? '') === 'Non-Aktif') ? 'selected' : '' ?>>Non-Aktif</option>
                            </select>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h6 class="text-primary mb-3"><i class="bi bi-sliders"></i> Atribut / Komponen Spesifikasi</h6>
                    <div class="p-3 bg-light rounded border">
                        <?php if (!empty($components)) : ?>
                            <div class="row">
                                <?php foreach ($components as $comp) : ?>
                                    <?php 
                                        $key      = $comp['key_komponen'];
                                        $val      = $specs[$key] ?? '';
                                        $reqAttr  = ($comp['is_required'] ?? 0) == 1 ? 'required' : '';
                                        $reqBadge = ($comp['is_required'] ?? 0) == 1 ? ' <span class="text-danger">*</span>' : '';
                                        $inputType = !empty($comp['tipe_input']) ? $comp['tipe_input'] : 'text';
                                    ?>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label font-weight-bold"><?= esc($comp['nama_komponen']) ?><?= $reqBadge ?></label>
                                        <input type="<?= esc($inputType) ?>" 
                                               name="specs[<?= esc($key) ?>]" 
                                               value="<?= esc($val) ?>" 
                                               class="form-control" 
                                               <?= $reqAttr ?>>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <p class="text-muted mb-0">Tidak ada komponen tambahan untuk kategori ini.</p>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 mt-4">Update Data Aset</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>