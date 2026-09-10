# Sistem Manajemen Aset (Asset Management System)

Aplikasi web untuk mengelola data aset/inventaris berbasis **CodeIgniter 4** dengan
spesifikasi dinamis per kategori aset dan manajemen pengguna ber-role.

## Fitur

- **Autentikasi & RBAC** — login berbasis hash password (`password_verify`), 3 role:
  `admin`, `staff`, `viewer`.
- **Dashboard** — statistik total aset, kategori, komponen, status perbaikan/rusak,
  daftar aset terbaru, dan distribusi aset per kategori.
- **Aset dinamis** — spesifikasi aset disimpan sebagai JSON lengkap sesuai komponen
  yang terpasang pada kategori asetnya (contoh: Merk, IMEI, Serial Number, File Garansi).
- **Master Kategori Aset** — kategori + komponen/atribut spesifikasi yang terhubung
  (bisa ditandai *wajib*).
- **Master Komponen** — atribut spesifikasi dengan tipe input: `text`, `number`,
  `password`, `date`, `file` (upload), dan `qr_code`.
- **Upload file spesifikasi** — gambar/dokumen (jpg, jpeg, png, webp, gif, pdf, doc,
  docx; maks. 5MB) disimpan di `public/uploads/specs`.
- **Pencarian & filter aset** — cari keyword di isi spesifikasi (JSON) dan filter
  berdasarkan kategori.
- **Soft delete** — data aset yang dihapus tetap tersimpan di database (`deleted_at`).
- **Floating toast** — notifikasi sukses/error ditampilkan sebagai toast di pojok
  kanan atas, terpusat di layout.

## Tech Stack

| Komponen   | Teknologi                                        |
| ---------- | ------------------------------------------------ |
| Backend    | PHP 8.2+, CodeIgniter 4.7.x                      |
| Database   | MySQL / MariaDB (via XAMPP)                      |
| Frontend   | Tailwind CSS v4, Bootstrap Icons, Satoshi font   |
| Auth       | Session + CSRF global + filter `auth` / `role`   |

## Struktur Database

| Tabel                    | Keterangan                                      |
| ------------------------ | ----------------------------------------------- |
| `users`                  | Pengguna sistem (admin, staff, viewer)          |
| `master_data`            | Kategori aset                                   |
| `components`             | Master atribut/komponen spesifikasi             |
| `master_data_components` | Relasi kategori ↔ komponen (+ tanda `is_required`) |
| `assets`                 | Unit aset fisik (status, spesifikasi JSON, user_id) |

Skema lengkap terdapat pada migration:
`app/Database/Migrations/2026-07-24-020503_CreateAssetManagementTables.php` dan
`2026-09-10-120000_DropAssetCodeColumns.php` (menghapus kolom `no_aset` & `nama_aset`).

## Menjalankan Aplikasi

### 1. Prasyarat

- PHP 8.2+ dengan ekstensi `intl`, `mbstring`, `mysqlnd`, `json`.
- Composer.
- Node.js + npm (untuk build Tailwind).
- MySQL / MariaDB (disarankan XAMPP).

### 2. Setup

```bash
# Install dependency PHP
composer install

# Copy env lalu sesuaikan baseURL dan database
cp env .env

# Install & build Tailwind
npm install
npm run build
```

### 3. Database

```bash
# Cara A: import langsung (jika ada file database.sql fresh)
# atau buat database kosong lalu jalankan migration:
php spark migrate
```

> Jika memakai `database.sql`, tabel `migrations` perlu di-sinkron-kan terlebih
> dahulu (insert baris baseline migration `CreateAssetManagementTables`) sebelum
> menjalankan migration drop kolom agar status tidak ganda.

### 4. Jalankan server

```bash
php spark serve
```

Akses aplikasi di `http://localhost:8080`.

### 5. Build Tailwind (saat developer)

```bash
npm run dev    # watch mode
npm run build  # build minified ke public/css/style.css
```

**Penting:** setiap menambah/mengubah class Tailwind di views, jalankan `npm run build`
(atau `npm run dev`) karena `public/css/style.css` dihasilkan dari `src/input.css`.

## Akun Default

| Email           | Password  | Role  |
| --------------- | --------- | ----- |
| `admin@mail.com` | `admin123` | admin |

Password disimpan terenkripsi (bcrypt) — jangan ubah lewat SQL tanpa `password_hash`.

## Rute Utama

| Method  | URI                     | Akses          | Fungsi                     |
| ------- | ----------------------- | -------------- | -------------------------- |
| GET     | `/` , `/dashboard`      | semua role     | Dashboard                  |
| GET     | `/asset`                | semua role     | Daftar & cari aset         |
| GET     | `/asset/create`         | admin, staff   | Form tambah aset           |
| POST    | `/asset/store`          | admin, staff   | Simpan aset baru           |
| GET     | `/asset/edit/(:num)`    | admin, staff   | Form edit aset             |
| POST    | `/asset/update/(:num)`  | admin, staff   | Update aset                |
| GET     | `/asset/get-components/(:num)` | semua role | AJAX komponen per kategori |
| POST    | `/asset/delete/(:num)`  | admin          | Hapus aset (soft delete)   |
| GET/POST| `/master/categories*`   | admin          | Kelola kategori aset       |
| GET/POST| `/master/components*`   | admin          | Kelola komponen spesifikasi|
| GET/POST| `/users*`               | admin          | Kelola pengguna            |

Semua rute selain login/logout dilindungi filter `auth`; fungsi tulis dibatasi
filter `role` (`admin`, `admin,staff`).

## Keamanan

- **CSRF global** aktif untuk semua request (`required` filter `csrf`); semua form
  POST wajib membawa `csrf_field()`.
- **Auto-routing OFF** — endpoint hanya yang terdaftar di `app/Config/Routes.php`.
- Operasi hapus memakai **POST** (bukan GET) + CSRF.
- Validasi tipe input `master_data_id`, `status`, dan ekstensi/ukuran file dilakukan
  **server-side** di `AssetController::store()`/`update()`.
- Flash data ditampilkan sebagai **floating toast** (key: `message` = sukses,
  `error`/`msg` = gagal, `errors` = error validasi).

## Pengembangan

- **Controllers** → `app/Controllers` (Asset, Dashboard, Auth, MasterCategory,
  MasterComponent, UserController).
- **Models** → `app/Models` (AssetModel, ComponentModel, MasterDataModel,
  MasterDataComponentModel, UserModel).
- **Views** → `app/Views` (layout bersama di `layout/main.php`).
- **CSS** → `src/input.css` (Tailwind v4, `@source "../app/Views/**/*.php"`).