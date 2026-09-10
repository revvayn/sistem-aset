<?php
/**
 * @var array $categories
 */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-purple-500/10 to-fuchsia-500/10 border border-purple-500/20 flex items-center justify-center text-purple-600 text-lg shrink-0">
                <i class="bi bi-tags"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900 tracking-tight">Master Kategori Aset</h2>
                <p class="text-xs text-gray-500 mt-0.5">Kelola data kategori aset dan komponen spesifikasi terhubung</p>
            </div>
        </div>
        <a href="<?= base_url('master/categories/create') ?>" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold text-sm rounded-xl shadow-md shadow-blue-600/20 transition-all duration-150 active:scale-[0.98]">
            <i class="bi bi-plus-lg"></i> Tambah Kategori Baru
        </a>
    </div>

    <!-- Card & Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 w-16 text-center text-[11px] font-bold uppercase tracking-wider text-gray-500">No</th>
                        <th scope="col" class="px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">Nama Kategori</th>
                        <th scope="col" class="px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">Keterangan</th>
                        <th scope="col" class="px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">Komponen Terpasang</th>
                        <th scope="col" class="px-6 py-3.5 text-center w-44 text-[11px] font-bold uppercase tracking-wider text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <?php if (!empty($categories)) : ?>
                        <?php $i = 1; foreach ($categories as $cat) : ?>
                            <tr class="hover:bg-purple-50/40 transition-colors">
                                <td class="px-6 py-4 text-center font-medium text-gray-400"><?= $i++ ?></td>
                                <td class="px-6 py-4 font-semibold text-gray-900"><?= esc($cat['nama_kategori']) ?></td>
                                <td class="px-6 py-4 text-gray-600 max-w-[240px]"><span class="line-clamp-2"><?= esc($cat['keterangan'] ?? '-') ?></span></td>
                                <td class="px-6 py-4">
                                    <?php if (!empty($cat['components'])) : ?>
                                        <div class="flex flex-wrap gap-1.5">
                                            <?php foreach ($cat['components'] as $comp) : ?>
                                                <?php if ($comp['is_required']) : ?>
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200">
                                                        <?= esc($comp['nama_komponen']) ?> *
                                                    </span>
                                                <?php else : ?>
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                        <?= esc($comp['nama_komponen']) ?>
                                                    </span>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else : ?>
                                        <span class="text-xs text-gray-400 italic">Belum ada komponen terhubung</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="<?= base_url('master/categories/edit/' . $cat['id']) ?>"
                                           class="w-8 h-8 inline-flex items-center justify-center border border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100 rounded-lg transition-colors text-sm" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="<?= base_url('master/categories/delete/' . $cat['id']) ?>"
                                           method="POST" class="inline"
                                           onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
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
                                    <i class="bi bi-tags text-3xl"></i>
                                </div>
                                <p class="text-sm font-medium text-gray-500">Belum ada kategori aset tersimpan</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>