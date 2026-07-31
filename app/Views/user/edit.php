<?php

/**
 * @var array $user
 */
?>

<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Card Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        
        <!-- Card Header (Aksen Amber untuk Edit) -->
        <div class="bg-amber-500 px-6 py-4 flex justify-between items-center text-white">
            <h3 class="text-lg font-bold flex items-center gap-2">
                <i class="bi bi-pencil-square"></i> Edit User
            </h3>
            <a href="<?= base_url('users') ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-black/10 hover:bg-black/20 text-white text-xs font-medium rounded-lg transition-colors">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <!-- Flash Alert Errors -->
            <?php if (session()->getFlashdata('errors')) : ?>
                <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
                    <div class="flex items-center gap-2 font-semibold mb-2">
                        <i class="bi bi-exclamation-triangle-fill text-red-600"></i>
                        <span>Terjadi Kesalahan Input:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-red-700 pl-2">
                        <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('users/update/' . $user['id']) ?>" method="POST" class="space-y-5">
                <?= csrf_field() ?>

                <!-- Input Nama Lengkap -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="nama" 
                           class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all placeholder-gray-400" 
                           value="<?= old('nama', $user['nama'] ?? '') ?>" 
                           required>
                </div>

                <!-- Input Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Email <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" 
                           name="email" 
                           class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all placeholder-gray-400" 
                           value="<?= old('email', $user['email'] ?? '') ?>" 
                           required>
                </div>

                <!-- Input Password Baru (Opsional) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Password Baru <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <input type="password" 
                           name="password" 
                           class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all placeholder-gray-400" 
                           placeholder="Kosongkan jika tidak ingin mengubah password">
                    <p class="mt-1.5 text-xs text-gray-500">Biarkan kosong jika password tidak diubah.</p>
                </div>

                <!-- Select Role -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Role / Hak Akses <span class="text-rose-500">*</span>
                    </label>
                    <?php $selectedRole = old('role', $user['role'] ?? ''); ?>
                    <select name="role" 
                            class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all cursor-pointer" 
                            required>
                        <option value="staff" <?= $selectedRole === 'staff' ? 'selected' : '' ?>>Staff (Input & Edit Data)</option>
                        <option value="admin" <?= $selectedRole === 'admin' ? 'selected' : '' ?>>Admin (Akses Penuh)</option>
                        <option value="viewer" <?= $selectedRole === 'viewer' ? 'selected' : '' ?>>Viewer (Hanya Lihat Data)</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="pt-3">
                    <button type="submit" 
                            class="w-full py-2.5 px-4 bg-amber-600 hover:bg-amber-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors duration-150 flex items-center justify-center gap-2">
                        <i class="bi bi-pencil-square"></i> Update Data User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>