<?php
/**
 * @var array $asset
 * @var array $categories
 * @var array $components
 * @var array $specs
 */
?>

<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto">
    <!-- Card Utama -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">

        <!-- Header Card -->
        <div class="px-6 py-5 bg-gradient-to-r from-amber-500 to-orange-500 flex justify-between items-center text-white">
            <h3 class="text-base font-bold flex items-center gap-2.5">
                <span class="w-9 h-9 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center text-lg"><i class="bi bi-pencil-square"></i></span>
                Edit Aset
            </h3>
            <a href="<?= base_url('asset') ?>" class="px-3 py-2 bg-white/10 hover:bg-white/20 text-white font-medium text-xs rounded-lg transition-colors duration-150 inline-flex items-center gap-1.5 border border-white/20">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Body Card -->
        <div class="p-6 sm:p-8">
            <form action="<?= base_url('asset/update/' . $asset['id']) ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                <?= csrf_field() ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Kategori Aset <span class="text-red-500">*</span>
                        </label>
                        <select name="master_data_id" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:bg-white outline-none transition-all cursor-pointer" required>
                            <?php foreach ($categories as $cat) : ?>
                                <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $asset['master_data_id']) ? 'selected' : '' ?>>
                                    <?= esc($cat['nama_kategori']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:bg-white outline-none transition-all cursor-pointer" required>
                            <option value="Aktif" <?= (($asset['status'] ?? '') === 'Aktif') ? 'selected' : '' ?>>Aktif</option>
                            <option value="Perbaikan" <?= (($asset['status'] ?? '') === 'Perbaikan') ? 'selected' : '' ?>>Perbaikan</option>
                            <option value="Rusak" <?= (($asset['status'] ?? '') === 'Rusak') ? 'selected' : '' ?>>Rusak</option>
                            <option value="Disimpan" <?= (($asset['status'] ?? '') === 'Disimpan') ? 'selected' : '' ?>>Disimpan</option>
                        </select>
                    </div>
                </div>

                <hr class="border-gray-200 my-6">

                <h4 class="text-base font-bold text-gray-700 mb-3">Data Standar Aset</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor Seri</label>
                        <input type="text" name="serial_number" value="<?= esc($asset['serial_number'] ?? '') ?>" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Lokasi</label>
                        <input type="text" name="location" value="<?= esc($asset['location'] ?? '') ?>" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Departemen</label>
                        <input type="text" name="department" value="<?= esc($asset['department'] ?? '') ?>" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Pembelian</label>
                        <input type="date" name="purchase_date" value="<?= esc($asset['purchase_date'] ?? '') ?>" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Harga (Rp)</label>
                        <input type="number" step="0.01" name="price" value="<?= esc($asset['price'] ?? '') ?>" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Garansi Hingga</label>
                        <input type="date" name="warranty_expiry" value="<?= esc($asset['warranty_expiry'] ?? '') ?>" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm">
                    </div>
                </div>

                <hr class="border-gray-200 my-6">

                <!-- Section Atribut / Komponen Spesifikasi -->
                <div>
                    <h4 class="text-base font-bold text-blue-600 flex items-center gap-2 mb-4">
                        <span class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-sm"><i class="bi bi-sliders"></i></span>
                        Atribut / Komponen Spesifikasi
                    </h4>

                    <div id="components-wrapper" class="p-5 bg-gray-50 rounded-xl border border-gray-200/80">
                        <?php if (!empty($components)) : ?>
                            <div id="components-grid" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <?php foreach ($components as $comp) : ?>
                                    <?php 
                                        $key       = $comp['key_komponen'];
                                        $val       = $specs[$key] ?? '';
                                        $reqAttr   = ($comp['is_required'] ?? 0) == 1 ? 'required' : '';
                                        $reqBadge  = ($comp['is_required'] ?? 0) == 1 ? ' <span class="text-red-500">*</span>' : '';
                                        $inputType = !empty($comp['tipe_input']) ? $comp['tipe_input'] : 'text';
                                    ?>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                            <?= esc($comp['nama_komponen']) ?><?= $reqBadge ?>
                                        </label>

                                        <?php if ($inputType === 'file' || $inputType === 'foto') : ?>
                                            <?php if (!empty($val) && file_exists(FCPATH . 'uploads/specs/' . $val)) : ?>
                                                <div class="mb-3 p-2 bg-white rounded-xl border border-gray-200 inline-block">
                                                    <a href="<?= base_url('uploads/specs/' . $val) ?>" target="_blank" class="inline-block">
                                                        <img src="<?= base_url('uploads/specs/' . $val) ?>" alt="Foto Aset" class="h-24 w-auto object-cover rounded-lg border border-gray-200 hover:opacity-90 transition-opacity">
                                                    </a>
                                                    <span class="block text-xs text-gray-500 mt-1 font-mono truncate max-w-[200px]">File: <?= esc($val) ?></span>
                                                </div>
                                            <?php endif; ?>

                                            <?php
                                            $acceptAttr = 'image/png, image/jpeg, image/jpg, .pdf, .doc, .docx';
                                            if ($inputType === 'foto') {
                                                $acceptAttr = 'image/png, image/jpeg, image/jpg';
                                            }
                                            ?>
                                            <input type="file"
                                                   name="specs[<?= esc($key) ?>]"
                                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-xl cursor-pointer bg-white outline-none focus:outline-none"
                                                   accept="<?= $acceptAttr ?>"
                                                   <?= empty($val) ? $reqAttr : '' ?>>
                                            <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah foto/file.</p>

                                        <?php else : ?>
                                            <input type="<?= esc($inputType) ?>"
                                                   name="specs[<?= esc($key) ?>]"
                                                   value="<?= esc($val) ?>"
                                                   class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                                   <?= $reqAttr ?>>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <p class="text-sm text-gray-500 italic text-center py-2">Tidak ada komponen tambahan untuk kategori ini.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn-primary w-full justify-center">
                        <i class="bi bi-check-circle-fill"></i> Update Data Aset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.querySelector('select[name="master_data_id"]');
    const grid = document.getElementById('components-grid');
    if (!categorySelect || !grid) return;

    function escapeHtml(s) {
        return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function renderComponents(components, values) {
        grid.innerHTML = '';
        if (!components || components.length === 0) {
            grid.innerHTML = '<p class="text-sm text-gray-500 italic col-span-2">Tidak ada komponen untuk kategori ini.</p>';
            return;
        }
        components.forEach(function(comp) {
            const key = comp.key_komponen;
            const type = comp.tipe_input || 'text';
            const required = comp.is_required == 1;
            const reqAttr = required ? 'required' : '';
            const reqBadge = required ? ' <span class="text-red-500">*</span>' : '';
            const val = (values && values[key] !== undefined && values[key] !== null) ? values[key] : '';
            const baseInputClass = "w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all";
            let inputHtml = '';
            if (type === 'password') {
                inputHtml = '<input type="password" class="' + baseInputClass + '" name="specs[' + key + ']" value="' + escapeHtml(val) + '" ' + reqAttr + '>';
            } else if (type === 'number') {
                inputHtml = '<input type="number" class="' + baseInputClass + '" name="specs[' + key + ']" value="' + escapeHtml(val) + '" ' + reqAttr + '>';
            } else if (type === 'date') {
                inputHtml = '<input type="date" class="' + baseInputClass + '" name="specs[' + key + ']" value="' + escapeHtml(val) + '" ' + reqAttr + '>';
            } else if (type === 'file' || type === 'foto') {
                const accept = type === 'foto' ? 'image/png, image/jpeg, image/jpg' : 'image/png, image/jpeg, image/jpg, .pdf, .doc, .docx';
                inputHtml = '<input type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-xl cursor-pointer bg-white outline-none focus:outline-none" name="specs[' + key + ']" accept="' + accept + '" ' + (val ? '' : reqAttr) + '><p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah.</p>';
            } else if (type === 'qr_code') {
                inputHtml = '<input type="text" class="' + baseInputClass + '" name="specs[' + key + ']" value="' + escapeHtml(val) + '" ' + reqAttr + ' placeholder="QR Code">';
            } else {
                inputHtml = '<input type="text" class="' + baseInputClass + '" name="specs[' + key + ']" value="' + escapeHtml(val) + '" ' + reqAttr + '>';
            }
            const field = '<div><label class="block text-sm font-semibold text-gray-700 mb-1.5">' + escapeHtml(comp.nama_komponen) + reqBadge + '</label>' + inputHtml + '</div>';
            grid.insertAdjacentHTML('beforeend', field);
        });
    }

    categorySelect.addEventListener('change', function() {
        const id = this.value;
        if (!id) return;
        fetch('<?= base_url('asset/get-components') ?>/' + id)
            .then(function(response) { return response.json(); })
            .then(function(data) { renderComponents(data, {}); })
            .catch(function(err) { console.error(err); });
    });
});
</script>
<?= $this->endSection() ?>