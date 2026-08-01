<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Sistem Dokumentasi Aset') ?></title>

    <!-- Style CSS (Tailwind v4) -->
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="h-full text-gray-800 antialiased font-sans">

    <?php 
        $role = session()->get('role'); 
        $uri  = uri_string();
    ?>

    <!-- PERBAIKAN 1: Gunakan flex-row (default) bukan flex-col -->
    <div class="flex h-screen overflow-hidden">
        
        <!-- ================= SIDEBAR ================= -->
        <!-- PERBAIKAN 2: Tambahkan overflow-y-auto agar jika menu sidebar panjang, hanya sidebar yang scroll -->
        <aside class="w-64 h-full bg-gray-900 text-gray-300 flex flex-col justify-between p-4 shrink-0 shadow-lg overflow-y-auto">
            
            <div>
                <!-- Brand / Logo Header -->
                <a href="<?= base_url('dashboard') ?>" class="flex items-center gap-3 px-2 py-2 text-white no-underline hover:opacity-90 transition-opacity">
                    <div class="w-9 h-9 rounded-lg bg-blue-600/20 border border-blue-500/30 flex items-center justify-center text-blue-500 text-xl">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div>
                        <h1 class="text-base font-bold tracking-wide text-white leading-tight">Sistem Aset</h1>
                        <p class="text-[10px] text-gray-400">Management & Monitoring</p>
                    </div>
                </a>

                <div class="h-px bg-gray-800 my-4"></div>

                <!-- Navigasi Menu -->
                <nav class="space-y-1">
                    
                    <!-- MENU UMUM -->
                    <div class="px-3 pb-1 text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                        Main Menu
                    </div>

                    <!-- Dashboard -->
                    <?php $isDashboard = ($uri === 'dashboard' || $uri === '' || $uri === '/'); ?>
                    <a href="<?= base_url('dashboard') ?>" 
                       class="flex items-center px-3 py-2 text-xs font-semibold rounded-lg transition-all duration-150 <?= $isDashboard ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                        <i class="bi bi-speedometer2 me-2.5 text-base <?= $isDashboard ? 'text-white' : 'text-gray-400' ?>"></i> 
                        Dashboard
                    </a>

                    <!-- Daftar Aset -->
                    <?php $isAsset = (strpos($uri, 'asset') === 0); ?>
                    <a href="<?= base_url('asset') ?>" 
                       class="flex items-center px-3 py-2 text-xs font-semibold rounded-lg transition-all duration-150 <?= $isAsset ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                        <i class="bi bi-layers me-2.5 text-base <?= $isAsset ? 'text-white' : 'text-gray-400' ?>"></i> 
                        Daftar Aset
                    </a>

                    <!-- KHUSUS ROLE ADMIN -->
                    <?php if ($role === 'admin') : ?>
                        <div class="px-3 pt-5 pb-1 text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                            Master Data
                        </div>

                        <!-- Master Kategori -->
                        <?php $isCatActive = (strpos($uri, 'master/categories') !== false); ?>
                        <a href="<?= base_url('master/categories') ?>" 
                           class="flex items-center px-3 py-2 text-xs font-semibold rounded-lg transition-all duration-150 <?= $isCatActive ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-tags me-2.5 text-base <?= $isCatActive ? 'text-white' : 'text-gray-400' ?>"></i> 
                            Master Kategori
                        </a>

                        <!-- Master Komponen -->
                        <?php $isCompActive = (strpos($uri, 'master/components') !== false); ?>
                        <a href="<?= base_url('master/components') ?>" 
                           class="flex items-center px-3 py-2 text-xs font-semibold rounded-lg transition-all duration-150 <?= $isCompActive ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-cpu me-2.5 text-base <?= $isCompActive ? 'text-white' : 'text-gray-400' ?>"></i> 
                            Master Komponen
                        </a>

                        <div class="px-3 pt-5 pb-1 text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                            Pengaturan
                        </div>

                        <!-- User Management -->
                        <?php $isUserActive = (strpos($uri, 'users') !== false); ?>
                        <a href="<?= base_url('users') ?>" 
                           class="flex items-center px-3 py-2 text-xs font-semibold rounded-lg transition-all duration-150 <?= $isUserActive ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-people me-2.5 text-base <?= $isUserActive ? 'text-white' : 'text-gray-400' ?>"></i> 
                            Management User
                        </a>
                    <?php endif; ?>

                </nav>
            </div>

            <!-- User Profile Footer & Logout Popover -->
            <div x-data="{ open: false }" class="relative pt-3 border-t border-gray-800">
                
                <button @click="open = !open" type="button" class="w-full flex items-center justify-between p-2 rounded-lg hover:bg-gray-800 transition-colors focus:outline-none group">
                    <div class="flex items-center gap-2.5 overflow-hidden">
                        <div class="w-8 h-8 rounded-full bg-gray-800 border border-gray-700 flex items-center justify-center text-gray-300 shrink-0 group-hover:border-gray-600">
                            <i class="bi bi-person text-lg"></i>
                        </div>
                        <div class="flex flex-col text-left truncate">
                            <span class="text-xs font-semibold text-white truncate">
                                <?= esc(session()->get('nama') ?? session()->get('username') ?? 'Pengguna') ?>
                            </span>
                            <span class="text-[10px] text-gray-400 capitalize">
                                <?= esc($role ?? 'Guest') ?>
                            </span>
                        </div>
                    </div>
                    <i class="bi bi-chevron-expand text-xs text-gray-400 shrink-0"></i>
                </button>

                <!-- Dropdown Popover Sign Out -->
                <div x-show="open" 
                     @click.outside="open = false" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute bottom-full left-0 mb-2 w-full bg-gray-800 border border-gray-700 rounded-lg shadow-xl overflow-hidden z-50">
                    
                    <form action="<?= base_url('logout') ?>" method="POST" id="logout-form">
                        <?= csrf_field() ?>
                        <button type="submit" class="w-full flex items-center px-3.5 py-2.5 text-xs font-semibold text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition-colors">
                            <i class="bi bi-box-arrow-right me-2 text-sm"></i> Sign out
                        </button>
                    </form>

                </div>
            </div>

        </aside>

        <!-- ================= CONTENT UTAMA ================= -->
        <!-- PERBAIKAN 3: Pastikan h-full dan overflow-y-auto ada di <main> -->
        <main class="flex-1 h-full p-6 lg:p-8 overflow-y-auto">
            <?= $this->renderSection('content') ?>
        </main>

    </div>

    <?= $this->renderSection('scripts') ?>

</body>
</html>