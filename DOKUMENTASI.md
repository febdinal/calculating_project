# 📘 Dokumentasi Lengkap Proyek: E-Commerce Cost Calculator & Project Configurator

---

## 📑 Daftar Isi
1. [Ringkasan Eksekutif](#1-ringkasan-eksekutif)
2. [Teknologi & Lingkungan Sistem](#2-teknologi--lingkungan-sistem)
3. [Arsitektur Sistem & Struktur Direktori](#3-arsitektur-sistem--struktur-direktori)
4. [Skema Database & Relasi Entity (ERD)](#4-skema-database--relasi-entity-erd)
5. [Fitur Utama & Alur Bisnis](#5-fitur-utama--alur-bisnis)
   - [5.1 Visual Kanban Configurator](#51-visual-kanban-configurator)
   - [5.2 Sistem Dependensi Antar-Fitur](#52-sistem-dependensi-antar-fitur)
   - [5.3 Mekanisme Pembekuan Snapshot Harga (*Frozen Snapshots*)](#53-mekanisme-pembekuan-snapshot-harga-frozen-snapshots)
   - [5.4 Keamanan Data Biaya Modal (*Zero Cost Leakage*)](#54-keamanan-data-biaya-modal-zero-cost-leakage)
   - [5.5 Penerbitan Quotation & Ekspor PDF](#55-penerbitan-quotation--ekspor-pdf)
   - [5.6 Panel Admin & Master Pricing Manager](#56-panel-admin--master-pricing-manager)
6. [Formula Kalkulasi Finansial](#6-formula-kalkulasi-finansial)
7. [Daftar Rute (Route Map) & Middleware](#7-daftar-rute-route-map--middleware)
8. [Panduan Instalasi & Setup Lokal](#8-panduan-instalasi--setup-lokal)
9. [Kredensial Default & Akun Pengujian](#9-kredensial-default--akun-pengujian)
10. [Pengujian Otomatis (Automated Testing) & Standar Kode](#10-pengujian-otomatis-automated-testing--standar-kode)

---

## 1. Ringkasan Eksekutif

**E-Commerce Cost Calculator & Project Configurator** adalah aplikasi web berbasis Laravel yang dirancang untuk merencanakan anggaran teknis, menyusun konfigurasi fitur toko online secara interaktif melalui papan **Kanban Drag & Drop**, menghitung estimasi biaya sewa tahunan secara real-time, serta menerbitkan **Surat Penawaran Resmi (Quotation PDF)** berstandar profesional.

Aplikasi ini mengimplementasikan pemisahan tegas antara **Harga Jual Publik (*Selling Price*)** dan **Biaya Modal Internal (*Cost Price*)**, memastikan tidak terjadi kebocoran margin keuntungan ke sisi pengguna publik, sembari memberikan visibilitas finansial penuh bagi administrator bisnis.

---

## 2. Teknologi & Lingkungan Sistem

| Komponen | Spesifikasi / Library | Keterangan |
|---|---|---|
| **Backend Framework** | Laravel 13.x | PHP 8.3+ |
| **Database** | MySQL 8.0+ / MariaDB | Relational Database dengan Foreign Key Constraints |
| **Frontend Styling** | Tailwind CSS 4.x | Utilitas modern via `@tailwindcss/vite` |
| **Bundler & Build Tool** | Vite 8.x | HMR & Asset Optimization |
| **JavaScript Engine** | Vanilla JS / Alpine.js Patterns | Native HTML5 Drag & Drop API |
| **PDF Generator** | `barryvdh/laravel-dompdf` (DomPDF 3.1) | Render template Blade ke dokumen A4 PDF |
| **Code Style Linter** | Laravel Pint | Standar PSR-12 / Laravel Code Style |
| **Test Suite** | PHPUnit 12.x / Artisan Test | Feature & Unit Testing |

---

## 3. Arsitektur Sistem & Struktur Direktori

Aplikasi menggunakan arsitektur MVC (Model-View-Controller) dengan penambahan layer **Service** (`CalculatorService`) untuk mengisolasi logika kalkulasi finansial server-side.

```
calculating-project/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                    # Controller khusus Panel Admin
│   │   │   │   ├── AddonController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── FeatureController.php
│   │   │   │   ├── PackageController.php
│   │   │   │   ├── PricingController.php
│   │   │   │   └── ProjectController.php
│   │   │   ├── AuthController.php        # Autentikasi Login/Logout
│   │   │   ├── CalculatorController.php  # Halaman Paket & Kanban Board
│   │   │   ├── Controller.php
│   │   │   └── ProjectController.php     # Penyimpanan Proyek, Show, PDF
│   │   └── Middleware/
│   │       └── AdminOnly.php             # Proteksi Role Admin
│   ├── Models/                           # Eloquent Models & Relasi
│   │   ├── Addon.php
│   │   ├── Category.php
│   │   ├── Feature.php
│   │   ├── FeatureDependency.php
│   │   ├── FeaturePrice.php
│   │   ├── Package.php
│   │   ├── PackageFeature.php
│   │   ├── Project.php
│   │   ├── ProjectAddon.php
│   │   ├── ProjectFeature.php
│   │   ├── Quotation.php
│   │   └── User.php
│   └── Services/
│       └── CalculatorService.php         # Engine Kalkulasi Harga & Snapshot
├── database/
│   ├── migrations/                       # 14 File Migrasi Skema Database
│   └── seeders/                          # Data Seeder Default (Paket, Fitur, Harga, Add-on)
├── resources/
│   ├── css/                              # Tailwind CSS
│   ├── js/                               # Skrip Frontend
│   └── views/
│       ├── admin/                        # View Blade Panel Admin
│       ├── auth/                         # View Blade Login
│       ├── calculator/                   # View Blade Kanban & Paket
│       ├── layouts/                      # Layout Master (App, Admin, Guest)
│       └── projects/                     # View Detail Proyek, PDF & Print
├── routes/
│   └── web.php                           # Definisi Rute Aplikasi
└── tests/
    └── Feature/                          # Test Otomatis (Kanban, Admin, Quotation)
```

---

## 4. Skema Database & Relasi Entity (ERD)

### Hubungan Relasi Data Utama:
```
+---------------+        1:N        +------------------+
|   packages    | ----------------> | package_features | <---+ N:1
+---------------+                   +------------------+     |
       |                                                     |
       | 1:N                                                 |
       v                                                     |
+---------------+        1:N        +------------------+     |
|   projects    | ----------------> | project_features |     |
+---------------+                   +------------------+     |
  |     |                                                    |
  |     | 1:N                       +------------------+     |
  |     +-------------------------> |  project_addons  |     |
  |                                 +------------------+     |
  | 1:1                                                      |
  +-------------------------------> +------------------+     |
  |                                 |    quotations    |     |
  |                                 +------------------+     |
  |                                                          |
+---------------+        1:N        +------------------+     |
|  categories   | ----------------> |     features     | ----+
+---------------+                   +------------------+
                                       |            |
                                   1:N |        1:N |
                                       v            v
                      +-------------------+   +----------------------+
                      |   feature_prices  |   | feature_dependencies |
                      +-------------------+   +----------------------+
```

### Penjelasan Tabel:

1. **`packages`**: Menyimpan paket sewa dasar (*Basic*, *Medium*, *Professional*, *Web Custom*), harga dasar tahunan, dan status.
2. **`categories`**: 10 Kategori fitur (*Website & Frontend*, *Produk*, *Transaksi*, *Pembayaran*, *Pengiriman*, *Marketing*, *Administrasi*, *SEO*, *Integrasi*, *Infrastruktur*).
3. **`features`**: 39 master fitur e-commerce dengan penanda `is_infrastructure` untuk pilar hosting/domain/SSL/backup.
4. **`package_features`**: Matriks status fitur per paket (`included`, `optional`, `not_available`).
5. **`feature_prices`**: Harga modal internal (`cost_price`) dan harga jual (`selling_price`) per varian kompleksitas (*basic*, *standard*, *advanced*, *custom*).
6. **`feature_dependencies`**: Relasi prasyarat antar fitur (contoh: *Payment Gateway* membutuhkan *Checkout Online*).
7. **`addons`**: Modul tambahan seperti *Mobile App Android/iOS*, *Multi-vendor Marketplace*, *ERP/POS*, *AI Recommendation*.
8. **`projects`**: Proyek yang disimpan customer/admin, menyimpan total nilai jual, modal internal, laba, dan status workflow (*draft*, *pending*, *approved*, *completed*, *rejected*).
9. **`project_features`**: **Tabel Snapshot** fitur yang dipilih pada saat proyek dibuat. Menyimpan salinan nama fitur, kategori, harga jual, dan modal saat itu.
10. **`project_addons`**: **Tabel Snapshot** add-on yang dipilih pada saat proyek dibuat.
11. **`quotations`**: Data penawaran resmi dengan nomor unik (`QUO-YYYYMM-XXXX`), masa berlaku (30 hari), dan syarat & ketentuan penawaran.
12. **`users`**: Data pengguna dan administrator (role `admin` atau `user`).

---

## 5. Fitur Utama & Alur Bisnis

### 5.1 Visual Kanban Configurator
- **Antarmuka 3 Kolom**:
  1. **Kolom Kiri (Available Features)**: Menampilkan fitur yang belum dipilih dengan filter kategori, indikator pencarian live, dan penanda paket.
  2. **Kolom Tengah (Selected Features Dropzone)**: Area drop interaktif dengan kartu fitur terpilih, pengubah tingkat kompleksitas (*Basic / Standard / Advanced*), dan tombol hapus/kembalikan.
  3. **Kolom Kanan (Live Sticky Summary)**: Rincian biaya paket dasar, penyesuaian/diskon fitur paket, total fitur tambahan, total add-on, dan tombol aksi (*Simpan Draf* / *Ajukan Penawaran Resmi*).
- **Mobile Responsive 3-Tab Switcher**: Navigasi mudah di layar ponsel cerdas dengan tab *Katalog*, *Pilihan Saya*, dan *Ringkasan Biaya*.

### 5.2 Sistem Dependensi Antar-Fitur
- Jika pengguna memilih fitur yang memiliki ketergantungan teknis (misal: *Laporan Penjualan* memerlukan *Checkout & Transaksi*), sistem secara otomatis:
  - Memberikan indikator peringatan visual di kartu fitur.
  - Menyediakan tombol 1-klik **+ Tambah Syarat** untuk otomatis memasukkan fitur prasyarat ke kolom terpilih.

### 5.3 Mekanisme Pembekuan Snapshot Harga (*Frozen Snapshots*)
- Ketika proyek disimpan, seluruh nilai harga (harga paket, harga jual fitur, harga modal fitur, dan addon) disalin ke tabel `project_features` dan `project_addons`.
- **Manfaat**: Jika di kemudian hari Administrator memperbarui daftar harga master di sistem, riwayat dan penawaran harga proyek yang telah diterbitkan sebelumnya **tetap stabil dan tidak berubah**.

### 5.4 Keamanan Data Biaya Modal (*Zero Cost Leakage*)
- Atribut `total_cost_price` dan `total_profit` dilindungi di level Eloquent Model menggunakan atribut PHP 8 `#[Hidden]`.
- Controller publik (`CalculatorController` dan `ProjectController`) hanya mengembalikan payload `selling` yang sudah disanitasi.
- Nilai HPP/Modal dan Persentase Margin Laba hanya dapat diakses oleh Administrator yang berautentikasi melalui middleware `AdminOnly`.

### 5.5 Penerbitan Quotation & Ekspor PDF
- Diterbitkan dengan format nomor resmi: `QUO-YYYYMM-XXXX`.
- File PDF berstandar korporat dibuat menggunakan **DomPDF** mencakup:
  - Header profil perusahaan & nomor surat penawaran.
  - Data klien & ringkasan paket sewa tahunan.
  - Tabel rincian fitur terpasang & varian kompleksitas.
  - 5 Pilar Infrastruktur Standar (Hosting, Domain, SSL, Backup, Garansi Pemeliharaan).
  - Syarat & ketentuan penawaran (masa berlaku 30 hari).
  - Kolom tanda tangan resmi.

### 5.6 Panel Admin & Master Pricing Manager
- **Financial Dashboard**: Monitoring Total Omzet (Selling), Total HPP (Cost), Total Profit, dan Rata-rata Margin Keuntungan %.
- **Feature Matrix Editor**: Pengaturan status fitur per paket (*Included*, *Add-on*, *Not Available*) dalam 1 klik.
- **Batch Pricing Manager**: Form pengeditan massal untuk harga modal dan harga jual seluruh varian fitur.
- **Project Workflow**: Manajemen approval status proyek (*Draft &rarr; Pending &rarr; Approved &rarr; Completed / Rejected*).

---

## 6. Formula Kalkulasi Finansial

Seluruh kalkulasi dijalankan di backend melalui `CalculatorService`:

```text
1. Harga Jual Efektif Paket =
   Maksimal(Harga_Paket_Dasar * 0.3, Harga_Paket_Dasar - Potongan_Fitur_Termasuk_Yang_Dihapus)
   * Catatan: Fitur paket bawaan yang dihapus pengguna diberikan kompensasi kredit 50% dari harga standard.

2. Subtotal Fitur Terpilih =
   - Jika status 'included' dalam paket  -> Biaya Tambahan = Rp 0
   - Jika status 'optional' (add-on)    -> Biaya Tambahan = Harga_Jual_Fitur(kompleksitas) * Kuantitas

3. Total Harga Jual (Selling Price) =
   Harga Jual Efektif Paket + Total Subtotal Fitur + Total Subtotal Add-on

4. Total Biaya Modal Internal (Cost Price) =
   Total Modal Fitur Termasuk (50%) + Total Modal Fitur Tambahan (100%) + Total Modal Add-on

5. Laba Kotor (Gross Profit) =
   Total Harga Jual - Total Biaya Modal Internal

6. Margin Keuntungan (%) =
   (Total Laba Kotor / Total Harga Jual) * 100%
```

---

## 7. Daftar Rute (Route Map) & Middleware

### Rute Publik & Customer
| Method | URI | Action Controller | Keterangan |
|---|---|---|---|
| `GET` | `/` atau `/packages` | `CalculatorController@packages` | Halaman katalog paket sewa & pilar infrastruktur |
| `GET` | `/calculator` | `CalculatorController@index` | Papan Kanban Configurator interaktif |
| `POST`| `/calculator/calculate` | `CalculatorController@calculate` | API kalkulasi estimasi harga dinamis (JSON) |
| `POST`| `/projects` | `ProjectController@store` | Menyimpan konfigurasi proyek & snapshot harga |
| `GET` | `/projects/{project}` | `ProjectController@show` | Tampilan publik detail proyek yang disimpan |
| `POST`| `/projects/{project}/quotation` | `ProjectController@requestQuotation` | Mengajukan draf menjadi penawaran resmi |
| `GET` | `/projects/{project}/pdf` | `ProjectController@pdf` | Download/Stream Surat Penawaran Resmi (PDF) |
| `GET` | `/projects/{project}/print` | `ProjectController@printView` | Tampilan cetak langsung browser (@media print) |
| `GET` | `/my-projects` | `ProjectController@myProjects` | Riwayat proyek pengguna / sesi tersimpan |

### Rute Autentikasi
| Method | URI | Action Controller | Keterangan |
|---|---|---|---|
| `GET` | `/login` | `AuthController@showLoginForm` | Form login pengguna & admin |
| `POST`| `/login` | `AuthController@login` | Proses autentikasi akun |
| `POST`| `/logout` | `AuthController@logout` | Logout sesi aktif |

### Rute Panel Admin (Prefix: `/admin`, Middleware: `['auth', 'admin']`)
| Method | URI | Action Controller | Keterangan |
|---|---|---|---|
| `GET` | `/admin` | `Admin\DashboardController@index` | Metrik finansial, omzet, laba & proyek terbaru |
| `GET` | `/admin/packages` | `Admin\PackageController@index` | Daftar paket sewa tahunan |
| `GET` | `/admin/packages/{id}/features` | `Admin\PackageController@features` | Matriks fitur per paket (Included/Add-on/NA) |
| `PUT` | `/admin/packages/{id}/features` | `Admin\PackageController@updateFeatures` | Simpan perubahan matriks fitur paket |
| `GET` | `/admin/categories` | `Admin\CategoryController@index` | Manajemen kategori fitur & palet warna |
| `GET` | `/admin/features` | `Admin\FeatureController@index` | CRUD Master fitur & relasi dependensi |
| `GET` | `/admin/pricing` | `Admin\PricingController@index` | Matriks Master Pricing (Batch Edit Modal & Jual) |
| `POST`| `/admin/pricing/batch-update` | `Admin\PricingController@batchUpdate` | Batch update harga modal dan jual |
| `GET` | `/admin/addons` | `Admin\AddonController@index` | CRUD modul add-on & custom development |
| `GET` | `/admin/projects` | `Admin\ProjectController@index` | Daftar seluruh proyek beserta data HPP & Margin |
| `GET` | `/admin/projects/{id}` | `Admin\ProjectController@show` | Detail proyek internal (HPP, Profit, Breakdown) |
| `PATCH`| `/admin/projects/{id}/status` | `Admin\ProjectController@updateStatus` | Update status approval (*Approved, Rejected, dll.*) |

---

## 8. Panduan Instalasi & Setup Lokal

### Prasyarat:
- PHP >= 8.3 (dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `gd`, `curl`)
- Database MySQL / MariaDB
- Composer >= 2.x
- Node.js >= 20.x & npm

### Langkah Pemasangan:

1. **Masuk ke folder proyek**:
   ```bash
   cd c:/laragon/www/calculating-project
   ```

2. **Install Dependensi PHP & Node.js**:
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment (`.env`)**:
   Salin `.env.example` ke `.env` (jika belum ada) dan sesuaikan konfigurasi database:
   ```env
   APP_NAME="E-Commerce Configurator"
   APP_ENV=local
   APP_KEY=base64:...
   APP_DEBUG=true
   APP_URL=http://localhost:8000

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=calculating_project
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Jalankan Migrasi & Database Seeder**:
   ```bash
   php artisan key:generate
   php artisan migrate:fresh --seed
   ```

5. **Build Aset Frontend**:
   ```bash
   npm run build
   # atau untuk mode development live-reload:
   npm run dev
   ```

6. **Jalankan Web Server**:
   ```bash
   php artisan serve
   ```
   Aplikasi siap diakses melalui peramban web di: **`http://localhost:8000`**

---

## 9. Kredensial Default & Akun Pengujian

Database seeder telah menyiapkan akun bawaan untuk pengujian:

| Tipe Akun | Email | Password | Hak Akses | URL Masuk |
|---|---|---|---|---|
| **Administrator** | `admin@ecomconfig.com` | `password` | Akses penuh Panel Admin, HPP, Margin, Master Pricing | `/login` &rarr; `/admin` |
| **Demo Customer** | `demo@ecomconfig.com` | `password` | Akses pembuatan konfigurasi & riwayat proyek | `/login` &rarr; `/my-projects` |

---

## 10. Pengujian Otomatis (Automated Testing) & Standar Kode

### Menjalankan Automated Test Suite:
Aplikasi dilengkapi pengujian unit dan fitur berbasis PHPUnit untuk memvalidasi:
- Keamanan isolasi data biaya modal (*Zero Cost Leakage*).
- Integritas perhitungan harga Kanban & diskon fitur.
- Pembekuan snapshot harga saat proyek disimpan.
- Pembuatan nomor quotation dan ekspor PDF.
- Otorisasi rute Panel Admin.

Jalankan perintah berikut:
```bash
php artisan test
```

### Memeriksa Format Kode (Laravel Pint):
Untuk memastikan standar penulisan kode sesuai kaidah Laravel:
```bash
vendor/bin/pint --format agent
```

---

*Dokumentasi ini disusun sebagai panduan teknis dan operasional untuk sistem E-Commerce Cost Calculator & Project Configurator.*
