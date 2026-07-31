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
<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Card Utama -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        
        <!-- Header Card -->
        <div class="bg-amber-500 px-6 py-4 flex justify-between items-center text-white">
            <h3 class="text-lg font-bold flex items-center gap-2">
                <i class="bi bi-pencil-square"></i> Edit Aset
            </h3>
            <a href="<?= base_url('asset') ?>" class="px-3 py-1.5 bg-gray-900 hover:bg-gray-800 text-white font-medium text-xs rounded-lg transition-colors duration-150 inline-flex items-center gap-1 shadow-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Body Card -->
        <div class="p-6">
            <!-- Form Wajib enctype multipart/form-data -->
            <form action="<?= base_url('asset/update/' . $asset['id']) ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                <?= csrf_field() ?>

                <!-- Grid Input Informasi Dasar Aset -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Kategori Aset -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Kategori Aset <span class="text-red-500">*</span>
                        </label>
                        <select name="master_data_id" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all" required>
                            <?php foreach ($categories as $cat) : ?>
                                <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $asset['master_data_id']) ? 'selected' : '' ?>>
                                    <?= esc($cat['nama_kategori']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- No. Aset / Kode -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            No. Aset / Kode <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="no_asset" value="<?= esc($asset['no_asset'] ?? '') ?>" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                    <!-- Nama Aset -->
                    <div class="md:col-span-8">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Aset <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_aset" value="<?= esc($asset['nama_aset'] ?? '') ?>" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all" required>
                    </div>

                    <!-- Status -->
                    <div class="md:col-span-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all" required>
                            <option value="Aktif" <?= (($asset['status'] ?? '') === 'Aktif') ? 'selected' : '' ?>>Aktif</option>
                            <option value="Perbaikan" <?= (($asset['status'] ?? '') === 'Perbaikan') ? 'selected' : '' ?>>Perbaikan</option>
                            <option value="Rusak" <?= (($asset['status'] ?? '') === 'Rusak') ? 'selected' : '' ?>>Rusak</option>
                            <option value="Non-Aktif" <?= (($asset['status'] ?? '') === 'Non-Aktif') ? 'selected' : '' ?>>Non-Aktif</option>
                        </select>
                    </div>
                </div>

                <hr class="border-gray-200 my-6">

                <!-- Section Atribut / Komponen Spesifikasi -->
                <div>
                    <h4 class="text-base font-bold text-blue-600 flex items-center gap-2 mb-3">
                        <i class="bi bi-sliders"></i> Atribut / Komponen Spesifikasi
                    </h4>
                    
                    <div class="p-5 bg-gray-50 rounded-xl border border-gray-200">
                        <?php if (!empty($components)) : ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <?php foreach ($components as $comp) : ?>
                                    <?php 
                                        $key       = $comp['key_komponen'];
                                        $val       = $specs[$key] ?? '';
                                        $reqAttr   = ($comp['is_required'] ?? 0) == 1 ? 'required' : '';
                                        $reqBadge  = ($comp['is_required'] ?? 0) == 1 ? ' <span class="text-red-500">*</span>' : '';
                                        $inputType = !empty($comp['tipe_input']) ? $comp['tipe_input'] : 'text';
                                    ?>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                                            <?= esc($comp['nama_komponen']) ?><?= $reqBadge ?>
                                        </label>
                                        
                                        <?php if ($inputType === 'file' || $inputType === 'foto') : ?>
                                            <!-- Penanganan Khusus Input FILE / FOTO -->
                                            <?php if (!empty($val) && file_exists(FCPATH . 'uploads/specs/' . $val)) : ?>
                                                <div class="mb-3 p-2 bg-white rounded-lg border border-gray-200 inline-block">
                                                    <a href="<?= base_url('uploads/specs/' . $val) ?>" target="_blank" class="inline-block">
                                                        <img src="<?= base_url('uploads/specs/' . $val) ?>" alt="Foto Aset" class="h-24 w-auto object-cover rounded border border-gray-200 hover:opacity-90 transition-opacity">
                                                    </a>
                                                    <span class="block text-xs text-gray-500 mt-1 font-mono truncate max-w-[200px]">File: <?= esc($val) ?></span>
                                                </div>
                                            <?php endif; ?>

                                            <input type="file" 
                                                   name="specs[<?= esc($key) ?>]" 
                                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg cursor-pointer bg-white outline-none focus:outline-none" 
                                                   accept="image/png, image/jpeg, image/jpg"
                                                   <?= empty($val) ? $reqAttr : '' ?>>
                                            <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah foto/file.</p>

                                        <?php else : ?>
                                            <!-- Input Tipe Biasa (Text, Number, Date, dll) -->
                                            <input type="<?= esc($inputType) ?>" 
                                                   name="specs[<?= esc($key) ?>]" 
                                                   value="<?= esc($val) ?>" 
                                                   class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" 
                                                   <?= $reqAttr ?>>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <p class="text-sm text-gray-500 italic text-center py-2">Tidak ada komponen tambahan untuk kategori ini.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="pt-2">
                    <button type="submit" class="w-full py-3 px-4 bg-amber-500 hover:bg-amber-600 text-white font-semibold text-sm rounded-lg shadow-sm hover:shadow transition-all duration-150 flex items-center justify-center gap-2">
                        <i class="bi bi-check-circle-fill"></i> Update Data Aset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>