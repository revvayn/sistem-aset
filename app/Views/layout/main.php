<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">

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

<body class="h-full text-gray-800 antialiased font-sans" x-data="{ sidebarOpen: false }">

    <?php
        $role = session()->get('role');
        $uri  = uri_string();
        $isAdmin = ($role === 'admin');
        $canEdit = in_array(strtolower($role ?? ''), ['admin', 'staff']);
    ?>

    <div class="flex h-screen overflow-hidden">

        <!-- ================= SIDEBAR ================= -->
        <!-- Overlay Mobile -->
        <div x-show="sidebarOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 lg:hidden"></div>

        <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-gray-900 text-gray-300 flex flex-col justify-between overflow-y-auto transition-transform duration-300 lg:static lg:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

            <!-- Brand / Logo Header -->
            <div class="p-5">
                <a href="<?= base_url('dashboard') ?>" class="flex items-center gap-3 rounded-xl px-2 py-2 text-white no-underline hover:opacity-90 transition-opacity">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg shadow-blue-900/40 flex items-center justify-center text-white text-xl shrink-0">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div>
                        <h1 class="text-sm font-bold tracking-wide text-white leading-tight">Sistem Aset</h1>
                        <p class="text-[10px] text-gray-400">Management & Monitoring</p>
                    </div>
                </a>

                <div class="h-px bg-gradient-to-r from-transparent via-gray-700 to-transparent my-5"></div>

                <!-- Navigasi Menu -->
                <nav class="space-y-1">
                    <div class="px-3 pb-2 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Menu Utama</div>

                    <?php $isDashboard = ($uri === 'dashboard' || $uri === '' || $uri === '/'); ?>
                    <a href="<?= base_url('dashboard') ?>"
                       class="group flex items-center gap-3 px-3 py-2.5 text-[13px] font-semibold rounded-xl transition-all duration-150 <?= $isDashboard ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-900/30' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white' ?>">
                        <i class="bi bi-speedometer2 text-base w-5 text-center <?= $isDashboard ? 'text-white' : 'text-gray-500 group-hover:text-white' ?>"></i>
                        Dashboard
                    </a>

                    <?php $isAsset = (strpos($uri, 'asset') === 0); ?>
                    <a href="<?= base_url('asset') ?>"
                       class="group flex items-center gap-3 px-3 py-2.5 text-[13px] font-semibold rounded-xl transition-all duration-150 <?= $isAsset ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-900/30' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white' ?>">
                        <i class="bi bi-layers text-base w-5 text-center <?= $isAsset ? 'text-white' : 'text-gray-500 group-hover:text-white' ?>"></i>
                        Daftar Aset
                    </a>

                    <?php if ($isAdmin) : ?>
                        <div class="px-3 pt-6 pb-2 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Master Data</div>

                        <?php $isCatActive = (strpos($uri, 'master/categories') !== false); ?>
                        <a href="<?= base_url('master/categories') ?>"
                           class="group flex items-center gap-3 px-3 py-2.5 text-[13px] font-semibold rounded-xl transition-all duration-150 <?= $isCatActive ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-900/30' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white' ?>">
                            <i class="bi bi-tags text-base w-5 text-center <?= $isCatActive ? 'text-white' : 'text-gray-500 group-hover:text-white' ?>"></i>
                            Master Kategori
                        </a>

                        <?php $isCompActive = (strpos($uri, 'master/components') !== false); ?>
                        <a href="<?= base_url('master/components') ?>"
                           class="group flex items-center gap-3 px-3 py-2.5 text-[13px] font-semibold rounded-xl transition-all duration-150 <?= $isCompActive ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-900/30' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white' ?>">
                            <i class="bi bi-cpu text-base w-5 text-center <?= $isCompActive ? 'text-white' : 'text-gray-500 group-hover:text-white' ?>"></i>
                            Master Komponen
                        </a>

                        <div class="px-3 pt-6 pb-2 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Pengaturan</div>

                        <?php $isUserActive = (strpos($uri, 'users') !== false); ?>
                        <a href="<?= base_url('users') ?>"
                           class="group flex items-center gap-3 px-3 py-2.5 text-[13px] font-semibold rounded-xl transition-all duration-150 <?= $isUserActive ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-900/30' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white' ?>">
                            <i class="bi bi-people text-base w-5 text-center <?= $isUserActive ? 'text-white' : 'text-gray-500 group-hover:text-white' ?>"></i>
                            Management User
                        </a>
                    <?php endif; ?>
                </nav>
            </div>

            <!-- User Profile Footer & Logout Popover -->
            <div x-data="{ open: false }" class="relative p-4 border-t border-gray-800/80">
                <button @click="open = !open" type="button"
                        class="w-full flex items-center justify-between gap-2 p-2.5 rounded-xl bg-gray-800/40 border border-gray-800 hover:bg-gray-800 transition-colors focus:outline-none group">
                    <div class="flex items-center gap-2.5 overflow-hidden">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-gray-700 to-gray-800 border border-gray-600 flex items-center justify-center text-gray-300 shrink-0 group-hover:border-gray-500">
                            <i class="bi bi-person text-lg"></i>
                        </div>
                        <div class="flex flex-col text-left truncate">
                            <span class="text-xs font-semibold text-white truncate">
                                <?= esc(session()->get('nama') ?? session()->get('username') ?? 'Pengguna') ?>
                            </span>
                            <span class="text-[10px] text-gray-500 capitalize">
                                <?= esc($role ?? 'Guest') ?>
                            </span>
                        </div>
                    </div>
                    <i class="bi bi-chevron-down text-xs text-gray-500 shrink-0 transition-transform duration-150" :class="open ? 'rotate-180' : ''"></i>
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
                     class="absolute bottom-full left-4 right-4 mb-2 bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden z-50">
                    <form action="<?= base_url('logout') ?>" method="POST" id="logout-form">
                        <?= csrf_field() ?>
                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-3 text-xs font-semibold text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition-colors">
                            <i class="bi bi-box-arrow-right text-sm"></i> Sign out
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- ================= CONTENT UTAMA ================= -->
        <div class="flex-1 flex flex-col min-w-0 h-full">

            <!-- Topbar -->
            <header class="h-16 shrink-0 bg-white/80 backdrop-blur border-b border-gray-200 flex items-center gap-4 px-4 lg:px-8 sticky top-0 z-30">
                <button type="button"
                        @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden w-10 h-10 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-100 flex items-center justify-center transition-colors">
                    <i class="bi bi-list text-xl"></i>
                </button>

                <div class="flex-1 min-w-0">
                    <h2 class="text-sm lg:text-base font-bold text-gray-900 truncate"><?= esc($title ?? 'Sistem Aset') ?></h2>
                    <p class="text-[11px] text-gray-400 capitalize hidden sm:block">
                        <i class="bi bi-house-door me-1"></i><?= esc(str_replace('/', ' / ', $uri ?: 'dashboard')) ?>
                    </p>
                </div>

                <div class="hidden sm:flex items-center gap-2 text-xs font-medium text-gray-500 bg-gray-100 border border-gray-200 px-3.5 py-2 rounded-lg">
                    <i class="bi bi-calendar3 text-blue-600"></i> <?= date('d M Y') ?>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-8">
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <!-- Floating Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-[100] flex flex-col items-end gap-3 max-w-sm w-[calc(100%-2rem)] pointer-events-none"></div>

    <?php
    $flashToasts = [];
    if ($m = session()->getFlashdata('message')) {
        $flashToasts[] = ['type' => 'success', 'text' => $m];
    }
    if ($e = session()->getFlashdata('error')) {
        $flashToasts[] = ['type' => 'danger', 'text' => $e];
    }
    if ($m = session()->getFlashdata('msg')) {
        $flashToasts[] = ['type' => 'danger', 'text' => $m];
    }
    $validationErrors = session()->getFlashdata('errors');
    if (is_array($validationErrors)) {
        foreach ($validationErrors as $err) {
            $flashToasts[] = ['type' => 'danger', 'text' => $err];
        }
    }
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('toast-container');
            const toasts    = <?= json_encode($flashToasts, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>;
            if (!container || !toasts.length) return;

            const configs = {
                success: { icon: 'bi-check-circle-fill',        color: 'text-emerald-600', bar: 'bg-emerald-500' },
                danger:  { icon: 'bi-exclamation-triangle-fill', color: 'text-rose-600',   bar: 'bg-rose-500'   },
                warning: { icon: 'bi-exclamation-circle-fill', color: 'text-amber-600',   bar: 'bg-amber-400'  },
                info:    { icon: 'bi-info-circle-fill',        color: 'text-blue-600',    bar: 'bg-blue-500'   }
            };

            function dismiss(toast) {
                if (!toast) return;
                toast.classList.add('toast-closing');
                setTimeout(function () { toast.remove(); }, 300);
            }

            toasts.forEach(function (t) {
                const cfg   = configs[t.type] || configs.info;
                const toast = document.createElement('div');
                toast.className = 'toast-item';
                toast.innerHTML =
                    '<div class="w-1 self-stretch shrink-0 ' + cfg.bar + '"></div>' +
                    '<div class="p-3.5 flex items-start gap-3 flex-1">' +
                        '<span class="text-lg ' + cfg.color + '"><i class="bi ' + cfg.icon + '"></i></span>' +
                        '<p class="flex-1 text-xs font-medium text-gray-700 leading-relaxed">' + t.text + '</p>' +
                        '<button type="button" class="toast-close flex-none -m-1 p-1 text-gray-400 hover:text-gray-700 transition-colors text-sm" aria-label="Tutup">&times;</button>' +
                    '</div>';
                container.appendChild(toast);

                requestAnimationFrame(function () { toast.classList.add('toast-visible'); });

                const timer = setTimeout(function () { dismiss(toast); }, t.type === 'danger' ? 7000 : 4000);
                toast.querySelector('.toast-close').addEventListener('click', function () {
                    clearTimeout(timer);
                    dismiss(toast);
                });
            });
        });
    </script>

    <?= $this->renderSection('scripts') ?>

</body>
</html>