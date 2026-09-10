<?php
/**
 * @var array $category
 * @var array $allComponents
 * @var array $activeCompIds
 * @var array $requiredCompIds
 */
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-5xl mx-auto">

    <!-- Card Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">

        <!-- Card Header -->
        <div class="px-6 py-5 bg-gradient-to-r from-amber-500 to-orange-500 flex items-center justify-between">
            <h2 class="text-base font-bold text-white flex items-center gap-2.5">
                <span class="w-9 h-9 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center text-lg"><i class="bi bi-pencil-square"></i></span>
                Edit Kategori Aset
            </h2>
            <a href="<?= base_url('master/categories') ?>" class="inline-flex items-center px-3 py-2 bg-white/10 hover:bg-white/20 text-white text-xs font-medium rounded-lg transition-colors border border-white/20">
                <i class="bi bi-arrow-left me-1.5"></i> Kembali
            </a>
        </div>

        <!-- Card Body -->
        <div class="p-6 sm:p-8">
            <form action="<?= base_url('master/categories/update/' . $category['id']) ?>" method="POST">
                <?= csrf_field() ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Nama Kategori Aset <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nama_kategori"
                               value="<?= esc($category['nama_kategori']) ?>"
                               class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:bg-white transition-all outline-none"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Keterangan
                        </label>
                        <textarea name="keterangan"
                                  rows="1"
                                  class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:bg-white transition-all outline-none resize-none"><?= esc($category['keterangan'] ?? '') ?></textarea>
                    </div>
                </div>

                <hr class="border-gray-200 my-6">

                <!-- Section Subheading -->
                <div class="flex items-center gap-2.5 mb-4 text-amber-700 font-semibold text-sm sm:text-base">
                    <span class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center text-sm"><i class="bi bi-sliders"></i></span>
                    <span>Atur Komponen/Atribut untuk Kategori Ini:</span>
                </div>

                <!-- Table Checklist Komponen -->
                <div class="bg-white rounded-xl border border-gray-200/80 overflow-hidden mb-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50/80 text-gray-500 uppercase text-[11px] tracking-wider border-b border-gray-200">
                                <tr>
                                    <th scope="col" class="px-4 py-3.5 w-16 text-center">Pilih</th>
                                    <th scope="col" class="px-5 py-3.5">Nama Komponen</th>
                                    <th scope="col" class="px-5 py-3.5">Tipe Input</th>
                                    <th scope="col" class="px-5 py-3.5 text-center w-36">Wajib Diisi?</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                <?php if (!empty($allComponents)) : ?>
                                    <?php foreach ($allComponents as $comp) : ?>
                                        <?php
                                            $isChecked = in_array($comp['id'], $activeCompIds);
                                            $isRequired = in_array($comp['id'], $requiredCompIds);
                                        ?>
                                        <tr class="hover:bg-amber-50/40 transition-colors">
                                            <td class="px-4 py-4 text-center">
                                                <input type="checkbox"
                                                       name="components[]"
                                                       value="<?= $comp['id'] ?>"
                                                       class="w-4 h-4 text-amber-600 bg-gray-100 border-gray-300 rounded focus:ring-amber-500 focus:ring-2 cursor-pointer"
                                                       <?= $isChecked ? 'checked' : '' ?>>
                                            </td>
                                            <td class="px-5 py-4 font-medium text-gray-900">
                                                <?= esc($comp['nama_komponen']) ?>
                                            </td>
                                            <td class="px-5 py-4">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100 capitalize">
                                                    <?= esc($comp['tipe_input']) ?>
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 text-center">
                                                <input type="checkbox"
                                                       name="required[]"
                                                       value="<?= $comp['id'] ?>"
                                                       class="w-4 h-4 text-amber-600 bg-gray-100 border-gray-300 rounded focus:ring-amber-500 focus:ring-2 cursor-pointer"
                                                       <?= $isRequired ? 'checked' : '' ?>>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">
                                            Belum ada master komponen. Tambahkan komponen terlebih dahulu.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-3.5 px-4 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold text-sm rounded-xl shadow-md shadow-amber-600/20 transition-all duration-150 active:scale-[0.99] flex items-center justify-center gap-2">
                    <i class="bi bi-pencil-square"></i> Update Master Kategori
                </button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>