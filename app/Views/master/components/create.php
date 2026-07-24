<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tambah Master Komponen Baru</h5>
                <a href="<?= base_url('master/components') ?>" class="btn btn-sm btn-light">Kembali</a>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <form action="<?= base_url('master/components/store') ?>" method="POST"> 
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Nama Komponen / Atribut <span class="text-danger">*</span></label>
                        <input type="text" name="nama_komponen" class="form-control" placeholder="Contoh: IMEI 1, Tanggal Garansi, Foto Aset" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipe Input Form <span class="text-danger">*</span></label>
                        <select name="tipe_input" class="form-select" required>
                            <option value="text" selected>Text (Teks Bebas / IP / MAC)</option>
                            <option value="number">Number (Angka / Kapasitas / Port)</option>
                            <option value="password">Password (Sandi / Secret Key)</option>
                            <option value="date">Date (Tanggal Pembelian / Garansi)</option>
                            <option value="file">File / Foto (Upload Gambar / Dokumen)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success w-100 mt-3">Simpan Master Komponen</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>