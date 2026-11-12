<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Riwayat Pemeliharaan Aset</h2>
            <p class="text-xs text-gray-500 mt-0.5">
                Kategori: <?= esc($asset['nama_kategori'] ?? '-') ?> &bull; Status: <?= esc($asset['status'] ?? '-') ?>
            </p>
        </div>
        <a href="<?= base_url('asset') ?>" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <?php $role = session()->get('role'); ?>
    <?php if (in_array(strtolower($role ?? ''), ['admin', 'staff'])) : ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-6 mb-6">
        <h3 class="text-base font-bold text-gray-900 mb-4">Tambah Riwayat</h3>
        <form action="<?= base_url('asset/' . $asset['id'] . '/maintenances/store') ?>" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?= csrf_field() ?>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal</label>
                <input type="date" name="tanggal" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Vendor/Teknisi</label>
                <input type="text" name="vendor" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Biaya</label>
                <input type="number" step="0.01" name="biaya" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status Setelah</label>
                <select name="status_sesudah" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm">
                    <?php foreach (['Aktif','Perbaikan','Rusak','Disimpan'] as $st) : ?>
                        <option value="<?= $st ?>" <?= (($asset['status'] ?? '') === $st) ? 'selected' : '' ?>><?= $st ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm" required></textarea>
            </div>
            <div class="md:col-span-2">
                <button type="submit" class="btn-primary">
                    <i class="bi bi-plus-lg"></i> Tambah
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
                        <th class="px-5 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">Tanggal</th>
                        <th class="px-5 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">Deskripsi</th>
                        <th class="px-5 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">Vendor</th>
                        <th class="px-5 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">Biaya</th>
                        <th class="px-5 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">Status</th>
                        <?php if (in_array(strtolower($role ?? ''), ['admin', 'staff'])) : ?>
                            <th class="px-5 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-gray-500">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (!empty($logs)) : ?>
                        <?php foreach ($logs as $log) : ?>
                            <tr>
                                <td class="px-5 py-4 text-gray-600"><?= esc($log['tanggal']) ?></td>
                                <td class="px-5 py-4 text-gray-800"><?= esc($log['deskripsi']) ?></td>
                                <td class="px-5 py-4 text-gray-600"><?= esc($log['vendor'] ?? '-') ?></td>
                                <td class="px-5 py-4 text-gray-600"><?= number_format((float) ($log['biaya'] ?? 0), 2, ',', '.') ?></td>
                                <td class="px-5 py-4 text-xs text-gray-600">
                                    <?= esc($log['status_sebelum'] ?? '-') ?> &rarr; <?= esc($log['status_sesudah'] ?? '-') ?>
                                </td>
                                <?php if (in_array(strtolower($role ?? ''), ['admin', 'staff'])) : ?>
                                    <td class="px-5 py-4 text-center">
                                        <form action="<?= base_url('maintenance/delete/' . $log['id']) ?>" method="POST" class="inline" onsubmit="return confirm('Hapus riwayat ini?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="w-8 h-8 inline-flex items-center justify-center border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 rounded-lg">
                                                <i class="bi bi-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="<?= in_array(strtolower($role ?? ''), ['admin', 'staff']) ? 6 : 5 ?>" class="px-5 py-14 text-center text-gray-400">
                                Belum ada riwayat pemeliharaan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>