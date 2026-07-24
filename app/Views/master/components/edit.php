<?php
/**
 * @var array $component
 */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Master Komponen</h5>
                <a href="<?= base_url('master/components') ?>" class="btn btn-sm btn-dark">Kembali</a>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <form action="<?= base_url('master/components/update/' . $component['id']) ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Nama Komponen / Atribut <span class="text-danger">*</span></label>
                        <input type="text" name="nama_komponen" class="form-control" value="<?= esc($component['nama_komponen']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipe Input Form <span class="text-danger">*</span></label>
                        <select name="tipe_input" class="form-select" required>
                            <option value="text" <?= $component['tipe_input'] === 'text' ? 'selected' : '' ?>>Text (Teks Bebas / IP / MAC)</option>
                            <option value="number" <?= $component['tipe_input'] === 'number' ? 'selected' : '' ?>>Number (Angka / Kapasitas / Port)</option>
                            <option value="password" <?= $component['tipe_input'] === 'password' ? 'selected' : '' ?>>Password (Sandi / Secret Key)</option>
                            <option value="date" <?= $component['tipe_input'] === 'date' ? 'selected' : '' ?>>Date (Tanggal Pembelian / Garansi)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 mt-3">Update Master Komponen</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>