<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Aset Terhapus</h2>
            <p class="text-xs text-gray-500 mt-0.5">Pulihkan atau hapus permanen aset yang telah dihapus (soft delete).</p>
        </div>
        <a href="<?= base_url('asset') ?>" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">ID</th>
                        <th class="px-5 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">Kategori</th>
                        <th class="px-5 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-5 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">Dihapus Pada</th>
                        <th class="px-5 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (!empty($assets)) : ?>
                        <?php foreach ($assets as $ast) : ?>
                            <tr class="hover:bg-gray-50/60">
                                <td class="px-5 py-4 text-gray-500"><?= esc($ast['id']) ?></td>
                                <td class="px-5 py-4"><?= esc($ast['nama_kategori'] ?? '-') ?></td>
                                <td class="px-5 py-4"><?= esc($ast['status'] ?? '-') ?></td>
                                <td class="px-5 py-4 text-gray-500"><?= esc($ast['deleted_at'] ?? '-') ?></td>
                                <td class="px-5 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="<?= base_url('asset/restore/' . $ast['id']) ?>" method="POST" class="inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-semibold hover:bg-emerald-100">
                                                <i class="bi bi-arrow-counterclockwise"></i> Pulihkan
                                            </button>
                                        </form>
                                        <form action="<?= base_url('asset/force-delete/' . $ast['id']) ?>" method="POST" class="inline" onsubmit="return confirm('Hapus permanen? Data tidak bisa dikembalikan.')">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-700 border border-rose-200 rounded-lg text-xs font-semibold hover:bg-rose-100">
                                                <i class="bi bi-trash"></i> Hapus Permanen
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="5" class="px-5 py-14 text-center text-gray-400">Tidak ada aset terhapus.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>