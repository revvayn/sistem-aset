<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Detail Aset') ?></title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body class="bg-gray-50 text-gray-800 p-6">
    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
        <div class="px-6 py-5 bg-gradient-to-r from-violet-600 to-indigo-600 text-white">
            <h1 class="text-lg font-bold">Detail Aset (Hasil Scan QR)</h1>
            <p class="text-xs text-white/80">Informasi diambil dari database sistem.</p>
        </div>
        <div class="p-6">
            <?php
            $specs = $asset['specifications'] ?? [];
            if (is_string($specs)) {
                $specs = json_decode($specs, true) ?? [];
            }
            ?>
            <table class="w-full text-sm">
                <tbody class="divide-y divide-gray-100">
                    <tr><th class="bg-gray-50 px-4 py-3 text-left w-1/3">ID Aset</th><td class="px-4 py-3"><?= esc($asset['id']) ?></td></tr>
                    <tr><th class="bg-gray-50 px-4 py-3 text-left">Kategori</th><td class="px-4 py-3"><?= esc($asset['nama_kategori'] ?? '-') ?></td></tr>
                    <tr><th class="bg-gray-50 px-4 py-3 text-left">Status</th><td class="px-4 py-3"><?= esc($asset['status'] ?? '-') ?></td></tr>
                    <tr><th class="bg-gray-50 px-4 py-3 text-left">Nomor Seri</th><td class="px-4 py-3"><?= esc($asset['serial_number'] ?? '-') ?></td></tr>
                    <tr><th class="bg-gray-50 px-4 py-3 text-left">Lokasi</th><td class="px-4 py-3"><?= esc($asset['location'] ?? '-') ?></td></tr>
                    <tr><th class="bg-gray-50 px-4 py-3 text-left">Departemen</th><td class="px-4 py-3"><?= esc($asset['department'] ?? '-') ?></td></tr>
                    <tr><th class="bg-gray-50 px-4 py-3 text-left">Tgl Pembelian</th><td class="px-4 py-3"><?= esc($asset['purchase_date'] ?? '-') ?></td></tr>
                    <tr><th class="bg-gray-50 px-4 py-3 text-left">Garansi Hingga</th><td class="px-4 py-3"><?= esc($asset['warranty_expiry'] ?? '-') ?></td></tr>
                </tbody>
            </table>

            <h3 class="text-sm font-bold text-gray-900 mt-6 mb-2">Spesifikasi</h3>
            <table class="w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
                <tbody class="divide-y divide-gray-100">
                    <?php if (!empty($specs)) : ?>
                        <?php foreach ($specs as $k => $v) : ?>
                            <?php if (is_string($v) && preg_match('/\.(jpg|jpeg|png|webp|gif|pdf|doc|docx)$/i', $v)) continue; ?>
                            <tr>
                                <th class="bg-gray-50 px-4 py-2 text-left w-1/3"><?= esc(strtoupper(str_replace('_', ' ', $k))) ?></th>
                                <td class="px-4 py-2"><?= esc($v) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="2" class="px-4 py-3 text-center text-gray-400">Tidak ada spesifikasi.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>