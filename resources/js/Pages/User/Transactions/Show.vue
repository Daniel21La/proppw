<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import {
    CheckCircle2,
    Calendar,
    Car,
    User,
    Phone,
    MapPin,
    Shield,
    Printer,
    Download,
    MessageSquare,
    ArrowLeft,
    Clock,
    QrCode,
    Sparkles,
} from 'lucide-vue-next';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Badge from '@/Components/Badge.vue';
import { useT } from '@/locales/translations';

const { t, currentLang } = useT();

const props = defineProps({
    transaksi: {
        type: Object,
        required: true,
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
        weekday: 'short',
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    });
}

const durationDays = computed(() => {
    if (!props.transaksi.tanggal_mulai || !props.transaksi.tanggal_selesai) return 1;
    const start = new Date(props.transaksi.tanggal_mulai);
    const end = new Date(props.transaksi.tanggal_selesai);
    const diff = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
    return diff >= 0 ? diff + 1 : 1;
});

function printVoucher() {
    window.print();
}
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`E-Voucher #${transaksi.nomor_booking || transaksi.id}`" />

        <!-- Print-friendly Styles -->
        <div class="max-w-4xl mx-auto mb-8 print:m-0 print:max-w-full">
            <!-- Top Controls (hidden when printing) -->
            <div class="flex items-center justify-between gap-4 mb-6 print:hidden">
                <Link
                    href="/transaksi"
                    class="inline-flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-white transition"
                >
                    <ArrowLeft class="w-4 h-4" />
                    {{ t('voucher.myOrders') }}
                </Link>

                <div class="flex items-center gap-3">
                    <a
                        href="https://wa.me/6281234567890?text=Halo%20Admin%20RentalMobil,%20saya%20butuh%20bantuan%20terkait%20booking%20"
                        target="_blank"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-emerald-600/10 hover:bg-emerald-600/20 text-emerald-400 border border-emerald-500/30 text-xs font-bold transition"
                    >
                        <MessageSquare class="w-3.5 h-3.5" />
                        {{ t('checkout.needHelp') }}
                    </a>

                    <button
                        type="button"
                        @click="printVoucher"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white text-xs font-bold shadow-lg shadow-red-600/20 transition active:scale-95 cursor-pointer"
                    >
                        <Printer class="w-4 h-4" />
                        {{ t('voucher.printPdf') }}
                    </button>
                </div>
            </div>

            <!-- Main E-Voucher Card -->
            <div class="bg-neutral-900 border border-neutral-800 rounded-3xl overflow-hidden shadow-2xl text-slate-200 print:border print:shadow-none">
                <!-- Header Banner -->
                <div class="bg-gradient-to-r from-red-600 via-rose-600 to-red-700 p-6 sm:p-8 text-white flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-black/20 backdrop-blur-md text-[11px] font-bold tracking-wider uppercase mb-2">
                            <CheckCircle2 class="w-3.5 h-3.5" />
                            {{ t('voucher.successTitle') }}
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight">
                            Digital Rental E-Voucher
                        </h1>
                        <p class="text-xs text-red-100 mt-0.5">
                            Konfirmasi resmi pemesanan unit mobil self-service.
                        </p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-4 text-right shrink-0">
                        <span class="text-[10px] uppercase tracking-wider text-red-100 block font-bold">
                            {{ t('voucher.bookingCode') }}
                        </span>
                        <span class="text-lg sm:text-xl font-black tracking-wider text-white font-mono">
                            {{ transaksi.nomor_booking || `#RM-${transaksi.id}` }}
                        </span>
                    </div>
                </div>

                <div class="p-6 sm:p-8 space-y-8">
                    <!-- Vehicle and Schedule Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start pb-8 border-b border-neutral-800">
                        <!-- Car Showcase (5 cols) -->
                        <div class="md:col-span-5 bg-neutral-950/60 rounded-2xl p-5 border border-neutral-800/80 flex flex-col justify-between">
                            <div>
                                <div class="h-44 rounded-xl bg-neutral-900 overflow-hidden mb-4 border border-neutral-800 flex items-center justify-center">
                                    <img
                                        v-if="transaksi.mobil?.gambar"
                                        :src="`/gambar_mobil/${transaksi.mobil.gambar}`"
                                        :alt="transaksi.mobil?.nama_mobil"
                                        class="w-full h-full object-cover"
                                    />
                                    <Car v-else class="w-12 h-12 text-neutral-600" />
                                </div>
                                <span class="px-2.5 py-0.5 rounded-md bg-red-600/20 text-red-400 border border-red-500/30 text-[10px] font-bold uppercase tracking-wider inline-block mb-1.5">
                                    {{ transaksi.mobil?.merk }}
                                </span>
                                <h3 class="text-lg font-black text-white">
                                    {{ transaksi.mobil?.nama_mobil }}
                                </h3>
                                <p class="text-xs text-slate-400 mt-1">
                                    {{ transaksi.mobil?.tipe_kendaraan || 'Sedan Eksekutif' }} • {{ transaksi.mobil?.transmisi || 'Matic' }} • {{ transaksi.mobil?.kapasitas_penumpang || 5 }} Kursi
                                </p>
                            </div>

                            <div class="mt-4 pt-3 border-t border-neutral-800/80 flex items-center justify-between text-xs">
                                <span class="text-slate-400">Pilihan Layanan:</span>
                                <span class="font-bold text-amber-400 uppercase tracking-wide">
                                    {{ transaksi.layanan === 'dengan_sopir' ? t('hero.withDriver') : t('hero.selfDrive') }}
                                </span>
                            </div>
                        </div>

                        <!-- Schedule & Meeting Point (7 cols) -->
                        <div class="md:col-span-7 space-y-5">
                            <div class="bg-neutral-950/40 rounded-2xl p-5 border border-neutral-800/60 space-y-4">
                                <!-- Location -->
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-red-600/10 text-red-500 flex items-center justify-center shrink-0 mt-0.5">
                                        <MapPin class="w-4 h-4" />
                                    </div>
                                    <div>
                                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">
                                            {{ t('voucher.pickupPoint') }}
                                        </span>
                                        <span class="text-sm font-bold text-white block mt-0.5">
                                            {{ transaksi.lokasi_jemput || 'Pool RentalMobil Pusat (Bisa Request Antar)' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Date & Time Grid -->
                                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-neutral-800/60">
                                    <div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">
                                            Mulai Sewa
                                        </span>
                                        <span class="text-xs font-bold text-white block mt-0.5">
                                            {{ formatDate(transaksi.tanggal_mulai) }}
                                        </span>
                                        <span class="text-[11px] text-amber-400 font-semibold block">
                                            Pukul {{ transaksi.jam_mulai || '09:00' }} WIB
                                        </span>
                                    </div>

                                    <div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">
                                            Selesai Sewa
                                        </span>
                                        <span class="text-xs font-bold text-white block mt-0.5">
                                            {{ formatDate(transaksi.tanggal_selesai) }}
                                        </span>
                                        <span class="text-[11px] text-amber-400 font-semibold block">
                                            Pukul {{ transaksi.jam_selesai || '09:00' }} WIB
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Driver Info Box (if With Driver) -->
                            <div
                                v-if="transaksi.layanan === 'dengan_sopir'"
                                class="bg-amber-950/20 border border-amber-500/30 rounded-2xl p-4 flex items-start gap-3 text-xs"
                            >
                                <User class="w-5 h-5 text-amber-400 shrink-0 mt-0.5" />
                                <div>
                                    <h4 class="font-bold text-amber-300">
                                        {{ t('voucher.driverContact') }}
                                    </h4>
                                    <p class="text-slate-300 mt-1 leading-relaxed">
                                        {{ t('voucher.driverNotice') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Self-Drive Document Reminder -->
                            <div
                                v-else
                                class="bg-blue-950/20 border border-blue-500/30 rounded-2xl p-4 flex items-start gap-3 text-xs"
                            >
                                <Shield class="w-5 h-5 text-blue-400 shrink-0 mt-0.5" />
                                <div>
                                    <h4 class="font-bold text-blue-300">
                                        Verifikasi Dokumen Lepas Kunci
                                    </h4>
                                    <p class="text-slate-300 mt-1 leading-relaxed">
                                        Harap siapkan KTP asli dan SIM A aktif saat serah terima unit kendaraan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment & Price Itemized Receipt -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">
                            Rincian Pembayaran & Bukti Transaksi
                        </h4>

                        <div class="bg-neutral-950/50 rounded-2xl p-5 border border-neutral-800/80 space-y-2.5 text-xs">
                            <div class="flex items-center justify-between text-slate-400">
                                <span>{{ t('checkout.rentCost') }} ({{ durationDays }} Hari x {{ formatRupiah(transaksi.mobil?.harga_per_hari) }})</span>
                                <span class="font-semibold text-white">{{ formatRupiah((transaksi.mobil?.harga_per_hari || 0) * durationDays) }}</span>
                            </div>

                            <div v-if="transaksi.biaya_sopir > 0" class="flex items-center justify-between text-slate-400">
                                <span>{{ t('checkout.driverCost') }}</span>
                                <span class="font-semibold text-white">{{ formatRupiah(transaksi.biaya_sopir) }}</span>
                            </div>

                            <div v-if="transaksi.biaya_asuransi > 0" class="flex items-center justify-between text-slate-400">
                                <span>{{ t('checkout.insuranceCost') }}</span>
                                <span class="font-semibold text-white">{{ formatRupiah(transaksi.biaya_asuransi) }}</span>
                            </div>

                            <div class="flex items-center justify-between text-slate-400">
                                <span>Metode Pembayaran</span>
                                <span class="font-bold text-amber-400 uppercase">{{ transaksi.metode_pembayaran || 'Virtual Account' }}</span>
                            </div>

                            <div class="pt-3 border-t border-neutral-800 flex items-center justify-between text-sm">
                                <span class="font-extrabold text-white">Status Transaksi & Total Dibayar</span>
                                <span class="font-black text-emerald-400 text-base">
                                    {{ formatRupiah(transaksi.total_harga) }} (LUNAS)
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Cancellation Policy Notice -->
                    <p class="text-[11px] text-slate-400 text-center italic border-t border-neutral-800/60 pt-4">
                        {{ t('voucher.cancelNotice') }}
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
