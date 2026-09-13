<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan & Performa Armada - QUANTUM STREAMLINE</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }
        body {
            background-color: #f8fafc;
            color: #0f172a;
            padding: 30px;
            font-size: 12px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }
        .no-print-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding: 12px 20px;
            background: #0f172a;
            color: #ffffff;
            border-radius: 8px;
        }
        .btn-print {
            background: #dc2626;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn-print:hover {
            background: #b91c1c;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            border-bottom: 3px double #dc2626;
            padding-bottom: 16px;
        }
        .header-logo {
            font-size: 20px;
            font-weight: 900;
            letter-spacing: 1px;
            color: #0f172a;
            text-transform: uppercase;
        }
        .header-logo span {
            color: #dc2626;
        }
        .company-info {
            text-align: right;
            font-size: 11px;
            color: #475569;
            line-height: 1.4;
        }
        .report-title {
            text-align: center;
            margin: 20px 0;
        }
        .report-title h1 {
            font-size: 18px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #0f172a;
        }
        .report-title p {
            font-size: 11px;
            color: #64748b;
            margin-top: 4px;
        }
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }
        .metric-card {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 12px;
        }
        .metric-card .label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
        }
        .metric-card .value {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 4px;
        }
        .metric-card.highlight {
            background: #fef2f2;
            border-color: #fca5a5;
        }
        .metric-card.highlight .value {
            color: #dc2626;
        }
        table.ledger {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            font-size: 11px;
        }
        table.ledger th {
            background: #0f172a;
            color: #ffffff;
            padding: 10px 12px;
            text-transform: uppercase;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-align: left;
        }
        table.ledger td {
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
        }
        table.ledger tr:nth-child(even) {
            background: #f8fafc;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: 700; }
        .font-mono { font-family: monospace; }
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-disetujui { background: #dcfce7; color: #15803d; }
        .status-pending { background: #fef9c3; color: #a16207; }
        .status-ditolak { background: #fee2e2; color: #b91c1c; }

        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .sig-box {
            text-align: center;
            width: 200px;
        }
        .sig-space {
            height: 60px;
        }
        .sig-line {
            border-bottom: 1px solid #0f172a;
            font-weight: bold;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .container {
                box-shadow: none;
                border: none;
                padding: 0;
                max-width: 100%;
            }
            .no-print-bar {
                display: none !important;
            }
            @page {
                size: A4 portrait;
                margin: 1.5cm;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Control Bar (Hidden on Print) -->
        <div class="no-print-bar">
            <div>
                <strong>Laporan Resmi Keuangan & Armada</strong> — Dokumen Cetak Executive
            </div>
            <button onclick="window.print()" class="btn-print">🖨️ Cetak / Simpan PDF</button>
        </div>

        <!-- Header -->
        <table class="header-table">
            <tr>
                <td style="vertical-align: top;">
                    <div class="header-logo">QUANTUM <span>STREAMLINE</span></div>
                    <div style="font-size: 10px; color: #64748b; font-weight: bold; margin-top: 2px;">
                        EXECUTIVE LUXURY CAR RENTAL
                    </div>
                </td>
                <td class="company-info" style="vertical-align: top;">
                    <strong>{{ $company['name'] }}</strong><br>
                    {{ $company['address'] }}<br>
                    Hotline: {{ $company['phone'] }} | Email: {{ $company['email'] }}
                </td>
            </tr>
        </table>

        <!-- Report Title -->
        <div class="report-title">
            <h1>LAPORAN KEUANGAN & UTILISASI ARMADA</h1>
            <p>Periode Data Cetak: {{ date('d F Y') }} | Pukul {{ date('H:i') }} WIB</p>
        </div>

        <!-- Metrics Grid -->
        <div class="metrics-grid">
            <div class="metric-card highlight">
                <div class="label">Total Omset Terverifikasi</div>
                <div class="value">Rp {{ number_format($keuangan, 0, ',', '.') }}</div>
            </div>
            <div class="metric-card">
                <div class="label">Total Reservasi Sukses</div>
                <div class="value">{{ $approvedTransactions->count() }} Transaksi</div>
            </div>
            <div class="metric-card">
                <div class="label">Armada Tersedia</div>
                <div class="value">{{ $stok_tersedia }} / {{ $total_mobil }} Unit</div>
            </div>
            <div class="metric-card">
                <div class="label">Armada Sedang Disewa</div>
                <div class="value">{{ $stok_disewa }} Unit</div>
            </div>
        </div>

        <!-- Transactions Ledger Table -->
        <h3 style="font-size: 12px; font-weight: bold; text-transform: uppercase; margin-bottom: 8px; color: #0f172a;">
            Buku Besar Transaksi Reservasi
        </h3>

        <table class="ledger">
            <thead>
                <tr>
                    <th>No. Booking</th>
                    <th>Tanggal</th>
                    <th>Nama Pelanggan</th>
                    <th>Unit Kendaraan</th>
                    <th>Periode Sewa</th>
                    <th>Layanan</th>
                    <th>Status</th>
                    <th class="text-right">Total Nilai</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksi as $t)
                <tr>
                    <td class="font-mono font-bold">{{ $t->nomor_booking ?? ('#TRX-'.$t->id) }}</td>
                    <td>{{ $t->created_at ? $t->created_at->format('d/m/Y') : '-' }}</td>
                    <td class="font-bold">{{ $t->user->name ?? 'Pelanggan' }}</td>
                    <td>{{ $t->mobil->nama_mobil ?? 'Unit Dihapus' }} ({{ $t->mobil->merk ?? '-' }})</td>
                    <td>{{ date('d/m/Y', strtotime($t->tanggal_mulai)) }} - {{ date('d/m/Y', strtotime($t->tanggal_selesai)) }}</td>
                    <td>{{ $t->layanan == 'dengan_sopir' ? 'Dgn Sopir' : 'Lepas Kunci' }}</td>
                    <td>
                        <span class="status-badge status-{{ strtolower($t->status) }}">
                            {{ $t->status }}
                        </span>
                    </td>
                    <td class="text-right font-mono font-bold">
                        Rp {{ number_format($t->total_harga, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px; color: #94a3b8;">
                        Belum ada data transaksi untuk ditampilkan.
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background: #f1f5f9; font-weight: bold;">
                    <td colspan="7" class="text-right" style="padding: 10px 12px; text-transform: uppercase;">
                        Total Pendapatan Terverifikasi (Status Disetujui):
                    </td>
                    <td class="text-right font-mono" style="padding: 10px 12px; color: #dc2626; font-size: 13px;">
                        Rp {{ number_format($keuangan, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- Signatures -->
        <div class="signature-section">
            <div class="sig-box">
                <p>Dibuat Oleh,</p>
                <div class="sig-space"></div>
                <p class="sig-line">Finance & Accounting Admin</p>
                <p style="font-size: 10px; color: #64748b;">Quantum Streamline</p>
            </div>
            <div class="sig-box">
                <p>Disetujui Oleh,</p>
                <div class="sig-space"></div>
                <p class="sig-line">Director of Operations</p>
                <p style="font-size: 10px; color: #64748b;">Quantum Streamline</p>
            </div>
        </div>
    </div>

</body>
</html>
