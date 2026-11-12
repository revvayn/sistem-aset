<?php

/**
 * @var array $assets
 * @var object $pager
 * @var int $currentPage
 * @var int $perPage
 * @var string|null $keyword
 * @var string|null $category
 * @var array $categories
 */

// Ambil role dari session
$role = session()->get('role');
?>

<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Header Section & Tombol Tambah -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div class="flex items-center gap-3">
        <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-blue-500/10 to-indigo-500/10 border border-blue-500/20 flex items-center justify-center text-blue-600 text-lg shrink-0">
            <i class="bi bi-box-seam"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Daftar Aset</h2>
            <p class="text-xs text-gray-500 mt-0.5">Kelola seluruh data unit aset fisik terdaftar</p>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-2">
        <a href="<?= base_url('asset/export') ?>" class="btn-secondary">
            <i class="bi bi-download"></i> Ekspor CSV
        </a>
        <?php if (strtolower($role ?? '') === 'admin') : ?>
            <a href="<?= base_url('asset/trashed') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl transition-all duration-150 active:scale-[0.98]">
                <i class="bi bi-trash"></i> Aset Terhapus
            </a>
        <?php endif; ?>
        <?php if (in_array(strtolower($role ?? ''), ['admin', 'staff'])) : ?>
            <a href="<?= base_url('asset/create') ?>" class="btn-primary">
                <i class="bi bi-plus-lg"></i> Tambah Aset Baru
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- ==================== FORM FILTER & SEARCH ==================== -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-5 mb-6">
    <form id="filterForm" action="<?= base_url('asset') ?>" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">
        <div class="md:col-span-4">
            <div class="relative flex items-center">
                <span class="absolute left-3.5 text-gray-400">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" id="keywordInput" name="keyword" class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white outline-none transition-all" placeholder="Cari di spesifikasi aset..." value="<?= esc($keyword ?? '') ?>" autocomplete="off">
            </div>
        </div>

        <div class="md:col-span-3">
            <select id="categorySelect" name="category" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white outline-none transition-all cursor-pointer">
                <option value="">-- Semua Kategori --</option>
                <?php if (!empty($categories)) : ?>
                    <?php foreach ($categories as $cat) : ?>
                        <option value="<?= $cat['nama_kategori'] ?>" <?= (($category ?? '') == $cat['nama_kategori']) ? 'selected' : '' ?>>
                            <?= esc($cat['nama_kategori']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="md:col-span-3">
            <select name="status" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white outline-none transition-all cursor-pointer">
                <option value="">-- Semua Status --</option>
                <?php foreach (['Aktif','Perbaikan','Rusak','Disimpan'] as $st) : ?>
                    <option value="<?= $st ?>" <?= (($status ?? '') === $st) ? 'selected' : '' ?>><?= $st ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="md:col-span-2 flex items-center justify-end gap-2">
            <?php if (!empty($keyword) || !empty($category) || !empty($status)) : ?>
                <a href="<?= base_url('asset') ?>" class="py-2.5 px-3 border border-rose-300 text-rose-600 hover:bg-rose-50 text-sm rounded-xl transition-colors duration-150 flex items-center justify-center gap-1.5" title="Reset Filter">
                    <i class="bi bi-x-circle text-lg"></i>
                </a>
            <?php endif; ?>
            <button type="submit" class="btn-primary">
                <i class="bi bi-funnel"></i> Filter
            </button>
        </div>
    </form>
</div>

<!-- ==================== TABEL DAFTAR ASET ==================== -->
<?php if (in_array(strtolower($role ?? ''), ['admin', 'staff'])) : ?>
<form action="<?= base_url('asset/bulk') ?>" method="POST" id="bulkForm">
    <?= csrf_field() ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-4 mb-3 flex flex-wrap items-center gap-3">
        <select name="bulk_action" id="bulkAction" class="px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm">
            <option value="">-- Aksi Massal --</option>
            <option value="status">Ubah Status</option>
            <?php if (strtolower($role ?? '') === 'admin') : ?>
                <option value="delete">Hapus Terpilih</option>
            <?php endif; ?>
        </select>
        <select name="bulk_status" id="bulkStatus" class="px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm">
            <option value="">-- Status --</option>
            <?php foreach (['Aktif','Perbaikan','Rusak','Disimpan'] as $st) : ?>
                <option value="<?= $st ?>"><?= $st ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl">Terapkan</button>
    </div>
</form>
<?php endif; ?>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm min-w-[980px]">
            <thead class="bg-gray-50/80 border-b border-gray-200 sticky top-0 z-10">
                <tr>
                    <?php if (in_array(strtolower($role ?? ''), ['admin', 'staff'])) : ?>
                        <th scope="col" class="px-5 py-3.5 text-center w-10 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300">
                        </th>
                    <?php endif; ?>
                    <th scope="col" class="px-5 py-3.5 text-center w-12 text-[11px] font-bold uppercase tracking-wider text-gray-500">No</th>
                    <th scope="col" class="px-5 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">Aset (Merk/Model)</th>
                    <th scope="col" class="px-5 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">No. Seri</th>
                    <th scope="col" class="px-5 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">Lokasi</th>
                    <th scope="col" class="px-5 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">Status</th>
                    <th scope="col" class="px-5 py-3.5 text-center w-48 text-[11px] font-bold uppercase tracking-wider text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (!empty($assets)) : ?>
                    <?php $i = 1 + (($perPage ?? 10) * (($currentPage ?? 1) - 1)); ?>
                    <?php foreach ($assets as $ast) : ?>
                        <tr class="hover:bg-blue-50/40 transition-colors duration-150">
                            <?php if (in_array(strtolower($role ?? ''), ['admin', 'staff'])) : ?>
                                <td class="px-5 py-4 text-center">
                                    <input type="checkbox" name="ids[]" value="<?= $ast['id'] ?>" form="bulkForm" class="row-check rounded border-gray-300">
                                </td>
                            <?php endif; ?>
                            <td class="px-5 py-4 text-center font-medium text-gray-400"><?= $i++ ?></td>
                            <?php
                            $rowSpecs = $ast['specifications'] ?? [];
                            if (is_string($rowSpecs)) {
                                $rowSpecs = json_decode($rowSpecs, true) ?? [];
                            }
                            $merkModel = trim(($rowSpecs['merk'] ?? '') . ' ' . ($rowSpecs['model'] ?? ''));
                            if ($merkModel === '') {
                                $merkModel = '-';
                            }
                            ?>
                            <td class="px-5 py-4">
                                <div class="text-sm font-semibold text-gray-900"><?= esc($merkModel) ?></div>
                                <div class="text-[11px] text-gray-400">ID #<?= esc($ast['id']) ?></div>
                            </td>
                            <td class="px-5 py-4 text-xs text-gray-700 font-mono max-w-[140px] truncate" title="<?= esc($ast['serial_number'] ?? '-') ?>"><?= esc($ast['serial_number'] ?? '-') ?></td>
                            <td class="px-5 py-4 text-xs text-gray-600 max-w-[120px] truncate" title="<?= esc($ast['location'] ?? '-') ?>"><?= esc($ast['location'] ?? '-') ?></td>
                            <td class="px-5 py-4">
                                <?php
                                $status = $ast['status'] ?? 'Aktif';
                                $badgeStyle = match ($status) {
                                    'Aktif'     => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'Perbaikan' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'Rusak'     => 'bg-rose-50 text-rose-700 border-rose-200',
                                    default     => 'bg-gray-50 text-gray-700 border-gray-200',
                                };
                                ?>
                                <span class="px-2.5 py-1 text-[11px] font-semibold rounded-full border <?= $badgeStyle ?> inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-60"></span><?= esc($status) ?>
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <?php
                                $specsData = $ast['specifications'] ?? [];
                                if (is_string($specsData)) {
                                    $specsData = json_decode($specsData, true) ?? [];
                                }
                                ?>

                                <div class="flex items-center justify-center gap-1 flex-wrap max-w-[200px] mx-auto">
                                    <a href="<?= base_url('asset/detail/' . $ast['id']) ?>"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 border border-cyan-200 bg-cyan-50 text-cyan-700 hover:bg-cyan-100 text-xs font-semibold rounded-lg transition-colors">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>

                                    <button type="button"
                                        class="btn-qr inline-flex items-center gap-1 px-3 py-1.5 border border-violet-200 bg-violet-50 text-violet-700 hover:bg-violet-100 text-xs font-semibold rounded-lg transition-colors"
                                        data-id="<?= esc($ast['id']) ?>"
                                        data-kode="<?= esc($ast['serial_number'] ?? ('AST-' . $ast['id'])) ?>"
                                        data-info='<?= htmlspecialchars(json_encode([
                                            'id' => $ast['id'],
                                            'kategori' => $ast['nama_kategori'] ?? '-',
                                            'status' => $ast['status'] ?? '-',
                                            'seri' => $ast['serial_number'] ?? '-',
                                            'lokasi' => $ast['location'] ?? '-',
                                            'departemen' => $ast['department'] ?? '-',
                                            'pembelian' => $ast['purchase_date'] ?? '-',
                                            'garansi' => $ast['warranty_expiry'] ?? '-',
                                        ]), ENT_QUOTES, 'UTF-8') ?>'
                                        data-url="<?= base_url('scan/' . $ast['id']) ?>">
                                        <i class="bi bi-qr-code"></i> QR
                                    </button>

                                    <?php if (in_array(strtolower($role ?? ''), ['admin', 'staff'])) : ?>
                                        <a href="<?= base_url('asset/edit/' . $ast['id']) ?>" class="w-8 h-8 inline-flex items-center justify-center border border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100 rounded-lg transition-colors" title="Edit">
                                            <i class="bi bi-pencil text-sm"></i>
                                        </a>
                                        <a href="<?= base_url('asset/' . $ast['id'] . '/maintenances') ?>" class="w-8 h-8 inline-flex items-center justify-center border border-indigo-200 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-lg transition-colors" title="Pemeliharaan">
                                            <i class="bi bi-tools text-sm"></i>
                                        </a>
                                        <a href="<?= base_url('asset/' . $ast['id'] . '/locations') ?>" class="w-8 h-8 inline-flex items-center justify-center border border-teal-200 bg-teal-50 text-teal-700 hover:bg-teal-100 rounded-lg transition-colors" title="Lokasi">
                                            <i class="bi bi-geo-alt text-sm"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if (strtolower($role ?? '') === 'admin') : ?>
                                        <form action="<?= base_url('asset/delete/' . $ast['id']) ?>" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aset ini?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="w-8 h-8 inline-flex items-center justify-center border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 rounded-lg transition-colors" title="Hapus">
                                                <i class="bi bi-trash text-sm"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="<?= in_array(strtolower($role ?? ''), ['admin', 'staff']) ? 7 : 6 ?>" class="px-5 py-14 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gray-50 border border-gray-200 mb-3 text-gray-300">
                                <i class="bi bi-inbox text-3xl"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-500">Belum ada data aset yang ditemukan</p>
                            <p class="text-xs text-gray-400 mt-1">Mulai tambahkan aset baru untuk mengisi data.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (!empty($assets) && isset($pager)) : ?>
        <div class="px-5 py-4 bg-white border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-3">
            <div class="text-xs text-gray-500">
                Menampilkan halaman <strong class="text-gray-800"><?= $currentPage ?? 1 ?></strong>
            </div>
            <?php
            $pagerGroup = 'asset';
            $currentPage = $pager->getCurrentPage($pagerGroup);
            $totalPages  = $pager->getPageCount($pagerGroup);
            ?>
            <div class="flex items-center justify-center gap-1 flex-wrap">
                <?php if ($currentPage > 1) : ?>
                    <a href="<?= $pager->getPageURI(1, $pagerGroup) ?>" class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 transition-colors" title="Halaman pertama">
                        <i class="bi bi-chevron-double-left"></i>
                    </a>
                    <a href="<?= $pager->getPageURI($currentPage - 1, $pagerGroup) ?>" class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 transition-colors">
                        <i class="bi bi-chevron-left"></i> Prev
                    </a>
                <?php endif; ?>

                <?php for ($p = 1; $p <= $totalPages; $p++) : ?>
                    <a href="<?= $pager->getPageURI($p, $pagerGroup) ?>"
                       class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors <?= ($p === $currentPage) ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-100' ?>">
                        <?= $p ?>
                    </a>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages) : ?>
                    <a href="<?= $pager->getPageURI($currentPage + 1, $pagerGroup) ?>" class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 transition-colors">
                        Next <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="<?= $pager->getPageURI($totalPages, $pagerGroup) ?>" class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 transition-colors" title="Halaman terakhir">
                        <i class="bi bi-chevron-double-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Detail Aset -->
<div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/60 backdrop-blur-sm items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden transform transition-all">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-cyan-600 to-blue-600 px-6 py-4 flex justify-between items-center text-white">
            <h3 class="text-base font-semibold flex items-center gap-2">
                <i class="bi bi-info-circle"></i> Detail Informasi Aset
            </h3>
            <button type="button" class="btn-close-modal text-white/80 hover:text-white text-xl font-bold leading-none">&times;</button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-5">
            <div class="rounded-xl border border-gray-200 overflow-hidden">
                <table class="w-full text-sm">
                    <tbody id="modalInfoContainer" class="divide-y divide-gray-100">
                        <tr>
                            <th class="w-1/3 bg-gray-50 px-4 py-3 text-left font-semibold text-gray-600 border-r border-gray-100">Kategori</th>
                            <td id="modalKategori" class="px-4 py-3 text-gray-800"></td>
                        </tr>
                        <tr>
                            <th class="bg-gray-50 px-4 py-3 text-left font-semibold text-gray-600 border-r border-gray-100">Status</th>
                            <td id="modalStatus" class="px-4 py-3"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h4 class="text-sm font-semibold text-blue-600 flex items-center gap-2 pt-2 border-t border-gray-100">
                <i class="bi bi-cpu"></i> Komponen & Spesifikasi Detail
            </h4>

            <div class="border border-gray-200 rounded-xl overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-4 py-3 w-2/5">Atribut / Komponen</th>
                            <th scope="col" class="px-4 py-3">Nilai / Spesifikasi</th>
                        </tr>
                    </thead>
                    <tbody id="modalSpecsContainer" class="divide-y divide-gray-100">
                        <!-- Komponen diisi via JS -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-3 flex justify-end border-t border-gray-200">
            <button type="button" class="btn-close-modal px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-xl transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal QR Code -->
<div id="qrModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/60 backdrop-blur-sm items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden transform transition-all">
        <div class="bg-gradient-to-r from-violet-600 to-indigo-600 px-6 py-4 flex justify-between items-center text-white">
            <h3 class="text-base font-semibold flex items-center gap-2"><i class="bi bi-qr-code"></i> QR Code Aset</h3>
            <button type="button" class="btn-close-qr text-white/80 hover:text-white text-xl font-bold leading-none">&times;</button>
        </div>
        <div class="p-6 text-center">
            <a id="qrLink" href="#" target="_blank" class="inline-block">
                <div id="qrcode" class="inline-block"></div>
            </a>
            <p id="qrLabel" class="text-xs text-gray-500 mt-3 font-mono"></p>
            <a id="qrOpenBtn" href="#" target="_blank" class="mt-3 inline-flex items-center gap-2 px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white text-xs font-semibold rounded-xl">
                <i class="bi bi-box-arrow-up-right"></i> Buka Halaman Info
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filterForm');
        const keywordInput = document.getElementById('keywordInput');
        const categorySelect = document.getElementById('categorySelect');

        categorySelect.addEventListener('change', function() {
            filterForm.submit();
        });

        const statusSelect = filterForm.querySelector('select[name="status"]');
        if (statusSelect) {
            statusSelect.addEventListener('change', function() {
                filterForm.submit();
            });
        }

        const selectAll = document.getElementById('selectAll');
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                document.querySelectorAll('.row-check').forEach(function(cb) {
                    cb.checked = selectAll.checked;
                });
            });
        }

        const bulkForm = document.getElementById('bulkForm');
        if (bulkForm) {
            bulkForm.addEventListener('submit', function(e) {
                const action = document.getElementById('bulkAction') ? document.getElementById('bulkAction').value : '';
                if (action === 'delete') {
                    if (!confirm('Hapus semua aset terpilih?')) {
                        e.preventDefault();
                    }
                }
            });
        }

        let debounceTimer;
        keywordInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                filterForm.submit();
            }, 500);
        });

        if (keywordInput.value.length > 0) {
            keywordInput.focus();
            const val = keywordInput.value;
            keywordInput.value = '';
            keywordInput.value = val;
        }

        const detailModal = document.getElementById('detailModal');
        const detailButtons = document.querySelectorAll('.btn-detail');
        const closeButtons = document.querySelectorAll('.btn-close-modal');

        detailButtons.forEach(button => {
            button.addEventListener('click', function() {
                const kategori = this.getAttribute('data-kategori');
                const status = this.getAttribute('data-status');
                const rawSpecs = this.getAttribute('data-specs');
                const rawStandard = this.getAttribute('data-standard');

                document.getElementById('modalKategori').textContent = kategori;
                document.getElementById('modalStatus').textContent = status;

                const infoContainer = document.getElementById('modalInfoContainer');
                // hapus baris standar lama (selain kategori & status)
                while (infoContainer.rows.length > 2) {
                    infoContainer.deleteRow(2);
                }

                try {
                    let std = typeof rawStandard === 'string' ? JSON.parse(rawStandard) : rawStandard;
                    const labels = {
                        serial_number: 'Nomor Seri',
                        location: 'Lokasi',
                        department: 'Departemen',
                        purchase_date: 'Tgl Pembelian',
                        price: 'Harga',
                        warranty_expiry: 'Garansi Hingga'
                    };
                    Object.keys(labels).forEach(function(key) {
                        const val = std ? std[key] : '';
                        if (val !== '' && val !== null) {
                            const tr = document.createElement('tr');
                            const th = document.createElement('th');
                            th.className = 'bg-gray-50 px-4 py-3 text-left font-semibold text-gray-600 border-r border-gray-100';
                            th.textContent = labels[key];
                            const td = document.createElement('td');
                            td.className = 'px-4 py-3 text-gray-800';
                            td.textContent = val;
                            tr.appendChild(th);
                            tr.appendChild(td);
                            infoContainer.appendChild(tr);
                        }
                    });

                    // Depresiasi sederhana (garis lurus 5 tahun)
                    if (std && std.price && std.purchase_date) {
                        const price = parseFloat(std.price);
                        const start = new Date(std.purchase_date);
                        const now = new Date();
                        const years = (now - start) / (1000 * 60 * 60 * 24 * 365.25);
                        const life = 5;
                        const ratio = Math.min(Math.max(years / life, 0), 1);
                        const value = price * (1 - ratio);
                        const tr = document.createElement('tr');
                        const th = document.createElement('th');
                        th.className = 'bg-gray-50 px-4 py-3 text-left font-semibold text-gray-600 border-r border-gray-100';
                        th.textContent = 'Nilai Buku (est.)';
                        const td = document.createElement('td');
                        td.className = 'px-4 py-3 text-gray-800';
                        td.textContent = 'Rp ' + value.toLocaleString('id-ID', {maximumFractionDigits: 2});
                        tr.appendChild(th);
                        tr.appendChild(td);
                        infoContainer.appendChild(tr);
                    }
                } catch (e) { console.error(e); }

                const specsContainer = document.getElementById('modalSpecsContainer');
                specsContainer.innerHTML = '';

                try {
                    let specs = typeof rawSpecs === 'string' ? JSON.parse(rawSpecs) : rawSpecs;

                    if (typeof specs === 'string') {
                        specs = JSON.parse(specs);
                    }

                    const keys = Object.keys(specs || {});

                    if (!specs || keys.length === 0) {
                        specsContainer.innerHTML = '<tr><td colspan="2" class="px-4 py-3 text-center text-gray-400 italic">Tidak ada komponen / spesifikasi tambahan.</td></tr>';
                    } else {
                        keys.forEach(key => {
                            const val = specs[key] ? specs[key] : '-';
                            const formattedKey = key.replace(/_/g, ' ').toUpperCase();

                            let displayValue = val;

                            if (typeof val === 'string' && /^\$2[aby]\$/.test(val)) {
                                displayValue = '•••••• (terenkripsi)';
                            } else if (typeof val === 'string' && val !== '-') {
                                const isImage = /\.(jpg|jpeg|png|webp|gif|svg)$/i.test(val);
                                const isPdf = /\.pdf$/i.test(val);
                                const isDoc = /\.(doc|docx)$/i.test(val);

                                const fileUrl = `<?= base_url('uploads/specs/') ?>/${val}`;

                                if (isImage) {
                                    displayValue = `
                                        <div class="my-1">
                                            <a href="${fileUrl}" target="_blank" class="inline-block">
                                                <img src="${fileUrl}" alt="${formattedKey}" class="h-28 w-auto rounded-lg border border-gray-300 shadow-sm object-cover hover:opacity-90">
                                            </a>
                                            <small class="block text-gray-400 text-[11px] mt-1"><i class="bi bi-box-arrow-up-right"></i> Klik untuk memperbesar</small>
                                        </div>
                                    `;
                                } else if (isPdf) {
                                    displayValue = `
                                        <a href="${fileUrl}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-100 text-xs font-medium rounded-lg transition-colors my-1">
                                            <i class="bi bi-file-earmark-pdf text-sm"></i> Buka Dokumen PDF
                                        </a>
                                    `;
                                } else if (isDoc) {
                                    displayValue = `
                                        <a href="${fileUrl}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 border border-blue-200 text-blue-600 hover:bg-blue-100 text-xs font-medium rounded-lg transition-colors my-1">
                                            <i class="bi bi-file-earmark-word text-sm"></i> Unduh Dokumen Word
                                        </a>
                                    `;
                                }
                            }

                            const row = `
                                <tr>
                                    <td class="px-4 py-3 font-medium text-gray-700 bg-gray-50/50">${formattedKey}</td>
                                    <td class="px-4 py-3 text-gray-800">${displayValue}</td>
                                </tr>
                            `;
                            specsContainer.innerHTML += row;
                        });
                    }
                } catch (e) {
                    console.error("Error parsing specs:", e);
                    specsContainer.innerHTML = '<tr><td colspan="2" class="px-4 py-3 text-center text-gray-400 italic">Gagal membaca data spesifikasi.</td></tr>';
                }

                detailModal.classList.remove('hidden');
                detailModal.classList.add('flex');
            });
        });

        closeButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                detailModal.classList.add('hidden');
                detailModal.classList.remove('flex');
            });
        });

        detailModal.addEventListener('click', function(e) {
            if (e.target === detailModal) {
                detailModal.classList.add('hidden');
                detailModal.classList.remove('flex');
            }
        });

        const qrModal = document.getElementById('qrModal');
        const qrButtons = document.querySelectorAll('.btn-qr');
        const closeQr = document.querySelectorAll('.btn-close-qr');

        qrButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const kode = this.getAttribute('data-kode');
                const rawInfo = this.getAttribute('data-info');
                const rawSpecs = this.getAttribute('data-specs');
                const url = this.getAttribute('data-url');
                const container = document.getElementById('qrcode');
                container.innerHTML = '';

                const link = document.getElementById('qrLink');
                const openBtn = document.getElementById('qrOpenBtn');
                if (link) link.href = url || '#';
                if (openBtn) openBtn.href = url || '#';

                let info = {};
                try { info = JSON.parse(rawInfo); } catch (e) { info = {}; }

                // QR berisi info aset langsung (untuk penggunaan lokal/offline)
                const lines = [];
                lines.push('SISTEM MANAJEMEN ASET');
                if (info.id) lines.push('ID Aset: ' + info.id);
                lines.push('Kategori: ' + (info.kategori || '-'));
                lines.push('Status: ' + (info.status || '-'));
                if (info.seri && info.seri !== '-') lines.push('No. Seri: ' + info.seri);
                if (info.lokasi && info.lokasi !== '-') lines.push('Lokasi: ' + info.lokasi);
                if (info.departemen && info.departemen !== '-') lines.push('Departemen: ' + info.departemen);
                if (info.pembelian && info.pembelian !== '-') lines.push('Tgl Pembelian: ' + info.pembelian);
                if (info.garansi && info.garansi !== '-') lines.push('Garansi Hingga: ' + info.garansi);

                try {
                    let specs = typeof rawSpecs === 'string' ? JSON.parse(rawSpecs) : rawSpecs;
                    if (typeof specs === 'string') specs = JSON.parse(specs);
                    if (specs && typeof specs === 'object') {
                        Object.keys(specs).forEach(function(key) {
                            const val = specs[key];
                            if (typeof val === 'string' && val !== '' && !/\.(jpg|jpeg|png|webp|gif|svg|pdf|doc|docx)$/i.test(val)) {
                                lines.push(key.replace(/_/g, ' ').toUpperCase() + ': ' + val);
                            } else if (typeof val === 'number') {
                                lines.push(key.replace(/_/g, ' ').toUpperCase() + ': ' + val);
                            }
                        });
                    }
                } catch (e) { console.error(e); }

                const qrText = lines.join('\n');

                document.getElementById('qrLabel').textContent = kode;
                if (typeof QRCode !== 'undefined') {
                    new QRCode(container, { text: qrText, width: 200, height: 200 });
                } else {
                    container.textContent = 'Library QR tidak tersedia.';
                }
                qrModal.classList.remove('hidden');
                qrModal.classList.add('flex');
            });
        });

        closeQr.forEach(function(btn) {
            btn.addEventListener('click', function() {
                qrModal.classList.add('hidden');
                qrModal.classList.remove('flex');
            });
        });

        if (qrModal) {
            qrModal.addEventListener('click', function(e) {
                if (e.target === qrModal) {
                    qrModal.classList.add('hidden');
                    qrModal.classList.remove('flex');
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>