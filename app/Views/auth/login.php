<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="min-h-screen bg-gray-950 font-sans antialiased">

    <!-- Floating Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-[100] flex flex-col items-end gap-3 max-w-sm w-[calc(100%-2rem)] pointer-events-none"></div>

    <?php
    $flashToasts = [];
    if ($m = session()->getFlashdata('msg')) {
        $flashToasts[] = ['type' => 'danger', 'text' => $m];
    }
    ?>

    <div class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

        <!-- Dekorasi blur -->
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-600/30 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-40 -right-40 w-[28rem] h-[28rem] bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/3 right-1/4 w-56 h-56 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative w-full max-w-md">
            <!-- Card Login -->
            <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/40 border border-white/40 overflow-hidden">
                <div class="p-8 sm:p-10">
                    <!-- Brand -->
                    <div class="flex justify-center mb-8">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg shadow-blue-500/30 flex items-center justify-center text-white text-2xl">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="text-center mb-8">
                        <h4 class="text-2xl font-bold text-gray-900 tracking-tight">Login Sistem Aset</h4>
                        <p class="text-xs text-gray-500 mt-1.5">Masukkan kredensial Anda untuk masuk</p>
                    </div>

                    <!-- Form -->
                    <form action="<?= base_url('login/process') ?>" method="post" class="space-y-4">
                        <?= csrf_field() ?>

                        <!-- Input Email -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Email</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email"
                                       name="email"
                                       class="w-full pl-10 pr-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400"
                                       required
                                       placeholder="admin@mail.com">
                            </div>
                        </div>

                        <!-- Input Password -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Password</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                                    <i class="bi bi-shield-lock"></i>
                                </span>
                                <input type="password"
                                       name="password"
                                       class="w-full pl-10 pr-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                       required>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-3">
                            <button type="submit"
                                    class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 active:scale-[0.99] text-white font-semibold text-sm rounded-xl shadow-lg shadow-blue-600/30 transition-all duration-150 flex items-center justify-center gap-2">
                                <i class="bi bi-box-arrow-in-right"></i> Masuk Sekarang
                            </button>
                        </div>
                    </form>
                </div>

                <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 text-center">
                    <p class="text-[11px] text-gray-400">Sistem Manajemen Aset &copy; <?= date('Y') ?></p>
                </div>
            </div>
        </div>
    </div>

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

</body>
</html>