# AGENTS.md

Panduan untuk agen/AI dan developer yang bekerja pada proyek **Sistem Manajemen Aset**
(CodeIgniter 4.7.x + Tailwind CSS v4). Baca ini sebelum mengubah kode.

## Ringkasan Proyek

Aplikasi manajemen aset dengan spesifikasi JSON dinamis per kategori, autentikasi
ber-role (`admin`, `staff`, `viewer`), dan dashboard statistik. Repo di
`D:\PROJEK\sistem-aset` (Windows), dikerjakan dengan PowerShell 5.1.

## Environment & Tooling

- **PHP**: 8.2+; CLI `php` tersedia di PATH.
- **MySQL**: via XAMPP — `C:\xampp\mysql\bin\mysql.exe`, user `root` tanpa password,
  database `sistem_aset`.
- **Frontend**: Tailwind v4 CLI. Source `src/input.css` → output
  `public/css/style.css`. `@source "../app/Views/**/*.php"` — class di views
  dideteksi otomatis, tapi WAJIB run build agar masuk ke CSS.
- **Framework**: CodeIgniter 4.7.x. Auto-routing **OFF** — semua endpoint harus
  terdaftar di `app/Config/Routes.php`.

## Perintah Penting

```bash
# Lint satu file (verifikasi syntax tiap file yang diubah)
php -l app/Controllers/Asset.php

# Build Tailwind (SKIP dilarang setelah ubah class di views)
npm run build        # minified
npm run dev          # watch

# Database migration
php spark migrate
php spark migrate:status

# Jalanin server
php spark serve
```

> PowerShell: backtick `` ` `` adalah escape char. Jangan pakai tanda backtick di
> dalam string argumen `mysql -e` (sudah sering merusak query) — lebih aman tulis
> SQL ke file temp lalu `& "C:\xampp\mysql\bin\mysql.exe" -u root sistem_aset < file`.

## Arsitektur & Konvensi

- **Controllers** di `app/Controllers`, langsung memakai model (bukan `$this->model`).
- **Models** di `app/Models`: `AssetModel`, `ComponentModel`, `MasterDataModel`,
  `MasterDataComponentModel`, `UserModel`.
- **Views** di `app/Views`, semua extend `layout/main` kecuali `auth/login`.
- Semua form POST wajib `<?= csrf_field() ?>` (CSRF global aktif).
- Flash data terpusat di layout sebagai **floating toast** — JANGAN pasang blok
  alert inline sendiri di view (akan dobel). Keys: `message` (sukses, hijau),
  `error`/`msg` (gagal, merah), `errors` (array validasi → toast per-item).
- Status aset valid: `Aktif`, `Perbaikan`, `Rusak`, `Disimpan`. Label "Non-Aktif"
  tidak dipakai — gunakan `Disimpan`. (Penting untuk konsistensi dengan ENUM DB.)
- Tidak ada `no_aset` / `nama_aset` di schema & alur aset — keduanya sudah dihapus
  (migration `2026-09-10-120000_DropAssetCodeColumns.php`). Pencarian aset bekerja
  pada kolom `assets.specifications` (teks JSON).
- Kode tanpa komentar berlebihan; pakai bahasa Indonesia untuk UI flash/error dan
  komentar mengikuti gaya file sekitar.

## Skema Database (ringkas)

- `users` — `id`, `nama`, `email`, `password` (hash bcrypt), `role` ENUM
  (`admin`/`staff`/`viewer`).
- `master_data` — kategori aset (`nama_kategori`, `keterangan`).
- `components` — `nama_komponen`, `key_komponen` (unique, slug), `tipe_input` ENUM
  (`text`,`number`,`password`,`date`,`file`,`qr_code`).
- `master_data_components` — relasi kategori↔komponen + `is_required`.
- `assets` — `master_data_id` (FK RESTRICT), `user_id` (FK SET NULL), `status` ENUM,
  `specifications` JSON, timestamp + `deleted_at` (soft delete).

Model `AssetModel` memakai `$useSoftDeletes = true` — query default otomatis
menyaring `deleted_at IS NULL`. Jangan lupa tambahkan `where('deleted_at', null)`
eksplisit bila query memakai `$db->table()` langsung.

## Perubahan Penting yang Sudah Dilakukan (Dokumentasi Sesi)

Berikut keseluruhan perbaikan yang sudah diteken di sesi kerja terakhir:

1. **Keamanan login** — `Auth::processLogin` memakai `password_verify` (bukan
   `sha1`/plain). Method `Auth::generate()` dan route `generate-admin` dihapus.
2. **CSRF global** — diaktifkan via `app/Config/Filters.php` (`globals.before`).
3. **Hapus lewat POST** — route delete asset/kategori/komponen/user diubah
   GET→POST dan semua tombol hapus berbentuk form `POST` + `csrf_field()`.
4. **Validasi server-side aset** (controller, bukan cuma frontend): `master_data_id`
   required|integer, `status` `in_list[Aktif,Perbaikan,Rusak,Disimpan]`, dan helper
   `validateSpecFile()` (ekstensi jpg/jpeg/png/webp/gif/pdf/doc/docx, maks 5MB).
5. **Kolom aset diganti** — `no_aset` dan `nama_aset` dihapus dari seluruh alur
   (controller, model, views, dashboard, database.sql, migration). Kategori sebagai
   identitas utama; spesifikasi dinamis di JSON.
6. **Floating toast** — container + emission PHP + JS di `layout/main.php` dan
   `auth/login.php`; CSS `.toast-item` di `src/input.css` (`@layer components`).
   Semua blok alert inline lama dihapus dari view (aset, master, user).
7. **Draf Keranjang dashboard dihapus** — blok statis (2 item hardcoded + form ke
   route `asset/process-cart` yang tidak ada) dihilangkan sampai fitur cart
   dibangun sungguhan. Jangan hidupkan ulang tanpa implementasi route `asset/process-cart`.
8. **Tipe `qr_code`** — kini diizinkan di whitelist `MasterComponent::store()` dan
   `update()` (memperbaiki inkonsistensi dengan ENUM DB + option dropdown view).
   Di form aset nilainya disimpan sebagai teks biasa (branch `else`), bukan file.
9. **Pesan hapus kategori akurat** — `MasterCategory::delete` memakai
   `countAllResults()` dan menampilkan jumlah unit aset, bukan klaim statis
   "beberapa unit".
10. **Perbaikan N+1** — `MasterCategory::index` memakai satu query batch
    `ComponentModel::getComponentsByMasterDataIds()` (dikelompokkan per kategori).
    Pakai metode ini jika menampilkan komponen untuk banyak kategori sekaligus.
11. **`user_id` diisi saat insert aset** — `Asset::store()` sekarang menyimpan
    `user_id` dari `session()->get('id')` (user yang login). Join `users.nama`
    di `AssetModel::getAssetWithDetails()` menjadi bermakna.

## Session Keys

Setelah login, session menyimpan: `id`, `nama`, `email`, `role`, `isLoggedIn`.
Gunakan `session()->get('id')` untuk user_id saat insert, `session()->get('role')`
untuk cek akses.

## Catatan Lain

- HTTPS via `forcehttps` di `required` filter bisa menyulitkan dev lokal — matikan
  lewat `.env` bila perlu.
- Default login: `admin@mail.com` / `admin123` (seed di `database.sql`). Password
  sudah di-hash bcrypt; jangan tulis plaintext langsung ke DB.
- Upload spesifikasi tersimpan di `public/uploads/specs` dengan `getRandomName()`.
  Saat update/hapus aset, file lama dihapus via `unlink` jika ada.
- Kategori menjadi parent identitas aset; saat mengubah `master_data_id` aset,
  komponen form & JSON spesifikasi lama diperlakukan sesuai komponen kategori baru.