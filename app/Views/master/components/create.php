<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-3xl mx-auto">

    <!-- Card Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">

        <!-- Card Header -->
        <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center justify-between">
            <h2 class="text-base font-bold text-white flex items-center gap-2.5">
                <span class="w-9 h-9 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center text-lg"><i class="bi bi-plus-circle"></i></span>
                Tambah Master Komponen Baru
            </h2>
            <a href="<?= base_url('master/components') ?>" class="inline-flex items-center px-3 py-2 bg-white/10 hover:bg-white/20 text-white text-xs font-medium rounded-lg transition-colors border border-white/20">
                <i class="bi bi-arrow-left me-1.5"></i> Kembali
            </a>
        </div>

        <!-- Card Body -->
        <div class="p-6 sm:p-8">
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 shadow-sm flex items-start justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-lg bg-rose-100 flex items-center justify-center shrink-0"><i class="bi bi-exclamation-triangle text-rose-600"></i></span>
                        <span><?= session()->getFlashdata('error') ?></span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800 text-lg leading-none">&times;</button>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('master/components/store') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Nama Komponen / Atribut <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nama_komponen"
                               class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all outline-none"
                               placeholder="Contoh: IMEI 1, Tanggal Garansi, Foto Aset"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Tipe Input Form <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="tipe_input"
                                    class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all outline-none appearance-none cursor-pointer"
                                    required>
                                <option value="text" selected>Text (Teks Bebas / IP / MAC)</option>
                                <option value="number">Number (Angka / Kapasitas / Port)</option>
                                <option value="password">Password (Sandi / Secret Key)</option>
                                <option value="date">Date (Tanggal Pembelian / Garansi)</option>
                                <option value="file">File / Foto (Upload Gambar / Dokumen)</option>
                                <option value="qr_code">QR Code</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="w-full mt-8 py-3.5 px-4 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-semibold text-sm rounded-xl shadow-md shadow-emerald-600/20 transition-all duration-150 active:scale-[0.99] flex items-center justify-center gap-2">
                    <i class="bi bi-check-circle"></i> Simpan Master Komponen
                </button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>