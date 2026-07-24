<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tambah Dokumentasi Aset Baru</h5>
                <a href="<?= base_url('asset') ?>" class="btn btn-sm btn-light">Kembali</a>
            </div>
            <div class="card-body">
                <form action="<?= base_url('asset/store') ?>" method="POST">
                    <?= csrf_field() ?>

                    <!-- Informasi Umum -->
                    <div class="mb-3">
                        <label for="no_asset" class="form-label">No. Aset / Kode Unik <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="no_asset" name="no_asset" placeholder="Contoh: AST-PC-2026-001" required>
                    </div>

                    <div class="mb-3">
                        <label for="nama_aset" class="form-label">Nama Aset <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_aset" name="nama_aset" placeholder="Contoh: PC Server Utama" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="master_data_id" class="form-label">Pilih Kategori (Master Data) <span class="text-danger">*</span></label>
                            <select class="form-select" id="master_data_id" name="master_data_id" required>
                                <option value="" selected disabled>-- Pilih Kategori Aset --</option>
                                <?php foreach ($categories ?? [] as $cat) : ?>
                                    <option value="<?= $cat['id'] ?>"><?= esc($cat['nama_kategori']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status Aset</label>
                            <select class="form-select" id="status" name="status">
                                <option value="Aktif" selected>Aktif</option>
                                <option value="Perbaikan">Perbaikan</option>
                                <option value="Rusak">Rusak</option>
                                <option value="Disimpan">Disimpan</option>
                            </select>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Dynamic Components Area -->
                    <div id="dynamic-components-wrapper" class="d-none">
                        <h6 class="text-primary mb-3">Spesifikasi Komponen Kategori</h6>
                        <div id="dynamic-components-fields"></div>
                    </div>

                    <div id="loading-spinner" class="text-center d-none my-3">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="small text-muted mt-2">Mengekstrak atribut komponen...</p>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-success btn-lg">Simpan Aset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const categorySelect = document.getElementById('master_data_id');
    const wrapper = document.getElementById('dynamic-components-wrapper');
    const container = document.getElementById('dynamic-components-fields');
    const spinner = document.getElementById('loading-spinner');

    if(categorySelect) {
        categorySelect.addEventListener('change', function () {
            const masterDataId = this.value;
            if (!masterDataId) return;

            spinner.classList.remove('d-none');
            wrapper.classList.add('d-none');
            container.innerHTML = '';

            fetch(`<?= base_url('asset/get-components') ?>/${masterDataId}`)
                .then(response => response.json())
                .then(data => {
                    spinner.classList.add('d-none');

                    if (data.length > 0) {
                        wrapper.classList.remove('d-none');

                        data.forEach(comp => {
                            const requiredAttr = comp.is_required == 1 ? 'required' : '';
                            const requiredLabel = comp.is_required == 1 ? '<span class="text-danger">*</span>' : '';
                            
                            let inputHtml = '';
                            if (comp.tipe_input === 'password') {
                                inputHtml = `<input type="password" class="form-control" name="specs[${comp.key_komponen}]" ${requiredAttr}>`;
                            } else if (comp.tipe_input === 'number') {
                                inputHtml = `<input type="number" class="form-control" name="specs[${comp.key_komponen}]" ${requiredAttr}>`;
                            } else if (comp.tipe_input === 'date') {
                                inputHtml = `<input type="date" class="form-control" name="specs[${comp.key_komponen}]" ${requiredAttr}>`;
                            } else {
                                inputHtml = `<input type="text" class="form-control" name="specs[${comp.key_komponen}]" ${requiredAttr}>`;
                            }

                            const fieldGroup = `
                                <div class="mb-3">
                                    <label class="form-label">${comp.nama_komponen} ${requiredLabel}</label>
                                    ${inputHtml}
                                </div>
                            `;

                            container.insertAdjacentHTML('beforeend', fieldGroup);
                        });
                    }
                })
                .catch(error => {
                    spinner.classList.add('d-none');
                    console.error('Error fetching components:', error);
                });
        });
    }
});
</script>
<?= $this->endSection() ?>