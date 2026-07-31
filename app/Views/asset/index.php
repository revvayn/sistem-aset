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
    <h2 class="text-2xl font-bold text-gray-800">Daftar Aset</h2>

    <!-- Tombol Tambah Aset: HANYA untuk Admin & Staf -->
    <?php if (in_array(strtolower($role ?? ''), ['admin', 'staff'])) : ?>
        <a href="<?= base_url('asset/create') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors duration-150">
            <i class="bi bi-plus-circle"></i> Tambah Aset Baru
        </a>
    <?php endif; ?>
</div>

<!-- Flash Message Notifications -->
<?php if (session()->getFlashdata('message')) : ?>
    <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-2">
            <i class="bi bi-check-circle-fill text-green-600"></i>
            <span><?= session()->getFlashdata('message') ?></span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800 text-xl font-bold leading-none">&times;</button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill text-red-600"></i>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800 text-xl font-bold leading-none">&times;</button>
    </div>
<?php endif; ?>

<!-- ==================== FORM FILTER & SEARCH (AUTO SUBMIT) ==================== -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
    <form id="filterForm" action="<?= base_url('asset') ?>" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">
        <!-- Input Cari Keyword -->
        <div class="md:col-span-6">
            <div class="relative flex items-center">
                <span class="absolute left-3 text-gray-400">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" id="keywordInput" name="keyword" class="w-full pl-9 pr-4 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Cari No Asset / Nama Asset..." value="<?= esc($keyword ?? '') ?>" autocomplete="off">
            </div>
        </div>

        <!-- Dropdown Filter Kategori -->
        <div class="md:col-span-5">
            <select id="categorySelect" name="category" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all cursor-pointer">
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

        <!-- Tombol Reset Filter (Hanya tampil jika ada filter aktif) -->
        <div class="md:col-span-1 flex items-center justify-end">
            <?php if (!empty($keyword) || !empty($category)) : ?>
                <a href="<?= base_url('asset') ?>" class="w-full py-2 border border-red-300 text-red-600 hover:bg-red-50 text-sm rounded-lg transition-colors duration-150 flex items-center justify-center gap-1.5" title="Reset Filter">
                    <i class="bi bi-x-circle text-lg"></i>
                    <span class="md:hidden text-xs font-medium">Reset</span>
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- ==================== TABEL DAFTAR ASET ==================== -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-900 text-gray-200 uppercase text-xs tracking-wider">
                <tr>
                    <th scope="col" class="px-4 py-3.5 text-center w-12">No</th>
                    <th scope="col" class="px-4 py-3.5">No. Aset</th>
                    <th scope="col" class="px-4 py-3.5">Nama Aset</th>
                    <th scope="col" class="px-4 py-3.5">Kategori</th>
                    <th scope="col" class="px-4 py-3.5">Status</th>
                    <th scope="col" class="px-4 py-3.5 text-center w-48">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (!empty($assets)) : ?>
                    <?php $i = 1 + (($perPage ?? 10) * (($currentPage ?? 1) - 1)); ?>
                    <?php foreach ($assets as $ast) : ?>
                        <tr class="hover:bg-gray-50/80 transition-colors duration-150">
                            <td class="px-4 py-3.5 text-center font-medium text-gray-500"><?= $i++ ?></td>
                            <td class="px-4 py-3.5">
                                <code class="px-2 py-1 bg-gray-100 text-gray-800 rounded font-mono text-xs border border-gray-200"><?= esc($ast['no_asset'] ?? '-') ?></code>
                            </td>
                            <td class="px-4 py-3.5 font-semibold text-gray-900"><?= esc($ast['nama_aset'] ?? '-') ?></td>
                            <td class="px-4 py-3.5">
                                <span class="px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full border border-gray-200"><?= esc($ast['nama_kategori'] ?? '-') ?></span>
                            </td>
                            <td class="px-4 py-3.5">
                                <?php
                                $status = $ast['status'] ?? 'Aktif';
                                $badgeStyle = match ($status) {
                                    'Aktif'     => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'Perbaikan' => 'bg-amber-100 text-amber-800 border-amber-200',
                                    'Rusak'     => 'bg-rose-100 text-rose-800 border-rose-200',
                                    default     => 'bg-gray-100 text-gray-800 border-gray-200',
                                };
                                ?>
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full border <?= $badgeStyle ?>"><?= esc($status) ?></span>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <!-- Parsing Specifications -->
                                <?php
                                $specsData = $ast['specifications'] ?? [];
                                if (is_string($specsData)) {
                                    $specsData = json_decode($specsData, true) ?? [];
                                }
                                ?>

                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Tombol Detail -->
                                    <button type="button"
                                        class="btn-detail inline-flex items-center gap-1 px-2.5 py-1.5 border border-cyan-500 text-cyan-600 hover:bg-cyan-50 text-xs font-medium rounded-lg transition-colors"
                                        data-no="<?= esc($ast['no_asset'] ?? '-') ?>"
                                        data-nama="<?= esc($ast['nama_aset'] ?? '-') ?>"
                                        data-kategori="<?= esc($ast['nama_kategori'] ?? '-') ?>"
                                        data-status="<?= esc($status) ?>"
                                        data-specs='<?= htmlspecialchars(json_encode($specsData), ENT_QUOTES, 'UTF-8') ?>'>
                                        <i class="bi bi-eye"></i> Detail
                                    </button>

                                    <!-- Tombol Edit: Admin & Staff -->
                                    <?php if (in_array(strtolower($role ?? ''), ['admin', 'staff'])) : ?>
                                        <a href="<?= base_url('asset/edit/' . $ast['id']) ?>" class="p-1.5 border border-amber-500 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    <?php endif; ?>

                                    <!-- Tombol Hapus: Khusus Admin -->
                                    <?php if (strtolower($role ?? '') === 'admin') : ?>
                                        <a href="<?= base_url('asset/delete/' . $ast['id']) ?>" class="p-1.5 border border-rose-500 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" onclick="return confirm('Apakah Anda yakin ingin menghapus aset ini?')" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-gray-500">
                            <i class="bi bi-inbox text-4xl block mb-2 text-gray-400"></i>
                            Belum ada data aset yang ditemukan.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- PAGINATION FOOTER -->
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
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl overflow-hidden transform transition-all">
        <!-- Modal Header -->
        <div class="bg-cyan-600 px-6 py-4 flex justify-between items-center text-white">
            <h3 class="text-base font-semibold flex items-center gap-2">
                <i class="bi bi-info-circle"></i> Detail Informasi Aset
            </h3>
            <button type="button" class="btn-close-modal text-white/80 hover:text-white text-xl font-bold leading-none">&times;</button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-5">
            <table class="w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                <tbody class="divide-y divide-gray-200">
                    <tr>
                        <th class="w-1/3 bg-gray-50 px-4 py-2.5 text-left font-semibold text-gray-600 border-r border-gray-200">No. Aset</th>
                        <td class="px-4 py-2.5"><code id="modalNoAsset" class="font-mono text-cyan-700 bg-cyan-50 px-2 py-0.5 rounded text-sm border border-cyan-200"></code></td>
                    </tr>
                    <tr>
                        <th class="bg-gray-50 px-4 py-2.5 text-left font-semibold text-gray-600 border-r border-gray-200">Nama Aset</th>
                        <td id="modalNamaAsset" class="px-4 py-2.5 font-bold text-gray-900"></td>
                    </tr>
                    <tr>
                        <th class="bg-gray-50 px-4 py-2.5 text-left font-semibold text-gray-600 border-r border-gray-200">Kategori</th>
                        <td id="modalKategori" class="px-4 py-2.5 text-gray-800"></td>
                    </tr>
                    <tr>
                        <th class="bg-gray-50 px-4 py-2.5 text-left font-semibold text-gray-600 border-r border-gray-200">Status</th>
                        <td id="modalStatus" class="px-4 py-2.5"></td>
                    </tr>
                </tbody>
            </table>

            <h4 class="text-sm font-semibold text-blue-600 flex items-center gap-2 pt-2 border-t border-gray-100">
                <i class="bi bi-cpu"></i> Komponen & Spesifikasi Detail
            </h4>

            <div class="border border-gray-200 rounded-lg overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="bg-gray-50 text-gray-700 text-xs uppercase border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-4 py-2.5 w-2/5">Atribut / Komponen</th>
                            <th scope="col" class="px-4 py-2.5">Nilai / Spesifikasi</th>
                        </tr>
                    </thead>
                    <tbody id="modalSpecsContainer" class="divide-y divide-gray-200">
                        <!-- Komponen diisi via JS -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-3 flex justify-end border-t border-gray-200">
            <button type="button" class="btn-close-modal px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ================= AUTO SUBMIT FILTER & SEARCH =================
        const filterForm = document.getElementById('filterForm');
        const keywordInput = document.getElementById('keywordInput');
        const categorySelect = document.getElementById('categorySelect');

        // Automatic submit saat Kategori diubah
        categorySelect.addEventListener('change', function() {
            filterForm.submit();
        });

        // Automatic submit saat mengetik Keyword (menggunakan Debounce 500ms agar tidak me-refresh setiap 1 huruf)
        let debounceTimer;
        keywordInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                filterForm.submit();
            }, 500); // Menunggu 0.5 detik setelah pengguna selesai mengetik
        });

        // Menempatkan kursor di akhir teks input keyword setelah halaman ter-refresh
        if (keywordInput.value.length > 0) {
            keywordInput.focus();
            const val = keywordInput.value;
            keywordInput.value = '';
            keywordInput.value = val;
        }

        // ================= MODAL DETAIL LOGIC =================
        const detailModal = document.getElementById('detailModal');
        const detailButtons = document.querySelectorAll('.btn-detail');
        const closeButtons = document.querySelectorAll('.btn-close-modal');

        // Toggle Open Modal
        detailButtons.forEach(button => {
            button.addEventListener('click', function() {
                const noAsset = this.getAttribute('data-no');
                const namaAsset = this.getAttribute('data-nama');
                const kategori = this.getAttribute('data-kategori');
                const status = this.getAttribute('data-status');
                const rawSpecs = this.getAttribute('data-specs');

                document.getElementById('modalNoAsset').textContent = noAsset;
                document.getElementById('modalNamaAsset').textContent = namaAsset;
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
                                                <img src="${fileUrl}" alt="${formattedKey}" class="h-28 w-auto rounded border border-gray-300 shadow-sm object-cover hover:opacity-90">
                                            </a>
                                            <small class="block text-gray-400 text-[11px] mt-1"><i class="bi bi-box-arrow-up-right"></i> Klik untuk memperbesar</small>
                                        </div>
                                    `;
                                } else if (isPdf) {
                                    displayValue = `
                                        <a href="${fileUrl}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-100 text-xs font-medium rounded-md transition-colors my-1">
                                            <i class="bi bi-file-earmark-pdf text-sm"></i> Buka Dokumen PDF
                                        </a>
                                    `;
                                } else if (isDoc) {
                                    displayValue = `
                                        <a href="${fileUrl}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 border border-blue-200 text-blue-600 hover:bg-blue-100 text-xs font-medium rounded-md transition-colors my-1">
                                            <i class="bi bi-file-earmark-word text-sm"></i> Unduh Dokumen Word
                                        </a>
                                    `;
                                }
                            }

                            const row = `
                                <tr>
                                    <td class="px-4 py-2.5 font-medium text-gray-700 bg-gray-50/50">${formattedKey}</td>
                                    <td class="px-4 py-2.5 text-gray-800">${displayValue}</td>
                                </tr>
                            `;
                            specsContainer.innerHTML += row;
                        });
                    }
                } catch (e) {
                    console.error("Error parsing specs:", e);
                    specsContainer.innerHTML = '<tr><td colspan="2" class="px-4 py-3 text-center text-gray-400 italic">Gagal membaca data spesifikasi.</td></tr>';
                }

                // Show modal
                detailModal.classList.remove('hidden');
                detailModal.classList.add('flex');
            });
        });

        // Toggle Close Modal
        closeButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                detailModal.classList.add('hidden');
                detailModal.classList.remove('flex');
            });
        });

        // Close on clicking backdrop
        detailModal.addEventListener('click', function(e) {
            if (e.target === detailModal) {
                detailModal.classList.add('hidden');
                detailModal.classList.remove('flex');
            }
        });
    });
</script>
<?= $this->endSection() ?>