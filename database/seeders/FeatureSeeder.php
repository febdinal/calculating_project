<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Feature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        $featuresData = [
            // 1. Website & Tampilan
            [
                'category_slug' => 'website-tampilan',
                'name' => 'Website Responsive',
                'slug' => 'website-responsive',
                'description' => 'Desain website fleksibel dan optimal di seluruh perangkat smartphone, tablet, dan desktop.',
                'icon' => '🌐',
                'sort_order' => 1,
                'sub_features' => [
                    ['name' => 'Layout Mobile & Desktop Fleksibel', 'price' => 750000],
                    ['name' => 'Cross-Browser & Device Compatibility', 'price' => 450000],
                    ['name' => 'Aset Gambar Ringan & Cepat Dimuat', 'price' => 400000],
                    ['name' => 'Dukungan Dark Mode & Light Mode', 'price' => 400000],
                ],
            ],
            [
                'category_slug' => 'website-tampilan',
                'name' => 'Custom Homepage Slider',
                'slug' => 'custom-homepage-slider',
                'description' => 'Slider carousel visual di beranda untuk menonjolkan produk unggulan dan promo terkini.',
                'icon' => '🖼️',
                'sort_order' => 2,
                'sub_features' => [
                    ['name' => 'Hero Banner Dinamis & Responsif', 'price' => 400000],
                    ['name' => 'Tombol CTA (Call to Action) Interaktif', 'price' => 300000],
                    ['name' => 'Auto-play Carousel dengan Pengaturan Waktu', 'price' => 300000],
                ],
            ],
            [
                'category_slug' => 'website-tampilan',
                'name' => 'Halaman Statis & Profil',
                'slug' => 'halaman-statis-profil',
                'description' => 'Halaman Tentang Kami, Kontak, Lokasi Peta Google, dan Syarat Ketentuan Layanan.',
                'icon' => '📄',
                'sort_order' => 3,
                'sub_features' => [
                    ['name' => 'Halaman Profil Tentang Kami', 'price' => 250000],
                    ['name' => 'Form Kontak & Lokasi Peta Google Maps', 'price' => 250000],
                    ['name' => 'FAQ (Tanya Jawab) Interaktif', 'price' => 150000],
                    ['name' => 'Halaman Kebijakan Privasi & Ketentuan', 'price' => 150000],
                ],
            ],
            [
                'category_slug' => 'website-tampilan',
                'name' => 'Blog & Publikasi Artikel',
                'slug' => 'blog-publikasi-artikel',
                'description' => 'Sistem blog untuk edukasi pelanggan, berita brand, dan pendukung konten SEO organik.',
                'icon' => '✍️',
                'sort_order' => 4,
                'sub_features' => [
                    ['name' => 'Editor Artikel Rich Text', 'price' => 500000],
                    ['name' => 'Kategori & Tagging Artikel', 'price' => 350000],
                    ['name' => 'Fitur Share ke Media Sosial', 'price' => 350000],
                ],
            ],

            // 2. Katalog & Produk
            [
                'category_slug' => 'katalog-produk',
                'name' => 'Katalog Produk Interaktif',
                'slug' => 'katalog-produk-interaktif',
                'description' => 'Tampilan etalase produk lengkap dengan filter kategori, harga, dan halaman detail produk.',
                'icon' => '📦',
                'sort_order' => 5,
                'sub_features' => [
                    ['name' => 'Daftar Produk Grid dengan Filter Kategori', 'price' => 500000],
                    ['name' => 'Halaman Detail Produk Lengkap', 'price' => 400000],
                    ['name' => 'Variasi Produk (Ukuran, Warna, Opsi)', 'price' => 350000],
                    ['name' => 'Pencarian Cepat dengan Live Search', 'price' => 250000],
                ],
            ],
            [
                'category_slug' => 'katalog-produk',
                'name' => 'Galeri Foto Multi-Sudut & Zoom',
                'slug' => 'galeri-foto-zoom',
                'description' => 'Penampil foto produk berkualitas tinggi dengan fitur zoom interaktif dan galeri thumbnail.',
                'icon' => '🔍',
                'sort_order' => 6,
                'sub_features' => [
                    ['name' => 'Fitur Hover Image Zoom', 'price' => 300000],
                    ['name' => 'Lightbox Preview Popup Layar Penuh', 'price' => 250000],
                    ['name' => 'Thumbnail Carousel Multi-Foto', 'price' => 200000],
                ],
            ],
            [
                'category_slug' => 'katalog-produk',
                'name' => 'Manajemen Stok & Inventori',
                'slug' => 'manajemen-stok-inventori',
                'description' => 'Pengawasan ketersediaan stok barang secara otomatis saat transaksi berlangsung.',
                'icon' => '📊',
                'sort_order' => 7,
                'sub_features' => [
                    ['name' => 'Pengurangan Stok Otomatis saat Pesanan', 'price' => 500000],
                    ['name' => 'Peringatan Stok Rendah (Low Stock Alert)', 'price' => 350000],
                    ['name' => 'Fitur Batch Update Stok Produk', 'price' => 350000],
                ],
            ],

            // 3. Transaksi & Pesanan
            [
                'category_slug' => 'transaksi-pesanan',
                'name' => 'Pemesanan Direct WhatsApp',
                'slug' => 'pemesanan-direct-whatsapp',
                'description' => 'Tombol pesan langsung ke nomor WhatsApp admin dengan template format pesanan otomatis.',
                'icon' => '💬',
                'sort_order' => 8,
                'sub_features' => [
                    ['name' => 'Tombol WhatsApp di Setiap Produk', 'price' => 250000],
                    ['name' => 'Template Pesan Otomatis (Nama, Item, Jumlah)', 'price' => 200000],
                    ['name' => 'Dukungan Multi-Nomor CS / Rotasi CS', 'price' => 150000],
                ],
            ],
            [
                'category_slug' => 'transaksi-pesanan',
                'name' => 'Keranjang & Checkout Online',
                'slug' => 'keranjang-checkout-online',
                'description' => 'Sistem keranjang belanja interaktif dan alur checkout formulir pemesanan terstruktur.',
                'icon' => '🛒',
                'sort_order' => 9,
                'sub_features' => [
                    ['name' => 'Keranjang Belanja Realtime Floating Bar', 'price' => 800000],
                    ['name' => 'Formulir Alamat Pengiriman Terstruktur', 'price' => 600000],
                    ['name' => 'Kalkulasi Total Otomatis', 'price' => 500000],
                    ['name' => 'Penyimpanan Riwayat Checkout Pelanggan', 'price' => 600000],
                ],
            ],
            [
                'category_slug' => 'transaksi-pesanan',
                'name' => 'Notifikasi Status Pesanan',
                'slug' => 'notifikasi-status-pesanan',
                'description' => 'Pengiriman informasi status pesanan dan resi pengiriman kepada pembeli secara instan.',
                'icon' => '🔔',
                'sort_order' => 10,
                'sub_features' => [
                    ['name' => 'Email Konfirmasi & Invoice Otomatis', 'price' => 500000],
                    ['name' => 'Notifikasi Status Pesanan Diproses & Dikirim', 'price' => 400000],
                ],
            ],

            // 4. Pembayaran & Checkout
            [
                'category_slug' => 'pembayaran-checkout',
                'name' => 'Payment Gateway Otomatis',
                'slug' => 'payment-gateway-otomatis',
                'description' => 'Integrasi gerbang pembayaran otomatis dengan verifikasi instan tanpa perlu cek mutasi manual.',
                'icon' => '💳',
                'sort_order' => 11,
                'sub_features' => [
                    ['name' => 'QRIS Otomatis (GoPay, OVO, DANA, ShopeePay)', 'price' => 600000],
                    ['name' => 'Virtual Account Bank (BCA, Mandiri, BNI, BRI)', 'price' => 600000],
                    ['name' => 'Pembayaran Kartu Kredit & Debit Online', 'price' => 500000],
                    ['name' => 'Verifikasi Pembayaran Otomatis Realtime', 'price' => 300000],
                ],
            ],
            [
                'category_slug' => 'pembayaran-checkout',
                'name' => 'Transfer Bank Manual',
                'slug' => 'transfer-bank-manual',
                'description' => 'Metode transfer bank konvensional dengan form upload bukti bayar bagi pembeli.',
                'icon' => '🏦',
                'sort_order' => 12,
                'sub_features' => [
                    ['name' => 'Daftar Nomor Rekening Perusahaan', 'price' => 200000],
                    ['name' => 'Formulir Upload Bukti Transfer', 'price' => 150000],
                    ['name' => 'Panel Konfirmasi Manual Admin', 'price' => 150000],
                ],
            ],

            // 5. Marketing & Promosi
            [
                'category_slug' => 'marketing-promosi',
                'name' => 'Kupon & Voucher Diskon',
                'slug' => 'kupon-voucher-diskon',
                'description' => 'Pembuatan kode voucher promo untuk diskon persentase, nominal tetap, atau potongan ongkir.',
                'icon' => '🎟️',
                'sort_order' => 13,
                'sub_features' => [
                    ['name' => 'Kode Voucher Persen & Nominal Rupiah', 'price' => 400000],
                    ['name' => 'Pengaturan Batas Kuota & Masa Berlaku', 'price' => 250000],
                    ['name' => 'Syarat Minimum Belanja', 'price' => 200000],
                ],
            ],
            [
                'category_slug' => 'marketing-promosi',
                'name' => 'Wishlist / Produk Favorit',
                'slug' => 'wishlist-produk-favorit',
                'description' => 'Fitur daftar keinginan agar pelanggan dapat menyimpan produk yang disukai untuk dibeli nanti.',
                'icon' => '❤️',
                'sort_order' => 14,
                'sub_features' => [
                    ['name' => 'Tombol Love / Simpan Produk', 'price' => 250000],
                    ['name' => 'Halaman Koleksi Wishlist Pribadi', 'price' => 200000],
                ],
            ],
            [
                'category_slug' => 'marketing-promosi',
                'name' => 'Ulasan & Rating Produk',
                'slug' => 'ulasan-rating-produk',
                'description' => 'Sistem review pelanggan dengan bintang penilaian, ulasan teks, dan foto testimoni.',
                'icon' => '⭐',
                'sort_order' => 15,
                'sub_features' => [
                    ['name' => 'Rating Bintang 1 sampai 5', 'price' => 250000],
                    ['name' => 'Form Komentar & Upload Foto Review', 'price' => 200000],
                    ['name' => 'Panel Moderasi Ulasan Admin', 'price' => 200000],
                ],
            ],
            [
                'category_slug' => 'marketing-promosi',
                'name' => 'Banner Promosi & Countdown Timer',
                'slug' => 'banner-promosi-countdown',
                'description' => 'Elemen hitung mundur flash sale dan bar pengumuman promo di bagian atas halaman.',
                'icon' => '⚡',
                'sort_order' => 16,
                'sub_features' => [
                    ['name' => 'Top Announcement Bar Promo', 'price' => 350000],
                    ['name' => 'Timer Hitung Mundur Flash Sale', 'price' => 350000],
                ],
            ],

            // 6. Administrasi & Laporan
            [
                'category_slug' => 'administrasi-laporan',
                'name' => 'Dashboard Admin & Laporan Penjualan',
                'slug' => 'dashboard-admin-laporan',
                'description' => 'Pusat kontrol operasional website dengan rangkuman grafik penjualan dan daftar pesanan masuk.',
                'icon' => '📈',
                'sort_order' => 17,
                'sub_features' => [
                    ['name' => 'Grafik Statistik Penjualan Berkala', 'price' => 800000],
                    ['name' => 'Daftar Produk Terlaris & Pelanggan Aktif', 'price' => 700000],
                    ['name' => 'Ekspor Laporan Transaksi ke Excel/PDF', 'price' => 700000],
                ],
            ],
            [
                'category_slug' => 'administrasi-laporan',
                'name' => 'Manajemen Multi-Admin & Hak Akses',
                'slug' => 'manajemen-multi-admin',
                'description' => 'Pengaturan banyak akun admin dengan peran berjenjang (Superadmin, CS, Staff Gudang).',
                'icon' => '👥',
                'sort_order' => 18,
                'sub_features' => [
                    ['name' => 'Peran Admin Berjenjang (Role Permission)', 'price' => 600000],
                    ['name' => 'Log Riwayat Aktivitas Akun Admin', 'price' => 500000],
                ],
            ],

            // 7. Optimasi & SEO
            [
                'category_slug' => 'optimasi-seo',
                'name' => 'Optimasi SEO On-Page Lengkap',
                'slug' => 'optimasi-seo-onpage',
                'description' => 'Konfigurasi meta tags dinamis, sitemap XML otomatis, dan struktur data schema markup.',
                'icon' => '🚀',
                'sort_order' => 19,
                'sub_features' => [
                    ['name' => 'Meta Title & Description Dinamis per Halaman', 'price' => 400000],
                    ['name' => 'Sitemap.xml & Robots.txt Otomatis', 'price' => 300000],
                    ['name' => 'Schema JSON-LD & OpenGraph Media Sosial', 'price' => 300000],
                ],
            ],
            [
                'category_slug' => 'optimasi-seo',
                'name' => 'Integrasi Google Analytics & Pixel',
                'slug' => 'google-analytics-pixel',
                'description' => 'Pemasangan pelacakan trafik pengunjung via Google Analytics 4 dan Meta Pixel.',
                'icon' => '📊',
                'sort_order' => 20,
                'sub_features' => [
                    ['name' => 'Pemasangan Kode Google Analytics 4', 'price' => 300000],
                    ['name' => 'Pemasangan Meta/Facebook Pixel Event', 'price' => 300000],
                ],
            ],

            // 8. Integrasi Sistem
            [
                'category_slug' => 'integrasi-sistem',
                'name' => 'Kalkulator Ongkos Kirim Otomatis',
                'slug' => 'kalkulator-ongkir-otomatis',
                'description' => 'Hitung otomatis tarif pengiriman kurir nasional (JNE, J&T, SiCepat, Pos) berdasarkan kecamatan.',
                'icon' => '🚚',
                'sort_order' => 21,
                'sub_features' => [
                    ['name' => 'Koneksi API Kurir Ekspedisi Nasional', 'price' => 600000],
                    ['name' => 'Pengecekan Tarif Otomatis per Kecamatan', 'price' => 500000],
                    ['name' => 'Fitur Cek Resi & Status Pengiriman', 'price' => 400000],
                ],
            ],
            [
                'category_slug' => 'integrasi-sistem',
                'name' => 'Integrasi WhatsApp Gateway API',
                'slug' => 'integrasi-whatsapp-gateway',
                'description' => 'Sistem bot WhatsApp untuk broadcast promosi dan notifikasi pengiriman resi otomatis.',
                'icon' => '🤖',
                'sort_order' => 22,
                'sub_features' => [
                    ['name' => 'Pengiriman Resi Otomatis via WhatsApp', 'price' => 1000000],
                    ['name' => 'Broadcast Pesan Promo ke Pelanggan', 'price' => 800000],
                ],
            ],
        ];

        foreach ($featuresData as $data) {
            $category = Category::where('slug', $data['category_slug'])->first();
            $categoryId = $category?->id;

            // Hitung total harga dan total harga real fitur utama dari total harga sub-fitur
            $totalFeaturePrice = 0;
            $totalFeatureRealPrice = 0;
            if (! empty($data['sub_features'])) {
                foreach ($data['sub_features'] as $subItem) {
                    $totalFeaturePrice += $subItem['price'];
                    $subRealPrice = $subItem['real_price'] ?? round($subItem['price'] * 0.60);
                    $totalFeatureRealPrice += $subRealPrice;
                }
            }

            $mainFeature = Feature::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'category_id' => $categoryId,
                    'parent_id' => null,
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'icon' => $data['icon'],
                    'price' => $totalFeaturePrice,
                    'real_price' => $totalFeatureRealPrice,
                    'sort_order' => $data['sort_order'],
                    'status' => 'active',
                ]
            );

            // Create or update sub features with their own prices and real prices
            if (! empty($data['sub_features'])) {
                foreach ($data['sub_features'] as $index => $subItem) {
                    $subSlug = Str::slug($mainFeature->slug.'-'.$subItem['name']);
                    $subRealPrice = $subItem['real_price'] ?? round($subItem['price'] * 0.60);
                    Feature::updateOrCreate(
                        ['slug' => $subSlug],
                        [
                            'category_id' => $categoryId,
                            'parent_id' => $mainFeature->id,
                            'name' => $subItem['name'],
                            'description' => null,
                            'icon' => null,
                            'price' => $subItem['price'],
                            'real_price' => $subRealPrice,
                            'sort_order' => $index + 1,
                            'status' => 'active',
                        ]
                    );
                }
            }
        }
    }
}
