<?php
/**
 * @var array $components
 */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Master Komponen / Atribut Aset</h2>
            <p class="text-xs text-gray-500 mt-1">Kelola atribut dinamis yang dapat terhubung ke berbagai kategori aset</p>
        </div>
        <a href="<?= base_url('master/components/create') ?>" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm hover:shadow transition-all duration-150">
            <i class="bi bi-plus-lg"></i> Tambah Komponen Baru
        </a>
    </div>

    <!-- Alert Flash Messages -->
    <?php if (session()->getFlashdata('message')) : ?>
        <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm flex items-start justify-between gap-3 shadow-sm">
            <div class="flex items-center gap-2">
                <i class="bi bi-check-circle-fill text-emerald-500 text-lg"></i>
                <span><?= session()->getFlashdata('message') ?></span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 text-lg leading-none">&times;</button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="mb-5 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm flex items-start justify-between gap-3 shadow-sm">
            <div class="flex items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill text-red-500 text-lg"></i>
                <span><?= session()->getFlashdata('error') ?></span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-800 text-lg leading-none">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Card & Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <!-- Table Header -->
                <thead class="bg-gray-800 text-gray-200 uppercase text-xs tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 w-16 text-center">No</th>
                        <th scope="col" class="px-6 py-3.5">Nama Komponen</th>
                        <th scope="col" class="px-6 py-3.5">Key Identifier</th>
                        <th scope="col" class="px-6 py-3.5">Tipe Field Form</th>
                        <th scope="col" class="px-6 py-3.5 text-center w-40">Aksi</th>
                    </tr>
                </thead>

                <!-- Table Body -->
                <tbody class="divide-y divide-gray-200 bg-white">
                    <?php if (!empty($components)) : ?>
                        <?php $i = 1; foreach ($components as $comp) : ?>
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="px-6 py-4 text-center font-medium text-gray-500"><?= $i++ ?></td>
                                <td class="px-6 py-4 font-semibold text-gray-900"><?= esc($comp['nama_komponen']) ?></td>
                                <td class="px-6 py-4">
                                    <code class="px-2 py-1 bg-gray-100 text-purple-700 rounded font-mono text-xs border border-gray-200"><?= esc($comp['key_komponen']) ?></code>
                                </td>
                                <td class="px-6 py-4">
                                    <?php 
                                        $tipe = strtolower($comp['tipe_input']);
                                        if ($tipe === 'file' || $tipe === 'foto') : 
                                    ?>
                                        <!-- Badge khusus untuk tipe Upload File/Foto -->
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                            <i class="bi bi-file-earmark-image"></i> File / Foto Upload
                                        </span>
                                    <?php elseif ($tipe === 'date') : ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-sky-100 text-sky-800 border border-sky-200">
                                            <i class="bi bi-calendar"></i> Date
                                        </span>
                                    <?php elseif ($tipe === 'number') : ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-300">
                                            <i class="bi bi-hash"></i> Number
                                        </span>
                                    <?php elseif ($tipe === 'password') : ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                            <i class="bi bi-key"></i> Password
                                        </span>
                                    <?php else : ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                            <i class="bi bi-fonts"></i> Text
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Tombol Edit -->
                                        <a href="<?= base_url('master/components/edit/' . $comp['id']) ?>" 
                                           class="px-2.5 py-1.5 text-amber-600 hover:text-amber-700 hover:bg-amber-50 rounded-lg border border-amber-300 transition-colors text-xs font-medium inline-flex items-center gap-1"
                                           title="Edit">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <!-- Tombol Hapus -->
                                        <a href="<?= base_url('master/components/delete/' . $comp['id']) ?>" 
                                           class="px-2.5 py-1.5 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg border border-red-300 transition-colors text-xs font-medium inline-flex items-center gap-1" 
                                           onclick="return confirm('Yakin ingin menghapus komponen ini?')"
                                           title="Hapus">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400 italic">
                                Belum ada master komponen. Silakan tambahkan komponen baru.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>