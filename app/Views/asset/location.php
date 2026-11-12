<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Riwayat Lokasi Aset</h2>
            <p class="text-xs text-gray-500 mt-0.5">
                Kategori: <?= esc($asset['nama_kategori'] ?? '-') ?> &bull; Lokasi saat ini: <?= esc($asset['location'] ?? '-') ?> &bull; Departemen: <?= esc($asset['department'] ?? '-') ?>
            </p>
        </div>
        <a href="<?= base_url('asset') ?>" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <?php $role = session()->get('role'); ?>
    <?php if (in_array(strtolower($role ?? ''), ['admin', 'staff'])) : ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-6 mb-6">
        <h3 class="text-base font-bold text-gray-900 mb-4">Perbarui Lokasi</h3>
        <form action="<?= base_url('asset/' . $asset['id'] . '/locations/store') ?>" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?= csrf_field() ?>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Lokasi Baru</label>
                <input type="text" name="location_to" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Departemen Baru</label>
                <input type="text" name="department_to" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Catatan</label>
                <textarea name="note" rows="2" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm"></textarea>
            </div>
            <div class="md:col-span-2">
                <button type="submit" class="btn-primary">
                    <i class="bi bi-geo-alt"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">Waktu</th>
                        <th class="px-5 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">Lokasi</th>
                        <th class="px-5 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">Departemen</th>
                        <th class="px-5 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (!empty($logs)) : ?>
                        <?php foreach ($logs as $log) : ?>
                            <tr>
                                <td class="px-5 py-4 text-gray-600"><?= esc($log['created_at']) ?></td>
                                <td class="px-5 py-4 text-gray-800"><?= esc($log['location_from'] ?? '-') ?> &rarr; <?= esc($log['location_to'] ?? '-') ?></td>
                                <td class="px-5 py-4 text-gray-600"><?= esc($log['department_from'] ?? '-') ?> &rarr; <?= esc($log['department_to'] ?? '-') ?></td>
                                <td class="px-5 py-4 text-gray-600"><?= esc($log['note'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4" class="px-5 py-14 text-center text-gray-400">Belum ada riwayat lokasi.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>