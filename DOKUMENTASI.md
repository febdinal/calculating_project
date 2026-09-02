# 📘 Dokumentasi Resmi: Website Feature Configurator & Price Calculator

---

## 📑 Daftar Isi
1. [Ringkasan Proyek & Prinsip Utama](#1-ringkasan-proyek--prinsip-utama)
2. [Arsitektur & Tech Stack](#2-arsitektur--tech-stack)
3. [Skema Database & Relasi Model](#3-skema-database--relasi-model)
4. [Hierarki Fitur & Sub-Fitur](#4-hierarki-fitur--sub-fitur)
5. [Formula & Logika Kalkulasi Harga](#5-formula--logika-kalkulasi-harga)
6. [Alur Pengguna (User Flow) & Kanban Configurator](#6-alur-pengguna-user-flow--kanban-configurator)
7. [Panel Administrasi Master Data](#7-panel-administrasi-master-data)
8. [Penerbitan Dokumen PDF](#8-penerbitan-dokumen-pdf)
9. [Daftar Rute (Route Map) & Middleware](#9-daftar-rute-route-map--middleware)
10. [Panduan Instalasi & Pengujian](#10-panduan-instalasi--pengujian)

---

## 1. Ringkasan Proyek & Prinsip Utama

**Website Feature Configurator** (Kalkulator Fitur & Estimasi Biaya) adalah aplikasi web berbasis Laravel untuk menyusun konfigurasi fitur website secara interaktif menggunakan papan **Kanban Drag & Drop**, menghitung estimasi biaya sewa tahunan secara realtime, dan menerbitkan dokumen **Estimasi Biaya (PDF)**.

### Prinsip Utama Sistem:
- **Bukan E-Commerce**: Tidak ada keranjang belanja, checkout, payment gateway, transaksi, atau invoice pembayaran.
- **Bukan Project Management / CRM**: Tidak ada akun customer, penyimpanan draf proyek, atau quotation approval workflow.
- **Sederhana & Bersih**: Bebas dari kompleksitas HPP, cost price, profit margin, modul add-on rumit, dependency engine, dan snapshot beku.
- **Fokus Inti**: Pilihan Paket &rarr; Kanban Drag & Drop Fitur &rarr; Realtime Calculation &rarr; Generate PDF &rarr; Selesai.

---

## 2. Arsitektur & Tech Stack

| Komponen | Teknologi | Keterangan |
|---|---|---|
| **Backend Framework** | Laravel 13.x | PHP 8.3+ |
| **Database** | MySQL 8.0+ | Struktur relasional sederhana (5 tabel utama) |
| **Frontend Styling** | Tailwind CSS 4.x | Dark theme modern & glassmorphism |
| **Bundler & Build Tool** | Vite 8.x | Asset compilation & HMR |
| **Interaktivitas UI** | Vanilla JS / Native Drag & Drop | Drag & Drop interaktif 3 kolom + mobile tab |
| **PDF Engine** | `barryvdh/laravel-dompdf` (DomPDF 3.1) | Dokumen estimasi biaya A4 rapi dan formal |
| **Test Suite** | PHPUnit 12.x | 15 Feature Tests (100% Passed) |

---

## 3. Skema Database & Relasi Model

Sistem hanya menggunakan 5 tabel inti:

```
+---------------+        1:N        +------------------+
|   packages    | ----------------> | package_features | <---+ N:1
+---------------+                   +------------------+     |
                                                             |
+---------------+        1:N        +------------------+     |
|  categories   | ----------------> |     features     | ----+
+---------------+                   +------------------+
                                       |            ^
                                       | parent_id  |
                                       +------------+ (Self-referencing Sub Features)
```

### Rincian Tabel:

1. **`users`**:
   - `id`, `name`, `email`, `password`, `role` (`admin`/`user`), `timestamps`.
   - Hanya digunakan untuk login Administrator.
2. **`packages`**:
   - `id`, `name`, `slug`, `description`, `price` (decimal), `period` (`tahun`), `status` (`active`/`inactive`), `sort_order`, `timestamps`.
3. **`categories`**:
   - `id`, `name`, `slug`, `description`, `icon`, `color`, `sort_order`, `status`, `timestamps`.
4. **`features`**:
   - `id`, `category_id` (FK), `parent_id` (FK nullable), `name`, `slug`, `description`, `icon`, `price` (decimal), `sort_order`, `status`, `timestamps`.
   - `parent_id = NULL`: Fitur Utama (harga merupakan akumulasi total harga dari seluruh sub-fiturnya).
   - `parent_id = {ID}`: Sub-Fitur (memiliki harga nominal masing-masing).
5. **`package_features`**:
   - `id`, `package_id` (FK), `feature_id` (FK), `timestamps`.
   - Tabel pivot untuk menentukan fitur apa saja yang sudah termasuk ke dalam harga paket.

---

## 4. Hierarki Fitur & Sub-Fitur

Setiap sub-fitur memiliki **harga tersendiri**, dan harga fitur utama dihitung secara otomatis dari total akumulasi harga sub-fiturnya:

```text
Katalog Produk (Fitur Utama - Total: Rp 1.500.000)
├── Daftar Produk Grid dengan Filter Kategori (Rp 500.000)
├── Halaman Detail Produk Lengkap (Rp 400.000)
├── Variasi Produk (Ukuran, Warna, Opsi) (Rp 350.000)
└── Pencarian Cepat dengan Live Search (Rp 250.000)
```

- **Sub-Fitur**: Memiliki harga nominal satuan, deskripsi komponen, dan dapat dikelola di panel admin.
- **Fitur Utama**: Total harga dihitung dari `SUM(sub_features.price)`.
- **Transparansi Biaya**: Pada papan Kanban dan dokumen PDF, rincian harga setiap sub-fitur ditampilkan secara terbuka kepada pengguna.

---

## 5. Formula & Logika Kalkulasi Harga

Semua kalkulasi harga dilakukan melalui `CalculatorService`:

```text
1. HARGA FITUR = SUM(HARGA SUB-FITUR AKTIF)
2. TOTAL ESTIMASI = HARGA PAKET + TOTAL HARGA FITUR TAMBAHAN
```

### Aturan Perhitungan:
1. **Harga Sub-Fitur & Fitur**: Setiap sub-fitur memiliki harga tersendiri. Harga total fitur utama adalah penjumlahan seluruh sub-fiturnya.
2. **Fitur Termasuk Paket (*Included*)**: Jika fitur terpilih sudah ada di dalam relasi `package_features` untuk paket yang aktif, harga tambahannya adalah **Rp 0** (sudah tercakup dalam harga paket).
3. **Fitur Tambahan (*Additional*)**: Jika fitur terpilih tidak ada di dalam `package_features`, maka nominal harga fitur tersebut ditambahkan ke total.
4. **Tampilan Kartu**: Harga fitur **tetap ditampilkan** nominal aslinya di kartu Kanban (tidak diubah menjadi Rp 0) dengan badge penjelas `Termasuk Paket` atau `Fitur Tambahan`. Dropdown sub-fitur juga merinci harga tiap komponennya.

---

## 6. Alur Pengguna (User Flow) & Kanban Configurator

1. **Langkah 1: Pilih Paket (`/packages`)**
   - Pengguna memilih paket awal (*Basic*, *Medium*, *Premium*).
   - Mengklik *Pilih Paket* langsung mengarahkan ke `/calculator?package={slug}`.
2. **Langkah 2: Konfigurasi Papan Kanban & Kustomisasi Sub-Fitur (`/calculator`)**
   - **Kolom 1 (Fitur Tersedia)**: Menampilkan fitur yang belum dipilih dengan pencarian instan, filter kategori, nominal harga langsung, dan tombol *+ Tambah*.
   - **Kolom 2 (Fitur Dipilih)**: 
     - Drag & drop antar-kolom dan drag to reorder.
     - **Kustomisasi Sub-Fitur**: Pengguna dapat mencentang/menonaktifkan masing-masing sub-fitur via checkbox interaktif, atau menggunakan tombol cepat *[Pilih Semua]* / *[Batal Semua]*.
     - **Live Recalculation**: Harga fitur pada kartu dan total estimasi proyek otomatis berkurang/bertambah sesuai sub-fitur yang aktif dipilih.
     - Tombol hapus (`×`) untuk mengembalikan fitur ke daftar tersedia.
   - **Kolom 3 (Ringkasan Biaya)**: Menampilkan harga paket, daftar fitur tambahan dengan jumlah sub-fitur aktif, total estimasi realtime, dan tombol *Generate PDF*.
3. **Langkah 3: Generate PDF (`/calculator/pdf`)**
   - Sistem memvalidasi pilihan fitur dan sub-fitur aktif secara server-side.
   - Dokumen PDF A4 diunduh/ditampilkan hanya mencantumkan sub-fitur yang dipilih oleh pengguna. Selesai!

---

## 7. Panel Administrasi Master Data

Panel Admin (`/admin`) dilindungi middleware `['auth', 'admin']`:

- **Dashboard**: Menampilkan total paket, kategori, fitur utama, dan sub-fitur.
- **Paket (`/admin/packages`)**:
  - CRUD Paket (nama, slug, harga, periode, urutan, status).
  - Checklist Fitur Paket (`/admin/packages/{id}/features`): Antarmuka centang sederhana untuk menentukan fitur bawaan paket.
- **Kategori (`/admin/categories`)**:
  - CRUD Kategori (nama, icon emoji, warna, deskripsi, urutan).
- **Fitur (`/admin/features`)**:
  - CRUD Fitur Utama (kategori, icon, nama, harga nominal, deskripsi).
  - Manajemen Sub-Fitur (dapat diinput cepat via textarea atau baris dinamis).

---

## 8. Penerbitan Dokumen PDF

PDF adalah output akhir resmi yang memuat:
- Judul: **ESTIMASI BIAYA WEBSITE**
- Identitas Paket & Periode Sewa
- Tabel Rincian Fitur Terpilih (No, Nama Fitur, Kategori, Status Termasuk/Tambahan, Harga)
- Rincian Sub-Fitur yang tercakup
- Box Ringkasan (Harga Paket, Total Fitur Tambahan, Total Estimasi Biaya)
- Catatan formal keabsahan estimasi

---

## 9. Daftar Rute (Route Map) & Middleware

### Rute Publik
| Method | URI | Controller Action | Keterangan |
|---|---|---|---|
| `GET` | `/` | Redirect ke `/packages` | Halaman awal |
| `GET` | `/packages` | `CalculatorController@packages` | Katalog pilihan paket |
| `GET` | `/calculator` | `CalculatorController@index` | Papan Kanban Configurator |
| `POST`| `/calculator/calculate` | `CalculatorController@calculate` | API kalkulasi JSON realtime |
| `POST`/`GET` | `/calculator/pdf` | `CalculatorController@pdf` | Generate & Download PDF |

### Rute Autentikasi
| Method | URI | Controller Action | Keterangan |
|---|---|---|---|
| `GET` | `/login` | `AuthController@showLoginForm` | Form login admin |
| `POST`| `/login` | `AuthController@login` | Proses autentikasi |
| `POST`| `/logout` | `AuthController@logout` | Logout admin |

### Rute Panel Admin (Middleware: `['auth', 'admin']`)
| Method | URI | Controller Action | Keterangan |
|---|---|---|---|
| `GET` | `/admin` | `Admin\DashboardController@index` | Dashboard master data |
| `GET`/`POST`/`PUT`/`DELETE` | `/admin/packages` | `Admin\PackageController` | CRUD Paket |
| `GET`/`PUT` | `/admin/packages/{id}/features` | `Admin\PackageController@features` | Atur checklist fitur paket |
| `GET`/`POST`/`PUT`/`DELETE` | `/admin/categories` | `Admin\CategoryController` | CRUD Kategori |
| `GET`/`POST`/`PUT`/`DELETE` | `/admin/features` | `Admin\FeatureController` | CRUD Fitur & Sub-Fitur |

---

## 10. Panduan Instalasi & Pengujian

### Setup Awal:
```bash
composer install
npm install
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

### Kredensial Default Admin:
- **Email**: `admin@featureconfig.com` (atau `admin@ecomconfig.com`)
- **Password**: `password`

### Menjalankan Automated Tests:
```bash
php artisan test
```
Seluruh 15 skenario pengujian unit & fitur berhasil dijalankan dengan hasil **100% Passed**.
