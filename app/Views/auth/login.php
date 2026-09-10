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

                    <!-- Flash Message Alert -->
                    <?php if(session()->getFlashdata('msg')):?>
                        <div class="mb-6 p-3.5 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm flex items-center gap-2.5">
                            <i class="bi bi-exclamation-circle-fill shrink-0"></i>
                            <span><?= session()->getFlashdata('msg') ?></span>
                        </div>
                    <?php endif;?>

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

</body>
</html>