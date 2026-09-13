<script setup>
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import {
    BarChart3,
    DollarSign,
    Car,
    CheckCircle2,
    Calendar,
    TrendingUp,
    Shield,
    FileSpreadsheet,
    FileText,
    Sparkles,
} from 'lucide-vue-next';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Badge from '@/Components/Badge.vue';

const props = defineProps({
    transaksi: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Object,
        default: () => ({
            keuangan: 0,
            stok_tersedia: 0,
            stok_disewa: 0,
            total_mobil: 0,
            total_transaksi: 0,
        }),
    },
});

function formatRupiah(num) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(num || 0);
}

function formatDate(d) {
    if (!d) return '-';
    return new Date(d).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    });
}

const occupancyRate = computed(() => {
    if (!props.stats.total_mobil) return 0;
    return Math.round((props.stats.stok_disewa / props.stats.total_mobil) * 100);
});

const approvedTransactions = computed(() => {
    return props.transaksi.filter((t) => t.status === 'disetujui');
});
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Laporan Keuangan & Statistik - Quantum Streamline" />

        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-2 h-2 rounded-full bg-red-600 animate-pulse" />
                    <span class="text-[11px] font-black tracking-widest text-red-500 uppercase">
                        Financial & Fleet Analytics
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white uppercase tracking-tight">
                    Laporan Keuangan & Utilisasi Armada
                </h1>
                <p class="text-xs sm:text-sm text-neutral-400 mt-1">
                    Rekapitulasi pendapatan kotor, tingkat okupansi unit armada, dan performa transaksi booking.
                </p>
            </div>

            <!-- Export Buttons -->
            <div class="flex items-center gap-3 shrink-0">
                <a
                    href="/admin/laporan/export-pdf"
                    target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-neutral-900 hover:bg-neutral-800 border border-neutral-700 text-white font-bold text-xs uppercase tracking-wider shadow-lg transition transform hover:-translate-y-0.5 active:scale-95 group"
                >
                    <FileText class="w-4 h-4 text-red-500 group-hover:scale-110 transition" />
                    <span>Cetak PDF Laporan</span>
                </a>
                <a
                    href="/admin/laporan/export-excel"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-emerald-600/30 transition transform hover:-translate-y-0.5 active:scale-95 group"
                >
                    <FileSpreadsheet class="w-4 h-4 text-white group-hover:scale-110 transition" />
                    <span>Unduh Excel (CSV)</span>
                </a>
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <!-- Total Revenue -->
            <div class="bg-gradient-to-br from-red-600 to-rose-700 rounded-3xl p-6 text-white shadow-2xl shadow-red-600/25 relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-28 h-28 bg-white/10 rounded-full blur-xl pointer-events-none" />
                <span class="text-[10px] font-black uppercase tracking-wider text-red-200 block">
                    Total Pendapatan Terverifikasi
                </span>
                <div class="text-2xl sm:text-3xl font-black tracking-tight mt-2">
                    {{ formatRupiah(stats.keuangan) }}
                </div>
                <div class="mt-4 flex items-center gap-1.5 text-xs text-red-100 font-bold">
                    <TrendingUp class="w-4 h-4" />
                    <span>{{ approvedTransactions.length }} Reservasi Sukses</span>
                </div>
            </div>

            <!-- Fleet Utilization -->
            <div class="bg-[#0f1015] rounded-3xl p-6 border border-neutral-800 shadow-xl">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-neutral-400">Tingkat Okupansi</span>
                    <Car class="w-5 h-5 text-red-500" />
                </div>
                <div class="text-3xl font-black text-white mt-2">
                    {{ occupancyRate }}%
                </div>
                <div class="mt-4 w-full bg-neutral-800 rounded-full h-2 overflow-hidden">
                    <div
                        class="bg-gradient-to-r from-red-600 to-rose-500 h-full rounded-full transition-all duration-500"
                        :style="{ width: `${occupancyRate}%` }"
                    />
                </div>
            </div>

            <!-- Available Stock -->
            <div class="bg-[#0f1015] rounded-3xl p-6 border border-neutral-800 shadow-xl">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-emerald-400">Armada Tersedia</span>
                    <CheckCircle2 class="w-5 h-5 text-emerald-500" />
                </div>
                <div class="text-3xl font-black text-emerald-400 mt-2">
                    {{ stats.stok_tersedia }}
                    <span class="text-xs font-normal text-neutral-500">/ {{ stats.total_mobil }} Unit</span>
                </div>
                <p class="mt-4 text-[11px] text-neutral-400">
                    Siap disewa hari ini di garasi
                </p>
            </div>

            <!-- Currently Rented -->
            <div class="bg-[#0f1015] rounded-3xl p-6 border border-neutral-800 shadow-xl">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-yellow-400">Sedang Disewa</span>
                    <Car class="w-5 h-5 text-yellow-500" />
                </div>
                <div class="text-3xl font-black text-yellow-400 mt-2">
                    {{ stats.stok_disewa }}
                    <span class="text-xs font-normal text-neutral-500">Unit Aktif</span>
                </div>
                <p class="mt-4 text-[11px] text-neutral-400">
                    Dalam masa sewa pelanggan
                </p>
            </div>
        </div>

        <!-- Ledger Table of Approved Transactions -->
        <div class="bg-[#0f1015] rounded-3xl border border-neutral-800 shadow-2xl overflow-hidden">
            <div class="p-6 border-b border-neutral-800 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-white">
                        Buku Besar Transaksi Masuk
                    </h3>
                    <p class="text-xs text-neutral-400 mt-0.5">
                        Daftar transaksi sewa yang telah disetujui dan menghasilkan omset.
                    </p>
                </div>
                <span class="px-3 py-1 rounded-full bg-red-950/60 text-red-400 border border-red-500/30 text-[10px] font-black uppercase tracking-wider">
                    {{ approvedTransactions.length }} Rekap Selesai
                </span>
            </div>

            <div v-if="approvedTransactions.length === 0" class="p-12 text-center">
                <BarChart3 class="w-10 h-10 text-neutral-600 mx-auto mb-2" />
                <p class="text-sm font-bold text-neutral-300">Belum ada transaksi yang disetujui</p>
                <p class="text-xs text-neutral-500 mt-1">Omset akan muncul saat pemesanan berstatus Disetujui.</p>
            </div>

            <div v-else class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-neutral-800 bg-neutral-900/80 text-[10px] font-black uppercase tracking-widest text-neutral-400">
                            <th class="py-4 px-6">ID & No. Booking</th>
                            <th class="py-4 px-6">Penyewa</th>
                            <th class="py-4 px-6">Kendaraan</th>
                            <th class="py-4 px-6">Periode Sewa</th>
                            <th class="py-4 px-6">Layanan</th>
                            <th class="py-4 px-6 text-right">Nilai Transaksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-800/80">
                        <tr
                            v-for="t in approvedTransactions"
                            :key="t.id"
                            class="hover:bg-neutral-900/40 transition duration-150"
                        >
                            <!-- ID & Booking -->
                            <td class="py-4 px-6">
                                <span class="font-black text-white block">
                                    {{ t.nomor_booking || `#TRX-${t.id}` }}
                                </span>
                                <span class="text-[10px] text-neutral-400">
                                    {{ formatDate(t.created_at) }}
                                </span>
                            </td>

                            <!-- Penyewa -->
                            <td class="py-4 px-6">
                                <span class="font-bold text-white block">
                                    {{ t.user?.name || 'Pelanggan' }}
                                </span>
                                <span class="text-[10px] text-neutral-400">
                                    {{ t.user?.email }}
                                </span>
                            </td>

                            <!-- Mobil -->
                            <td class="py-4 px-6">
                                <span class="font-bold text-white block">
                                    {{ t.mobil?.nama_mobil || 'Unit Dihapus' }}
                                </span>
                                <span class="text-[10px] text-neutral-400">
                                    {{ t.mobil?.merk }}
                                </span>
                            </td>

                            <!-- Periode -->
                            <td class="py-4 px-6 text-neutral-300">
                                <div class="flex items-center gap-1 text-[11px]">
                                    <Calendar class="w-3.5 h-3.5 text-red-500 shrink-0" />
                                    <span>{{ formatDate(t.tanggal_mulai) }}</span>
                                    <span>&rarr;</span>
                                    <span>{{ formatDate(t.tanggal_selesai) }}</span>
                                </div>
                            </td>

                            <!-- Layanan -->
                            <td class="py-4 px-6">
                                <span class="uppercase font-bold text-[10px] text-neutral-400">
                                    {{ t.layanan === 'dengan_sopir' ? 'Dgn Sopir' : 'Lepas Kunci' }}
                                </span>
                            </td>

                            <!-- Nilai -->
                            <td class="py-4 px-6 text-right font-black text-emerald-400 text-sm">
                                + {{ formatRupiah(t.total_harga) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
