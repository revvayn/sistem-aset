<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
        <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
            <h3 class="text-base font-bold flex items-center gap-2.5">
                <span class="w-9 h-9 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center text-lg"><i class="bi bi-person-circle"></i></span>
                Profil Saya
            </h3>
        </div>
        <div class="p-6 sm:p-8">
            <form action="<?= base_url('profile/update') ?>" method="POST" class="space-y-5">
                <?= csrf_field() ?>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama</label>
                    <input type="text" name="nama" value="<?= esc($user['nama'] ?? '') ?>" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="<?= esc($user['email'] ?? '') ?>" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm" required>
                </div>

                <hr class="border-gray-200 my-4">
                <p class="text-xs text-gray-500">Kosongkan bagian password jika tidak ingin mengubah.</p>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password Saat Ini</label>
                    <input type="password" name="current_password" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password Baru</label>
                    <input type="password" name="new_password" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi Password Baru</label>
                    <input type="password" name="confirm_password" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm">
                </div>

                <button type="submit" class="btn-primary w-full justify-center">
                    <i class="bi bi-check-circle-fill"></i> Simpan Profil
                </button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>