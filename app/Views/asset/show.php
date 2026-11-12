<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Detail Aset</h2>
            <p class="text-xs text-gray-500 mt-0.5">Informasi lengkap unit aset.</p>
        </div>
        <a href="<?= base_url('asset') ?>" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <?php
    $role = session()->get('role');
    $specs = is_array($asset['specifications'] ?? null) ? $asset['specifications'] : (json_decode($asset['specifications'] ?? '{}', true) ?? []);
    $compMap = [];
    foreach (($components ?? []) as $c) {
        $compMap[$c['key_komponen']] = $c;
    }
    ?>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden mb-6">
        <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
            <h3 class="text-base font-bold flex items-center gap-2.5">
                <span class="w-9 h-9 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center text-lg"><i class="bi bi-box-seam"></i></span>
                <?= esc($asset['serial_number'] ?? ('Aset #' . $asset['id'])) ?>
            </h3>
            <p class="text-xs text-white/80 mt-1">ID #<?= esc($asset['id']) ?> &bull; Kategori: <?= esc($asset['nama_kategori'] ?? '-') ?></p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-500">Status</span>
                        <span class="font-semibold text-gray-800"><?= esc($asset['status'] ?? '-') ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-500">Nomor Seri</span>
                        <span class="font-mono text-gray-800"><?= esc($asset['serial_number'] ?? '-') ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-500">Lokasi</span>
                        <span class="text-gray-800"><?= esc($asset['location'] ?? '-') ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-500">Departemen</span>
                        <span class="text-gray-800"><?= esc($asset['department'] ?? '-') ?></span>
                    </div>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-500">Tgl Pembelian</span>
                        <span class="text-gray-800"><?= esc($asset['purchase_date'] ?? '-') ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-500">Harga</span>
                        <span class="text-gray-800"><?= $asset['price'] !== null ? 'Rp ' . number_format((float)$asset['price'], 2, ',', '.') : '-' ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-500">Garansi Hingga</span>
                        <span class="text-gray-800"><?= esc($asset['warranty_expiry'] ?? '-') ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-500">Dibuat Oleh</span>
                        <span class="text-gray-800"><?= esc($asset['nama_pengguna'] ?? '-') ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                <i class="bi bi-cpu text-blue-600"></i> Spesifikasi & Komponen
            </h3>
        </div>
        <div class="p-6">
            <?php if (!empty($specs)) : ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($specs as $key => $val) : ?>
                        <?php
                        $comp = $compMap[$key] ?? null;
                        $label = $comp['nama_komponen'] ?? ucfirst(str_replace('_', ' ', $key));
                        $type = $comp['tipe_input'] ?? 'text';
                        if (is_string($val) && preg_match('/\.(jpg|jpeg|png|webp|gif|svg|pdf|doc|docx)$/i', $val)) {
                            $display = '<a href="' . base_url('uploads/specs/' . $val) . '" target="_blank" class="text-blue-600 hover:underline">Lihat File</a>';
                        } elseif (preg_match('/^\$2[aby]\$/', (string)$val)) {
                            $display = '•••••• (terenkripsi)';
                        } else {
                            $display = esc($val);
                        }
                        ?>
                        <div class="border border-gray-100 rounded-xl p-3">
                            <p class="text-xs font-semibold text-gray-500"><?= esc($label) ?></p>
                            <p class="text-sm text-gray-800 mt-1"><?= $display ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p class="text-sm text-gray-400 italic">Tidak ada spesifikasi tambahan.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        <?php if (in_array(strtolower($role ?? ''), ['admin', 'staff'])) : ?>
            <a href="<?= base_url('asset/edit/' . $asset['id']) ?>" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-xl">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="<?= base_url('asset/' . $asset['id'] . '/maintenances') ?>" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl">
                <i class="bi bi-tools"></i> Pemeliharaan
            </a>
            <a href="<?= base_url('asset/' . $asset['id'] . '/locations') ?>" class="px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold rounded-xl">
                <i class="bi bi-geo-alt"></i> Lokasi
            </a>
        <?php endif; ?>
        <?php if (strtolower($role ?? '') === 'admin') : ?>
            <form action="<?= base_url('asset/delete/' . $asset['id']) ?>" method="POST" class="inline" onsubmit="return confirm('Hapus aset ini?')">
                <?= csrf_field() ?>
                <button type="submit" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold rounded-xl">
                    <i class="bi bi-trash"></i> Hapus
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>