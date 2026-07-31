<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    
    <!-- Card Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        
        <!-- Card Header -->
        <div class="bg-blue-600 px-6 py-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-white">Tambah Master Komponen Baru</h2>
            <a href="<?= base_url('master/components') ?>" class="inline-flex items-center px-3 py-1.5 bg-white/10 hover:bg-white/20 text-white text-xs font-medium rounded-lg transition-colors">
                <i class="bi bi-arrow-left me-1.5"></i> Kembali
            </a>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <!-- Alert Flashdata Error -->
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="mb-5 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm flex items-start justify-between gap-3 shadow-sm">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill text-red-500 text-lg"></i>
                        <span><?= session()->getFlashdata('error') ?></span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-800 text-lg leading-none">&times;</button>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('master/components/store') ?>" method="POST"> 
                <?= csrf_field() ?>

                <!-- Input Nama Komponen -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Nama Komponen / Atribut <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="nama_komponen" 
                           class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all outline-none" 
                           placeholder="Contoh: IMEI 1, Tanggal Garansi, Foto Aset" 
                           required>
                </div>

                <!-- Select Tipe Input -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Tipe Input Form <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="tipe_input" 
                                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all outline-none appearance-none cursor-pointer" 
                                required>
                            <option value="text" selected>Text (Teks Bebas / IP / MAC)</option>
                            <option value="number">Number (Angka / Kapasitas / Port)</option>
                            <option value="password">Password (Sandi / Secret Key)</option>
                            <option value="date">Date (Tanggal Pembelian / Garansi)</option>
                            <option value="file">File / Foto (Upload Gambar / Dokumen)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                            <i class="bi bi-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8">
                    <button type="submit" 
                            class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-lg shadow-sm hover:shadow transition-all duration-150 flex items-center justify-center gap-2">
                        <i class="bi bi-check-circle"></i> Simpan Master Komponen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>