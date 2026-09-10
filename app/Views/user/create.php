<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="max-w-3xl mx-auto">
    <!-- Card Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">

        <!-- Card Header -->
        <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-indigo-600 flex justify-between items-center text-white">
            <h3 class="text-base font-bold flex items-center gap-2.5">
                <span class="w-9 h-9 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center text-lg"><i class="bi bi-person-plus"></i></span>
                Tambah User Baru
            </h3>
            <a href="<?= base_url('users') ?>" class="inline-flex items-center gap-1.5 px-3 py-2 bg-white/10 hover:bg-white/20 text-white text-xs font-medium rounded-lg border border-white/20 transition-colors">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Card Body -->
        <div class="p-6 sm:p-8">
            <form action="<?= base_url('users/store') ?>" method="POST" class="space-y-5">
                <?= csrf_field() ?>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"><i class="bi bi-person text-sm"></i></span>
                        <input type="text" name="nama" class="w-full pl-10 pr-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white outline-none transition-all placeholder-gray-400" value="<?= old('nama') ?>" required placeholder="Contoh: Budi Santoso">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Email <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"><i class="bi bi-envelope text-sm"></i></span>
                        <input type="email" name="email" class="w-full pl-10 pr-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white outline-none transition-all placeholder-gray-400" value="<?= old('email') ?>" required placeholder="user@mail.com">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Password <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"><i class="bi bi-shield-lock text-sm"></i></span>
                        <input type="password" name="password" class="w-full pl-10 pr-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white outline-none transition-all placeholder-gray-400" required placeholder="Minimal 6 karakter">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Role / Hak Akses <span class="text-rose-500">*</span>
                    </label>
                    <select name="role" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white outline-none transition-all cursor-pointer" required>
                        <option value="staff" <?= old('role', 'staff') === 'staff' ? 'selected' : '' ?>>Staff (Input & Edit Data)</option>
                        <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Admin (Akses Penuh)</option>
                        <option value="viewer" <?= old('role') === 'viewer' ? 'selected' : '' ?>>Viewer (Hanya Lihat Data)</option>
                    </select>
                </div>

                <div class="pt-3">
                    <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-semibold text-sm rounded-xl shadow-md shadow-emerald-600/20 transition-all duration-150 active:scale-[0.99] flex items-center justify-center gap-2">
                        <i class="bi bi-check-circle"></i> Simpan User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>