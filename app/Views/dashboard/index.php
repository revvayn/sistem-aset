<?php
/**
 * @var int $totalAssets
 * @var int $totalCategories
 * @var int $totalComponents
 * @var int $totalMaintenance
 * @var array $recentAssets
 * @var array $categoryStats
 */
?>

<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Header Section -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Dashboard Utama</h2>
        <p class="text-sm text-gray-500">Ringkasan statistik data aset, kategori, komponen, dan kelola draf peminjaman.</p>
    </div>
    <div class="text-xs font-medium text-gray-600 bg-white border border-gray-200 px-3.5 py-2 rounded-lg shadow-sm flex items-center gap-2">
        <i class="bi bi-calendar3 text-blue-600"></i> <?= date('d M Y') ?>
    </div>
</div>

<!-- ==================== 1. STATS CARDS ==================== -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <!-- Total Aset -->
    <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
            <i class="bi bi-box-seam"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium">Total Aset</p>
            <h3 class="text-2xl font-bold text-gray-800"><?= $totalAssets ?? 0 ?></h3>
            <span class="text-[11px] text-emerald-600 font-medium inline-flex items-center gap-0.5">
                <i class="bi bi-check-circle"></i> Terdata di sistem
            </span>
        </div>
    </div>

    <!-- Total Kategori -->
    <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xl shrink-0">
            <i class="bi bi-tags"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium">Total Kategori</p>
            <h3 class="text-2xl font-bold text-gray-800"><?= $totalCategories ?? 0 ?></h3>
            <span class="text-[11px] text-gray-400 font-medium">Kategori aktif</span>
        </div>
    </div>

    <!-- Total Komponen Spesifikasi -->
    <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
            <i class="bi bi-cpu"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium">Jenis Komponen</p>
            <h3 class="text-2xl font-bold text-gray-800"><?= $totalComponents ?? 0 ?></h3>
            <span class="text-[11px] text-gray-400 font-medium">Atribut spesifikasi</span>
        </div>
    </div>

    <!-- Aset Non-Aktif -->
    <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xl shrink-0">
            <i class="bi bi-exclamation-octagon"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium">Aset Non-Aktif</p>
            <h3 class="text-2xl font-bold text-gray-800"><?= $totalMaintenance ?? 0 ?></h3>
            <span class="text-[11px] text-rose-600 font-medium">Perbaikan / Rusak</span>
        </div>
    </div>
</div>

<!-- ==================== 2. GRID UTAMA ==================== -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    
    <!-- LEFT COLUMN: Tabel Aset Terbaru & Draf Peminjaman (8 Kolom) -->
    <div class="lg:col-span-8 space-y-6">
        
        <!-- Tabel Aset Terbaru -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                    <i class="bi bi-clock-history text-blue-600"></i> Aset Terbaru Ditambahkan
                </h3>
                <a href="<?= base_url('asset') ?>" class="text-xs text-blue-600 hover:underline font-medium">Lihat Semua Aset &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-[11px] tracking-wider border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3">No. Aset</th>
                            <th class="px-4 py-3">Nama Aset</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if (!empty($recentAssets)) : ?>
                            <?php foreach ($recentAssets as $ast) : ?>
                                <tr class="hover:bg-gray-50/80 transition-colors">
                                    <td class="px-4 py-3 font-mono text-xs">
                                        <code class="px-2 py-0.5 bg-gray-100 text-gray-800 rounded font-mono border border-gray-200"><?= esc($ast['no_asset'] ?? '-') ?></code>
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-gray-900"><?= esc($ast['nama_aset'] ?? '-') ?></td>
                                    <td class="px-4 py-3 text-xs">
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded-full border border-gray-200"><?= esc($ast['nama_kategori'] ?? '-') ?></span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?php
                                        $st = $ast['status'] ?? 'Aktif';
                                        $badge = match ($st) {
                                            'Aktif'     => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            'Perbaikan' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'Rusak'     => 'bg-rose-100 text-rose-800 border-rose-200',
                                            default     => 'bg-gray-100 text-gray-800 border-gray-200',
                                        };
                                        ?>
                                        <span class="px-2.5 py-0.5 text-[11px] font-semibold rounded-full border <?= $badge ?>"><?= esc($st) ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-400 text-xs">Belum ada data aset.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section Keranjang / Draf Peminjaman Aset (Cart) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-100">
                <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                    <i class="bi bi-cart3 text-emerald-600"></i> Draf Keranjang Pengajuan / Peminjaman Aset
                </h3>
                <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-700 font-medium rounded-full text-xs border border-emerald-200">
                    2 Item Terpilih
                </span>
            </div>

            <!-- List Item Cart -->
            <div class="space-y-3 mb-5">
                <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm shrink-0">
                            <i class="bi bi-laptop"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 text-xs">Laptop ThinkPad X1 Carbon</h4>
                            <p class="text-[11px] text-gray-400 font-mono">AST-2026-001 • <span class="text-gray-600">Elektronik</span></p>
                        </div>
                    </div>
                    <button type="button" class="text-rose-500 hover:text-rose-700 p-1 rounded hover:bg-rose-50 transition-colors" title="Hapus dari Draf">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-sm shrink-0">
                            <i class="bi bi-display"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 text-xs">Monitor Dell UltraSharp 27"</h4>
                            <p class="text-[11px] text-gray-400 font-mono">AST-2026-042 • <span class="text-gray-600">Perangkat Output</span></p>
                        </div>
                    </div>
                    <button type="button" class="text-rose-500 hover:text-rose-700 p-1 rounded hover:bg-rose-50 transition-colors" title="Hapus dari Draf">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>

            <!-- Form Eksekusi Cart -->
            <form action="<?= base_url('asset/process-cart') ?>" method="POST" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                <?= csrf_field() ?>
                <div class="sm:col-span-8">
                    <input type="text" name="catatan" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none placeholder-gray-400" placeholder="Keterangan pengajuan / nama peminjam..." required>
                </div>
                <div class="sm:col-span-4">
                    <button type="submit" class="w-full py-2 px-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs rounded-lg shadow-sm transition-colors flex items-center justify-center gap-1.5">
                        <i class="bi bi-send"></i> Ajukan Draf
                    </button>
                </div>
            </form>
        </div>

    </div>

    <!-- RIGHT COLUMN: Distribusi Kategori & Komponen System Info (4 Kolom) -->
    <div class="lg:col-span-4 space-y-6">
        
        <!-- Distribusi Aset per Kategori -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="font-bold text-gray-800 text-sm mb-4 flex items-center gap-2 border-b border-gray-100 pb-2">
                <i class="bi bi-pie-chart text-purple-600"></i> Distribusi Kategori
            </h3>
            <div class="space-y-3.5">
                <?php if (!empty($categoryStats)) : ?>
                    <?php foreach ($categoryStats as $cat) : ?>
                        <?php 
                            $percentage = ($totalAssets > 0) ? round(($cat['total'] / $totalAssets) * 100) : 0; 
                        ?>
                        <div>
                            <div class="flex justify-between text-xs font-medium text-gray-700 mb-1">
                                <span><?= esc($cat['nama_kategori']) ?></span>
                                <span class="text-gray-500 font-semibold"><?= $cat['total'] ?> Aset (<?= $percentage ?>%)</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-purple-600 h-2 rounded-full transition-all duration-300" style="width: <?= $percentage ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="text-xs text-gray-400 text-center py-4">Belum ada statistik kategori.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Summary Komponen / System Status -->
        <div class="bg-gray-900 rounded-xl shadow-sm p-5 text-white">
            <h3 class="font-bold text-sm mb-2 flex items-center gap-2 text-gray-100">
                <i class="bi bi-cpu text-emerald-400"></i> Ringkasan Komponen Aset
            </h3>
            <p class="text-xs text-gray-400 mb-4">
                Sistem menyimpan spesifikasi dinamis berbasis JSON seperti Processor, RAM, GPU, Serial Number, dan File Garansi/Gambar.
            </p>
            
            <div class="pt-3 border-t border-gray-800 flex justify-between items-center text-xs">
                <span class="text-gray-400">Status Sistem Aset:</span>
                <span class="px-2 py-0.5 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded font-semibold text-[11px]">Online / Normal</span>
            </div>
        </div>

    </div>

</div>

<?= $this->endSection() ?>