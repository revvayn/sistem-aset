<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sistem Dokumentasi Aset' ?></title>
    
    <!-- Link file CSS hasil kompilasi Tailwind v4 -->
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    
    <!-- Bootstrap Icons (tetap dipertahankan untuk icon) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Alpine.js (opsional, untuk menangani interaktivitas dropdown tanpa Bootstrap JS) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100 min-h-screen text-gray-800 antialiased">

    <?php $role = session()->get('role'); ?>

    <div class="flex min-h-screen">
        <!-- Sidebar Navigasi Samping -->
        <aside class="w-64 bg-gray-900 text-white flex flex-col justify-between p-4 shrink-0">
            <div>
                <!-- Brand / Header Sidebar -->
                <a href="<?= base_url('asset') ?>" class="flex items-center gap-3 text-white no-underline px-2 py-1 mb-4">
                    <i class="bi bi-box-seam text-2xl text-blue-500"></i>
                    <span class="text-lg font-bold tracking-wide">Sistem Aset</span>
                </a>

                <hr class="border-gray-700 my-4">

                <!-- Navigation Links -->
                <nav class="space-y-1">
                    <!-- SEMUA ROLE -->
                    <?php $isActive = (uri_string() == 'asset' || uri_string() == ''); ?>
                    <a href="<?= base_url('asset') ?>" 
                       class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 <?= $isActive ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                        <i class="bi bi-table me-3 text-base"></i> Daftar Aset
                    </a>

                    <!-- KHUSUS ROLE ADMIN -->
                    <?php if ($role === 'admin') : ?>
                        <div class="px-4 pt-5 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            Master Data
                        </div>

                        <?php $isCatActive = strpos(uri_string(), 'master/categories') !== false; ?>
                        <a href="<?= base_url('master/categories') ?>" 
                           class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 <?= $isCatActive ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-tags me-3 text-base"></i> Master Kategori
                        </a>

                        <?php $isCompActive = strpos(uri_string(), 'master/components') !== false; ?>
                        <a href="<?= base_url('master/components') ?>" 
                           class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 <?= $isCompActive ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-cpu me-3 text-base"></i> Master Komponen
                        </a>

                        <div class="px-4 pt-5 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            Pengaturan
                        </div>

                        <?php $isUserActive = strpos(uri_string(), 'users') !== false; ?>
                        <a href="<?= base_url('users') ?>" 
                           class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 <?= $isUserActive ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-people me-3 text-base"></i> Management User
                        </a>
                    <?php endif; ?>
                </nav>
            </div>

            <!-- User Info & Dropdown Logout -->
            <div x-data="{ open: false }" class="relative border-t border-gray-700 pt-4 mt-auto">
                <button @click="open = !open" type="button" class="w-full flex items-center justify-between text-left text-white focus:outline-none group">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-person-circle text-2xl text-gray-400 group-hover:text-white"></i>
                        <div class="flex flex-col">
                            <span class="text-sm font-semibold leading-tight"><?= session()->get('nama') ?? session()->get('username') ?? 'User' ?></span>
                            <span class="inline-block mt-1">
                                <span class="px-2 py-0.5 text-[10px] font-semibold text-gray-300 bg-gray-800 rounded border border-gray-700 capitalize">
                                    <?= esc($role ?? 'Guest') ?>
                                </span>
                            </span>
                        </div>
                    </div>
                    <i class="bi bi-chevron-up text-xs text-gray-400"></i>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" 
                     @click.outside="open = false" 
                     x-transition 
                     class="absolute bottom-full left-0 mb-2 w-full bg-gray-800 border border-gray-700 rounded-lg shadow-lg overflow-hidden z-50">
                    <form action="<?= base_url('logout') ?>" method="POST" id="logout-form">
                        <?= csrf_field() ?>
                        <button type="submit" class="w-full flex items-center px-4 py-2.5 text-sm text-red-400 hover:bg-gray-700 transition-colors duration-150">
                            <i class="bi bi-box-arrow-right me-2"></i> Sign out
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Konten Utama -->
        <main class="flex-1 p-6 overflow-y-auto">
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <?= $this->renderSection('scripts') ?>
</body>

</html>