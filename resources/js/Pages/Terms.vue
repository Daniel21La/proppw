<script setup>
import { Head, Link } from '@inertiajs/vue3';
import {
    ShieldCheck,
    Clock,
    AlertTriangle,
    Fuel,
    Gauge,
    FileText,
    ArrowLeft,
    CheckCircle2,
    Car,
    Phone,
    MapPin,
    Calendar,
    Coins,
} from 'lucide-vue-next';

function formatRupiah(num) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(num || 0);
}
</script>

<template>
    <Head title="Syarat & Ketentuan Sewa — Quantum Streamline" />

    <div class="min-h-screen bg-[#070709] text-white selection:bg-red-600 selection:text-white">
        <!-- Top Navbar Minimal -->
        <header class="border-b border-neutral-800/80 bg-[#0f1015]/80 backdrop-blur-xl sticky top-0 z-50">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
                <Link href="/" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-red-600 to-rose-600 flex items-center justify-center font-black text-white shadow-lg shadow-red-600/30">
                        Q
                    </div>
                    <span class="text-sm font-black tracking-widest uppercase text-white">
                        QUANTUM<span class="text-red-500">RENT</span>
                    </span>
                </Link>

                <div class="flex items-center gap-4">
                    <Link
                        href="/"
                        class="inline-flex items-center gap-2 text-xs font-bold text-neutral-400 hover:text-white transition"
                    >
                        <ArrowLeft class="w-3.5 h-3.5" />
                        Kembali ke Beranda
                    </Link>

                    <Link
                        href="/transaksi/create"
                        class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white text-xs font-black uppercase tracking-wider shadow-lg shadow-red-600/30 transition"
                    >
                        Reservasi Unit
                    </Link>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-5xl mx-auto px-4 sm:px-6 py-12 space-y-12">
            <!-- Header Hero -->
            <div class="text-center space-y-3">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-red-600/10 border border-red-500/30 text-red-400 text-xs font-bold uppercase tracking-widest">
                    <ShieldCheck class="w-4 h-4" />
                    Transparansi Biaya & Perlindungan Konsumen
                </div>
                <h1 class="text-3xl sm:text-5xl font-black uppercase tracking-tight text-white">
                    Syarat & Ketentuan Sewa
                </h1>
                <p class="text-neutral-400 text-xs sm:text-sm max-w-2xl mx-auto leading-relaxed">
                    Dokumen ini mengikat seluruh perjanjian sewa kendaraan mandiri di Quantum Streamline. Seluruh kalkulasi denda, pembatalan, dan tarif antar-jemput dihitung transparan secara otomatis oleh sistem kami tanpa biaya siluman.
                </p>
                <p class="text-[11px] text-neutral-500 font-mono">
                    Berlaku efektif sejak: 01 Januari 2026 • Versi 2.4 (UU PDP & Transparansi Biaya)
                </p>
            </div>

            <!-- 5 Essential Clauses Cards -->
            <div class="space-y-8">
                <!-- Pasal 1: Keterlambatan Pengembalian (Late Return) -->
                <div class="bg-[#0f1015] border border-neutral-800 rounded-3xl p-6 sm:p-8 space-y-5">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-600/20 text-amber-500 flex items-center justify-center shrink-0 border border-amber-500/30">
                            <Clock class="w-6 h-6" />
                        </div>
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-widest text-amber-500">Pasal 1</span>
                            <h2 class="text-lg sm:text-xl font-black uppercase text-white">
                                Kebijakan Keterlambatan Pengembalian (Late Return)
                            </h2>
                            <p class="text-xs text-neutral-400 mt-1">
                                Pengembalian unit di luar jadwal yang telah disepakati pada kontrak reservasi diatur dengan skema berjenjang yang adil:
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                        <div class="p-4 rounded-2xl bg-black/40 border border-neutral-800 space-y-1.5">
                            <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-wider">Masa Tenggang (Grace Period)</span>
                            <h3 class="text-base font-black text-white">0 s/d 30 Menit</h3>
                            <p class="text-[11px] text-neutral-400">
                                <b>BEBAS DENDA</b>. Toleransi wajar untuk kendala cuci mobil, pengisian BBM, atau kemacetan lalu lintas.
                            </p>
                        </div>

                        <div class="p-4 rounded-2xl bg-black/40 border border-neutral-800 space-y-1.5">
                            <span class="text-[10px] font-bold text-amber-400 uppercase tracking-wider">Denda Bertingkat Tier 1</span>
                            <h3 class="text-base font-black text-white">31 s/d 180 Menit</h3>
                            <p class="text-[11px] text-neutral-400">
                                Dikenakan denda <b>Rp 50.000 / jam</b>, dihitung <b>proporsional per menit</b> secara adil tanpa pembulatan sepihak oleh admin.
                            </p>
                        </div>

                        <div class="p-4 rounded-2xl bg-black/40 border border-neutral-800 space-y-1.5">
                            <span class="text-[10px] font-bold text-red-500 uppercase tracking-wider">Denda Bertingkat Tier 2</span>
                            <h3 class="text-base font-black text-white">> 180 Menit (> 3 Jam)</h3>
                            <p class="text-[11px] text-neutral-400">
                                Otomatis dihitung sebagai <b>sewa tambahan 1 hari penuh</b> sesuai tarif harian resmi/musiman unit yang berlaku.
                            </p>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-xl bg-neutral-900/60 border border-neutral-800 text-xs text-neutral-400 flex items-center gap-3">
                        <AlertTriangle class="w-4 h-4 text-amber-400 shrink-0" />
                        <span>
                            Sistem secara otomatis menghitung dan mencantumkan denda di invoice saat petugas menekan tombol <b>Unit Kembali</b>. Notifikasi peringatan dikirim via WhatsApp jika waktu kembali telah terlampaui.
                        </span>
                    </div>
                </div>

                <!-- Pasal 2: Kebijakan Pembatalan Bertingkat (Cancellation Policy) -->
                <div class="bg-[#0f1015] border border-neutral-800 rounded-3xl p-6 sm:p-8 space-y-5">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-red-600/20 text-red-500 flex items-center justify-center shrink-0 border border-red-500/30">
                            <Coins class="w-6 h-6" />
                        </div>
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-widest text-red-500">Pasal 2</span>
                            <h2 class="text-lg sm:text-xl font-black uppercase text-white">
                                Kebijakan Pembatalan & Pengembalian Dana (Refund Policy)
                            </h2>
                            <p class="text-xs text-neutral-400 mt-1">
                                Pembatalan sewa sebelum jadwal mulai diatur berdasarkan waktu pengajuan pembatalan resmi:
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                        <div class="p-4 rounded-2xl bg-black/40 border border-neutral-800 space-y-1.5">
                            <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-wider">H-2 atau Lebih (> 48 Jam)</span>
                            <h3 class="text-base font-black text-white">Refund 100%</h3>
                            <p class="text-[11px] text-neutral-400">
                                Dana dikembalikan penuh (hanya dikenakan potongan biaya transfer bank pihak ketiga Rp 10.000 jika antarbank).
                            </p>
                        </div>

                        <div class="p-4 rounded-2xl bg-black/40 border border-neutral-800 space-y-1.5">
                            <span class="text-[10px] font-bold text-amber-400 uppercase tracking-wider">H-1 (24 s/d 48 Jam)</span>
                            <h3 class="text-base font-black text-white">Refund 50%</h3>
                            <p class="text-[11px] text-neutral-400">
                                Pengembalian dana 50% dari total sewa, sebagai kompensasi unit yang telah di-hold dan menolak pelanggan lain.
                            </p>
                        </div>

                        <div class="p-4 rounded-2xl bg-black/40 border border-neutral-800 space-y-1.5">
                            <span class="text-[10px] font-bold text-red-500 uppercase tracking-wider">Hari-H (< 24 Jam)</span>
                            <h3 class="text-base font-black text-white">Refund 0% (Hangus)</h3>
                            <p class="text-[11px] text-neutral-400">
                                Pembatalan di hari pemakaian tidak mendapatkan pengembalian dana karena unit armada telah disiapkan dan dialokasikan.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Pasal 3: Bahan Bakar (Fuel Policy) & Batas Jarak Tempuh -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- BBM Same-to-Same -->
                    <div class="bg-[#0f1015] border border-neutral-800 rounded-3xl p-6 sm:p-8 space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-600/20 text-blue-400 flex items-center justify-center shrink-0 border border-blue-500/30">
                                <Fuel class="w-5 h-5" />
                            </div>
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-blue-400">Pasal 3</span>
                                <h3 class="text-base font-black uppercase text-white">Kebijakan BBM (Same-to-Same)</h3>
                            </div>
                        </div>
                        <p class="text-xs text-neutral-400 leading-relaxed">
                            Kendaraan diserahterimakan dengan posisi indikator bahan bakar tertentu (misal: 3 bar / Full) yang dicatat pada form serah terima. Pelanggan wajib mengembalikan kendaraan pada indikator level yang sama.
                        </p>
                        <div class="p-3 rounded-xl bg-black/40 border border-neutral-800 text-[11px] text-neutral-400">
                            Bila level BBM kurang saat pengembalian, pelanggan dikenakan biaya selisih BBM sesuai harga resmi SPBU Pertamina + biaya jasa pengisian sebesar <b>Rp 25.000</b>.
                        </div>
                    </div>

                    <!-- Batas Jarak Tempuh -->
                    <div class="bg-[#0f1015] border border-neutral-800 rounded-3xl p-6 sm:p-8 space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-600/20 text-purple-400 flex items-center justify-center shrink-0 border border-purple-500/30">
                                <Gauge class="w-5 h-5" />
                            </div>
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-purple-400">Pasal 4</span>
                                <h3 class="text-base font-black uppercase text-white">Batas Jarak Tempuh (Mileage Limit)</h3>
                            </div>
                        </div>
                        <p class="text-xs text-neutral-400 leading-relaxed">
                            Untuk layanan Lepas Kunci (Self-Drive), batas jarak tempuh adalah <b>300 km / hari</b> (dihitung akumulatif jika sewa multi-hari). Untuk layanan Dengan Sopir berlaku <b>Unlimited Mileage</b> dalam area operasional yang disepakati.
                        </p>
                        <div class="p-3 rounded-xl bg-black/40 border border-neutral-800 text-[11px] text-neutral-400">
                            Kelebihan kilometer (over-mileage) untuk sewa lepas kunci dikenakan biaya wajar sebesar <b>Rp 1.500 / km</b> yang tercatat pada odometer saat unit kembali.
                        </div>
                    </div>
                </div>

                <!-- Pasal 5: Klaim Kerusakan & Asuransi -->
                <div class="bg-[#0f1015] border border-neutral-800 rounded-3xl p-6 sm:p-8 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600/20 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30">
                            <ShieldCheck class="w-5 h-5" />
                        </div>
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-widest text-emerald-400">Pasal 5</span>
                            <h3 class="text-base font-black uppercase text-white">
                                Prosedur Klaim Kerusakan & Asuransi Kendaraan
                            </h3>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 text-xs">
                        <div class="p-4 rounded-2xl bg-black/40 border border-neutral-800 space-y-2">
                            <h4 class="font-bold text-white flex items-center gap-2">
                                <CheckCircle2 class="w-4 h-4 text-emerald-400" />
                                Dengan Proteksi Total (+Rp 50rb/hari)
                            </h4>
                            <p class="text-neutral-400 text-[11px] leading-relaxed">
                                Jika terjadi baret, lecet, atau kecelakaan, pelanggan hanya menanggung biaya <b>Own Risk (OR) asuransi resmi sebesar Rp 300.000 – Rp 500.000 per insiden</b>. Sisa perbaikan ditanggung penuh oleh asuransi All-Risk.
                            </p>
                        </div>

                        <div class="p-4 rounded-2xl bg-black/40 border border-neutral-800 space-y-2">
                            <h4 class="font-bold text-white flex items-center gap-2">
                                <AlertTriangle class="w-4 h-4 text-amber-400" />
                                Tanpa Proteksi Tambahan
                            </h4>
                            <p class="text-neutral-400 text-[11px] leading-relaxed">
                                Pelanggan bertanggung jawab penuh atas biaya perbaikan di bengkel rekanan resmi serta kompensasi sewa harian selama unit kendaraan menginap di bengkel perbaikan.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Pasal 6: Pengantaran Google Maps & Add-on Jam -->
                <div class="bg-[#0f1015] border border-neutral-800 rounded-3xl p-6 sm:p-8 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-600/20 text-red-500 flex items-center justify-center shrink-0 border border-red-500/30">
                            <MapPin class="w-5 h-5" />
                        </div>
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-widest text-red-500">Pasal 6</span>
                            <h3 class="text-base font-black uppercase text-white">
                                Tarif Pengantaran Luar Kota (Google Maps API) & Add-on Jam
                            </h3>
                        </div>
                    </div>
                    <p class="text-xs text-neutral-400 leading-relaxed">
                        Biaya pengantaran dan penjemputan unit di luar garasi utama (Soekarno-Hatta Terminal 3) dihitung otomatis oleh sistem menggunakan <b>Google Maps Distance Matrix API</b> dengan tarif standar <b>Rp 4.000 / km</b> jarak mengemudi riil. Pengambilan di pool/garasi bebas biaya (Rp 0).
                    </p>
                    <p class="text-xs text-neutral-400 leading-relaxed">
                        Pelanggan juga dapat memilih <b>Add-on Jam Tambahan (+2, +4, +6 jam)</b> saat pemesanan awal dengan tarif hemat <b>Rp 35.000 / jam</b>, jauh lebih hemat dibandingkan tarif denda keterlambatan.
                    </p>
                </div>
            </div>

            <!-- Footer CTA -->
            <div class="p-8 rounded-3xl bg-gradient-to-r from-red-950/40 via-neutral-900 to-black border border-neutral-800 text-center space-y-4">
                <h3 class="text-lg font-black uppercase text-white">
                    Siap Menikmati Perjalanan Tanpa Beban & Transparan?
                </h3>
                <p class="text-xs text-neutral-400 max-w-lg mx-auto">
                    Seluruh reservasi armada kami dilindungi oleh garansi unit bersih, wangi, terawat, dan invoice resmi instan.
                </p>
                <Link
                    href="/transaksi/create"
                    class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-gradient-to-r from-red-600 to-rose-600 hover:opacity-90 text-white text-xs font-black uppercase tracking-wider shadow-xl shadow-red-600/30 transition"
                >
                    <Car class="w-4 h-4" />
                    Mulai Reservasi Kendaraan Sekarang
                </Link>
            </div>
        </main>
    </div>
</template>
