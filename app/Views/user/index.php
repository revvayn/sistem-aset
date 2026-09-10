<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-sky-500/10 to-blue-500/10 border border-sky-500/20 flex items-center justify-center text-sky-600 text-lg shrink-0">
                <i class="bi bi-people"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900 tracking-tight">Management User</h2>
                <p class="text-xs text-gray-500 mt-0.5">Kelola data pengguna, penetapan peran, dan hak akses sistem</p>
            </div>
        </div>
        <a href="<?= base_url('users/create') ?>" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold text-sm rounded-xl shadow-md shadow-blue-600/20 transition-all duration-150 active:scale-[0.98]">
            <i class="bi bi-plus-lg"></i> Tambah User Baru
        </a>
    </div>

    <!-- Alert Flash Messages -->
    <?php if (session()->getFlashdata('message')) : ?>
        <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 shadow-sm flex items-start justify-between gap-3">
            <div class="flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center shrink-0"><i class="bi bi-check-lg text-emerald-600"></i></span>
                <span><?= session()->getFlashdata('message') ?></span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 text-lg leading-none">&times;</button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 shadow-sm flex items-start justify-between gap-3">
            <div class="flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-lg bg-rose-100 flex items-center justify-center shrink-0"><i class="bi bi-exclamation-triangle text-rose-600"></i></span>
                <span><?= session()->getFlashdata('error') ?></span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800 text-lg leading-none">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Card & Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 w-16 text-center text-[11px] font-bold uppercase tracking-wider text-gray-500">No</th>
                        <th scope="col" class="px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">Nama Pengguna</th>
                        <th scope="col" class="px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">Email</th>
                        <th scope="col" class="px-6 py-3.5 text-[11px] font-bold uppercase tracking-wider text-gray-500">Role / Hak Akses</th>
                        <th scope="col" class="px-6 py-3.5 text-center w-44 text-[11px] font-bold uppercase tracking-wider text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <?php if (!empty($users)) : ?>
                        <?php $i = 1;
                        foreach ($users as $u) : ?>
                            <tr class="hover:bg-sky-50/40 transition-colors">
                                <td class="px-6 py-4 text-center font-medium text-gray-400"><?= $i++ ?></td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-gray-700 to-gray-800 border border-gray-200 flex items-center justify-center text-gray-300 text-sm shrink-0">
                                            <i class="bi bi-person"></i>
                                        </div>
                                        <span class="font-semibold text-gray-900"><?= esc($u['nama']) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600"><?= esc($u['email']) ?></td>
                                <td class="px-6 py-4">
                                    <?php
                                    $badgeStyle = 'bg-gray-100 text-gray-700 border-gray-200';
                                    if ($u['role'] === 'admin') {
                                        $badgeStyle = 'bg-rose-50 text-rose-700 border-rose-200';
                                    } elseif ($u['role'] === 'staff') {
                                        $badgeStyle = 'bg-blue-50 text-blue-700 border-blue-200';
                                    }
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border <?= $badgeStyle ?>">
                                        <?= strtoupper(esc($u['role'])) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="<?= base_url('users/edit/' . $u['id']) ?>"
                                           class="w-8 h-8 inline-flex items-center justify-center border border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100 rounded-lg transition-colors text-sm" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <?php if (session()->get('id') != $u['id']) : ?>
                                            <a href="<?= base_url('users/delete/' . $u['id']) ?>"
                                               class="w-8 h-8 inline-flex items-center justify-center border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 rounded-lg transition-colors text-sm"
                                               onclick="return confirm('Yakin ingin menghapus user ini?')" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        <?php else: ?>
                                            <span class="px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-500 rounded-lg border border-gray-200 italic">
                                                Akun Saya
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="px-6 py-14 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gray-50 border border-gray-200 mb-3 text-gray-300">
                                    <i class="bi bi-people text-3xl"></i>
                                </div>
                                <p class="text-sm font-medium text-gray-500">Belum ada user terdaftar</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>