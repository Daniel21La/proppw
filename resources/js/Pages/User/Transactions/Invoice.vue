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
</script>

<template>
    <Head :title="'E-Voucher & Invoice #' + transaksi.nomor_booking" />

    <div class="min-h-screen bg-slate-900 text-slate-100 py-8 px-4 sm:px-6 lg:px-8 print:bg-white print:text-slate-900 print:py-0 print:px-0">
        <!-- Action Header Bar (Hidden on Print) -->
        <div class="max-w-4xl mx-auto mb-6 flex flex-col sm:flex-row items-center justify-between gap-4 print:hidden">
            <Link :href="route('transaksi.index')" class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-white transition">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Riwayat Pesanan
            </Link>

            <div class="flex items-center space-x-3">
                <button @click="handlePrint" class="inline-flex items-center px-4 py-2.5 bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-semibold text-sm rounded-xl shadow-lg shadow-cyan-500/25 transition transform active:scale-95">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak / Simpan PDF
                </button>
            </div>
        </div>

        <!-- Printable E-Voucher Container -->
        <div class="max-w-4xl mx-auto bg-slate-800/90 border border-slate-700/80 rounded-2xl shadow-2xl overflow-hidden print:border-none print:shadow-none print:bg-white print:rounded-none">
            <!-- Top Branding Banner -->
            <div class="bg-gradient-to-r from-cyan-600 via-blue-600 to-indigo-700 p-6 sm:p-8 text-white relative overflow-hidden print:bg-slate-900 print:text-white">
                <div class="relative z-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <div class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold uppercase tracking-wider mb-2">
                            OFFICIAL E-VOUCHER & INVOICE
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">{{ company.name }}</h1>
                        <p class="text-cyan-100 text-xs sm:text-sm mt-1">{{ company.tagline }}</p>
                    </div>
                    <div class="text-right sm:text-right">
                        <span class="inline-block px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider"
                            :class="{
                                'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30': transaksi.status === 'dikonfirmasi' || transaksi.status === 'disetujui' || transaksi.status === 'selesai',
                                'bg-amber-500/20 text-amber-300 border border-amber-500/30': transaksi.status === 'pending',
                                'bg-rose-500/20 text-rose-300 border border-rose-500/30': transaksi.status === 'dibatalkan' || transaksi.status === 'ditolak'
                            }">
                            {{ transaksi.status }}
                        </span>
                        <div class="text-xl sm:text-2xl font-mono font-bold mt-2 text-cyan-200">#{{ transaksi.nomor_booking }}</div>
                        <p class="text-xs text-cyan-100/80 mt-0.5">Diterbitkan: {{ transaksi.created_at }}</p>
                    </div>
                </div>
            </div>

            <!-- Details Body -->
            <div class="p-6 sm:p-8 space-y-8">
                <!-- Grid Info Pelanggan & Perusahaan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-slate-700/60 print:border-slate-300">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-cyan-400 print:text-cyan-800 mb-2">DITERBITKAN UNTUK (PELANGGAN)</h3>
                        <p class="text-lg font-bold text-slate-100 print:text-slate-900">{{ transaksi.user.name }}</p>
                        <p class="text-sm text-slate-300 print:text-slate-700">Email: {{ transaksi.user.email }}</p>
                        <p class="text-sm text-slate-300 print:text-slate-700">No. WhatsApp / HP: {{ transaksi.user.phone }}</p>
                        <p class="text-sm text-slate-300 print:text-slate-700 mt-2">Lokasi Penjemputan: <span class="font-medium text-slate-100 print:text-slate-900">{{ transaksi.lokasi_jemput }}</span></p>
                    </div>
                    <div class="md:text-right">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-cyan-400 print:text-cyan-800 mb-2">PENYEDIA LAYANAN</h3>
                        <p class="text-lg font-bold text-slate-100 print:text-slate-900">{{ company.name }}</p>
                        <p class="text-sm text-slate-300 print:text-slate-700">{{ company.address }}</p>
                        <p class="text-sm text-slate-300 print:text-slate-700">Telp/WA CS: {{ company.phone }}</p>
                        <p class="text-sm text-slate-300 print:text-slate-700">Email CS: {{ company.email }}</p>
                    </div>
                </div>

                <!-- Unit Kendaraan & Masa Sewa -->
                <div class="bg-slate-900/60 print:bg-slate-50 border border-slate-700/60 print:border-slate-200 rounded-xl p-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                        <div class="flex items-center space-x-4">
                            <img v-if="transaksi.mobil" :src="transaksi.mobil.gambar_url" alt="Mobil" class="w-20 h-14 object-cover rounded-lg border border-slate-700 print:border-slate-300" />
                            <div>
                                <h4 class="text-base font-bold text-slate-100 print:text-slate-900">{{ transaksi.mobil ? transaksi.mobil.nama_mobil : 'Kendaraan' }}</h4>
                                <p class="text-xs text-slate-400 print:text-slate-600 font-mono">{{ transaksi.mobil ? transaksi.mobil.nopol : '-' }} | {{ transaksi.mobil ? transaksi.mobil.transmisi : '-' }}</p>
                                <span class="inline-block mt-1 px-2 py-0.5 bg-cyan-500/10 text-cyan-300 print:text-cyan-800 text-xs rounded border border-cyan-500/20">
                                    Layanan: {{ transaksi.layanan === 'dengan_sopir' ? 'Dengan Sopir (Driver VIP)' : 'Lepas Kunci (Tanpa Sopir)' }}
                                </span>
                            </div>
                        </div>

                        <div class="text-center md:border-x border-slate-700/60 print:border-slate-300 px-4">
                            <p class="text-xs text-slate-400 print:text-slate-600 font-medium uppercase">TANGGAL MULAI</p>
                            <p class="text-sm font-bold text-slate-100 print:text-slate-900 mt-1">{{ transaksi.tanggal_mulai }}</p>
                            <p class="text-xs text-cyan-400 print:text-cyan-800">Jam {{ transaksi.jam_mulai }} WIB</p>
                        </div>

                        <div class="text-center">
                            <p class="text-xs text-slate-400 print:text-slate-600 font-medium uppercase">TANGGAL SELESAI</p>
                            <p class="text-sm font-bold text-slate-100 print:text-slate-900 mt-1">{{ transaksi.tanggal_selesai }}</p>
                            <p class="text-xs text-cyan-400 print:text-cyan-800">Jam {{ transaksi.jam_selesai }} WIB</p>
                        </div>
                    </div>
                </div>

                <!-- Detail Driver Assigned (Jika Ada) -->
                <div v-if="transaksi.nama_sopir_assigned" class="bg-indigo-950/40 print:bg-indigo-50 border border-indigo-500/30 print:border-indigo-200 rounded-xl p-4 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-500/20 text-indigo-400 print:text-indigo-800 flex items-center justify-center font-bold text-lg">
                            👨‍✈️
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-indigo-300 print:text-indigo-900 uppercase">DRIVER RESMI TERDRAFT / ASSIGNED</p>
                            <p class="text-sm font-bold text-slate-100 print:text-slate-900">{{ transaksi.nama_sopir_assigned }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-400 print:text-slate-600">Kontak Driver</p>
                        <p class="text-sm font-mono font-bold text-indigo-300 print:text-indigo-800">{{ transaksi.no_hp_sopir_assigned }}</p>
                    </div>
                </div>

                <!-- Transparent Itemized Price Table -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 print:text-slate-700 mb-3">RINCIAN TRASPARANSI BIAYA (ITEMIZED BILLING)</h3>
                    <div class="border border-slate-700/60 print:border-slate-300 rounded-xl overflow-hidden">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-900/80 print:bg-slate-100 text-slate-300 print:text-slate-800 text-xs uppercase font-semibold">
                                <tr>
                                    <th class="py-3 px-4">Deskripsi Item</th>
                                    <th class="py-3 px-4 text-center">Detail / Kuantitas</th>
                                    <th class="py-3 px-4 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-700/50 print:divide-slate-200 text-slate-200 print:text-slate-800">
                                <tr>
                                    <td class="py-3 px-4 font-medium">Sewa Unit {{ transaksi.mobil ? transaksi.mobil.nama_mobil : 'Kendaraan' }}</td>
                                    <td class="py-3 px-4 text-center text-xs text-slate-400 print:text-slate-600">Tarif Harian / Musiman</td>
                                    <td class="py-3 px-4 text-right font-mono">{{ formatRupiah(transaksi.total_harga - transaksi.biaya_sopir - transaksi.biaya_asuransi - transaksi.biaya_extra_hours - transaksi.biaya_pengantaran) }}</td>
                                </tr>
                                <tr v-if="transaksi.biaya_sopir > 0">
                                    <td class="py-3 px-4 font-medium">Jasa Layanan Driver / Sopir VIP</td>
                                    <td class="py-3 px-4 text-center text-xs text-slate-400 print:text-slate-600">{{ transaksi.layanan }}</td>
                                    <td class="py-3 px-4 text-right font-mono">{{ formatRupiah(transaksi.biaya_sopir) }}</td>
                                </tr>
                                <tr v-if="transaksi.asuransi_tambahan">
                                    <td class="py-3 px-4 font-medium">Asuransi Perlindungan Tambahan All-Risk</td>
                                    <td class="py-3 px-4 text-center text-xs text-slate-400 print:text-slate-600">Proteksi Penuh</td>
                                    <td class="py-3 px-4 text-right font-mono">{{ formatRupiah(transaksi.biaya_asuransi) }}</td>
                                </tr>
                                <tr v-if="transaksi.biaya_extra_hours > 0">
                                    <td class="py-3 px-4 font-medium">Biaya Extra Hours (+{{ transaksi.extra_hours }} Jam)</td>
                                    <td class="py-3 px-4 text-center text-xs text-slate-400 print:text-slate-600">Add-on Jam Tambahan</td>
                                    <td class="py-3 px-4 text-right font-mono">{{ formatRupiah(transaksi.biaya_extra_hours) }}</td>
                                </tr>
                                <tr v-if="transaksi.biaya_pengantaran > 0">
                                    <td class="py-3 px-4 font-medium">Biaya Pengantaran Unit (Jarak: {{ transaksi.jarak_pengantaran_km }} km)</td>
                                    <td class="py-3 px-4 text-center text-xs text-slate-400 print:text-slate-600">Google Distance API</td>
                                    <td class="py-3 px-4 text-right font-mono">{{ formatRupiah(transaksi.biaya_pengantaran) }}</td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-slate-900/90 print:bg-slate-100 font-bold border-t border-slate-700/80 print:border-slate-300">
                                <tr>
                                    <td colspan="2" class="py-3.5 px-4 text-right text-slate-300 print:text-slate-900">GRAND TOTAL LUNAS</td>
                                    <td class="py-3.5 px-4 text-right text-lg font-mono text-cyan-400 print:text-cyan-900">{{ formatRupiah(transaksi.total_harga) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Footer Legal & UU PDP Disclaimer -->
                <div class="border-t border-slate-700/60 print:border-slate-300 pt-6 text-xs text-slate-400 print:text-slate-600 space-y-2">
                    <p class="font-bold text-slate-300 print:text-slate-800">📌 KLAUSUL KEAMANAN DATA & KEPATUHAN UU PDP NO. 27 TAHUN 2022:</p>
                    <p>
                        Dokumen identitas (KTP & SIM) yang diunggah untuk transaksi ini disimpan pada repositori aman terenkripsi (private storage). Sesuai dengan ketentuan Undang-Undang Perlindungan Data Pribadi (UU PDP No. 27/2022), seluruh salinan dokumen identitas fisik akan di-purge dan dihapus secara permanen dari server otomatis 30 hari setelah unit kendaraan dikembalikan, kecuali terdapat pengajuan klaim/sengketa resmi.
                    </p>
                    <p v-if="transaksi.dokumen_purged_at" class="text-amber-400 print:text-amber-700 font-semibold">
                        Status Dokumen: Berkas fisik KTP/SIM telah di-purge otomatis pada {{ transaksi.dokumen_purged_at }}.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@media print {
    body {
        background-color: white !important;
        color: black !important;
    }
}
</style>
