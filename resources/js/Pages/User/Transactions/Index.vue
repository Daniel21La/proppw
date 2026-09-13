<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import {
    Calendar,
    Car,
    Clock,
    Plus,
    XCircle,
    FileText,
    ArrowRight,
    Sparkles,
    CheckCircle2,
    Ticket,
    Shield,
    Key,
    UserCheck,
    Printer,
} from 'lucide-vue-next';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Badge from '@/Components/Badge.vue';
import { useT } from '@/locales/translations';

const { t, currentLang } = useT();

const props = defineProps({
    transaksis: {
        type: Array,
        default: () => [],
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
    return new Date(d).toLocaleDateString(currentLang.value === 'id' ? 'id-ID' : 'en-US', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    });
}

function cancelBooking(id) {
    if (confirm('Apakah Anda yakin ingin membatalkan reservasi ini?')) {
        router.post(`/transaksi/${id}/cancel`, {}, {
            preserveScroll: true,
        });
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Riwayat Pemesanan & E-Voucher - Quantum Streamline" />

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-2 h-2 rounded-full bg-red-600 animate-pulse" />
                    <span class="text-[11px] font-black tracking-widest text-red-500 uppercase">
                        Executive Member Area
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white uppercase tracking-tight">
                    Pesanan & E-Voucher Saya
                </h1>
                <p class="text-xs sm:text-sm text-neutral-400 mt-1">
                    Pantau status reservasi, rincian biaya sewa, dan akses tiket digital E-Voucher resmi Anda.
                </p>
            </div>

            <div>
                <Link
                    href="/transaksi/create"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 active:scale-95 text-white text-xs font-black uppercase tracking-wider shadow-lg shadow-red-600/30 transition cursor-pointer"
                >
                    <Plus class="w-4 h-4" />
                    Sewa Armada Baru
                </Link>
            </div>
        </div>

        <!-- Empty State -->
        <div
            v-if="transaksis.length === 0"
            class="bg-[#0f1015] rounded-3xl p-12 text-center border border-neutral-800 shadow-2xl max-w-md mx-auto my-12"
        >
            <div class="w-16 h-16 rounded-2xl bg-neutral-900 border border-neutral-800 text-red-500 flex items-center justify-center mx-auto mb-4 shadow-md">
                <Car class="w-8 h-8" />
            </div>
            <h3 class="text-base font-black uppercase tracking-wider text-white">
                Belum Ada Riwayat Sewa
            </h3>
            <p class="text-xs text-neutral-400 mt-1 max-w-xs mx-auto leading-relaxed">
                Anda belum memiliki pemesanan aktif. Pilih kendaraan impian Anda dari katalog eksklusif kami dan mulai perjalanan Anda!
            </p>
            <Link
                href="/transaksi/create"
                class="mt-6 inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-black uppercase tracking-wider shadow-lg shadow-red-600/30 transition cursor-pointer"
            >
                Mulai Reservasi
                <ArrowRight class="w-4 h-4" />
            </Link>
        </div>

        <!-- Table & Card List View -->
        <div
            v-else
            class="bg-[#0f1015] rounded-3xl border border-neutral-800 shadow-2xl overflow-hidden"
        >
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-neutral-800 bg-neutral-900/80 text-[10px] font-black uppercase tracking-widest text-neutral-400">
                            <th class="py-4 px-6">No. Booking & Tanggal</th>
                            <th class="py-4 px-6">Kendaraan</th>
                            <th class="py-4 px-6">Layanan</th>
                            <th class="py-4 px-6">Jadwal Sewa</th>
                            <th class="py-4 px-6">Total Biaya</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6 text-right">E-Voucher & Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-800/80">
                        <tr
                            v-for="t in transaksis"
                            :key="t.id"
                            class="hover:bg-neutral-900/50 transition duration-150"
                        >
                            <!-- Booking Code & Created Date -->
                            <td class="py-4 px-6">
                                <span class="font-black text-white text-xs tracking-wider block">
                                    {{ t.nomor_booking || `#TRX-${t.id}` }}
                                </span>
                                <span class="text-[10px] text-neutral-400 font-medium">
                                    {{ formatDate(t.created_at) }}
                                </span>
                            </td>

                            <!-- Car Info -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-14 h-10 rounded-xl bg-neutral-900 overflow-hidden shrink-0 border border-neutral-800 flex items-center justify-center">
                                        <img
                                            v-if="t.mobil?.gambar"
                                            :src="`/gambar_mobil/${t.mobil.gambar}`"
                                            :alt="t.mobil?.nama_mobil"
                                            class="w-full h-full object-cover"
                                        />
                                        <Car v-else class="w-4 h-4 text-neutral-600" />
                                    </div>
                                    <div>
                                        <span class="font-extrabold text-white block">
                                            {{ t.mobil?.nama_mobil || 'Unit Armada' }}
                                        </span>
                                        <span class="text-[10px] text-neutral-400 uppercase tracking-wider font-bold">
                                            {{ t.mobil?.merk }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Layanan -->
                            <td class="py-4 px-6">
                                <span
                                    v-if="t.layanan === 'dengan_sopir'"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-950/60 text-red-400 border border-red-500/30 text-[10px] font-bold uppercase tracking-wider"
                                >
                                    <UserCheck class="w-3 h-3" />
                                    Dengan Sopir
                                </span>
                                <span
                                    v-else
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-neutral-800 text-neutral-300 border border-neutral-700 text-[10px] font-bold uppercase tracking-wider"
                                >
                                    <Key class="w-3 h-3 text-neutral-400" />
                                    Lepas Kunci
                                </span>
                            </td>

                            <!-- Dates -->
                            <td class="py-4 px-6 font-semibold text-neutral-300">
                                <div class="flex items-center gap-1.5 text-xs">
                                    <Calendar class="w-3.5 h-3.5 text-red-500 shrink-0" />
                                    <span>{{ formatDate(t.tanggal_mulai) }}</span>
                                    <span class="text-neutral-500">&rarr;</span>
                                    <span>{{ formatDate(t.tanggal_selesai) }}</span>
                                </div>
                            </td>

                            <!-- Total -->
                            <td class="py-4 px-6 font-black text-red-400 text-sm">
                                {{ formatRupiah(t.total_harga) }}
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6">
                                <Badge :status="t.status || 'pending'" />
                            </td>

                            <!-- Action: E-Voucher Link -->
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Direct Link to E-Voucher Detail -->
                                    <Link
                                        :href="`/transaksi/${t.id}`"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-neutral-800 hover:bg-neutral-700 text-white border border-neutral-700 font-bold transition text-xs cursor-pointer shadow-sm"
                                        title="Buka Detail E-Voucher"
                                    >
                                        <Ticket class="w-3.5 h-3.5 text-amber-400" />
                                        <span>Detail</span>
                                    </Link>

                                    <!-- Direct Print PDF Button -->
                                    <a
                                        :href="`/transaksi/${t.id}/invoice?print=1`"
                                        target="_blank"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white font-bold transition text-xs cursor-pointer shadow-sm"
                                        title="Cetak / Unduh PDF E-Voucher"
                                    >
                                        <Printer class="w-3.5 h-3.5" />
                                        <span>Cetak PDF</span>
                                    </a>

                                    <!-- Cancel button if pending -->
                                    <button
                                        v-if="t.status === 'pending'"
                                        type="button"
                                        @click="cancelBooking(t.id)"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-neutral-400 hover:text-rose-400 hover:bg-neutral-800 transition text-xs cursor-pointer"
                                        title="Batalkan Permintaan"
                                    >
                                        <XCircle class="w-3.5 h-3.5" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
