<?php

/**
 * @var array $user
 */
?>

<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="max-w-3xl mx-auto">
    <!-- Card Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">

        <!-- Card Header -->
        <div class="px-6 py-5 bg-gradient-to-r from-amber-500 to-orange-500 flex justify-between items-center text-white">
            <h3 class="text-base font-bold flex items-center gap-2.5">
                <span class="w-9 h-9 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center text-lg"><i class="bi bi-pencil-square"></i></span>
                Edit User
            </h3>
            <a href="<?= base_url('users') ?>" class="inline-flex items-center gap-1.5 px-3 py-2 bg-white/10 hover:bg-white/20 text-white text-xs font-medium rounded-lg border border-white/20 transition-colors">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Card Body -->
        <div class="p-6 sm:p-8">
            <?php if (session()->getFlashdata('errors')) : ?>
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm">
                    <div class="flex items-center gap-2 font-semibold mb-2">
                        <span class="w-6 h-6 rounded-md bg-rose-100 flex items-center justify-center shrink-0"><i class="bi bi-exclamation-triangle text-xs text-rose-600"></i></span>
                        <span>Terjadi Kesalahan Input:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-rose-600 pl-2">
                        <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('users/update/' . $user['id']) ?>" method="POST" class="space-y-5">
                <?= csrf_field() ?>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"><i class="bi bi-person text-sm"></i></span>
                        <input type="text"
                               name="nama"
                               class="w-full pl-10 pr-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:bg-white outline-none transition-all placeholder-gray-400"
                               value="<?= old('nama', $user['nama'] ?? '') ?>"
                               required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Email <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"><i class="bi bi-envelope text-sm"></i></span>
                        <input type="email"
                               name="email"
                               class="w-full pl-10 pr-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:bg-white outline-none transition-all placeholder-gray-400"
                               value="<?= old('email', $user['email'] ?? '') ?>"
                               required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Password Baru <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"><i class="bi bi-shield-lock text-sm"></i></span>
                        <input type="password"
                               name="password"
                               class="w-full pl-10 pr-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:bg-white outline-none transition-all placeholder-gray-400"
                               placeholder="Kosongkan jika tidak ingin mengubah password">
                    </div>
                    <p class="mt-1.5 text-xs text-gray-500">Biarkan kosong jika password tidak diubah.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Role / Hak Akses <span class="text-rose-500">*</span>
                    </label>
                    <?php $selectedRole = old('role', $user['role'] ?? ''); ?>
                    <select name="role"
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:bg-white outline-none transition-all cursor-pointer"
                            required>
                        <option value="staff" <?= $selectedRole === 'staff' ? 'selected' : '' ?>>Staff (Input & Edit Data)</option>
                        <option value="admin" <?= $selectedRole === 'admin' ? 'selected' : '' ?>>Admin (Akses Penuh)</option>
                        <option value="viewer" <?= $selectedRole === 'viewer' ? 'selected' : '' ?>>Viewer (Hanya Lihat Data)</option>
                    </select>
                </div>

                <div class="pt-3">
                    <button type="submit"
                            class="w-full py-3.5 px-4 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold text-sm rounded-xl shadow-md shadow-amber-600/20 transition-all duration-150 active:scale-[0.99] flex items-center justify-center gap-2">
                        <i class="bi bi-pencil-square"></i> Update Data User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>