<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Voucher Reservasi Quantum Streamline</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #070709;
            color: #ffffff;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        table {
            border-collapse: collapse;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #0f1015;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #26262b;
        }
        .header {
            background: linear-gradient(135deg, #181920 0%, #0c0d12 100%);
            padding: 36px 32px 28px;
            text-align: center;
            border-bottom: 2px solid #e63946;
        }
        .logo-title {
            font-size: 20px;
            font-weight: 900;
            letter-spacing: 3px;
            color: #ffffff;
            text-transform: uppercase;
            margin: 0 0 6px;
        }
        .logo-title span {
            color: #e63946;
        }
        .subtitle {
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #a1a1aa;
            margin: 0;
        }
        .content {
            padding: 32px;
        }
        .greeting {
            font-size: 16px;
            color: #e4e4e7;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        .badge-confirmed {
            display: inline-block;
            background-color: rgba(16, 185, 129, 0.15);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.4);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 6px 14px;
            border-radius: 999px;
            margin-bottom: 20px;
        }
        .booking-card {
            background-color: #171821;
            border: 1px solid #272832;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }
        .booking-code {
            font-size: 24px;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: 1px;
            margin: 4px 0 0;
            font-family: monospace;
        }
        .detail-table {
            width: 100%;
            margin-top: 16px;
        }
        .detail-table td {
            padding: 8px 0;
            font-size: 13px;
            border-bottom: 1px solid #23242e;
        }
        .detail-table tr:last-child td {
            border-bottom: none;
        }
        .detail-label {
            color: #9ca3af;
            width: 40%;
        }
        .detail-val {
            color: #ffffff;
            font-weight: 600;
            text-align: right;
        }
        .driver-box {
            background: rgba(230, 57, 70, 0.08);
            border: 1px solid rgba(230, 57, 70, 0.3);
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 24px;
        }
        .driver-title {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #ff6b6b;
            margin: 0 0 8px;
        }
        .cta-btn {
            display: block;
            text-align: center;
            background: linear-gradient(135deg, #e63946 0%, #c1121f 100%);
            color: #ffffff !important;
            text-decoration: none;
            font-weight: 800;
            font-size: 14px;
            letter-spacing: 1px;
            padding: 16px 24px;
            border-radius: 999px;
            text-transform: uppercase;
            margin: 28px 0;
            box-shadow: 0 4px 20px rgba(230, 57, 70, 0.4);
        }
        .footer {
            background-color: #0b0c10;
            padding: 24px 32px;
            text-align: center;
            border-top: 1px solid #1f2029;
            font-size: 11px;
            color: #71717a;
            line-height: 1.6;
        }
        .footer a {
            color: #e63946;
            text-decoration: none;
        }
    </style>
</head>
<body style="margin: 0; padding: 24px 0; background-color: #070709;">
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1 class="logo-title">QUANTUM <span>STREAMLINE</span></h1>
            <p class="subtitle">Official Luxury Fleet Reservation</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div style="text-align: center;">
                <span class="badge-confirmed">✓ Reservasi Terkonfirmasi</span>
            </div>

            <p class="greeting">
                Halo <strong>{{ $user->name ?? $transaksi->nama_pelanggan_offline ?? 'Pelanggan Terhormat' }}</strong>,<br>
                Terima kasih atas kepercayaan Anda. Pemesanan rental armada luxury Anda telah berhasil diverifikasi dan pembayaran telah berstatus <strong>LUNAS</strong>.
            </p>

            <!-- Booking Card -->
            <div class="booking-card">
                <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; color: #9ca3af;">Nomor Booking Resmi</div>
                <div class="booking-code">{{ $transaksi->nomor_booking }}</div>

                <table class="detail-table">
                    <tr>
                        <td class="detail-label">Unit Armada</td>
                        <td class="detail-val">{{ $mobil ? ($mobil->merk . ' ' . $mobil->nama_mobil) : 'Armada Pilihan' }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Paket Layanan</td>
                        <td class="detail-val">
                            {{ $transaksi->layanan === 'dengan_sopir' ? 'DENGAN SOPIR' : 'LEPAS KUNCI (SELF-DRIVE)' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="detail-label">Jadwal Mulai</td>
                        <td class="detail-val">
                            {{ $transaksi->tanggal_mulai ? $transaksi->tanggal_mulai->format('d M Y') : '-' }} ({{ $transaksi->jam_mulai ?? '09:00' }} WIB)
                        </td>
                    </tr>
                    <tr>
                        <td class="detail-label">Jadwal Selesai</td>
                        <td class="detail-val">
                            {{ $transaksi->tanggal_selesai ? $transaksi->tanggal_selesai->format('d M Y') : '-' }} ({{ $transaksi->jam_selesai ?? '09:00' }} WIB)
                        </td>
                    </tr>
                    <tr>
                        <td class="detail-label">Lokasi Penjemputan</td>
                        <td class="detail-val">{{ $transaksi->lokasi_jemput }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Total Pembayaran</td>
                        <td class="detail-val" style="color: #4ade80; font-size: 15px;">
                            Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Driver Info if applicable -->
            @if($transaksi->layanan === 'dengan_sopir')
                <div class="driver-box">
                    <div class="driver-title">👤 Informasi Driver Operasional</div>
                    @if($transaksi->nama_sopir_assigned)
                        <div style="font-size: 13px; color: #e4e4e7; line-height: 1.6;">
                            • <strong>Nama Driver:</strong> {{ $transaksi->nama_sopir_assigned }}<br>
                            • <strong>Kontak WhatsApp:</strong> {{ $transaksi->no_hp_sopir_assigned }}<br>
                            <span style="font-size: 11px; color: #a1a1aa;">Driver kami akan menghubungi Anda 1 jam sebelum jadwal penjemputan.</span>
                        </div>
                    @else
                        <div style="font-size: 12px; color: #fbbf24;">
                            Tim operasional kami sedang menugaskan driver VIP terbaik untuk perjalanan Anda. Informasi driver akan otomatis dikirimkan melalui WhatsApp sebelum keberangkatan.
                        </div>
                    @endif
                </div>
            @endif

            <!-- Action CTA Button -->
            <a href="{{ url('/transaksi/' . $transaksi->id) }}" class="cta-btn">
                Buka E-Voucher Digital &amp; QR Code &rarr;
            </a>

            <div style="background-color: #12131a; border-radius: 8px; padding: 14px; text-align: center; font-size: 12px; color: #a1a1aa;">
                💡 <em>Tunjukkan tautan E-Voucher atau barcode digital kepada staf serah terima kami pada saat pengambilan unit armada.</em>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0 0 6px;">
                <strong>Quantum Streamline Luxury Car Rental</strong><br>
                Jl. Sudirman Boulevard No. 88, Jakarta Selatan &bull; Hotline 24/7: +62 812-3456-7890
            </p>
            <p style="margin: 0; font-size: 10px; color: #52525b;">
                Email ini dikirimkan secara otomatis oleh sistem notifikasi terpadu Quantum Streamline sesuai regulasi UU PDP No. 27/2022.
            </p>
        </div>
    </div>
</body>
</html>
