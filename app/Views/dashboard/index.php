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

<!-- Notifikasi Mengambang -->
<?php
$notifications = [];

foreach (($warrantyExpired ?? []) as $w) {
    $days = (int) floor((strtotime(date('Y-m-d')) - strtotime($w['warranty_expiry'])) / 86400);
    $notifications[] = [
        'tone'  => 'rose',
        'icon'  => 'bi-shield-x',
        'title' => 'Garansi sudah berakhir',
        'desc'  => 'Berakhir ' . max(1, $days) . ' hari lalu (' . date('d M Y', strtotime($w['warranty_expiry'])) . ')',
        'asset' => $w,
    ];
}

foreach (($warrantyAlerts ?? []) as $w) {
    $days = (int) ceil((strtotime($w['warranty_expiry']) - strtotime(date('Y-m-d'))) / 86400);
    $notifications[] = [
        'tone'  => 'amber',
        'icon'  => 'bi-exclamation-triangle',
        'title' => 'Garansi berakhir dalam ' . max(0, $days) . ' hari',
        'desc'  => 'Jatuh tempo ' . date('d M Y', strtotime($w['warranty_expiry'])),
        'asset' => $w,
    ];
}

foreach (($staleMaintenance ?? []) as $s) {
    $days = (int) floor((time() - strtotime($s['updated_at'])) / 86400);
    $notifications[] = [
        'tone'  => 'orange',
        'icon'  => 'bi-tools',
        'title' => 'Perbaikan berlarut-larut',
        'desc'  => 'Dalam perbaikan ' . max(1, $days) . ' hari (sejak ' . date('d M Y', strtotime($s['updated_at'])) . ')',
        'asset' => $s,
    ];
}
$notificationTotal = (int) ($totalNotifications ?? count($notifications));
?>
<?php if (!empty($notifications)) : ?>
<div x-data="{ openNotes: false }"
     @click.outside="openNotes = false"
     class="fixed bottom-6 right-6 z-[80] print:hidden">

    <!-- Panel Notifikasi -->
    <div x-show="openNotes"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
         class="absolute bottom-20 right-0 w-[calc(100vw-3rem)] sm:w-[380px] bg-white rounded-2xl border border-gray-200 shadow-2xl shadow-gray-900/20 overflow-hidden origin-bottom-right">
        <div class="flex items-center gap-3 px-5 py-3.5 border-b border-gray-100 bg-gradient-to-r from-white to-amber-50/60">
            <span class="w-9 h-9 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center relative shrink-0">
                <i class="bi bi-bell"></i>
            </span>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-gray-900">Pusat Notifikasi</p>
                <p class="text-[11px] text-gray-500">
                    <?php if (count($notifications) < $notificationTotal) : ?>
                        Menampilkan <?= count($notifications) ?> dari <?= $notificationTotal ?> notifikasi terbaru
                    <?php else : ?>
                        <?= $notificationTotal ?> notifikasi perlu ditindaklanjuti
                    <?php endif; ?>
                </p>
            </div>
            <button type="button" @click="openNotes = false" class="w-8 h-8 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 flex items-center justify-center transition-colors" aria-label="Tutup notifikasi">
                <i class="bi bi-x-lg text-xs"></i>
            </button>
        </div>
        <div class="max-h-[min(65vh,420px)] overflow-y-auto divide-y divide-gray-100">
            <?php foreach ($notifications as $note) : ?>
                <?php
                $asset = $note['asset'];
                $status = $asset['status'] ?? 'Aktif';
                $statusClass = match ($status) {
                    'Aktif'     => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'Perbaikan' => 'bg-amber-50 text-amber-700 border-amber-200',
                    'Rusak'     => 'bg-rose-50 text-rose-700 border-rose-200',
                    default     => 'bg-gray-50 text-gray-600 border-gray-200',
                };
                $toneClasses = [
                    'rose'   => ['icon' => 'bg-rose-100 text-rose-600',   'title' => 'text-rose-800',   'desc' => 'text-rose-600'],
                    'amber'  => ['icon' => 'bg-amber-100 text-amber-600', 'title' => 'text-amber-800', 'desc' => 'text-amber-600'],
                    'orange' => ['icon' => 'bg-orange-100 text-orange-600', 'title' => 'text-orange-800', 'desc' => 'text-orange-600'],
                ];
                $tone = $toneClasses[$note['tone']] ?? $toneClasses['amber'];
                ?>
                <a href="<?= base_url('asset/detail/' . $asset['id']) ?>" class="flex items-start gap-3 px-5 py-3.5 hover:bg-gray-50 transition-colors group">
                    <span class="w-8 h-8 rounded-lg <?= $tone['icon'] ?> flex items-center justify-center shrink-0">
                        <i class="bi <?= esc($note['icon']) ?> text-sm"></i>
                    </span>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="text-xs font-bold <?= $tone['title'] ?>"><?= esc($note['title']) ?></p>
                            <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full border <?= $statusClass ?>"><?= esc($status) ?></span>
                        </div>
                        <p class="text-[11px] text-gray-600 mt-0.5 truncate">
                            <span class="font-semibold text-gray-800"><?= esc($asset['serial_number'] ?: ('Aset #' . $asset['id'])) ?></span>
                            &bull; <?= esc($asset['nama_kategori'] ?? '-') ?>
                            <?php if (!empty($asset['location'])) : ?>
                                &bull; <?= esc($asset['location']) ?>
                            <?php endif; ?>
                        </p>
                        <p class="text-[11px] <?= $tone['desc'] ?> mt-0.5"><?= esc($note['desc']) ?></p>
                    </div>
                    <i class="bi bi-chevron-right text-gray-300 group-hover:text-gray-500 mt-1 text-xs transition-colors"></i>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Tombol Ikon Notifikasi -->
    <button type="button"
            @click="openNotes = !openNotes"
            :aria-expanded="openNotes.toString()"
            class="relative w-14 h-14 rounded-full bg-white border border-gray-200 text-gray-700 shadow-xl shadow-gray-900/20 flex items-center justify-center hover:bg-gray-50 hover:scale-105 active:scale-95 transition-all duration-150"
            aria-label="Buka notifikasi"
            title="Notifikasi">
        <i class="bi text-xl" :class="openNotes ? 'bi-x-lg' : 'bi-bell'"></i>
        <?php if ($notificationTotal > 0) : ?>
            <span class="absolute -top-1 -right-1 min-w-[20px] h-5 px-1 rounded-full bg-rose-500 text-white text-[10px] font-bold flex items-center justify-center border-2 border-white">
                <?= $notificationTotal > 99 ? '99+' : $notificationTotal ?>
            </span>
        <?php endif; ?>
    </button>
</div>
<?php endif; ?>

<!-- Header Section -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Dashboard Utama</h2>
        <p class="text-sm text-gray-500">Ringkasan statistik data aset, kategori, dan komponen.</p>
    </div>
    <a href="<?= base_url('asset/create') ?>" class="btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Aset
    </a>
</div>

<!-- ==================== 1. STATS CARDS ==================== -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <!-- Total Aset -->
    <div class="group bg-white rounded-2xl p-5 border border-gray-200/80 shadow-sm hover:shadow-md transition-all duration-200 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-blue-50 rounded-bl-full -z-0"></div>
        <div class="relative flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center text-xl shadow-md shadow-blue-600/20 shrink-0">
                <i class="bi bi-box-seam"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Total Aset</p>
                <h3 class="text-2xl font-bold text-gray-900"><?= $totalAssets ?? 0 ?></h3>
                <span class="text-[11px] text-emerald-600 font-medium inline-flex items-center gap-0.5">
                    <i class="bi bi-check-circle"></i> Terdata di sistem
                </span>
            </div>
        </div>
    </div>

    <!-- Total Kategori -->
    <div class="group bg-white rounded-2xl p-5 border border-gray-200/80 shadow-sm hover:shadow-md transition-all duration-200 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-purple-50 rounded-bl-full -z-0"></div>
        <div class="relative flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-fuchsia-600 text-white flex items-center justify-center text-xl shadow-md shadow-purple-600/20 shrink-0">
                <i class="bi bi-tags"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Total Kategori</p>
                <h3 class="text-2xl font-bold text-gray-900"><?= $totalCategories ?? 0 ?></h3>
                <span class="text-[11px] text-gray-400 font-medium">Kategori aktif</span>
            </div>
        </div>
    </div>

    <!-- Total Komponen Spesifikasi -->
    <div class="group bg-white rounded-2xl p-5 border border-gray-200/80 shadow-sm hover:shadow-md transition-all duration-200 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-50 rounded-bl-full -z-0"></div>
        <div class="relative flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center text-xl shadow-md shadow-emerald-600/20 shrink-0">
                <i class="bi bi-cpu"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Jenis Komponen</p>
                <h3 class="text-2xl font-bold text-gray-900"><?= $totalComponents ?? 0 ?></h3>
                <span class="text-[11px] text-gray-400 font-medium">Atribut spesifikasi</span>
            </div>
        </div>
    </div>

    <!-- Aset Non-Aktif -->
    <div class="group bg-white rounded-2xl p-5 border border-gray-200/80 shadow-sm hover:shadow-md transition-all duration-200 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-rose-50 rounded-bl-full -z-0"></div>
        <div class="relative flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-500 to-red-600 text-white flex items-center justify-center text-xl shadow-md shadow-rose-600/20 shrink-0">
                <i class="bi bi-exclamation-octagon"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Aset Non-Aktif</p>
                <h3 class="text-2xl font-bold text-gray-900"><?= $totalMaintenance ?? 0 ?></h3>
                <span class="text-[11px] text-rose-600 font-medium">Perbaikan / Rusak</span>
            </div>
        </div>
    </div>
</div>

<!-- ==================== 2. GRID UTAMA ==================== -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    
    <!-- LEFT COLUMN: Tabel Aset Terbaru (8 Kolom) -->
    <div class="lg:col-span-8 space-y-6">
        
        <!-- Tabel Aset Terbaru -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 text-xs"><i class="bi bi-clock-history"></i></span>
                    Aset Terbaru Ditambahkan
                </h3>
                <a href="<?= base_url('asset') ?>" class="text-xs text-blue-600 hover:text-blue-800 font-medium inline-flex items-center gap-1">Lihat Semua Aset <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50/80 text-gray-500 uppercase text-[11px] tracking-wider border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3">Spesifikasi</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (!empty($recentAssets)) : ?>
                            <?php foreach ($recentAssets as $ast) : ?>
                                <tr class="hover:bg-blue-50/40 transition-colors">
                                    <td class="px-4 py-3 text-xs text-gray-600">
                                        <?php
                                        $dSpecs = $ast['specifications'] ?? [];
                                        if (is_string($dSpecs)) {
                                            $dSpecs = json_decode($dSpecs, true) ?? [];
                                        }
                                        $texts = [];
                                        foreach ($dSpecs as $v) {
                                            if (is_string($v) && !preg_match('/\.(jpg|jpeg|png|webp|gif|svg|pdf|doc|docx)$/i', $v)) {
                                                $texts[] = $v;
                                            }
                                            if (count($texts) >= 2) {
                                                break;
                                            }
                                        }
                                        $snippet = !empty($texts) ? implode(' • ', $texts) : '-';
                                        ?>
                                        <span class="line-clamp-1"><?= esc($snippet) ?></span>
                                    </td>
                                    <td class="px-4 py-3 text-xs">
                                        <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded-full border border-indigo-100"><?= esc($ast['nama_kategori'] ?? '-') ?></span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?php
                                        $st = $ast['status'] ?? 'Aktif';
                                        $badge = match ($st) {
                                            'Aktif'     => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'Perbaikan' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'Rusak'     => 'bg-rose-50 text-rose-700 border-rose-200',
                                            default     => 'bg-gray-50 text-gray-700 border-gray-200',
                                        };
                                        ?>
                                        <span class="px-2.5 py-0.5 text-[11px] font-semibold rounded-full border <?= $badge ?>"><?= esc($st) ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="3" class="px-4 py-10 text-center text-gray-400 text-xs">Belum ada data aset.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Aset Perlu Perhatian -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200">
                <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-rose-50 flex items-center justify-center text-rose-600 text-xs"><i class="bi bi-exclamation-triangle"></i></span>
                    Aset Perlu Perhatian
                </h3>
            </div>
            <div class="p-5 space-y-2">
                <?php if (!empty($attentionAssets)) : ?>
                    <?php foreach ($attentionAssets as $a) : ?>
                        <a href="<?= base_url('asset/' . $a['id'] . '/maintenances') ?>" class="block text-xs text-gray-700 hover:text-blue-600">
                            <span class="font-medium"><?= esc($a['nama_kategori'] ?? '-') ?></span>
                            <span class="text-gray-400">&bull; <?= esc($a['status']) ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="text-xs text-gray-400 text-center py-2">Tidak ada aset yang perlu perhatian.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Aktivitas Terbaru -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200">
                <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600 text-xs"><i class="bi bi-activity"></i></span>
                    Aktivitas Terbaru
                </h3>
            </div>
            <div class="p-5 space-y-3">
                <?php if (!empty($recentActivities)) : ?>
                    <?php foreach ($recentActivities as $act) : ?>
                        <div class="flex items-start gap-3 text-xs">
                            <span class="w-2 h-2 mt-1.5 rounded-full bg-blue-500 shrink-0"></span>
                            <div>
                                <p class="text-gray-700 font-medium"><?= esc($act['action']) ?> <?= esc($act['entity']) ?><?= !empty($act['entity_id']) ? ' #' . esc($act['entity_id']) : '' ?></p>
                                <p class="text-gray-400"><?= esc($act['nama_user'] ?? 'Sistem') ?> &bull; <?= esc($act['created_at'] ?? '') ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="text-xs text-gray-400 text-center py-4">Belum ada aktivitas tercatat.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Section Keranjang / Draf Peminjaman Aset (Cart) -->
        <!-- Fitur cart belum diimplementasikan; blok statis dihapus agar tidak menampilkan tombol/aksi yang mati. -->
    </div>

    <!-- RIGHT COLUMN: Distribusi Kategori & Komponen System Info (4 Kolom) -->
    <div class="lg:col-span-4 space-y-6">
        
                <!-- Distribusi Status Aset -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-5">
            <h3 class="font-bold text-gray-900 text-sm mb-4 flex items-center gap-2 border-b border-gray-100 pb-2">
                <span class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 text-xs"><i class="bi bi-bar-chart"></i></span>
                Distribusi Status
            </h3>
            <div class="space-y-3.5">
                <?php
                $statusColors = [
                    'Aktif'     => 'from-emerald-500 to-teal-500',
                    'Perbaikan' => 'from-amber-500 to-orange-500',
                    'Rusak'     => 'from-rose-500 to-red-500',
                    'Disimpan'  => 'from-gray-400 to-gray-500',
                ];
                foreach (($statusStats ?? []) as $st => $cnt) :
                    $pct = ($totalAssets > 0) ? round(($cnt / $totalAssets) * 100) : 0;
                ?>
                    <div>
                        <div class="flex justify-between text-xs font-medium text-gray-700 mb-1">
                            <span><?= esc($st) ?></span>
                            <span class="text-gray-500 font-semibold"><?= $cnt ?> (<?= $pct ?>%)</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-gradient-to-r <?= $statusColors[$st] ?? 'from-blue-500 to-indigo-500' ?> h-2 rounded-full transition-all duration-300" style="width: <?= $pct ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Distribusi Aset per Kategori -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-5">
            <h3 class="font-bold text-gray-900 text-sm mb-4 flex items-center gap-2 border-b border-gray-100 pb-2">
                <span class="w-7 h-7 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600 text-xs"><i class="bi bi-pie-chart"></i></span>
                Distribusi Kategori
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
                                <div class="bg-gradient-to-r from-purple-500 to-fuchsia-500 h-2 rounded-full transition-all duration-300" style="width: <?= $percentage ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="text-xs text-gray-400 text-center py-4">Belum ada statistik kategori.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Summary Komponen / System Status -->
        <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl shadow-lg shadow-gray-900/10 p-5 text-white">
            <h3 class="font-bold text-sm mb-2 flex items-center gap-2 text-gray-100">
                <span class="w-7 h-7 rounded-lg bg-emerald-500/20 flex items-center justify-center text-emerald-400 text-xs"><i class="bi bi-cpu"></i></span>
                Ringkasan Komponen Aset
            </h3>
            <p class="text-xs text-gray-400 mb-4 leading-relaxed">
                Sistem menyimpan spesifikasi dinamis berbasis JSON seperti Processor, RAM, GPU, Serial Number, dan File Garansi/Gambar.
            </p>

            <div class="pt-3 border-t border-gray-700/70 flex justify-between items-center text-xs">
                <span class="text-gray-400">Status Sistem Aset:</span>
                <span class="px-2.5 py-1 bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 rounded-full font-semibold text-[11px] inline-flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Online / Normal
                </span>
            </div>
        </div>

    </div>

</div>

<?= $this->endSection() ?>