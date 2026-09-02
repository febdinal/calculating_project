<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        $features = [
            // ─── Website & Frontend ───────────────────────────────────────
            [
                'category_slug' => 'website-frontend',
                'name' => 'Website Responsive',
                'slug' => 'website-responsive',
                'description' => 'Website yang tampil optimal di semua perangkat: desktop, tablet, dan mobile.',
                'icon' => '📱',
                'is_infrastructure' => false,
                'sort_order' => 1,
            ],
            [
                'category_slug' => 'website-frontend',
                'name' => 'Homepage / Landing Page',
                'slug' => 'homepage-landing-page',
                'description' => 'Halaman utama yang menarik untuk menyambut pengunjung dan memperkenalkan toko.',
                'icon' => '🏠',
                'is_infrastructure' => false,
                'sort_order' => 2,
            ],
            [
                'category_slug' => 'website-frontend',
                'name' => 'Katalog Produk',
                'slug' => 'katalog-produk',
                'description' => 'Tampilan grid/list produk dengan foto, harga, dan informasi singkat.',
                'icon' => '🗂️',
                'is_infrastructure' => false,
                'sort_order' => 3,
            ],
            [
                'category_slug' => 'website-frontend',
                'name' => 'Kategori Produk',
                'slug' => 'kategori-produk-frontend',
                'description' => 'Navigasi produk berdasarkan kategori untuk memudahkan pencarian.',
                'icon' => '🏷️',
                'is_infrastructure' => false,
                'sort_order' => 4,
            ],
            [
                'category_slug' => 'website-frontend',
                'name' => 'Pencarian Produk',
                'slug' => 'pencarian-produk',
                'description' => 'Fitur search untuk mencari produk berdasarkan nama, kategori, atau kata kunci.',
                'icon' => '🔎',
                'is_infrastructure' => false,
                'sort_order' => 5,
            ],
            [
                'category_slug' => 'website-frontend',
                'name' => 'Tombol WhatsApp / Telepon',
                'slug' => 'tombol-whatsapp-telepon',
                'description' => 'Tombol kontak langsung ke WhatsApp atau telepon untuk komunikasi cepat.',
                'icon' => '📞',
                'is_infrastructure' => false,
                'sort_order' => 6,
            ],
            [
                'category_slug' => 'website-frontend',
                'name' => 'Menu Navigasi',
                'slug' => 'menu-navigasi',
                'description' => 'Struktur menu yang memudahkan pengunjung menjelajahi seluruh website.',
                'icon' => '☰',
                'is_infrastructure' => false,
                'sort_order' => 7,
            ],
            [
                'category_slug' => 'website-frontend',
                'name' => 'Template Premium',
                'slug' => 'template-premium',
                'description' => 'Desain tampilan premium yang modern dan profesional.',
                'icon' => '🎨',
                'is_infrastructure' => false,
                'sort_order' => 8,
            ],
            [
                'category_slug' => 'website-frontend',
                'name' => 'Integrasi Sosial Media',
                'slug' => 'integrasi-sosial-media',
                'description' => 'Koneksi akun Instagram, Facebook, TikTok, dan sosial media lainnya ke website.',
                'icon' => '📲',
                'is_infrastructure' => false,
                'sort_order' => 9,
            ],
            [
                'category_slug' => 'website-frontend',
                'name' => 'Free Banner & Logo',
                'slug' => 'free-banner-logo',
                'description' => 'Desain banner website dan logo toko secara gratis.',
                'icon' => '🖼️',
                'is_infrastructure' => false,
                'sort_order' => 10,
            ],
            [
                'category_slug' => 'website-frontend',
                'name' => 'Google Maps',
                'slug' => 'google-maps',
                'description' => 'Integrasi peta Google Maps untuk menampilkan lokasi toko.',
                'icon' => '📍',
                'is_infrastructure' => false,
                'sort_order' => 11,
            ],
            [
                'category_slug' => 'website-frontend',
                'name' => 'Standard Contact Form',
                'slug' => 'standard-contact-form',
                'description' => 'Formulir kontak standar untuk pesan dari pengunjung.',
                'icon' => '✉️',
                'is_infrastructure' => false,
                'sort_order' => 12,
            ],

            // ─── Produk ───────────────────────────────────────────────────
            [
                'category_slug' => 'produk',
                'name' => 'Manajemen Stok',
                'slug' => 'manajemen-stok',
                'description' => 'Kelola jumlah stok produk, alert stok menipis, dan update otomatis saat ada pesanan.',
                'icon' => '📊',
                'is_infrastructure' => false,
                'sort_order' => 1,
            ],
            [
                'category_slug' => 'produk',
                'name' => 'Variasi Produk',
                'slug' => 'variasi-produk',
                'description' => 'Dukungan variasi produk seperti ukuran, warna, dan atribut lainnya.',
                'icon' => '🔢',
                'is_infrastructure' => false,
                'sort_order' => 2,
            ],

            // ─── Transaksi ────────────────────────────────────────────────
            [
                'category_slug' => 'transaksi',
                'name' => 'Keranjang Belanja',
                'slug' => 'keranjang-belanja',
                'description' => 'Sistem cart untuk menampung produk sebelum checkout.',
                'icon' => '🛒',
                'is_infrastructure' => false,
                'sort_order' => 1,
            ],
            [
                'category_slug' => 'transaksi',
                'name' => 'Checkout Online',
                'slug' => 'checkout-online',
                'description' => 'Proses pemesanan online dengan form pengiriman dan ringkasan pesanan.',
                'icon' => '✅',
                'is_infrastructure' => false,
                'sort_order' => 2,
            ],
            [
                'category_slug' => 'transaksi',
                'name' => 'Akun Pelanggan',
                'slug' => 'akun-pelanggan',
                'description' => 'Sistem login/register untuk pelanggan menyimpan data dan riwayat belanja.',
                'icon' => '👤',
                'is_infrastructure' => false,
                'sort_order' => 3,
            ],
            [
                'category_slug' => 'transaksi',
                'name' => 'Riwayat Pesanan',
                'slug' => 'riwayat-pesanan',
                'description' => 'Halaman riwayat pesanan di akun pelanggan dengan status tracking.',
                'icon' => '📋',
                'is_infrastructure' => false,
                'sort_order' => 4,
            ],

            // ─── Pembayaran ───────────────────────────────────────────────
            [
                'category_slug' => 'pembayaran',
                'name' => 'Pembayaran Online',
                'slug' => 'pembayaran-online',
                'description' => 'Sistem pembayaran digital terintegrasi dengan konfirmasi otomatis.',
                'icon' => '💰',
                'is_infrastructure' => false,
                'sort_order' => 1,
            ],
            [
                'category_slug' => 'pembayaran',
                'name' => 'Payment Gateway',
                'slug' => 'payment-gateway',
                'description' => 'Integrasi payment gateway (Midtrans, Xendit, dll.) untuk berbagai metode bayar: transfer bank, kartu kredit, QRIS, e-wallet.',
                'icon' => '🏦',
                'is_infrastructure' => false,
                'sort_order' => 2,
            ],

            // ─── Pengiriman ───────────────────────────────────────────────
            [
                'category_slug' => 'pengiriman',
                'name' => 'Integrasi Ongkos Kirim',
                'slug' => 'integrasi-ongkos-kirim',
                'description' => 'Kalkulasi otomatis ongkos kirim via JNE, JET, SiCepat, GoSend, dan ekspedisi lainnya menggunakan RajaOngkir atau sejenisnya.',
                'icon' => '📦',
                'is_infrastructure' => false,
                'sort_order' => 1,
            ],

            // ─── Marketing ────────────────────────────────────────────────
            [
                'category_slug' => 'marketing',
                'name' => 'Voucher / Promo',
                'slug' => 'voucher-promo',
                'description' => 'Sistem kode voucher diskon, promosi flash sale, dan potongan harga.',
                'icon' => '🎫',
                'is_infrastructure' => false,
                'sort_order' => 1,
            ],
            [
                'category_slug' => 'marketing',
                'name' => 'Wishlist',
                'slug' => 'wishlist',
                'description' => 'Fitur simpan produk favorit untuk dibeli nanti.',
                'icon' => '❤️',
                'is_infrastructure' => false,
                'sort_order' => 2,
            ],
            [
                'category_slug' => 'marketing',
                'name' => 'Review & Rating',
                'slug' => 'review-rating',
                'description' => 'Sistem ulasan dan rating produk dari pelanggan yang sudah membeli.',
                'icon' => '⭐',
                'is_infrastructure' => false,
                'sort_order' => 3,
            ],

            // ─── Administrasi ─────────────────────────────────────────────
            [
                'category_slug' => 'administrasi',
                'name' => 'Dashboard Admin',
                'slug' => 'dashboard-admin',
                'description' => 'Panel admin lengkap untuk mengelola semua aspek toko online.',
                'icon' => '🖥️',
                'is_infrastructure' => false,
                'sort_order' => 1,
            ],
            [
                'category_slug' => 'administrasi',
                'name' => 'Laporan Penjualan',
                'slug' => 'laporan-penjualan',
                'description' => 'Laporan penjualan harian, mingguan, bulanan, dan tahunan dengan grafik.',
                'icon' => '📈',
                'is_infrastructure' => false,
                'sort_order' => 2,
            ],
            [
                'category_slug' => 'administrasi',
                'name' => 'Export Excel / PDF',
                'slug' => 'export-excel-pdf',
                'description' => 'Ekspor laporan penjualan, daftar pesanan, dan data produk ke format Excel dan PDF.',
                'icon' => '📄',
                'is_infrastructure' => false,
                'sort_order' => 3,
            ],
            [
                'category_slug' => 'administrasi',
                'name' => 'Multi-role Admin',
                'slug' => 'multi-role-admin',
                'description' => 'Sistem manajemen admin dengan berbagai level akses (super admin, admin, operator).',
                'icon' => '👥',
                'is_infrastructure' => false,
                'sort_order' => 4,
            ],
            [
                'category_slug' => 'administrasi',
                'name' => 'Notifikasi Email',
                'slug' => 'notifikasi-email',
                'description' => 'Notifikasi otomatis via email untuk pesanan baru, pembayaran, dan status pengiriman.',
                'icon' => '📧',
                'is_infrastructure' => false,
                'sort_order' => 5,
            ],
            [
                'category_slug' => 'administrasi',
                'name' => 'Notifikasi WhatsApp',
                'slug' => 'notifikasi-whatsapp',
                'description' => 'Notifikasi otomatis via WhatsApp API untuk pesanan baru dan update status.',
                'icon' => '💬',
                'is_infrastructure' => false,
                'sort_order' => 6,
            ],
            [
                'category_slug' => 'administrasi',
                'name' => 'Analytics',
                'slug' => 'analytics',
                'description' => 'Integrasi Google Analytics dan dashboard analitik pengunjung, konversi, dan perilaku user.',
                'icon' => '📉',
                'is_infrastructure' => false,
                'sort_order' => 7,
            ],

            // ─── SEO ──────────────────────────────────────────────────────
            [
                'category_slug' => 'seo',
                'name' => 'SEO Dasar',
                'slug' => 'seo-dasar',
                'description' => 'Optimasi dasar: meta title, meta description, Open Graph, sitemap XML, dan robots.txt.',
                'icon' => '🔍',
                'is_infrastructure' => false,
                'sort_order' => 1,
            ],
            [
                'category_slug' => 'seo',
                'name' => 'SEO Lanjutan',
                'slug' => 'seo-lanjutan',
                'description' => 'SEO lanjutan: schema markup, structured data, canonical URL, breadcrumbs, dan optimasi kecepatan.',
                'icon' => '🚀',
                'is_infrastructure' => false,
                'sort_order' => 2,
            ],

            // ─── Integrasi ────────────────────────────────────────────────
            [
                'category_slug' => 'integrasi',
                'name' => 'API / Integrasi Eksternal',
                'slug' => 'api-integrasi-eksternal',
                'description' => 'Pengembangan dan integrasi REST API atau webhook dengan sistem eksternal (ERP, CRM, marketplace, dll.).',
                'icon' => '🔌',
                'is_infrastructure' => false,
                'sort_order' => 1,
            ],

            // ─── Infrastruktur (sudah termasuk paket) ─────────────────────
            [
                'category_slug' => 'infrastruktur',
                'name' => 'Hosting / VPS',
                'slug' => 'hosting-vps',
                'description' => 'Layanan hosting atau VPS untuk menjalankan website. Sudah diperhitungkan dalam harga paket sewa.',
                'icon' => '🖥️',
                'is_infrastructure' => true,
                'sort_order' => 1,
            ],
            [
                'category_slug' => 'infrastruktur',
                'name' => 'Domain',
                'slug' => 'domain',
                'description' => 'Registrasi dan pembaruan domain website. Sudah diperhitungkan dalam harga paket sewa.',
                'icon' => '🌐',
                'is_infrastructure' => true,
                'sort_order' => 2,
            ],
            [
                'category_slug' => 'infrastruktur',
                'name' => 'SSL / HTTPS',
                'slug' => 'ssl-https',
                'description' => 'Sertifikat SSL untuk keamanan dan enkripsi data. Sudah diperhitungkan dalam harga paket sewa.',
                'icon' => '🔒',
                'is_infrastructure' => true,
                'sort_order' => 3,
            ],
            [
                'category_slug' => 'infrastruktur',
                'name' => 'Backup Otomatis',
                'slug' => 'backup-otomatis',
                'description' => 'Backup data otomatis harian/mingguan. Sudah diperhitungkan dalam harga paket sewa.',
                'icon' => '💾',
                'is_infrastructure' => true,
                'sort_order' => 4,
            ],
            [
                'category_slug' => 'infrastruktur',
                'name' => 'Maintenance Teknis',
                'slug' => 'maintenance-teknis',
                'description' => 'Pemeliharaan teknis rutin, update keamanan, dan monitoring uptime. Sudah diperhitungkan dalam harga paket sewa.',
                'icon' => '🔧',
                'is_infrastructure' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($features as $featureData) {
            $categorySlug = $featureData['category_slug'];
            unset($featureData['category_slug']);

            $category = Category::where('slug', $categorySlug)->first();
            if (! $category) {
                continue;
            }

            Feature::firstOrCreate(
                ['slug' => $featureData['slug']],
                array_merge($featureData, [
                    'category_id' => $category->id,
                    'status' => 'active',
                ])
            );
        }
    }
}
