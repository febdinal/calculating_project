# E-Commerce Cost Calculator & Project Configurator 🛍️

Aplikasi web modern berbasis **Laravel 13**, **Tailwind CSS 4**, dan **Vanilla JS** untuk perencanaan anggaran teknis, konfigurasi fitur toko online berbasis **Kanban Board Drag & Drop**, estimasi biaya sewa tahunan, pembekuan snapshot harga (*frozen snapshots*), serta penerbitan **Surat Penawaran Resmi (Quotation PDF)** otomatis.

Dibangun berdasarkan dokumen:
> **"Perencanaan Anggaran Teknis E-Commerce — Paket Sewa"**
>
> 📖 **Panduan & Dokumentasi Teknis Lengkap**: Lihat [DOKUMENTASI.md](file:///c:/laragon/www/calculating-project/DOKUMENTASI.md)

---

## 🌟 Fitur Utama

### 1. 🎛️ Visual Kanban Configurator
- **3 Kolom Interaktif**: *Fitur Tersedia (Available)* &bull; *Fitur Terpilih (Selected)* &bull; *Ringkasan Proyek (Sticky Summary)*.
- **Drag & Drop Engine**: Tarik kartu fitur ke dropzone dengan animasi visual feedback.
- **Live Search & Kategori Filter**: Pencarian instan dan filter berbasis 10 kategori fitur.
- **Deteksi Dependensi Prasyarat**: Peringatan otomatis jika fitur membutuhkan fitur lain (misal: *Payment Gateway* membutuhkan *Checkout Online*) dilengkapi tombol 1-klik *+ Tambah Syarat*.
- **Varian Kompleksitas**: Pilihan tingkat kerumitan (*Basic*, *Standard*, *Advanced*) di setiap kartu fitur terpilih yang langsung memperbarui estimasi harga.
- **Drawer Pemilihan Add-on**: Pilihan modul khusus (Mobile App Android/iOS, Multi-vendor Marketplace, ERP/POS, AI, Loyalty Point).
- **Mobile 3-Tab Switcher**: Pengalaman interaktif responsif pada smartphone.

### 2. 🛡️ Paket Sewa Tahunan & Infrastruktur
- **Basic (Rp4.000.000 / tahun)**: Katalog online, pemesanan WhatsApp, SEO dasar.
- **Medium (Rp8.000.000 / tahun)**: E-commerce transaksi lengkap, keranjang, checkout, payment gateway, ongkir otomatis (*Paling Populer*).
- **Professional (Rp15.000.000 / tahun)**: Fitur marketing lanjutan (voucher, wishlist, review), multi-role admin, notifikasi WhatsApp, analitik.
- **Web Custom**: Kebutuhan khusus enterprise & custom development.
- **5 Pilar Infrastruktur Standar (Termasuk dalam Paket)**:
  1. 🖥️ Hosting / VPS
  2. 🌐 Domain Website
  3. 🔒 SSL / HTTPS
  4. 💾 Backup Otomatis
  5. 🔧 Pemeliharaan Teknis & Garansi

### 3. 🔒 Isolasi Keamanan Biaya Modal (Zero Cost Leakage)
- Kolom internal `cost_price`, `total_cost_price`, `total_profit`, dan `margin_percentage` dilindungi di level Model Eloquent via `#[Hidden]` PHP 8.
- Frontend publik, API kalkulator, dan dokumen PDF customer **100% bersih dari kebocoran data biaya modal**.
- Biaya modal dan margin keuntungan hanya dapat diakses oleh Admin berautentikasi melalui middleware `AdminOnly`.

### 4. 📄 Pembekuan Snapshot & Quotation PDF
- Menyimpan snapshot harga pada saat konfigurasi dibuat (`package_price_snapshot`, `project_features`, `project_addons`).
- Perubahan harga master tidak akan mengubah riwayat proyek masa lalu.
- Format nomor Quotation otomatis: `QUO-YYYYMM-XXXX`.
- Ekspor PDF penawaran resmi berstandar korporat via **DomPDF** dan dukungan cetak langsung via `@media print`.

### 5. 📊 Dashboard Admin & Master Pricing Engine
- **Metrik Finansial**: Total omzet (selling price), total modal (cost price), laba kotor (profit), dan rata-rata margin keuntungan %.
- **Matriks Fitur Interaktif**: Penentuan status fitur (*Included*, *Add-on*, *Not Available*) per paket dalam 1 klik.
- **Master Pricing Table**: Batch update harga modal dan harga jual dengan perhitungan live laba dan margin %.
- **Manajemen Proyek & Status Approval**: Workflow status (*Draft, Pending, Approved, Completed, Rejected*).

---

## 🚀 Panduan Instalasi & Menjalankan

### Persyaratan Sistem:
- PHP >= 8.3 (dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `gd`)
- MySQL >= 8.0
- Composer >= 2.x
- Node.js >= 20.x

### Langkah Setup:

1. **Clone repository dan masuk ke direktori**:
   ```bash
   cd c:/laragon/www/calculating-project
   ```

2. **Install Dependensi PHP & Node.js**:
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Lingkungan (`.env`)**:
   Pastikan konfigurasi database MySQL sudah sesuai:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=calculating_project
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Jalankan Migrasi & Database Seeder**:
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Build Aset Frontend**:
   ```bash
   npm run build
   ```

6. **Jalankan Server Development**:
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses pada browser di: `http://localhost:8000`

---

## 🔑 Kredensial Pengujian

| Peran | Email | Password | Akses URL |
|---|---|---|---|
| **Administrator** | `admin@ecomconfig.com` | `password` | `/login` &rarr; `/admin` |
| **Demo User** | `demo@ecomconfig.com` | `password` | `/login` &rarr; `/my-projects` |

---

## 🗺️ Peta Rute Aplikasi (Route Map)

### Halaman Publik & Customer
- `/` atau `/packages` — Halaman pilihan paket & showcase infrastruktur.
- `/calculator` — Papan Kanban Configurator interaktif.
- `/projects/{id}` — Tampilan detail proyek tersimpan.
- `/projects/{id}/pdf` — Unduh Surat Penawaran Resmi (PDF).
- `/projects/{id}/print` — Tampilan cetak browser formal.
- `/my-projects` — Riwayat proyek tersimpan customer.

### Panel Admin (`/admin`)
- `/admin` — Metrik Dashboard & Ringkasan Finansial.
- `/admin/packages` — CRUD Paket Sewa & Pengaturan Harga.
- `/admin/packages/{id}/features` — Matriks Fitur per Paket.
- `/admin/categories` — CRUD Kategori Fitur & Ikon.
- `/admin/features` — CRUD Master Fitur & Dependensi.
- `/admin/pricing` — Master Pricing Manager (Batch Edit Modal & Jual).
- `/admin/addons` — CRUD Modul Add-on & Custom Development.
- `/admin/projects` — Manajemen Snapshot Proyek & Approval Status.

---

## 🧪 Menjalankan Automated Tests

Seluruh pengujian unit dan fitur dapat dijalankan dengan perintah:
```bash
php artisan test
```

### Hasil Pengujian:
```text
Pass: 15 tests, 61 assertions (100% Success)
```

---

## 📁 Struktur Data & Model

- `Package` — Paket sewa layanan tahunan (Basic, Medium, Professional, Custom).
- `Category` — 10 kategori fitur e-commerce.
- `Feature` — 39 master fitur dengan penanda infrastruktur.
- `PackageFeature` — Matriks status fitur per paket (`included`, `optional`, `not_available`).
- `FeaturePrice` — Harga modal (*cost*) dan harga jual (*selling*) per varian kompleksitas.
- `FeatureDependency` — Relasi prasyarat antar fitur.
- `Addon` — Modul add-on eksternal dan custom development.
- `Project` — Data proyek tersimpan beserta akumulasi nilai jual, modal, dan profit.
- `ProjectFeature` — Snapshot fitur beku per proyek.
- `ProjectAddon` — Snapshot add-on beku per proyek.
- `Quotation` — Rekod penawaran harga resmi dengan nomor unik dan masa berlaku.
