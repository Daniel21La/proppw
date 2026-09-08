<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import {
    Car,
    Calendar,
    CheckCircle2,
    Search,
    Sparkles,
    Shield,
    Clock,
    ArrowRight,
    ArrowLeft,
    User,
    Phone,
    CreditCard,
    MapPin,
    AlertCircle,
    Check,
    Lock,
    Key,
    UserCheck,
    FileCheck,
    ChevronRight,
} from 'lucide-vue-next';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Badge from '@/Components/Badge.vue';
import { useT } from '@/locales/translations';

const { t, currentLang } = useT();

const props = defineProps({
    mobils: {
        type: Array,
        default: () => [],
    },
    selectedMobilId: {
        type: Number,
        default: null,
    },
    initialLayanan: {
        type: String,
        default: 'lepas_kunci',
    },
    initialLokasi: {
        type: String,
        default: 'Jakarta',
    },
});

// Step Management: 1 = Details, 2 = Payment & Breakdown, 3 = Processing
const currentStep = ref(1);

// Format dates
const today = new Date().toISOString().split('T')[0];
const tomorrow = new Date(Date.now() + 86400000).toISOString().split('T')[0];

const form = useForm({
    mobil_id: props.selectedMobilId || (props.mobils[0]?.id ?? ''),
    layanan: props.initialLayanan || 'lepas_kunci',
    no_hp_pelanggan: '',
    lokasi_jemput: props.initialLokasi || 'Bandara Soekarno-Hatta (CGK)',
    tanggal_mulai: today,
    jam_mulai: '09:00',
    tanggal_selesai: tomorrow,
    jam_selesai: '09:00',
    asuransi_tambahan: true,
    catatan_sopir: '',
    metode_pembayaran: 'bca_va',
    pdp_consent: true,
    ktp: null,
    sim: null,
});

// Point 3: WhatsApp OTP State
const isSendingOtp = ref(false);
const isVerifyingOtp = ref(false);
const isOtpSent = ref(false);
const isPhoneVerified = ref(false);
const otpCode = ref('');
const otpCountdown = ref(0);
const otpMsg = ref('');
const debugOtp = ref('');
const ktpFileName = ref('');
const simFileName = ref('');

let countdownTimer = null;

function handleKtpChange(e) {
    const file = e.target.files[0];
    if (file) {
        form.ktp = file;
        ktpFileName.value = file.name;
    }
}

function handleSimChange(e) {
    const file = e.target.files[0];
    if (file) {
        form.sim = file;
        simFileName.value = file.name;
    }
}

async function requestOtp() {
    if (!form.no_hp_pelanggan || form.no_hp_pelanggan.length < 9) {
        alert('Masukkan nomor WhatsApp yang valid terlebih dahulu.');
        return;
    }
    isSendingOtp.value = true;
    otpMsg.value = '';
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const res = await fetch('/otp/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ no_hp: form.no_hp_pelanggan }),
        });
        const data = await res.json();
        if (data.success) {
            isOtpSent.value = true;
            otpMsg.value = data.message;
            if (data.debug_otp) {
                debugOtp.value = data.debug_otp;
                otpCode.value = data.debug_otp; // convenient autofill
            }
            otpCountdown.value = 60;
            if (countdownTimer) clearInterval(countdownTimer);
            countdownTimer = setInterval(() => {
                if (otpCountdown.value > 0) {
                    otpCountdown.value--;
                } else {
                    clearInterval(countdownTimer);
                }
            }, 1000);
        } else {
            alert(data.message || 'Gagal mengirim OTP.');
        }
    } catch (err) {
        alert('Terjadi kendala jaringan saat mengirim OTP.');
    } finally {
        isSendingOtp.value = false;
    }
}

async function verifyOtp() {
    if (!otpCode.value || otpCode.value.length !== 6) {
        alert('Masukkan 6 digit kode OTP WhatsApp.');
        return;
    }
    isVerifyingOtp.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const res = await fetch('/otp/verify', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                no_hp: form.no_hp_pelanggan,
                otp_code: otpCode.value,
            }),
        });
        const data = await res.json();
        if (data.success) {
            isPhoneVerified.value = true;
            otpMsg.value = 'Nomor WhatsApp terverifikasi!';
            if (countdownTimer) clearInterval(countdownTimer);
        } else {
            alert(data.message || 'Kode OTP tidak cocok.');
        }
    } catch (err) {
        alert('Gagal memverifikasi OTP.');
    } finally {
        isVerifyingOtp.value = false;
    }
}

const searchQuery = ref('');
const selectedBrand = ref('all');

const brands = computed(() => {
    const set = new Set(props.mobils.map((m) => m.merk).filter(Boolean));
    return ['all', ...Array.from(set)];
});

const filteredMobils = computed(() => {
    return props.mobils.filter((m) => {
        const matchesBrand =
            selectedBrand.value === 'all' ||
            m.merk?.toLowerCase() === selectedBrand.value.toLowerCase();
        const q = searchQuery.value.toLowerCase();
        const matchesSearch =
            !q ||
            m.nama_mobil?.toLowerCase().includes(q) ||
            m.merk?.toLowerCase().includes(q) ||
            m.tipe_kendaraan?.toLowerCase().includes(q);
        return matchesBrand && matchesSearch;
    });
});

const selectedCar = computed(() => {
    return props.mobils.find((m) => m.id === Number(form.mobil_id)) || null;
});

const durationDays = computed(() => {
    if (!form.tanggal_mulai || !form.tanggal_selesai) return 1;
    const start = new Date(form.tanggal_mulai);
    const end = new Date(form.tanggal_selesai);
    const diff = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
    return diff >= 0 ? diff + 1 : 1;
});

const baseCarTotal = computed(() => {
    if (!selectedCar.value) return 0;
    return selectedCar.value.harga_per_hari * durationDays.value;
});

const driverCost = computed(() => {
    if (form.layanan !== 'dengan_sopir' || !selectedCar.value) return 0;
    const rate = selectedCar.value.biaya_sopir_per_hari || 250000;
    return rate * durationDays.value;
});

const insuranceCost = computed(() => {
    if (!form.asuransi_tambahan) return 0;
    return 50000 * durationDays.value;
});

const grandTotal = computed(() => {
    return baseCarTotal.value + driverCost.value + insuranceCost.value;
});

function formatRupiah(num) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(num || 0);
}

function selectCar(id) {
    form.mobil_id = id;
}

const quickLocations = [
    'Bandara Soekarno-Hatta (CGK)',
    'Hotel Pusat Kota (SCBD / Thamrin)',
    'Stasiun Gambir / Halim KCIC',
    'Pengantaran ke Alamat Pribadi',
];

const paymentMethods = [
    {
        id: 'bca_va',
        name: 'BCA Virtual Account',
        desc: 'Verifikasi Otomatis 24 Jam Bebas Antre',
        badge: 'Instan',
        logo: 'BCA',
    },
    {
        id: 'mandiri_va',
        name: 'Mandiri Livin’ VA',
        desc: 'Transfer cepat via Livin by Mandiri',
        badge: 'Populer',
        logo: 'MANDIRI',
    },
    {
        id: 'qris',
        name: 'QRIS Instant Pay',
        desc: 'Scan via GoPay, OVO, ShopeePay, Dana, BCA Mobile',
        badge: 'Bebas Admin',
        logo: 'QRIS',
    },
    {
        id: 'credit_card',
        name: 'Kartu Kredit / Debit Visa & Mastercard',
        desc: 'Enkripsi 3D Secure 256-bit SSL',
        badge: 'Internasional',
        logo: 'VISA / MC',
    },
];

const step1Valid = computed(() => {
    const basic =
        !!form.mobil_id &&
        !!form.lokasi_jemput &&
        !!form.tanggal_mulai &&
        !!form.tanggal_selesai &&
        !!form.no_hp_pelanggan &&
        form.pdp_consent;

    if (form.layanan === 'lepas_kunci') {
        return basic && !!form.ktp && !!form.sim && isPhoneVerified.value;
    }
    return basic && isPhoneVerified.value;
});

function goToStep2() {
    if (!form.no_hp_pelanggan) {
        alert('Mohon masukkan nomor WhatsApp aktif Anda.');
        return;
    }
    if (!isPhoneVerified.value) {
        alert('Mohon lakukan verifikasi OTP WhatsApp terlebih dahulu untuk keamanan pesanan.');
        return;
    }
    if (form.layanan === 'lepas_kunci') {
        if (!form.ktp || !form.sim) {
            alert('Untuk layanan Lepas Kunci, Anda wajib mengunggah foto e-KTP dan SIM A asli.');
            return;
        }
    }
    if (!step1Valid.value) {
        alert('Mohon lengkapi seluruh formulir data dan persetujuan kebijakan privasi UU PDP.');
        return;
    }
    currentStep.value = 2;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function submitBooking() {
    currentStep.value = 3;
    form.post('/transaksi/store', {
        onError: () => {
            currentStep.value = 2;
        },
    });
}
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Checkout Reservasi Kendaraan - Quantum Streamline" />

        <div class="max-w-6xl mx-auto space-y-8">
            <!-- Luxury Breadcrumb / Stepper Progress Header -->
            <div class="bg-[#0f1015] border border-neutral-800 rounded-3xl p-6 sm:p-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-neutral-800 pb-6 mb-6">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <div class="w-2 h-2 rounded-full bg-red-600 animate-pulse" />
                            <span class="text-[11px] font-black tracking-widest text-red-500 uppercase">
                                Self-Service Online Checkout
                            </span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-black text-white uppercase tracking-tight">
                            Reservasi Armada Eksklusif
                        </h1>
                        <p class="text-xs sm:text-sm text-neutral-400 mt-1">
                            Sistem pemesanan mandiri terintegrasi langsung dengan garansi unit tersedia & penerbitan E-Voucher instan.
                        </p>
                    </div>

                    <!-- Steps Pill indicator -->
                    <div class="flex items-center gap-2">
                        <div
                            :class="[
                                'flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider transition',
                                currentStep === 1
                                    ? 'bg-red-600 text-white shadow-lg shadow-red-600/30'
                                    : 'bg-neutral-800/80 text-neutral-400',
                            ]"
                        >
                            <span>1</span>
                            <span class="hidden sm:inline">Data & Unit</span>
                        </div>
                        <ChevronRight class="w-3.5 h-3.5 text-neutral-600" />
                        <div
                            :class="[
                                'flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider transition',
                                currentStep === 2
                                    ? 'bg-red-600 text-white shadow-lg shadow-red-600/30'
                                    : 'bg-neutral-800/80 text-neutral-400',
                            ]"
                        >
                            <span>2</span>
                            <span class="hidden sm:inline">Pembayaran</span>
                        </div>
                        <ChevronRight class="w-3.5 h-3.5 text-neutral-600" />
                        <div
                            :class="[
                                'flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider transition',
                                currentStep === 3
                                    ? 'bg-red-600 text-white shadow-lg shadow-red-600/30'
                                    : 'bg-neutral-800/80 text-neutral-400',
                            ]"
                        >
                            <span>3</span>
                            <span class="hidden sm:inline">E-Voucher</span>
                        </div>
                    </div>
                </div>

                <!-- Service Switcher (Lepas Kunci vs Dengan Sopir) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <button
                        type="button"
                        @click="form.layanan = 'lepas_kunci'"
                        :class="[
                            'p-4 sm:p-5 rounded-2xl border text-left transition relative cursor-pointer',
                            form.layanan === 'lepas_kunci'
                                ? 'bg-gradient-to-br from-red-950/40 via-neutral-900 to-[#121318] border-red-600/80 ring-1 ring-red-500/40 shadow-xl'
                                : 'bg-neutral-900/60 border-neutral-800 hover:border-neutral-700 text-neutral-400',
                        ]"
                    >
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2.5">
                                <Key class="w-5 h-5" :class="form.layanan === 'lepas_kunci' ? 'text-red-500' : 'text-neutral-500'" />
                                <span class="text-sm font-black uppercase tracking-wider text-white">
                                    Lepas Kunci (Self-Drive)
                                </span>
                            </div>
                            <span
                                v-if="form.layanan === 'lepas_kunci'"
                                class="w-5 h-5 rounded-full bg-red-600 text-white flex items-center justify-center text-[10px]"
                            >
                                <Check class="w-3 h-3" />
                            </span>
                        </div>
                        <p class="text-xs text-neutral-400 leading-relaxed">
                            Kebebasan penuh mengendarai sendiri. Syarat: e-KTP, SIM A aktif, dan jaminan verifikasi instan.
                        </p>
                    </button>

                    <button
                        type="button"
                        @click="form.layanan = 'dengan_sopir'"
                        :class="[
                            'p-4 sm:p-5 rounded-2xl border text-left transition relative cursor-pointer',
                            form.layanan === 'dengan_sopir'
                                ? 'bg-gradient-to-br from-red-950/40 via-neutral-900 to-[#121318] border-red-600/80 ring-1 ring-red-500/40 shadow-xl'
                                : 'bg-neutral-900/60 border-neutral-800 hover:border-neutral-700 text-neutral-400',
                        ]"
                    >
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2.5">
                                <UserCheck class="w-5 h-5" :class="form.layanan === 'dengan_sopir' ? 'text-red-500' : 'text-neutral-500'" />
                                <span class="text-sm font-black uppercase tracking-wider text-white">
                                    Dengan Sopir (Chauffeur)
                                </span>
                            </div>
                            <span
                                v-if="form.layanan === 'dengan_sopir'"
                                class="w-5 h-5 rounded-full bg-red-600 text-white flex items-center justify-center text-[10px]"
                            >
                                <Check class="w-3 h-3" />
                            </span>
                        </div>
                        <p class="text-xs text-neutral-400 leading-relaxed">
                            Nikmati perjalanan tanpa lelah bersama pengemudi profesional bersertifikat, rapi, dan memahami rute terbaik.
                        </p>
                    </button>
                </div>
            </div>

            <!-- STEP 1: UNIT SELECTION, DATES & RENTER FORM -->
            <div v-if="currentStep === 1" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Left 7 cols: Car Fleet Catalog Picker -->
                <div class="lg:col-span-7 space-y-6">
                    <!-- Search & Filter bar -->
                    <div class="bg-[#0f1015] border border-neutral-800 rounded-3xl p-4 flex flex-col sm:flex-row items-center gap-3">
                        <div class="relative flex-1 w-full">
                            <Search class="w-4 h-4 text-neutral-500 absolute left-3.5 top-1/2 -translate-y-1/2" />
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Cari nama mobil, merk (Audi, BMW, Camry)..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white placeholder:text-neutral-500 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition"
                            />
                        </div>

                        <div class="flex items-center gap-1.5 overflow-x-auto w-full sm:w-auto py-1">
                            <button
                                v-for="b in brands"
                                :key="b"
                                @click="selectedBrand = b"
                                type="button"
                                :class="[
                                    'px-3 py-1.5 rounded-xl text-xs font-bold transition capitalize shrink-0 cursor-pointer',
                                    selectedBrand === b
                                        ? 'bg-red-600 text-white'
                                        : 'bg-neutral-800 text-neutral-400 hover:text-white',
                                ]"
                            >
                                {{ b === 'all' ? 'Semua' : b }}
                            </button>
                        </div>
                    </div>

                    <!-- Fleet List -->
                    <div v-if="filteredMobils.length === 0" class="bg-[#0f1015] border border-neutral-800 rounded-3xl p-12 text-center">
                        <Car class="w-10 h-10 text-neutral-600 mx-auto mb-2" />
                        <p class="text-sm font-bold text-neutral-300">Unit tidak ditemukan</p>
                        <p class="text-xs text-neutral-500 mt-1">Coba kata kunci pencarian lain.</p>
                    </div>

                    <div v-else class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div
                            v-for="car in filteredMobils"
                            :key="car.id"
                            @click="selectCar(car.id)"
                            :class="[
                                'group rounded-3xl p-4 border transition-all duration-200 cursor-pointer flex flex-col justify-between relative overflow-hidden',
                                Number(form.mobil_id) === car.id
                                    ? 'bg-gradient-to-b from-neutral-900 to-[#121318] border-red-600 ring-2 ring-red-600/40 shadow-xl'
                                    : 'bg-[#0f1015] border-neutral-800/80 hover:border-neutral-700 hover:bg-neutral-900/60',
                            ]"
                        >
                            <!-- Active Badge -->
                            <div
                                v-if="Number(form.mobil_id) === car.id"
                                class="absolute top-3 right-3 w-6 h-6 rounded-full bg-red-600 text-white flex items-center justify-center shadow-lg"
                            >
                                <Check class="w-3.5 h-3.5" />
                            </div>

                            <!-- Vehicle Image -->
                            <div class="h-40 rounded-2xl bg-neutral-900/90 overflow-hidden mb-3 border border-neutral-800/80 flex items-center justify-center relative">
                                <img
                                    v-if="car.gambar"
                                    :src="`/gambar_mobil/${car.gambar}`"
                                    :alt="car.nama_mobil"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                />
                                <Car v-else class="w-10 h-10 text-neutral-600" />
                                
                                <div class="absolute bottom-2 left-2 px-2.5 py-1 rounded-lg bg-black/70 backdrop-blur-xs text-[10px] font-bold text-neutral-300 uppercase tracking-wider">
                                    {{ car.tipe_kendaraan || 'Sedan Premium' }}
                                </div>
                            </div>

                            <!-- Details -->
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-wider text-red-500 block">
                                    {{ car.merk }}
                                </span>
                                <h4 class="text-sm font-extrabold text-white group-hover:text-red-400 transition truncate">
                                    {{ car.nama_mobil }}
                                </h4>

                                <div class="flex items-center gap-3 text-[11px] text-neutral-400 mt-2 pb-2 border-b border-neutral-800">
                                    <span>{{ car.transmisi || 'Otomatis' }}</span>
                                    <span>•</span>
                                    <span>{{ car.kapasitas_penumpang || 5 }} Kursi</span>
                                </div>

                                <div class="mt-3 flex items-baseline justify-between">
                                    <div>
                                        <span class="text-xs font-normal text-neutral-400">Tarif/hari:</span>
                                        <p class="text-sm font-black text-white">
                                            {{ formatRupiah(car.harga_per_hari) }}
                                        </p>
                                    </div>
                                    <span class="text-[11px] font-bold text-emerald-400">
                                        Unit Ready
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right 5 cols: Booking Config & Form -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-[#0f1015] border border-neutral-800 rounded-3xl p-6 sm:p-7 shadow-2xl space-y-6">
                        <div class="flex items-center justify-between border-b border-neutral-800 pb-4">
                            <h3 class="text-sm font-extrabold uppercase tracking-wider text-white flex items-center gap-2">
                                <Calendar class="w-4 h-4 text-red-500" />
                                Jadwal & Penjemputan
                            </h3>
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-red-950/80 text-red-400 border border-red-600/30">
                                Terkonfirmasi
                            </span>
                        </div>

                        <!-- Location Select & Quick pills -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-2">
                                Lokasi Penjemputan Kendaraan
                            </label>
                            <div class="relative">
                                <MapPin class="w-4 h-4 text-neutral-500 absolute left-3.5 top-1/2 -translate-y-1/2" />
                                <input
                                    v-model="form.lokasi_jemput"
                                    type="text"
                                    required
                                    placeholder="Contoh: Bandara Soetta T3, Hotel Mulia, dll"
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 focus:border-red-600 transition"
                                />
                            </div>
                            <div class="flex flex-wrap gap-1.5 mt-2">
                                <button
                                    v-for="loc in quickLocations"
                                    :key="loc"
                                    type="button"
                                    @click="form.lokasi_jemput = loc"
                                    class="px-2.5 py-1 rounded-lg bg-neutral-900 border border-neutral-800 text-[10px] font-bold text-neutral-400 hover:text-white hover:border-neutral-700 transition cursor-pointer"
                                >
                                    {{ loc }}
                                </button>
                            </div>
                        </div>

                        <!-- Dates & Time Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-300 mb-1">
                                    Mulai Sewa
                                </label>
                                <input
                                    v-model="form.tanggal_mulai"
                                    type="date"
                                    :min="today"
                                    required
                                    class="w-full px-3 py-2 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                                />
                                <input
                                    v-model="form.jam_mulai"
                                    type="time"
                                    class="w-full mt-1.5 px-3 py-1.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-neutral-300"
                                />
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-300 mb-1">
                                    Selesai Sewa
                                </label>
                                <input
                                    v-model="form.tanggal_selesai"
                                    type="date"
                                    :min="form.tanggal_mulai || today"
                                    required
                                    class="w-full px-3 py-2 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                                />
                                <input
                                    v-model="form.jam_selesai"
                                    type="time"
                                    class="w-full mt-1.5 px-3 py-1.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-neutral-300"
                                />
                            </div>
                        </div>

                        <!-- Contact & WhatsApp OTP Verification -->
                        <div class="bg-neutral-900/90 rounded-2xl p-4 border border-neutral-800 space-y-3">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300">
                                    Nomor WhatsApp Aktif
                                </label>
                                <span v-if="isPhoneVerified" class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-400 bg-emerald-950/80 px-2 py-0.5 rounded-full border border-emerald-500/40">
                                    <CheckCircle2 class="w-3 h-3" /> Terverifikasi
                                </span>
                            </div>

                            <div class="flex items-center gap-2">
                                <div class="relative flex-1">
                                    <Phone class="w-4 h-4 text-neutral-500 absolute left-3.5 top-1/2 -translate-y-1/2" />
                                    <input
                                        v-model="form.no_hp_pelanggan"
                                        type="tel"
                                        :disabled="isPhoneVerified"
                                        placeholder="081234567890"
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white placeholder:text-neutral-600 focus:ring-2 focus:ring-red-600 transition disabled:opacity-60"
                                    />
                                </div>
                                <button
                                    v-if="!isPhoneVerified"
                                    type="button"
                                    @click="requestOtp"
                                    :disabled="isSendingOtp || otpCountdown > 0"
                                    class="px-3.5 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-black text-xs uppercase tracking-wider transition shrink-0 cursor-pointer disabled:opacity-40"
                                >
                                    {{ isSendingOtp ? 'Mengirim...' : (otpCountdown > 0 ? `${otpCountdown}s` : (isOtpSent ? 'Kirim Ulang' : 'Kirim OTP')) }}
                                </button>
                            </div>

                            <!-- OTP Input field when sent -->
                            <div v-if="isOtpSent && !isPhoneVerified" class="p-3 rounded-xl bg-black/50 border border-neutral-800 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] text-neutral-400 font-bold uppercase">Masukkan 6 Digit Kode OTP</span>
                                    <span v-if="debugOtp" class="text-[10px] text-amber-400 font-mono">
                                        Simulasi OTP: <b>{{ debugOtp }}</b>
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input
                                        v-model="otpCode"
                                        type="text"
                                        maxlength="6"
                                        placeholder="123456"
                                        class="w-full text-center tracking-widest font-mono text-base font-black px-3 py-2 rounded-xl border border-neutral-700 bg-neutral-950 text-white focus:ring-2 focus:ring-red-600"
                                    />
                                    <button
                                        type="button"
                                        @click="verifyOtp"
                                        :disabled="isVerifyingOtp || otpCode.length !== 6"
                                        class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shrink-0 cursor-pointer disabled:opacity-40"
                                    >
                                        {{ isVerifyingOtp ? 'Memeriksa...' : 'Verifikasi' }}
                                    </button>
                                </div>
                                <p v-if="otpMsg" class="text-[10px] text-neutral-400">{{ otpMsg }}</p>
                            </div>
                        </div>

                        <!-- Lepas Kunci Document Upload (KTP & SIM A) -->
                        <div v-if="form.layanan === 'lepas_kunci'" class="bg-[#12131a] rounded-2xl p-4 border border-red-600/30 space-y-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <Shield class="w-4 h-4 text-red-500" />
                                    <h4 class="text-xs font-black text-white uppercase tracking-wider">
                                        Verifikasi Dokumen Identitas (Wajib Lepas Kunci)
                                    </h4>
                                </div>
                                <p class="text-[10px] text-neutral-400 mt-1 leading-relaxed">
                                    Demi keamanan unit armada, unggah berkas KTP & SIM A asli yang masih berlaku.
                                </p>
                            </div>

                            <!-- Upload e-KTP -->
                            <div class="space-y-1.5">
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-300">
                                    1. Foto e-KTP Pemesan
                                </label>
                                <div class="relative">
                                    <input
                                        type="file"
                                        accept="image/jpeg,image/png,image/webp,application/pdf"
                                        @change="handleKtpChange"
                                        class="w-full text-xs text-neutral-400 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-[11px] file:font-black file:uppercase file:tracking-wider file:bg-neutral-800 file:text-white hover:file:bg-neutral-700 cursor-pointer bg-[#070709] border border-neutral-800 rounded-xl p-1.5"
                                    />
                                </div>
                                <span v-if="ktpFileName" class="text-[10px] text-emerald-400 font-bold block">
                                    &bull; File terpilih: {{ ktpFileName }}
                                </span>
                            </div>

                            <!-- Upload SIM A -->
                            <div class="space-y-1.5">
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-300">
                                    2. Foto SIM A Pengemudi
                                </label>
                                <div class="relative">
                                    <input
                                        type="file"
                                        accept="image/jpeg,image/png,image/webp,application/pdf"
                                        @change="handleSimChange"
                                        class="w-full text-xs text-neutral-400 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-[11px] file:font-black file:uppercase file:tracking-wider file:bg-neutral-800 file:text-white hover:file:bg-neutral-700 cursor-pointer bg-[#070709] border border-neutral-800 rounded-xl p-1.5"
                                    />
                                </div>
                                <span v-if="simFileName" class="text-[10px] text-emerald-400 font-bold block">
                                    &bull; File terpilih: {{ simFileName }}
                                </span>
                            </div>

                            <!-- UU PDP Compliance Notice Box -->
                            <div class="p-3 rounded-xl bg-black/40 border border-neutral-800 text-[10px] text-neutral-400 leading-relaxed space-y-1">
                                <p class="text-white font-bold flex items-center gap-1.5">
                                    <Lock class="w-3.5 h-3.5 text-red-500" />
                                    Jaminan Keamanan UU PDP No. 27 Tahun 2022
                                </p>
                                <p>
                                    Berkas KTP & SIM Anda dienkripsi pada direktori penyimpanan terisolasi (*private server storage*). Berkas ini <b>otomatis terhapus permanen 30 hari setelah unit mobil Anda kembalikan</b>, kecuali bila terjadi sengketa hukum atau tindak kriminal.
                                </p>
                            </div>
                        </div>

                        <!-- Driver Notes if With Driver -->
                        <div v-if="form.layanan === 'dengan_sopir'">
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-300 mb-1">
                                Rute & Catatan Standby Sopir (Opsional)
                            </label>
                            <textarea
                                v-model="form.catatan_sopir"
                                rows="2"
                                placeholder="Contoh: Perjalanan dinas ke kawasan industri Karawang, standby dari jam 08:00 WIB"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white placeholder:text-neutral-600 focus:ring-2 focus:ring-red-600 transition"
                            />
                        </div>

                        <!-- Addon: Total Damage Protection -->
                        <div class="bg-neutral-900/90 rounded-2xl p-4 border border-neutral-800 space-y-2">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input
                                    v-model="form.asuransi_tambahan"
                                    type="checkbox"
                                    class="mt-1 w-4 h-4 rounded text-red-600 bg-neutral-800 border-neutral-700 focus:ring-red-500"
                                />
                                <div>
                                    <div class="flex items-center gap-2">
                                        <Shield class="w-4 h-4 text-emerald-400" />
                                        <span class="text-xs font-bold text-white">
                                            Proteksi Total Kerusakan (Add-on)
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-neutral-400 mt-0.5">
                                        Bebas resiko lecet, benturan, & ganti rugi pihak ketiga hanya +Rp 50.000/hari.
                                    </p>
                                </div>
                            </label>
                        </div>

                        <!-- PDP Privacy Consent -->
                        <div class="bg-neutral-900/50 rounded-2xl p-3 border border-neutral-800/80">
                            <label class="flex items-start gap-2.5 cursor-pointer">
                                <input
                                    v-model="form.pdp_consent"
                                    type="checkbox"
                                    required
                                    class="mt-0.5 w-3.5 h-3.5 rounded text-red-600 bg-neutral-800 border-neutral-700 focus:ring-red-500"
                                />
                                <span class="text-[11px] text-neutral-400 leading-relaxed">
                                    Saya menyetujui pemrosesan data identitas (KTP, SIM A, No. WhatsApp) secara terenkripsi untuk verifikasi reservasi sesuai ketentuan <strong class="text-neutral-200">UU PDP No. 27 Tahun 2022</strong>.
                                </span>
                            </label>
                        </div>

                        <!-- Next to Step 2 Button -->
                        <button
                            type="button"
                            @click="goToStep2"
                            :disabled="!selectedCar"
                            class="w-full inline-flex items-center justify-center gap-2 px-5 py-4 rounded-2xl bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 active:scale-98 text-white text-xs font-black uppercase tracking-wider shadow-xl shadow-red-600/30 transition duration-200 disabled:opacity-40 cursor-pointer"
                        >
                            <span>Lanjut Ke Rincian & Pembayaran</span>
                            <ArrowRight class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- STEP 2: COST BREAKDOWN & PAYMENT METHOD -->
            <div v-else-if="currentStep === 2" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Left 7 cols: Payment Method Selection -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="bg-[#0f1015] border border-neutral-800 rounded-3xl p-6 sm:p-8 space-y-6">
                        <div class="flex items-center justify-between border-b border-neutral-800 pb-4">
                            <h3 class="text-sm font-black uppercase tracking-wider text-white flex items-center gap-2">
                                <CreditCard class="w-4 h-4 text-red-500" />
                                Pilih Metode Pembayaran Online
                            </h3>
                            <span class="text-[10px] font-bold text-emerald-400 bg-emerald-950/60 border border-emerald-500/30 px-2.5 py-1 rounded-full">
                                Instant Verification
                            </span>
                        </div>

                        <div class="space-y-3">
                            <div
                                v-for="pm in paymentMethods"
                                :key="pm.id"
                                @click="form.metode_pembayaran = pm.id"
                                :class="[
                                    'p-4 rounded-2xl border transition cursor-pointer flex items-center justify-between gap-4',
                                    form.metode_pembayaran === pm.id
                                        ? 'bg-neutral-900 border-red-600 ring-1 ring-red-600/40 shadow-lg'
                                        : 'bg-[#070709] border-neutral-800 hover:border-neutral-700',
                                ]"
                            >
                                <div class="flex items-center gap-3.5">
                                    <div class="w-12 h-12 rounded-xl bg-neutral-800 border border-neutral-700/80 flex items-center justify-center font-black text-xs text-white tracking-wider">
                                        {{ pm.logo }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-xs font-black uppercase text-white">
                                                {{ pm.name }}
                                            </h4>
                                            <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-neutral-800 text-red-400 border border-neutral-700">
                                                {{ pm.badge }}
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-neutral-400 mt-0.5">
                                            {{ pm.desc }}
                                        </p>
                                    </div>
                                </div>

                                <div
                                    :class="[
                                        'w-5 h-5 rounded-full border flex items-center justify-center transition shrink-0',
                                        form.metode_pembayaran === pm.id
                                            ? 'border-red-600 bg-red-600 text-white'
                                            : 'border-neutral-600',
                                    ]"
                                >
                                    <Check v-if="form.metode_pembayaran === pm.id" class="w-3 h-3" />
                                </div>
                            </div>
                        </div>

                        <!-- Security guarantee pill -->
                        <div class="bg-neutral-900/60 rounded-2xl p-4 border border-neutral-800 flex items-center gap-3 text-xs text-neutral-400">
                            <Lock class="w-4 h-4 text-emerald-400 shrink-0" />
                            <span>
                                Transaksi aman dengan enkripsi bank-grade 256-bit SSL. E-Voucher resmi langsung diterbitkan begitu konfirmasi terkirim.
                            </span>
                        </div>

                        <!-- Back to step 1 button -->
                        <button
                            type="button"
                            @click="currentStep = 1"
                            class="inline-flex items-center gap-2 text-xs font-bold text-neutral-400 hover:text-white transition cursor-pointer"
                        >
                            <ArrowLeft class="w-4 h-4" />
                            Kembali & Ubah Unit / Jadwal
                        </button>
                    </div>
                </div>

                <!-- Right 5 cols: Transparent Live Breakdown & Submit -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-[#0f1015] border border-neutral-800 rounded-3xl p-6 sm:p-7 shadow-2xl space-y-5">
                        <h3 class="text-sm font-black uppercase tracking-wider text-white border-b border-neutral-800 pb-4">
                            Rincian Biaya Transparan
                        </h3>

                        <!-- Unit snippet -->
                        <div class="flex items-center gap-3.5 bg-[#070709] p-3 rounded-2xl border border-neutral-800">
                            <div class="w-14 h-12 rounded-xl bg-neutral-850 overflow-hidden shrink-0 border border-neutral-750 flex items-center justify-center">
                                <img
                                    v-if="selectedCar?.gambar"
                                    :src="`/gambar_mobil/${selectedCar.gambar}`"
                                    class="w-full h-full object-cover"
                                />
                                <Car v-else class="w-5 h-5 text-neutral-500" />
                            </div>
                            <div class="min-w-0">
                                <span class="text-[10px] font-black uppercase text-red-500 block">
                                    {{ selectedCar?.merk }}
                                </span>
                                <h4 class="text-xs font-black text-white truncate">
                                    {{ selectedCar?.nama_mobil }}
                                </h4>
                                <span class="text-[10px] text-neutral-400">
                                    {{ form.layanan === 'lepas_kunci' ? 'Lepas Kunci' : 'Dengan Sopir' }} • {{ durationDays }} Hari
                                </span>
                            </div>
                        </div>

                        <!-- Line Items -->
                        <div class="space-y-2.5 text-xs">
                            <div class="flex justify-between text-neutral-300">
                                <span>Tarif Sewa ({{ durationDays }} Hari x {{ formatRupiah(selectedCar?.harga_per_hari) }})</span>
                                <span class="font-bold text-white">{{ formatRupiah(baseCarTotal) }}</span>
                            </div>

                            <div v-if="form.layanan === 'dengan_sopir'" class="flex justify-between text-neutral-300">
                                <span>Layanan Sopir Profesional</span>
                                <span class="font-bold text-white">{{ formatRupiah(driverCost) }}</span>
                            </div>

                            <div v-if="form.asuransi_tambahan" class="flex justify-between text-neutral-300">
                                <span>Proteksi Kerusakan Total</span>
                                <span class="font-bold text-white">{{ formatRupiah(insuranceCost) }}</span>
                            </div>

                            <div class="flex justify-between text-neutral-400">
                                <span>Biaya Layanan & Pajak</span>
                                <span class="font-bold text-emerald-400">GRATIS</span>
                            </div>

                            <div class="pt-4 border-t border-neutral-800 flex items-baseline justify-between">
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-wider text-neutral-400 block">
                                        Total Pembayaran
                                    </span>
                                    <span class="text-[10px] text-neutral-500">
                                        Tanpa biaya tersembunyi
                                    </span>
                                </div>
                                <span class="text-xl font-black text-red-500">
                                    {{ formatRupiah(grandTotal) }}
                                </span>
                            </div>
                        </div>

                        <!-- Submit Final Button -->
                        <button
                            type="button"
                            @click="submitBooking"
                            :disabled="form.processing"
                            class="w-full inline-flex items-center justify-center gap-2 px-5 py-4 rounded-2xl bg-gradient-to-r from-red-600 via-rose-600 to-red-600 hover:opacity-90 active:scale-98 text-white text-xs font-black uppercase tracking-wider shadow-2xl shadow-red-600/30 transition duration-200 cursor-pointer disabled:opacity-50"
                        >
                            <Sparkles class="w-4 h-4" />
                            <span>{{ form.processing ? 'Memproses Reservasi...' : 'Bayar Sekarang & Terbitkan E-Voucher' }}</span>
                        </button>

                        <p class="text-[11px] text-neutral-500 text-center leading-relaxed">
                            Setelah pembayaran dikonfirmasi, nomor booking dan QR Code E-Voucher resmi dapat diunduh atau dicetak kapan saja.
                        </p>
                    </div>
                </div>
            </div>

            <!-- STEP 3: PROCESSING STATE -->
            <div v-else class="bg-[#0f1015] border border-neutral-800 rounded-3xl p-12 text-center max-w-md mx-auto my-12 space-y-4">
                <div class="w-16 h-16 rounded-full bg-red-600/20 text-red-500 flex items-center justify-center mx-auto animate-bounce">
                    <Sparkles class="w-8 h-8" />
                </div>
                <h3 class="text-lg font-black uppercase tracking-wider text-white">
                    Memproses Reservasi Mandiri
                </h3>
                <p class="text-xs text-neutral-400 leading-relaxed">
                    Sistem sedang memvalidasi pembayaran dan mengalokasikan unit kendaraan Anda. Anda akan segera diarahkan ke E-Voucher digital...
                </p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
