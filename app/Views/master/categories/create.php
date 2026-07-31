<?php
/**
 * @var array $components
 */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    
    <!-- Card Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        
        <!-- Card Header -->
        <div class="bg-blue-600 px-6 py-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-white">Tambah Master Kategori Aset Baru</h2>
            <a href="<?= base_url('master/categories') ?>" class="inline-flex items-center px-3 py-1.5 bg-white/10 hover:bg-white/20 text-white text-xs font-medium rounded-lg transition-colors">
                <i class="bi bi-arrow-left me-1.5"></i> Kembali
            </a>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <form action="<?= base_url('master/categories/store') ?>" method="POST">
                <?= csrf_field() ?>
                
                <!-- Input Nama Kategori -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Nama Kategori Aset <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="nama_kategori" 
                           class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all outline-none" 
                           placeholder="Contoh: Switch, Router, Laptop" 
                           required>
                </div>

                <!-- Input Keterangan -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Keterangan / Deskripsi
                    </label>
                    <textarea name="keterangan" 
                              rows="3" 
                              class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all outline-none" 
                              placeholder="Penjelasan singkat mengenai kategori aset ini..."></textarea>
                </div>

                <!-- Divider -->
                <hr class="border-gray-200 my-6">

                <!-- Section Subheading -->
                <div class="flex items-center gap-2 mb-4 text-blue-600 font-semibold text-sm sm:text-base">
                    <i class="bi bi-cpu"></i>
                    <span>Pilih Komponen / Atribut yang Dimiliki Kategori Ini:</span>
                </div>
                
                <!-- Table Checklist Komponen -->
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden mb-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead class="bg-gray-50 text-gray-700 uppercase text-xs tracking-wider border-b border-gray-200">
                                <tr>
                                    <th scope="col" class="px-4 py-3.5 w-16 text-center">Pilih</th>
                                    <th scope="col" class="px-6 py-3.5">Nama Komponen</th>
                                    <th scope="col" class="px-6 py-3.5">Key Database</th>
                                    <th scope="col" class="px-6 py-3.5">Tipe Input</th>
                                    <th scope="col" class="px-6 py-3.5 text-center w-36">Wajib Diisi?</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <?php if (!empty($components)) : ?>
                                    <?php foreach ($components as $comp) : ?>
                                        <tr class="hover:bg-gray-50/80 transition-colors">
                                            <!-- Checkbox Pilih Komponen -->
                                            <td class="px-4 py-4 text-center">
                                                <input type="checkbox" 
                                                       name="components[]" 
                                                       value="<?= $comp['id'] ?>" 
                                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 cursor-pointer">
                                            </td>
                                            
                                            <!-- Nama Komponen -->
                                            <td class="px-6 py-4 font-medium text-gray-900">
                                                <?= esc($comp['nama_komponen']) ?>
                                            </td>
                                            
                                            <!-- Key Komponen -->
                                            <td class="px-6 py-4">
                                                <code class="px-2 py-1 bg-gray-100 text-purple-700 rounded font-mono text-xs border border-gray-200"><?= esc($comp['key_komponen']) ?></code>
                                            </td>
                                            
                                            <!-- Tipe Input Badge -->
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800 border border-sky-200">
                                                    <?= esc($comp['tipe_input']) ?>
                                                </span>
                                            </td>
                                            
                                            <!-- Checkbox Required -->
                                            <td class="px-6 py-4 text-center">
                                                <input type="checkbox" 
                                                       name="required[]" 
                                                       value="<?= $comp['id'] ?>" 
                                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 cursor-pointer">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-400 italic">
                                            Belum ada master komponen. Tambahkan komponen terlebih dahulu di menu Master Komponen.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-6">
                    <button type="submit" 
                            class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-lg shadow-sm hover:shadow transition-all duration-150 flex items-center justify-center gap-2">
                        <i class="bi bi-check-circle"></i> Simpan Master Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>