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

    <?php if (in_array(strtolower($role ?? ''), ['admin', 'staff'])) : ?>
        <a href="<?= base_url('asset/create') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold text-sm rounded-xl shadow-md shadow-blue-600/20 transition-all duration-150 active:scale-[0.98]">
            <i class="bi bi-plus-lg"></i> Tambah Aset Baru
        </a>
    <?php endif; ?>
</div>

<!-- ==================== FORM FILTER & SEARCH ==================== -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-5 mb-6">
    <form id="filterForm" action="<?= base_url('asset') ?>" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">
        <div class="md:col-span-6">
            <div class="relative flex items-center">
                <span class="absolute left-3.5 text-gray-400">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" id="keywordInput" name="keyword" class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white outline-none transition-all" placeholder="Cari di spesifikasi aset..." value="<?= esc($keyword ?? '') ?>" autocomplete="off">
            </div>
        </div>

        <div class="md:col-span-5">
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

        <div class="md:col-span-1 flex items-center justify-end">
            <?php if (!empty($keyword) || !empty($category)) : ?>
                <a href="<?= base_url('asset') ?>" class="w-full py-2.5 border border-rose-300 text-rose-600 hover:bg-rose-50 text-sm rounded-xl transition-colors duration-150 flex items-center justify-center gap-1.5" title="Reset Filter">
                    <i class="bi bi-x-circle text-lg"></i>
                    <span class="md:hidden text-xs font-medium">Reset</span>
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- ==================== TABEL DAFTAR ASET ==================== -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50/80 border-b border-gray-200">
                <tr>
                    <th scope="col" class="px-5 py-3.5 text-center w-12 text-[11px] font-bold uppercase tracking-wider text-gray-500">No</th>
                    <th scope="col" class="px-5 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">Kategori</th>
                    <th scope="col" class="px-5 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">Status</th>
                    <th scope="col" class="px-5 py-3.5 text-center w-48 text-[11px] font-bold uppercase tracking-wider text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (!empty($assets)) : ?>
                    <?php $i = 1 + (($perPage ?? 10) * (($currentPage ?? 1) - 1)); ?>
                    <?php foreach ($assets as $ast) : ?>
                        <tr class="hover:bg-blue-50/40 transition-colors duration-150">
                            <td class="px-5 py-4 text-center font-medium text-gray-400"><?= $i++ ?></td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 text-xs font-medium bg-indigo-50 text-indigo-700 rounded-full border border-indigo-100"><?= esc($ast['nama_kategori'] ?? '-') ?></span>
                            </td>
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

                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button"
                                        class="btn-detail inline-flex items-center gap-1 px-3 py-1.5 border border-cyan-200 bg-cyan-50 text-cyan-700 hover:bg-cyan-100 text-xs font-semibold rounded-lg transition-colors"
                                        data-kategori="<?= esc($ast['nama_kategori'] ?? '-') ?>"
                                        data-status="<?= esc($status) ?>"
                                        data-specs='<?= htmlspecialchars(json_encode($specsData), ENT_QUOTES, 'UTF-8') ?>'>
                                        <i class="bi bi-eye"></i> Detail
                                    </button>

                                    <?php if (in_array(strtolower($role ?? ''), ['admin', 'staff'])) : ?>
                                        <a href="<?= base_url('asset/edit/' . $ast['id']) ?>" class="w-8 h-8 inline-flex items-center justify-center border border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100 rounded-lg transition-colors" title="Edit">
                                            <i class="bi bi-pencil text-sm"></i>
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
                        <td colspan="4" class="px-5 py-14 text-center">
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
            <div>
                <?= $pager->links('asset', 'default_full') ?>
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
                    <tbody class="divide-y divide-gray-100">
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filterForm');
        const keywordInput = document.getElementById('keywordInput');
        const categorySelect = document.getElementById('categorySelect');

        categorySelect.addEventListener('change', function() {
            filterForm.submit();
        });

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

                document.getElementById('modalKategori').textContent = kategori;
                document.getElementById('modalStatus').textContent = status;

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

                            if (typeof val === 'string' && val !== '-') {
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
    });
</script>
<?= $this->endSection() ?>