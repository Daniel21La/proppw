<script setup>
import { ref, computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
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
    AlertTriangle,
    RefreshCw,
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
    window.open(`/transaksi/${props.transaksi.id}/invoice?print=1`, '_blank');
}

// Point 5B: Rental Extension (Extend) State & Handler
const isExtendModalOpen = ref(false);
const isCheckingExtend = ref(false);
const extendCheckResult = ref(null);

const extendForm = useForm({
    durasi_hari: 1,
    metode_pembayaran: 'bca_va',
});

async function runCheckExtend() {
    isCheckingExtend.value = true;
    extendCheckResult.value = null;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const res = await fetch(`/transaksi/${props.transaksi.id}/check-extend`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ durasi_hari: extendForm.durasi_hari }),
        });
        const data = await res.json();
        extendCheckResult.value = data;
    } catch (e) {
        extendCheckResult.value = { allowed: false, message: 'Gagal memvalidasi jadwal ketersediaan.' };
    } finally {
        isCheckingExtend.value = false;
    }
}

function openExtendModal() {
    isExtendModalOpen.value = true;
    runCheckExtend();
}

function submitExtend() {
    extendForm.post(`/transaksi/${props.transaksi.id}/apply-extend`, {
        preserveScroll: true,
        onSuccess: () => {
            isExtendModalOpen.value = false;
        },
    });
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
                    <button
                        v-if="['dikonfirmasi', 'berjalan'].includes(transaksi.status)"
                        type="button"
                        @click="openExtendModal"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-amber-600/20 hover:bg-amber-600/30 text-amber-400 border border-amber-500/40 text-xs font-bold transition active:scale-95 cursor-pointer"
                    >
                        <Clock class="w-3.5 h-3.5" />
                        Ajukan Extend Sewa
                    </button>

                    <a
                        href="https://wa.me/6281234567890?text=Halo%20Admin%20RentalMobil,%20saya%20butuh%20bantuan%20terkait%20booking%20"
                        target="_blank"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-emerald-600/10 hover:bg-emerald-600/20 text-emerald-400 border border-emerald-500/30 text-xs font-bold transition"
                    >
                        <MessageSquare class="w-3.5 h-3.5" />
                        {{ t('checkout.needHelp') }}
                    </a>

                    <a
                        :href="`/transaksi/${transaksi.id}/invoice`"
                        target="_blank"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-neutral-800 hover:bg-neutral-700 text-slate-200 border border-neutral-700 text-xs font-bold transition active:scale-95"
                    >
                        <FileText class="w-4 h-4 text-red-400" />
                        Invoice Standalone
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
                        <div class="flex items-center gap-2 mb-2">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-black/20 backdrop-blur-md text-[11px] font-bold tracking-wider uppercase">
                                <CheckCircle2 class="w-3.5 h-3.5" />
                                {{ t('voucher.successTitle') }}
                            </div>
                            <span v-if="transaksi.is_extended" class="px-2.5 py-1 rounded-full bg-amber-400 text-black font-black text-[10px] uppercase tracking-wider">
                                Masa Sewa Diperpanjang
                            </span>
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
                                        <span v-if="transaksi.jarak_pengantaran_km > 0" class="text-[10px] text-neutral-400 block mt-0.5">
                                            Jarak Google Maps: {{ transaksi.jarak_pengantaran_km }} km dari Garasi
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

                            <!-- Point 5D: Extra Hours Line Item -->
                            <div v-if="transaksi.extra_hours > 0" class="flex items-center justify-between text-slate-400">
                                <span>Add-on Jam Tambahan (+{{ transaksi.extra_hours }} Jam)</span>
                                <span class="font-semibold text-amber-400">{{ formatRupiah(transaksi.biaya_extra_hours) }}</span>
                            </div>

                            <!-- Point 5C: Google Maps Delivery Fee Line Item -->
                            <div v-if="transaksi.biaya_pengantaran > 0" class="flex items-center justify-between text-slate-400">
                                <span>Biaya Pengantaran Luar Kota ({{ transaksi.jarak_pengantaran_km }} km)</span>
                                <span class="font-semibold text-white">{{ formatRupiah(transaksi.biaya_pengantaran) }}</span>
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

                        <!-- Point 5A: Late Return Fine Alert if Occurred -->
                        <div v-if="transaksi.denda_keterlambatan > 0" class="mt-4 p-4 rounded-2xl border" :class="transaksi.status_denda === 'lunas' ? 'bg-emerald-950/30 border-emerald-500/30' : 'bg-red-950/30 border-red-500/40'">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-3">
                                    <AlertTriangle class="w-5 h-5 mt-0.5" :class="transaksi.status_denda === 'lunas' ? 'text-emerald-400' : 'text-red-400'" />
                                    <div>
                                        <h4 class="font-bold text-xs uppercase" :class="transaksi.status_denda === 'lunas' ? 'text-emerald-300' : 'text-red-300'">
                                            Tagihan Denda Keterlambatan Pengembalian
                                        </h4>
                                        <p class="text-[11px] text-slate-400 mt-0.5 leading-relaxed">
                                            Unit dikembalikan terlambat <b>{{ Math.floor(transaksi.menit_terlambat / 60) }} jam {{ transaksi.menit_terlambat % 60 }} menit</b> melewati batas toleransi grace period 30 menit. Denda otomatis dihitung oleh sistem.
                                        </p>
                                    </div>
                                </div>

                                <div class="text-right shrink-0">
                                    <span class="text-sm font-black block" :class="transaksi.status_denda === 'lunas' ? 'text-emerald-400' : 'text-red-400'">
                                        {{ formatRupiah(transaksi.denda_keterlambatan) }}
                                    </span>
                                    <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-full inline-block mt-1" :class="transaksi.status_denda === 'lunas' ? 'bg-emerald-900/60 text-emerald-300' : 'bg-red-900/60 text-red-300'">
                                        {{ transaksi.status_denda === 'lunas' ? 'Lunas' : 'Belum Dibayar' }}
                                    </span>
                                </div>
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

        <!-- Point 5B: Rental Extension (Extend) Modal -->
        <div
            v-if="isExtendModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
            @click.self="isExtendModalOpen = false"
        >
            <div class="bg-[#0f1015] border border-neutral-800 rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl text-slate-200 space-y-5 animate-in fade-in zoom-in duration-200">
                <div class="flex items-center justify-between border-b border-neutral-800 pb-4">
                    <div class="flex items-center gap-2.5">
                        <Clock class="w-5 h-5 text-amber-500" />
                        <h3 class="text-sm font-black uppercase tracking-wider text-white">
                            Ajukan Perpanjangan Sewa (Extend)
                        </h3>
                    </div>
                    <button
                        type="button"
                        @click="isExtendModalOpen = false"
                        class="p-1.5 rounded-lg bg-neutral-900 text-neutral-400 hover:text-white"
                    >
                        ✕
                    </button>
                </div>

                <div class="space-y-4">
                    <!-- Unit Info -->
                    <div class="p-3.5 rounded-2xl bg-black/40 border border-neutral-800 flex items-center justify-between text-xs">
                        <div>
                            <span class="text-slate-400 text-[11px] block">Unit Kendaraan:</span>
                            <span class="font-bold text-white">{{ transaksi.mobil?.nama_mobil }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-slate-400 text-[11px] block">Jadwal Selesai Saat Ini:</span>
                            <span class="font-bold text-amber-400">{{ formatDate(transaksi.tanggal_selesai) }}</span>
                        </div>
                    </div>

                    <!-- Duration Select -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-2">
                            Pilih Tambahan Durasi Hari
                        </label>
                        <div class="grid grid-cols-3 gap-2">
                            <button
                                v-for="d in [1, 2, 3]"
                                :key="d"
                                type="button"
                                @click="extendForm.durasi_hari = d; runCheckExtend();"
                                :class="[
                                    'py-2.5 rounded-xl border text-center transition font-bold text-xs cursor-pointer',
                                    extendForm.durasi_hari === d
                                        ? 'bg-amber-600 border-amber-500 text-white shadow-lg shadow-amber-600/30'
                                        : 'bg-[#070709] border-neutral-800 text-neutral-400 hover:text-white'
                                ]"
                            >
                                +{{ d }} Hari
                            </button>
                        </div>
                    </div>

                    <!-- Live Conflict & Fee Check Result -->
                    <div v-if="isCheckingExtend" class="p-4 rounded-xl bg-neutral-900/50 border border-neutral-800 text-center text-xs text-neutral-400">
                        <RefreshCw class="w-4 h-4 text-amber-400 animate-spin mx-auto mb-1.5" />
                        <span>Memeriksa ketersediaan jadwal armada...</span>
                    </div>

                    <div v-else-if="extendCheckResult">
                        <!-- If Conflict (Automated Rejection) -->
                        <div v-if="!extendCheckResult.allowed" class="p-4 rounded-2xl bg-red-950/40 border border-red-500/40 space-y-2">
                            <div class="flex items-center gap-2 text-red-400 font-bold text-xs">
                                <AlertTriangle class="w-4 h-4 shrink-0" />
                                <span>Perpanjangan Tidak Dapat Diproses</span>
                            </div>
                            <p class="text-[11px] text-slate-300 leading-relaxed">
                                {{ extendCheckResult.message }}
                            </p>
                        </div>

                        <!-- If Available (Live Invoice) -->
                        <div v-else class="p-4 rounded-2xl bg-black/50 border border-emerald-500/30 space-y-2.5 text-xs">
                            <div class="flex items-center justify-between text-emerald-400 font-bold pb-2 border-b border-neutral-800">
                                <span>Jadwal Tersedia ✓</span>
                                <span>Perpanjangan Hingga: {{ extendCheckResult.new_end_date }}</span>
                            </div>
                            <div class="flex justify-between text-slate-400">
                                <span>Tarif Unit (+{{ extendCheckResult.durasi_hari }} Hari)</span>
                                <span class="text-white font-bold">{{ formatRupiah(extendCheckResult.additional_car_cost) }}</span>
                            </div>
                            <div v-if="extendCheckResult.additional_driver_cost > 0" class="flex justify-between text-slate-400">
                                <span>Layanan Sopir (+{{ extendCheckResult.durasi_hari }} Hari)</span>
                                <span class="text-white font-bold">{{ formatRupiah(extendCheckResult.additional_driver_cost) }}</span>
                            </div>
                            <div class="pt-2 border-t border-neutral-800 flex justify-between text-sm">
                                <span class="font-black text-white">Total Tagihan Tambahan:</span>
                                <span class="font-black text-amber-400">{{ formatRupiah(extendCheckResult.total_additional_cost) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method Select -->
                    <div v-if="extendCheckResult?.allowed">
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-2">
                            Metode Pembayaran
                        </label>
                        <select
                            v-model="extendForm.metode_pembayaran"
                            class="w-full px-3 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white"
                        >
                            <option value="bca_va">BCA Virtual Account (Otomatis)</option>
                            <option value="mandiri_va">Mandiri Livin' VA</option>
                            <option value="qris">QRIS Instant Payment</option>
                            <option value="credit_card">Kartu Kredit / Debit Visa & Mastercard</option>
                        </select>
                    </div>

                    <!-- Action Button -->
                    <button
                        type="button"
                        @click="submitExtend"
                        :disabled="extendForm.processing || !extendCheckResult?.allowed"
                        class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-amber-600 to-rose-600 hover:from-amber-500 hover:to-rose-500 text-white font-black text-xs uppercase tracking-wider shadow-xl shadow-amber-600/30 transition cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed"
                    >
                        {{ extendForm.processing ? 'Memproses Perpanjangan...' : 'Konfirmasi & Bayar Tagihan Perpanjangan' }}
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
