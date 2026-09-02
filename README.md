# Website Feature Configurator 🎛️
### Kalkulator Fitur & Estimasi Biaya Website

Aplikasi web modern berbasis **Laravel 13**, **Tailwind CSS 4**, dan **Vanilla JS** untuk perencanaan dan konfigurasi fitur website secara interaktif menggunakan papan **Kanban Drag & Drop**, kalkulasi estimasi biaya sewa tahunan secara realtime, serta pembuatan dokumen **Estimasi Biaya (PDF)** instan.

> 📖 **Dokumentasi Lengkap**: Lihat [DOKUMENTASI.md](file:///c:/laragon/www/calculating-project/DOKUMENTASI.md)

---

## 🌟 Fitur Utama

1. **Pilihan Paket Website**:
   - Tampilan paket website dasar (*Basic*, *Medium*, *Premium*) dengan harga dan periode sewa tahunan yang jelas.
2. **Visual Kanban Configurator (3 Kolom)**:
   - **Fitur Tersedia**: Daftar fitur website dengan pencarian realtime, filter kategori, nominal harga langsung, dan tombol *+ Tambah*.
   - **Fitur Dipilih**: Area dropzone interaktif dengan kemampuan drag & drop, drag to reorder, tombol hapus (×), dan **Kustomisasi Sub-Fitur Interaktif (Checkbox / Toggle On-Off)** yang secara otomatis mengkalkulasi ulang harga fitur dan total estimasi proyek.
   - **Ringkasan Biaya**: Panel ringkasan realtime yang menampilkan harga paket, rincian fitur tambahan & sub-fitur aktif, total estimasi, dan tombol *Generate PDF*.
3. **Hierarki Fitur & Sub-Fitur Berharga**:
   - Setiap sub-fitur memiliki harga tersendiri.
   - Total harga fitur utama dihitung secara otomatis dari akumulasi harga seluruh sub-fiturnya (`SUM(sub_features.price)`).
4. **Formula Kalkulasi Sederhana**:
   - `HARGA FITUR = SUM(HARGA SUB-FITUR)`
   - `TOTAL ESTIMASI = HARGA PAKET + TOTAL HARGA FITUR TAMBAHAN`
   - Fitur yang sudah termasuk dalam paket bawaan tidak membebankan biaya tambahan ke total (namun nominal harga aslinya tetap tampil di kartu).
5. **Output Dokumen PDF Langsung**:
   - Dibuat instan dari konfigurasi saat ini tanpa memerlukan registrasi akun atau alur checkout.
6. **Panel Admin Sederhana**:
   - Manajemen Master Data: **Paket** (CRUD + checklist fitur paket), **Kategori** (CRUD), dan **Fitur / Sub-Fitur** (CRUD).

---

## 🚀 Panduan Menjalankan

### Persyaratan Sistem:
- PHP >= 8.3
- MySQL >= 8.0
- Composer >= 2.x
- Node.js >= 20.x

### Langkah Setup:
```bash
# 1. Masuk direktori
cd c:/laragon/www/calculating-project

# 2. Install dependensi
composer install
npm install

# 3. Jalankan migrasi & seeder
php artisan migrate:fresh --seed

# 4. Build frontend
npm run build

# 5. Jalankan server
php artisan serve
```
Aplikasi siap dibuka pada browser di: `http://localhost:8000`

---

## 🔑 Kredensial Admin

| Role | Email | Password | URL Akses |
|---|---|---|---|
| **Administrator** | `admin@featureconfig.com` | `password` | `/login` &rarr; `/admin` |

---

## 🗺️ Peta Rute Aplikasi

- `GET /` &rarr; Redirect ke `/packages`
- `GET /packages` &rarr; Halaman Pilihan Paket
- `GET /calculator` &rarr; Kanban Configurator Interaktif
- `POST /calculator/calculate` &rarr; Endpoint Kalkulasi Realtime (JSON)
- `POST /calculator/pdf` & `GET /calculator/pdf` &rarr; Unduh Dokumen Estimasi Biaya (PDF)
- `GET /admin` &rarr; Dashboard Admin
- `GET /admin/packages` &rarr; CRUD Paket & Checklist Fitur
- `GET /admin/categories` &rarr; CRUD Kategori
- `GET /admin/features` &rarr; CRUD Fitur & Sub-Fitur

---

## 🧪 Pengujian Otomatis (Automated Tests)

Jalankan test suite menggunakan:
```bash
php artisan test
```
Hasil: **15 tests, 33 assertions (100% Passed)**.
