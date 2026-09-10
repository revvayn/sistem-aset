<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto">
    <!-- Card Utama -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">

        <!-- Header Card -->
        <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-indigo-600 flex justify-between items-center text-white">
            <h3 class="text-base font-bold flex items-center gap-2.5">
                <span class="w-9 h-9 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center text-lg"><i class="bi bi-plus-circle-fill"></i></span>
                Tambah Dokumentasi Aset Baru
            </h3>
            <a href="<?= base_url('asset') ?>" class="px-3 py-2 bg-white/10 hover:bg-white/20 text-white font-medium text-xs rounded-lg transition-colors duration-150 inline-flex items-center gap-1.5 border border-white/20">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Body Card -->
        <div class="p-6 sm:p-8">
            <?php if (session()->getFlashdata('errors')) : ?>
                <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 shadow-sm">
                    <p class="flex items-center gap-2 text-sm font-semibold mb-1.5"><i class="bi bi-exclamation-triangle"></i> Periksa kembali isian berikut:</p>
                    <ul class="text-xs list-disc list-inside space-y-0.5">
                        <?php foreach (session()->getFlashdata('errors') as $err) : ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 shadow-sm flex items-start justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-lg bg-rose-100 flex items-center justify-center shrink-0"><i class="bi bi-exclamation-triangle text-rose-600"></i></span>
                        <span><?= session()->getFlashdata('error') ?></span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800 text-lg leading-none">&times;</button>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('asset/store') ?>" method="POST" enctype="multipart/form-data" class="space-y-5">
                <?= csrf_field() ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="master_data_id" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Pilih Kategori (Master Data) <span class="text-red-500">*</span>
                        </label>
                        <select class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white outline-none transition-all cursor-pointer" id="master_data_id" name="master_data_id" required>
                            <option value="" selected disabled>-- Pilih Kategori Aset --</option>
                            <?php foreach ($categories ?? [] as $cat) : ?>
                                <option value="<?= $cat['id'] ?>"><?= esc($cat['nama_kategori']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Status Aset
                        </label>
                        <select class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white outline-none transition-all cursor-pointer" id="status" name="status">
                            <option value="Aktif" selected>Aktif</option>
                            <option value="Perbaikan">Perbaikan</option>
                            <option value="Rusak">Rusak</option>
                            <option value="Disimpan">Disimpan</option>
                        </select>
                    </div>
                </div>

                <hr class="border-gray-200 my-6">

                <!-- Dynamic Components Area -->
                <div id="dynamic-components-wrapper" class="hidden">
                    <h4 class="text-base font-bold text-blue-600 flex items-center gap-2 mb-4">
                        <span class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-sm"><i class="bi bi-sliders"></i></span>
                        Spesifikasi Komponen Kategori
                    </h4>
                    <div id="dynamic-components-fields" class="p-5 bg-gray-50 rounded-xl border border-gray-200/80 grid grid-cols-1 md:grid-cols-2 gap-5"></div>
                </div>

                <!-- Loading Spinner -->
                <div id="loading-spinner" class="text-center hidden my-6 py-4">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-blue-600 border-t-transparent" role="status"></div>
                    <p class="text-xs text-gray-500 mt-2 font-medium">Mengekstrak atribut komponen...</p>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-semibold text-sm rounded-xl shadow-md shadow-emerald-600/20 active:scale-[0.99] transition-all duration-150 flex items-center justify-center gap-2">
                        <i class="bi bi-check-circle-fill"></i> Simpan Aset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('master_data_id');
        const wrapper = document.getElementById('dynamic-components-wrapper');
        const container = document.getElementById('dynamic-components-fields');
        const spinner = document.getElementById('loading-spinner');

        if (categorySelect) {
            categorySelect.addEventListener('change', function() {
                const masterDataId = this.value;
                if (!masterDataId) return;

                spinner.classList.remove('hidden');
                wrapper.classList.add('hidden');
                container.innerHTML = '';

                fetch(`<?= base_url('asset/get-components') ?>/${masterDataId}`)
                    .then(response => response.json())
                    .then(data => {
                        spinner.classList.add('hidden');

                        if (data.length > 0) {
                            wrapper.classList.remove('hidden');

                            data.forEach(comp => {
                                const requiredAttr = comp.is_required == 1 ? 'required' : '';
                                const requiredLabel = comp.is_required == 1 ? '<span class="text-red-500">*</span>' : '';
                                const baseInputClass = "w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all";

                                let inputHtml = '';
                                if (comp.tipe_input === 'password') {
                                    inputHtml = `<input type="password" class="${baseInputClass}" name="specs[${comp.key_komponen}]" ${requiredAttr}>`;
                                } else if (comp.tipe_input === 'number') {
                                    inputHtml = `<input type="number" class="${baseInputClass}" name="specs[${comp.key_komponen}]" ${requiredAttr}>`;
                                } else if (comp.tipe_input === 'date') {
                                    inputHtml = `<input type="date" class="${baseInputClass}" name="specs[${comp.key_komponen}]" ${requiredAttr}>`;
                                } else if (comp.tipe_input === 'file' || comp.tipe_input === 'foto') {
                                    let acceptFormat = "image/png, image/jpeg, image/jpg, .pdf, .doc, .docx";
                                    let helpText = "Format yang diizinkan: JPG, PNG, PDF, DOC, DOCX";

                                    if (comp.tipe_input === 'foto') {
                                        acceptFormat = "image/png, image/jpeg, image/jpg";
                                        helpText = "Format yang diizinkan: JPG, JPEG, PNG";
                                    } else if (comp.tipe_input === 'file') {
                                        acceptFormat = ".pdf, .doc, .docx, image/png, image/jpeg, image/jpg";
                                        helpText = "Format yang diizinkan: PDF, DOC, DOCX, JPG, PNG";
                                    }

                                    const fileInputClass = "block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-xl cursor-pointer bg-white outline-none focus:outline-none";

                                    inputHtml = `
                                        <input type="file" class="${fileInputClass}" name="specs[${comp.key_komponen}]" accept="${acceptFormat}" ${requiredAttr}>
                                        <p class="text-xs text-gray-500 mt-1">${helpText}</p>
                                    `;
                                } else {
                                    inputHtml = `<input type="text" class="${baseInputClass}" name="specs[${comp.key_komponen}]" ${requiredAttr} placeholder="${comp.nama_komponen}">`;
                                }

                                const fieldGroup = `
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">${comp.nama_komponen} ${requiredLabel}</label>
                                        ${inputHtml}
                                    </div>
                                `;

                                container.insertAdjacentHTML('beforeend', fieldGroup);
                            });
                        }
                    })
                    .catch(error => {
                        spinner.classList.add('hidden');
                        console.error('Error fetching components:', error);
                    });
            });
        }
    });
</script>
<?= $this->endSection() ?>