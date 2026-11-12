<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
        <div class="px-6 py-5 bg-gradient-to-r from-gray-700 to-gray-900 text-white">
            <h3 class="text-base font-bold flex items-center gap-2.5">
                <span class="w-9 h-9 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center text-lg"><i class="bi bi-database"></i></span>
                Backup & Restore Database
            </h3>
        </div>
        <div class="p-6 sm:p-8 space-y-6">
            <div>
                <h4 class="text-sm font-bold text-gray-900 mb-2">Backup</h4>
                <p class="text-xs text-gray-500 mb-3">Unduh salinan database dalam format SQL.</p>
                <a href="<?= base_url('backup/download') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl">
                    <i class="bi bi-download"></i> Unduh Backup
                </a>
            </div>

            <hr class="border-gray-200">

            <div>
                <h4 class="text-sm font-bold text-gray-900 mb-2">Restore</h4>
                <p class="text-xs text-gray-500 mb-3">Unggah file SQL untuk memulihkan database. Gunakan dengan hati-hati.</p>
                <form action="<?= base_url('backup/restore') ?>" method="POST" enctype="multipart/form-data" class="space-y-3">
                    <?= csrf_field() ?>
                    <input type="file" name="sql_file" accept=".sql" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-xl cursor-pointer bg-white" required>
                    <button type="submit" onclick="return confirm('Restore database? Data saat ini dapat tertimpa.')" class="btn-danger">
                        <i class="bi bi-upload"></i> Restore
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>