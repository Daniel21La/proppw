<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    transaksi: {
        type: Object,
        required: true,
    },
    company: {
        type: Object,
        required: true,
    }
});

const formatRupiah = (val) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val || 0);
};

const handlePrint = () => {
    window.print();
};

const qrCodeUrl = computed(() => {
    const bookingNo = props.transaksi.nomor_booking || props.transaksi.id;
    return `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=VERIFIED-QUANTUM-${bookingNo}`;
});
</script>

<template>
    <Head :title="'E-Voucher & Digital Invoice #' + (transaksi.nomor_booking || transaksi.id)" />

    <div class="min-h-screen bg-slate-950 text-slate-100 py-8 px-4 sm:px-6 lg:px-8 print:bg-white print:text-slate-900 print:py-0 print:px-0">
        <!-- Action Header Bar (Hidden on Print) -->
        <div class="max-w-4xl mx-auto mb-6 flex flex-col sm:flex-row items-center justify-between gap-4 print:hidden">
            <Link href="/user/transaksi" class="inline-flex items-center text-sm font-semibold text-slate-400 hover:text-white transition group">
                <svg class="w-5 h-5 mr-1.5 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Riwayat Pesanan
            </Link>

            <div class="flex items-center space-x-3">
                <button
                    @click="handlePrint"
                    class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-red-600/30 transition transform hover:-translate-y-0.5 active:scale-95 cursor-pointer"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak / Simpan PDF (Portrait)
                </button>
            </div>
        </div>

        <!-- Printable E-Voucher Container -->
        <div class="max-w-4xl mx-auto bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl overflow-hidden print:border-none print:shadow-none print:bg-white print:rounded-none">
            <!-- Top Branding Banner -->
            <div class="bg-gradient-to-r from-neutral-900 via-neutral-950 to-red-950 p-6 sm:p-8 text-white relative overflow-hidden border-b border-neutral-800 print:bg-neutral-900 print:text-white">
                <!-- Red Glow Accent -->
                <div class="absolute right-0 top-0 w-64 h-64 bg-red-600/10 rounded-full blur-3xl pointer-events-none" />

                <div class="relative z-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-red-600/20 border border-red-500/40 rounded-full text-[10px] font-black text-red-400 uppercase tracking-widest mb-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse" />
                            OFFICIAL E-VOUCHER & DIGITAL INVOICE
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white uppercase">{{ company.name }}</h1>
                        <p class="text-neutral-400 text-xs sm:text-sm mt-1 font-medium">{{ company.tagline }}</p>
                    </div>

                    <div class="flex items-center gap-4 text-right">
                        <!-- QR Code -->
                        <div class="bg-white p-2 rounded-xl border border-neutral-700 shadow-md">
                            <img :src="qrCodeUrl" alt="Booking QR Verification" class="w-16 h-16 object-contain" />
                            <span class="text-[8px] font-bold text-neutral-800 block text-center mt-0.5">SCAN VERIFY</span>
                        </div>

                        <div>
                            <span
                                class="inline-block px-3 py-1 rounded-lg text-xs font-black uppercase tracking-wider"
                                :class="{
                                    'bg-emerald-500/20 text-emerald-400 border border-emerald-500/40': transaksi.status === 'dikonfirmasi' || transaksi.status === 'disetujui' || transaksi.status === 'selesai',
                                    'bg-amber-500/20 text-amber-400 border border-amber-500/40': transaksi.status === 'pending',
                                    'bg-rose-500/20 text-rose-400 border border-rose-500/40': transaksi.status === 'dibatalkan' || transaksi.status === 'ditolak'
                                }"
                            >
                                {{ transaksi.status }}
                            </span>
                            <div class="text-xl sm:text-2xl font-mono font-black mt-1.5 text-white">
                                #{{ transaksi.nomor_booking || transaksi.id }}
                            </div>
                            <p class="text-[11px] text-neutral-400 mt-0.5">Diterbitkan: {{ transaksi.created_at }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details Body -->
            <div class="p-6 sm:p-8 space-y-8 print:p-4">
                <!-- Grid Info Pelanggan & Perusahaan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-slate-800 print:border-slate-300">
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-red-500 print:text-red-700 mb-2">
                            DITERBITKAN UNTUK (PELANGGAN)
                        </h3>
                        <p class="text-lg font-black text-slate-100 print:text-slate-900">{{ transaksi.user?.name }}</p>
                        <p class="text-xs text-slate-400 print:text-slate-700 mt-0.5">Email: {{ transaksi.user?.email }}</p>
                        <p class="text-xs text-slate-400 print:text-slate-700">No. WhatsApp / HP: {{ transaksi.user?.phone || '-' }}</p>
                        <p class="text-xs text-slate-400 print:text-slate-700 mt-2">
                            Lokasi Penjemputan: <span class="font-bold text-slate-200 print:text-slate-900">{{ transaksi.lokasi_jemput }}</span>
                        </p>
                    </div>

                    <div class="md:text-right">
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-red-500 print:text-red-700 mb-2">
                            PENYEDIA LAYANAN RESMI
                        </h3>
                        <p class="text-lg font-black text-slate-100 print:text-slate-900">{{ company.name }}</p>
                        <p class="text-xs text-slate-400 print:text-slate-700 mt-0.5">{{ company.address }}</p>
                        <p class="text-xs text-slate-400 print:text-slate-700">Hotline / WA CS: {{ company.phone }}</p>
                        <p class="text-xs text-slate-400 print:text-slate-700">Email CS: {{ company.email }}</p>
                    </div>
                </div>

                <!-- Unit Kendaraan & Masa Sewa -->
                <div class="bg-slate-950/80 print:bg-slate-50 border border-slate-800 print:border-slate-300 rounded-2xl p-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                        <div class="flex items-center space-x-4">
                            <div class="w-20 h-14 rounded-xl bg-neutral-900 overflow-hidden shrink-0 border border-slate-700 print:border-slate-300 flex items-center justify-center">
                                <img
                                    v-if="transaksi.mobil"
                                    :src="`/gambar_mobil/${transaksi.mobil.gambar}`"
                                    :alt="transaksi.mobil.nama_mobil"
                                    class="w-full h-full object-cover"
                                />
                            </div>
                            <div>
                                <h4 class="text-base font-black text-slate-100 print:text-slate-900">
                                    {{ transaksi.mobil ? transaksi.mobil.nama_mobil : 'Kendaraan' }}
                                </h4>
                                <p class="text-xs text-slate-400 print:text-slate-600 font-mono mt-0.5">
                                    {{ transaksi.mobil?.nopol || '-' }} | {{ transaksi.mobil?.transmisi || '-' }}
                                </p>
                                <span class="inline-block mt-1 px-2.5 py-0.5 bg-red-950/60 text-red-400 print:text-red-800 text-[10px] font-bold rounded-lg border border-red-500/30">
                                    Layanan: {{ transaksi.layanan === 'dengan_sopir' ? 'Dengan Sopir (Driver VIP)' : 'Lepas Kunci (Tanpa Sopir)' }}
                                </span>
                            </div>
                        </div>

                        <div class="text-center md:border-x border-slate-800 print:border-slate-300 px-4">
                            <p class="text-[10px] text-slate-400 print:text-slate-600 font-bold uppercase tracking-wider">TANGGAL MULAI</p>
                            <p class="text-sm font-black text-slate-100 print:text-slate-900 mt-1">{{ transaksi.tanggal_mulai }}</p>
                            <p class="text-xs text-red-400 print:text-red-700 font-semibold">Jam {{ transaksi.jam_mulai || '09:00' }} WIB</p>
                        </div>

                        <div class="text-center">
                            <p class="text-[10px] text-slate-400 print:text-slate-600 font-bold uppercase tracking-wider">TANGGAL SELESAI</p>
                            <p class="text-sm font-black text-slate-100 print:text-slate-900 mt-1">{{ transaksi.tanggal_selesai }}</p>
                            <p class="text-xs text-red-400 print:text-red-700 font-semibold">Jam {{ transaksi.jam_selesai || '09:00' }} WIB</p>
                        </div>
                    </div>
                </div>

                <!-- Detail Driver Assigned (Jika Ada) -->
                <div v-if="transaksi.nama_sopir_assigned" class="bg-rose-950/30 print:bg-rose-50 border border-rose-500/30 print:border-rose-200 rounded-2xl p-4 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-rose-500/20 text-rose-400 print:text-rose-800 flex items-center justify-center font-bold text-lg">
                            👨‍✈️
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-rose-400 print:text-rose-900 uppercase tracking-wider">DRIVER RESMI TERDRAFT / ASSIGNED</p>
                            <p class="text-sm font-bold text-slate-100 print:text-slate-900">{{ transaksi.nama_sopir_assigned }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-400 print:text-slate-600">Kontak Driver</p>
                        <p class="text-sm font-mono font-bold text-rose-400 print:text-rose-800">{{ transaksi.no_hp_sopir_assigned }}</p>
                    </div>
                </div>

                <!-- Transparent Itemized Price Table -->
                <div>
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-slate-400 print:text-slate-700 mb-3">
                        RINCIAN TRANSPARANSI BIAYA (ITEMIZED BILLING)
                    </h3>
                    <div class="border border-slate-800 print:border-slate-300 rounded-2xl overflow-hidden">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-950 print:bg-slate-100 text-slate-400 print:text-slate-800 text-[10px] uppercase font-black tracking-wider">
                                <tr>
                                    <th class="py-3.5 px-4">Deskripsi Item</th>
                                    <th class="py-3.5 px-4 text-center">Detail / Kuantitas</th>
                                    <th class="py-3.5 px-4 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800 print:divide-slate-200 text-slate-200 print:text-slate-800">
                                <tr>
                                    <td class="py-3 px-4 font-bold">Sewa Unit {{ transaksi.mobil ? transaksi.mobil.nama_mobil : 'Kendaraan' }}</td>
                                    <td class="py-3 px-4 text-center text-xs text-slate-400 print:text-slate-600">Tarif Harian / Musiman</td>
                                    <td class="py-3 px-4 text-right font-mono font-semibold">{{ formatRupiah(transaksi.total_harga - (transaksi.biaya_sopir || 0) - (transaksi.biaya_asuransi || 0) - (transaksi.biaya_extra_hours || 0) - (transaksi.biaya_pengantaran || 0)) }}</td>
                                </tr>
                                <tr v-if="transaksi.biaya_sopir > 0">
                                    <td class="py-3 px-4 font-bold">Jasa Layanan Driver / Sopir VIP</td>
                                    <td class="py-3 px-4 text-center text-xs text-slate-400 print:text-slate-600">{{ transaksi.layanan }}</td>
                                    <td class="py-3 px-4 text-right font-mono font-semibold">{{ formatRupiah(transaksi.biaya_sopir) }}</td>
                                </tr>
                                <tr v-if="transaksi.asuransi_tambahan">
                                    <td class="py-3 px-4 font-bold">Asuransi Perlindungan Tambahan All-Risk</td>
                                    <td class="py-3 px-4 text-center text-xs text-slate-400 print:text-slate-600">Proteksi Penuh</td>
                                    <td class="py-3 px-4 text-right font-mono font-semibold">{{ formatRupiah(transaksi.biaya_asuransi) }}</td>
                                </tr>
                                <tr v-if="transaksi.biaya_extra_hours > 0">
                                    <td class="py-3 px-4 font-bold">Biaya Extra Hours (+{{ transaksi.extra_hours }} Jam)</td>
                                    <td class="py-3 px-4 text-center text-xs text-slate-400 print:text-slate-600">Add-on Jam Tambahan</td>
                                    <td class="py-3 px-4 text-right font-mono font-semibold">{{ formatRupiah(transaksi.biaya_extra_hours) }}</td>
                                </tr>
                                <tr v-if="transaksi.biaya_pengantaran > 0">
                                    <td class="py-3 px-4 font-bold">Biaya Pengantaran Unit (Jarak: {{ transaksi.jarak_pengantaran_km }} km)</td>
                                    <td class="py-3 px-4 text-center text-xs text-slate-400 print:text-slate-600">Google Distance API</td>
                                    <td class="py-3 px-4 text-right font-mono font-semibold">{{ formatRupiah(transaksi.biaya_pengantaran) }}</td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-slate-950 print:bg-slate-100 font-black border-t border-slate-800 print:border-slate-300">
                                <tr>
                                    <td colspan="2" class="py-3.5 px-4 text-right text-slate-300 print:text-slate-900 uppercase">GRAND TOTAL LUNAS</td>
                                    <td class="py-3.5 px-4 text-right text-base font-mono text-red-500 print:text-red-700">{{ formatRupiah(transaksi.total_harga) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Footer Legal & UU PDP Disclaimer -->
                <div class="border-t border-slate-800 print:border-slate-300 pt-6 text-[11px] text-slate-400 print:text-slate-600 space-y-2">
                    <p class="font-black text-slate-300 print:text-slate-800 uppercase tracking-wider">
                        📌 KLAUSUL KEAMANAN DATA & KEPATUHAN UU PDP NO. 27 TAHUN 2022:
                    </p>
                    <p class="leading-relaxed">
                        Dokumen identitas (KTP & SIM) yang diunggah untuk transaksi ini disimpan pada repositori aman terenkripsi (private storage). Sesuai dengan ketentuan Undang-Undang Perlindungan Data Pribadi (UU PDP No. 27/2022), seluruh salinan dokumen identitas fisik akan di-purge dan dihapus secara permanen dari server otomatis 30 hari setelah unit kendaraan dikembalikan, kecuali terdapat pengajuan klaim/sengketa resmi.
                    </p>
                    <p v-if="transaksi.dokumen_purged_at" class="text-amber-400 print:text-amber-700 font-bold">
                        Status Dokumen: Berkas fisik KTP/SIM telah di-purge otomatis pada {{ transaksi.dokumen_purged_at }}.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@media print {
    @page {
        size: A4 portrait;
        margin: 1cm;
    }
    body {
        background-color: white !important;
        color: black !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>
