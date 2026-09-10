<?php
/**
 * @var array $components
 */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-emerald-500/10 to-teal-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-600 text-lg shrink-0">
                <i class="bi bi-cpu"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900 tracking-tight">Master Komponen / Atribut Aset</h2>
                <p class="text-xs text-gray-500 mt-0.5">Kelola atribut dinamis yang dapat terhubung ke berbagai kategori aset</p>
            </div>
        </div>
        <a href="<?= base_url('master/components/create') ?>" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold text-sm rounded-xl shadow-md shadow-blue-600/20 transition-all duration-150 active:scale-[0.98]">
            <i class="bi bi-plus-lg"></i> Tambah Komponen Baru
        </a>
    </div>

    <!-- Card & Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 w-16 text-center text-[11px] font-bold uppercase tracking-wider text-gray-500">No</th>
                        <th scope="col" class="px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">Nama Komponen</th>
                        <th scope="col" class="px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">Key Identifier</th>
                        <th scope="col" class="px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">Tipe Field Form</th>
                        <th scope="col" class="px-6 py-3.5 text-center w-44 text-[11px] font-bold uppercase tracking-wider text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <?php if (!empty($components)) : ?>
                        <?php $i = 1; foreach ($components as $comp) : ?>
                            <tr class="hover:bg-emerald-50/40 transition-colors">
                                <td class="px-6 py-4 text-center font-medium text-gray-400"><?= $i++ ?></td>
                                <td class="px-6 py-4 font-semibold text-gray-900"><?= esc($comp['nama_komponen']) ?></td>
                                <td class="px-6 py-4">
                                    <code class="px-2 py-1 bg-gray-100 text-purple-700 rounded-lg font-mono text-xs border border-gray-200"><?= esc($comp['key_komponen']) ?></code>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                        $tipe = strtolower($comp['tipe_input']);
                                        if ($tipe === 'file' || $tipe === 'foto') :
                                    ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700 border border-purple-200">
                                            <i class="bi bi-file-earmark-image"></i> File / Foto Upload
                                        </span>
                                    <?php elseif ($tipe === 'date') : ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-sky-50 text-sky-700 border border-sky-200">
                                            <i class="bi bi-calendar"></i> Date
                                        </span>
                                    <?php elseif ($tipe === 'number') : ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                            <i class="bi bi-hash"></i> Number
                                        </span>
                                    <?php elseif ($tipe === 'password') : ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                            <i class="bi bi-key"></i> Password
                                        </span>
                                    <?php elseif ($tipe === 'qr_code') : ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-cyan-50 text-cyan-700 border border-cyan-200">
                                            <i class="bi bi-qr-code"></i> QR Code
                                        </span>
                                    <?php else : ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                            <i class="bi bi-fonts"></i> Text
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="<?= base_url('master/components/edit/' . $comp['id']) ?>"
                                           class="w-8 h-8 inline-flex items-center justify-center border border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100 rounded-lg transition-colors text-sm" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="<?= base_url('master/components/delete/' . $comp['id']) ?>"
                                           method="POST" class="inline"
                                           onsubmit="return confirm('Yakin ingin menghapus komponen ini?')">
                                            <?= csrf_field() ?>
                                            <button type="submit"
                                               class="w-8 h-8 inline-flex items-center justify-center border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 rounded-lg transition-colors text-sm"
                                               title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="px-6 py-14 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gray-50 border border-gray-200 mb-3 text-gray-300">
                                    <i class="bi bi-cpu text-3xl"></i>
                                </div>
                                <p class="text-sm font-medium text-gray-500">Belum ada master komponen</p>
                                <p class="text-xs text-gray-400 mt-1">Silakan tambahkan komponen baru.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>