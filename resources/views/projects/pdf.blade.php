<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Quotation — {{ $project->name }}</title>
    <style>
        @page {
            margin: 20mm 15mm 20mm 15mm;
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #1e293b;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 15px;
        }
        .header-table td {
            vertical-align: top;
        }
        .brand-title {
            font-size: 20px;
            font-weight: 800;
            color: #1e1b4b;
            letter-spacing: -0.5px;
        }
        .brand-subtitle {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }
        .quotation-title {
            font-size: 16px;
            font-weight: 800;
            color: #4f46e5;
            text-align: right;
            text-transform: uppercase;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .meta-table td {
            vertical-align: top;
            padding: 6px 8px;
            font-size: 11px;
        }
        .meta-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px;
        }
        .meta-box-title {
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 15px;
        }
        table.items-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 10px;
            border: 1px solid #cbd5e1;
            text-align: left;
        }
        table.items-table td {
            padding: 7px 10px;
            border: 1px solid #e2e8f0;
            font-size: 10.5px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge-included {
            display: inline-block;
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
            border-radius: 4px;
            padding: 1px 5px;
            font-size: 8.5px;
            font-weight: 700;
        }
        .badge-addon {
            display: inline-block;
            background: #fffbeb;
            color: #d97706;
            border: 1px solid #fde68a;
            border-radius: 4px;
            padding: 1px 5px;
            font-size: 8.5px;
            font-weight: 700;
        }
        .total-section {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .total-section td {
            padding: 4px 8px;
        }
        .grand-total-row {
            background: #1e1b4b;
            color: #ffffff;
            font-weight: 800;
            font-size: 13px;
        }
        .grand-total-row td {
            padding: 8px 10px;
        }
        .terms-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 12px;
            margin-top: 15px;
            font-size: 9.5px;
            color: #475569;
        }
        .terms-title {
            font-size: 10px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
            text-transform: uppercase;
        }
        .signature-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        .signature-table td {
            width: 50%;
            vertical-align: top;
            text-align: center;
            font-size: 10px;
        }
        .signature-line {
            width: 160px;
            border-bottom: 1px solid #475569;
            margin: 60px auto 4px auto;
        }
    </style>
</head>
<body>
    <!-- Header Table -->
    <table class="header-table">
        <tr>
            <td style="width: 55%;">
                <div class="brand-title">E-COMMERCE CONFIGURATOR</div>
                <div class="brand-subtitle">Layanan Pengembangan & Sewa Sistem E-Commerce Tahunan</div>
            </td>
            <td style="width: 45%;" class="text-right">
                <div class="quotation-title">PENAWARAN HARGA</div>
                <div style="font-size: 10px; color: #64748b; margin-top: 2px;">
                    No: <strong style="color: #1e293b;">{{ $project->quotation?->quotation_number ?? 'QUO-'.date('Ym').'-'.sprintf('%04d', $project->id) }}</strong>
                </div>
            </td>
        </tr>
    </table>

    <!-- Meta Information: Bill To & Details -->
    <table class="meta-table">
        <tr>
            <td style="width: 50%; padding-left: 0;">
                <div class="meta-box">
                    <div class="meta-box-title">DITUJUKAN KEPADA (BILL TO):</div>
                    <div style="font-weight: 800; font-size: 12px; color: #1e293b;">{{ $project->customer_name }}</div>
                    @if ($project->customer_company)
                        <div style="color: #475569; font-weight: 600;">{{ $project->customer_company }}</div>
                    @endif
                    <div style="color: #64748b; margin-top: 2px;">Email: {{ $project->customer_email }}</div>
                    <div style="color: #64748b;">WhatsApp/Telp: {{ $project->customer_phone }}</div>
                </div>
            </td>
            <td style="width: 50%; padding-right: 0;">
                <div class="meta-box">
                    <div class="meta-box-title">INFORMASI PROYEK & TANGGAL:</div>
                    <div style="font-weight: 700; color: #1e293b;">Proyek: {{ $project->name }}</div>
                    <div>Paket Pilihan: <strong style="color: #4f46e5;">{{ $project->package?->name ?? 'Custom' }}</strong></div>
                    <div>Tanggal Terbit: {{ ($project->quotation?->issued_at ?? $project->created_at)->format('d F Y') }}</div>
                    <div>Masa Berlaku: <strong style="color: #b45309;">{{ ($project->quotation?->valid_until ?? now()->addDays(30))->format('d F Y') }} (30 Hari)</strong></div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Scope Note -->
    <div style="font-size: 10px; color: #64748b; margin-bottom: 8px;">
        Berikut adalah rincian spesifikasi teknis dan estimasi investasi untuk pengembangan toko online Anda:
    </div>

    <!-- Items Table: Features Snapshot -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;">Spesifikasi Fitur</th>
                <th style="width: 20%;">Kategori</th>
                <th style="width: 12%;" class="text-center">Varian</th>
                <th style="width: 13%;" class="text-center">Status</th>
                <th style="width: 15%;" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <!-- Base Package Row -->
            <tr style="background-color: #f8fafc; font-weight: 700;">
                <td class="text-center">1</td>
                <td>
                    <strong style="color: #1e1b4b;">Paket Sewa Dasar: {{ $project->package?->name }}</strong>
                    <div style="font-size: 9px; font-weight: 400; color: #64748b;">Mencakup hosting/VPS, domain, SSL, backup, dan maintenance</div>
                </td>
                <td>Infrastruktur & Core</td>
                <td class="text-center">Tahunan</td>
                <td class="text-center"><span class="badge-included">Paket Inti</span></td>
                <td class="text-right" style="font-family: monospace;">Rp {{ number_format((float)$project->package_price_snapshot, 0, ',', '.') }}</td>
            </tr>

            @php $rowNum = 2; @endphp
            @foreach ($project->projectFeatures as $pf)
                <tr>
                    <td class="text-center" style="color: #64748b;">{{ $rowNum++ }}</td>
                    <td>
                        <div style="font-weight: 600; color: #1e293b;">{{ $pf->feature_name }}</div>
                    </td>
                    <td style="color: #64748b;">{{ $pf->category_name ?? '—' }}</td>
                    <td class="text-center" style="text-transform: uppercase; font-size: 9px; color: #475569;">{{ $pf->complexity }}</td>
                    <td class="text-center">
                        @if ($pf->is_included_in_package)
                            <span class="badge-included">Included</span>
                        @else
                            <span class="badge-addon">Add-on</span>
                        @endif
                    </td>
                    <td class="text-right" style="font-family: monospace; font-weight: 600;">
                        {{ $pf->is_included_in_package ? 'Rp 0' : 'Rp ' . number_format((float)$pf->subtotal, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach

            <!-- Add-ons Rows (If any) -->
            @foreach ($project->projectAddons as $pa)
                <tr>
                    <td class="text-center" style="color: #64748b;">{{ $rowNum++ }}</td>
                    <td>
                        <strong style="color: #0f172a;">{{ $pa->addon_name }}</strong>
                        <div style="font-size: 9px; color: #64748b;">Modul Add-on & Pengembangan Khusus</div>
                    </td>
                    <td>Add-on Modul</td>
                    <td class="text-center">{{ $pa->quantity }} Qty</td>
                    <td class="text-center"><span class="badge-addon">Custom</span></td>
                    <td class="text-right" style="font-family: monospace; font-weight: 700; color: #0f172a;">
                        Rp {{ number_format((float)$pa->subtotal, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals Summary Table -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <div class="terms-box">
                    <div class="terms-title">Infrastruktur & SLA Standar Termasuk:</div>
                    <ul style="margin: 0; padding-left: 15px; font-size: 9px; color: #475569;">
                        <li>Layanan Server VPS / Cloud Hosting 99.9% Uptime.</li>
                        <li>Sertifikat Keamanan SSL 256-bit Encryption.</li>
                        <li>Pencadangan Data Database & Aset Otomatis.</li>
                        <li>Garansi Teknis & Update Pemeliharaan Sistem.</li>
                    </ul>
                </div>
            </td>
            <td style="width: 50%; vertical-align: top; padding-left: 15px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr class="grand-total-row">
                        <td style="padding: 10px; font-size: 11px;">TOTAL INVESTASI SEWA TAHUNAN:</td>
                        <td class="text-right" style="padding: 10px; font-family: monospace; font-size: 14px;">
                            Rp {{ number_format((float)$project->total_selling_price, 0, ',', '.') }}
                        </td>
                    </tr>
                </table>
                <div style="font-size: 9px; color: #64748b; text-align: right; margin-top: 4px;">
                    *Harga sewa tahunan berlaku untuk 12 bulan layanan penuh.
                </div>
            </td>
        </tr>
    </table>

    <!-- Terms & Conditions -->
    <div class="terms-box" style="margin-top: 15px;">
        <div class="terms-title">SYARAT & KETENTUAN PENAWARAN (TERMS & CONDITIONS):</div>
        <div style="font-size: 9px; line-height: 1.6;">
            1. Penawaran harga bersifat tetap selama 30 (tiga puluh) hari kalender sejak tanggal diterbitkan.<br>
            2. Biaya yang tercantum sudah mencakup biaya infrastruktur dasar dan konfigurasi sistem sesuai spesifikasi fitur di atas.<br>
            3. Waktu pengerjaan dan deployment standar berkisar antara 7 hingga 21 hari kerja setelah konfirmasi pesanan.<br>
            4. Pembayaran dilakukan via transfer bank resmi setelah konfirmasi pesanan ditandatangani.
        </div>
    </div>

    <!-- Signatures -->
    <table class="signature-table">
        <tr>
            <td>
                <div>Disiapkan Oleh:</div>
                <div style="font-weight: 700; color: #1e293b;">E-COMMERCE CONFIGURATOR</div>
                <div class="signature-line"></div>
                <div style="font-size: 9px; color: #64748b;">Tim Konsultan Teknis</div>
            </td>
            <td>
                <div>Disetujui Oleh (Klien):</div>
                <div style="font-weight: 700; color: #1e293b;">{{ $project->customer_company ?: $project->customer_name }}</div>
                <div class="signature-line"></div>
                <div style="font-size: 9px; color: #64748b;">( {{ $project->customer_name }} )</div>
            </td>
        </tr>
    </table>
</body>
</html>
