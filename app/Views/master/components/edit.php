<?php
/**
 * @var array $component
 */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-3xl mx-auto">

    <!-- Card Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">

        <!-- Card Header -->
        <div class="px-6 py-5 bg-gradient-to-r from-amber-500 to-orange-500 flex items-center justify-between">
            <h2 class="text-base font-bold text-white flex items-center gap-2.5">
                <span class="w-9 h-9 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center text-lg"><i class="bi bi-pencil-square"></i></span>
                Edit Master Komponen
            </h2>
            <a href="<?= base_url('master/components') ?>" class="inline-flex items-center px-3 py-2 bg-white/10 hover:bg-white/20 text-white text-xs font-medium rounded-lg transition-colors border border-white/20">
                <i class="bi bi-arrow-left me-1.5"></i> Kembali
            </a>
        </div>

        <!-- Card Body -->
        <div class="p-6 sm:p-8">
            <form action="<?= base_url('master/components/update/' . $component['id']) ?>" method="POST">
                <?= csrf_field() ?>

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Nama Komponen / Atribut <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nama_komponen"
                               value="<?= esc($component['nama_komponen']) ?>"
                               class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:bg-white transition-all outline-none"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Tipe Input Form <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="tipe_input"
                                    class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:bg-white transition-all outline-none appearance-none cursor-pointer"
                                    required>
                                <option value="text" <?= $component['tipe_input'] === 'text' ? 'selected' : '' ?>>Text (Teks Bebas / IP / MAC)</option>
                                <option value="number" <?= $component['tipe_input'] === 'number' ? 'selected' : '' ?>>Number (Angka / Kapasitas / Port)</option>
                                <option value="password" <?= $component['tipe_input'] === 'password' ? 'selected' : '' ?>>Password (Sandi / Secret Key)</option>
                                <option value="date" <?= $component['tipe_input'] === 'date' ? 'selected' : '' ?>>Date (Tanggal Pembelian / Garansi)</option>
                                <option value="file" <?= $component['tipe_input'] === 'file' ? 'selected' : '' ?>>File / Foto (Upload Gambar / Dokumen)</option>
                                <option value="qr_code" <?= $component['tipe_input'] === 'qr_code' ? 'selected' : '' ?>>QR Code</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                <i class="bi bi-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="w-full mt-8 py-3.5 px-4 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold text-sm rounded-xl shadow-md shadow-amber-600/20 transition-all duration-150 active:scale-[0.99] flex items-center justify-center gap-2">
                    <i class="bi bi-pencil-square"></i> Update Master Komponen
                </button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>