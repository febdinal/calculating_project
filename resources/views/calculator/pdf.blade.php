<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Estimasi Biaya Website — Paket {{ $package->name }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 25mm 20mm;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            line-height: 1.5;
            font-size: 11pt;
            margin: 0;
            padding: 0;
        }

        .header {
            border-bottom: 2px solid #6366f1;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header table {
            width: 100%;
        }

        .title {
            font-size: 18pt;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .subtitle {
            font-size: 10pt;
            color: #64748b;
            margin-top: 4px;
        }

        .meta-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 25px;
        }

        .meta-table {
            width: 100%;
        }

        .meta-table td {
            padding: 3px 0;
            font-size: 10pt;
        }

        .section-title {
            font-size: 12pt;
            font-weight: bold;
            color: #0f172a;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 6px;
            margin-bottom: 12px;
            margin-top: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        table.items-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 10px;
            border: 1px solid #cbd5e1;
            text-align: left;
        }

        table.items-table td {
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
            font-size: 9.5pt;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .sub-features-text {
            font-size: 8.5pt;
            color: #64748b;
            margin-top: 3px;
        }

        .badge-included {
            display: inline-block;
            background-color: #dcfce7;
            color: #166534;
            font-size: 8pt;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .badge-additional {
            display: inline-block;
            background-color: #e0e7ff;
            color: #3730a3;
            font-size: 8pt;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .summary-box {
            width: 55%;
            margin-left: auto;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 25px;
        }

        .summary-table {
            width: 100%;
        }

        .summary-table td {
            padding: 4px 0;
            font-size: 10pt;
        }

        .summary-total {
            border-top: 2px solid #6366f1;
            padding-top: 8px;
            margin-top: 6px;
            font-size: 12pt;
            font-weight: bold;
            color: #1e1b4b;
        }

        .note-box {
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 10px 14px;
            font-size: 9pt;
            color: #92400e;
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 8.5pt;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <table>
            <tr>
                <td>
                    <h1 class="title">ESTIMASI BIAYA WEBSITE</h1>
                    <div class="subtitle">Website Feature Configurator & Price Calculator</div>
                </td>
                <td class="text-right" style="vertical-align: middle;">
                    <div style="font-size: 9pt; color: #64748b;">
                        Tanggal Dokumen: <strong>{{ $generatedAt->translatedFormat('d F Y') }}</strong>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Meta Package Box -->
    <div class="meta-box">
        <table class="meta-table">
            <tr>
                <td style="width: 25%; font-weight: bold; color: #475569;">Paket Pilihan</td>
                <td style="width: 35%; font-weight: bold; color: #0f172a;">: Paket {{ $package->name }}</td>
                <td style="width: 20%; font-weight: bold; color: #475569;">Harga Paket Dasar</td>
                <td style="width: 20%; font-weight: bold; color: #4338ca;" class="text-right">: Rp {{ number_format($package->price, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold; color: #475569;">Periode Layanan</td>
                <td style="color: #0f172a;">: 1 {{ ucfirst($package->period) }}</td>
                <td style="font-weight: bold; color: #475569;">Total Fitur Dipilih</td>
                <td style="color: #0f172a;" class="text-right">: {{ count($includedFeatures) + count($additionalFeatures) }} Fitur</td>
            </tr>
        </table>
    </div>

    <!-- Features Table -->
    <div class="section-title">RINCIAN FITUR YANG DIPILIH</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 6%;" class="text-center">No</th>
                <th style="width: 44%;">Nama Fitur</th>
                <th style="width: 22%;">Kategori</th>
                <th style="width: 15%;" class="text-center">Status</th>
                <th style="width: 13%;" class="text-right">Harga</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp

            {{-- 1. Included Features --}}
            @foreach ($includedFeatures as $item)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>
                        <div class="font-bold">{{ $item['name'] }}</div>
                        @php
                            $activeSubs = collect($item['sub_features'] ?? [])->filter(fn($s) => ($s['is_selected'] ?? true) === true);
                        @endphp
                        @if ($activeSubs->isNotEmpty())
                            <div class="sub-features-text">
                                Rincian Sub Fitur: {{ $activeSubs->map(fn($s) => $s['name'] . (!empty($s['price']) ? ' (Rp ' . number_format($s['price'], 0, ',', '.') . ')' : ''))->join(', ') }}
                            </div>
                        @endif
                    </td>
                    <td>{{ $item['category_name'] }}</td>
                    <td class="text-center">
                        <span class="badge-included">Termasuk Paket</span>
                    </td>
                    <td class="text-right font-bold">
                        Rp {{ number_format($item['price'], 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach

            {{-- 2. Additional Features --}}
            @foreach ($additionalFeatures as $item)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>
                        <div class="font-bold">{{ $item['name'] }}</div>
                        @php
                            $activeSubs = collect($item['sub_features'] ?? [])->filter(fn($s) => ($s['is_selected'] ?? true) === true);
                        @endphp
                        @if ($activeSubs->isNotEmpty())
                            <div class="sub-features-text">
                                Rincian Sub Fitur: {{ $activeSubs->map(fn($s) => $s['name'] . (!empty($s['price']) ? ' (Rp ' . number_format($s['price'], 0, ',', '.') . ')' : ''))->join(', ') }}
                            </div>
                        @endif
                    </td>
                    <td>{{ $item['category_name'] }}</td>
                    <td class="text-center">
                        <span class="badge-additional">Fitur Tambahan</span>
                    </td>
                    <td class="text-right font-bold">
                        Rp {{ number_format($item['price'], 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Calculation Summary Box -->
    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td style="color: #475569;">Harga Paket Dasar ({{ $package->name }})</td>
                <td class="text-right font-bold">Rp {{ number_format($packagePrice ?? $package->price, 0, ',', '.') }}</td>
            </tr>
            @if (!empty($includedDeduction) && $includedDeduction > 0)
                <tr>
                    <td style="color: #059669; font-weight: 600;">Potongan Pengurangan Sub-Fitur Paket</td>
                    <td class="text-right font-bold" style="color: #059669;">- Rp {{ number_format($includedDeduction, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="color: #475569; font-style: italic;">Subtotal Paket Disesuaikan</td>
                    <td class="text-right font-bold">Rp {{ number_format($adjustedPackagePrice ?? ($package->price - $includedDeduction), 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr>
                <td style="color: #475569;">Total Fitur Tambahan ({{ count($additionalFeatures) }} Fitur)</td>
                <td class="text-right font-bold">Rp {{ number_format($additionalFeaturesTotal, 0, ',', '.') }}</td>
            </tr>
            <tr class="summary-total">
                <td style="font-weight: bold; color: #0f172a;">TOTAL ESTIMASI BIAYA</td>
                <td class="text-right font-bold" style="color: #4338ca; font-size: 13pt;">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Notes -->
    <div class="note-box">
        <strong>Catatan:</strong> Dokumen ini merupakan estimasi biaya berdasarkan konfigurasi fitur yang dipilih. Biaya di atas dihitung untuk periode 1 (satu) {{ $package->period }}.
    </div>

    <!-- Footer -->
    <div class="footer">
        Website Feature Configurator &bull; Dokumen dibuat otomatis pada {{ $generatedAt->translatedFormat('d F Y, H:i') }} WIB
    </div>

</body>
</html>
