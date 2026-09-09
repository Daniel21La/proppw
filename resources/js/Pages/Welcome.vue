<script setup>
import { ref, computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import {
    Car,
    Shield,
    Clock,
    CreditCard,
    Sparkles,
    Search,
    MapPin,
    Calendar,
    ChevronRight,
    ArrowRight,
    CheckCircle2,
    MessageSquare,
    Globe,
    Phone,
    SlidersHorizontal,
    Star,
    Check,
    X,
} from 'lucide-vue-next';
import { useT } from '@/locales/translations';
import Modal from '@/Components/Modal.vue';

const { t, currentLang, setLanguage } = useT();

const props = defineProps({
    mobils: {
        type: Array,
        default: () => [],
    },
    canLogin: Boolean,
    canRegister: Boolean,
});

const page = usePage();
const user = computed(() => page.props.auth?.user);

// Search Widget state
const serviceType = ref('lepas_kunci'); // 'lepas_kunci' | 'dengan_sopir'
const pickupLocation = ref('Jakarta');
const startDate = ref(new Date().toISOString().split('T')[0]);
const endDate = ref(new Date(Date.now() + 86400000).toISOString().split('T')[0]);
const locationError = ref('');

const supportedAreas = ['Jakarta', 'Bogor', 'Depok', 'Tangerang', 'Bekasi', 'Bandung', 'Yogyakarta', 'Surabaya', 'Bali'];

function validateLocation() {
    const loc = pickupLocation.value.trim().toLowerCase();
    const match = supportedAreas.some((a) => loc.includes(a.toLowerCase()));
    if (!match && loc.length > 0) {
        locationError.value = 'Area belum terjangkau. Area layanan kami: ' + supportedAreas.join(', ');
    } else {
        locationError.value = '';
    }
}

// Category filter in Today Specials
const activeSpecialCategory = ref('luxury');
const selectedCarForModal = ref(null);
const carModalOpen = ref(false);

function openCarModal(car) {
    selectedCarForModal.value = car;
    carModalOpen.value = true;
}

function formatRupiah(num) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(num || 0);
}

// Fase 1: Instant 4-Column Consultation Form State & Micro-interactions
const consultForm = ref({
    nama: '',
    whatsapp: '',
    kendaraan: 'Alphard Transformer VIP',
    tanggal: new Date().toISOString().split('T')[0],
});
const isSubmittingConsult = ref(false);
const consultSubmitted = ref(false);
const consultSuccessMessage = ref('');

function submitConsultation() {
    if (!consultForm.value.nama || !consultForm.value.whatsapp) {
        alert('Mohon lengkapi Nama dan Nomor WhatsApp Anda.');
        return;
    }
    isSubmittingConsult.value = true;
    setTimeout(() => {
        isSubmittingConsult.value = false;
        consultSubmitted.value = true;
        consultSuccessMessage.value = `Terima kasih Bpk/Ibu ${consultForm.value.nama}. Permintaan konsultasi armada ${consultForm.value.kendaraan} untuk tanggal ${consultForm.value.tanggal} telah diterima. Tim WhatsApp Concierge kami akan menghubungi Anda dalam 5 menit.`;
    }, 600);
}
</script>

<template>
    <Head>
        <title>QUANTUM STREAMLINE - Rental Mobil Mewah Lepas Kunci & Sopir</title>
        <meta
            name="description"
            content="Platform rental mobil mewah terpercaya di Indonesia. Tersedia sewa Lepas Kunci & Dengan Sopir armada Alphard, Camry, Fortuner, dan sportscar eksklusif. Bersih, wangi, asuransi penuh."
        />
        <meta
            name="keywords"
            content="rental mobil mewah, sewa mobil lepas kunci, rental alphard jakarta, rental camaro, sewa mobil dengan sopir, quantum streamline"
        />
        <link rel="canonical" href="/" />

        <!-- OpenGraph (Facebook, WhatsApp) -->
        <meta property="og:type" content="website" />
        <meta property="og:title" content="QUANTUM STREAMLINE - Rental Mobil Mewah Lepas Kunci & Sopir" />
        <meta
            property="og:description"
            content="Platform sewa mobil mewah mandiri. Pilih unit armada, tentukan lepas kunci atau dengan sopir, dan nikmati perjalanan eksklusif."
        />
        <meta property="og:image" content="/images/audi_front_dark.webp" />
        <meta property="og:url" content="/" />

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="QUANTUM STREAMLINE - Rental Mobil Mewah" />
        <meta
            name="twitter:description"
            content="Platform sewa mobil mewah mandiri lepas kunci atau dengan sopir armada Alphard, Camry, dan supercar."
        />
        <meta name="twitter:image" content="/images/audi_front_dark.webp" />

        <!-- AutoRental Schema.org Structured Data -->
        <component :is="'script'" type="application/ld+json">
            {{ JSON.stringify({
                "@context": "https://schema.org",
                "@type": "AutoRental",
                "name": "QUANTUM STREAMLINE",
                "image": "/images/audi_front_dark.webp",
                "description": "Layanan rental mobil mewah premium mandiri dengan opsi lepas kunci dan supir profesional.",
                "priceRange": "IDR 350.000 - IDR 5.000.000",
                "telephone": "+6281234567890",
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "Jl. Jend. Sudirman No. 88",
                    "addressLocality": "Jakarta Selatan",
                    "addressRegion": "DKI Jakarta",
                    "postalCode": "12190",
                    "addressCountry": "ID"
                },
                "geo": {
                    "@type": "GeoCoordinates",
                    "latitude": -6.2255,
                    "longitude": 106.8091
                },
                "openingHoursSpecification": {
                    "@type": "OpeningHoursSpecification",
                    "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
                    "opens": "00:00",
                    "closes": "23:59"
                }
            }) }}
        </component>
    </Head>

    <div class="min-h-screen bg-[#070709] text-white font-sans selection:bg-red-600 selection:text-white flex flex-col relative overflow-x-hidden">
        <!-- Floating WhatsApp Assistance Button -->
        <a
            href="https://wa.me/6281234567890?text=Halo%20RentalMobil,%20saya%20tertarik%20untuk%20sewa%20mobil"
            target="_blank"
            class="fixed bottom-6 right-6 z-50 inline-flex items-center gap-2.5 px-4 py-3 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-2xl shadow-emerald-600/50 transition-all transform hover:scale-105"
            title="Chat via WhatsApp"
        >
            <MessageSquare class="w-4 h-4 fill-white" />
            <span class="hidden sm:inline">{{ t('checkout.needHelp') }}</span>
        </a>

        <!-- Top Navigation Bar -->
        <header class="sticky top-0 z-40 bg-[#070709]/90 backdrop-blur-md border-b border-neutral-800/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
                <!-- Left: Logo & Streamline Accent -->
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-8 bg-red-600 rounded-full hidden sm:block shadow-sm shadow-red-600" />
                    <Link href="/" class="flex items-center gap-2.5 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-neutral-800 to-neutral-900 border border-neutral-700/60 flex items-center justify-center text-red-500 group-hover:scale-105 transition shadow-lg">
                            <Car class="w-5 h-5" />
                        </div>
                        <div>
                            <span class="text-lg font-black tracking-wider text-white uppercase block">
                                QUANTUM <span class="text-red-600">STREAMLINE</span>
                            </span>
                            <span class="text-[9px] font-bold tracking-widest uppercase text-neutral-400 block -mt-1">
                                Luxury Car Rental
                            </span>
                        </div>
                    </Link>
                </div>

                <!-- Center Nav Links -->
                <nav class="hidden lg:flex items-center gap-8 text-xs font-bold tracking-wider uppercase text-neutral-300">
                    <a href="#" class="text-red-500 transition">{{ t('nav.home') }}</a>
                    <a href="#about" class="hover:text-red-400 transition">{{ t('nav.about') }}</a>
                    <a href="#specials" class="hover:text-red-400 transition">{{ t('nav.vehicles') }}</a>
                    <a href="#experience" class="hover:text-red-400 transition">{{ t('nav.services') }}</a>
                    <a href="#gallery" class="hover:text-red-400 transition">{{ t('nav.gallery') }}</a>
                </nav>

                <!-- Right: Language Toggle & User Actions -->
                <div class="flex items-center gap-3">
                    <!-- Bilingual ID / EN Switcher -->
                    <div class="inline-flex items-center p-1 rounded-xl bg-neutral-900 border border-neutral-800 text-xs font-bold">
                        <button
                            type="button"
                            @click="setLanguage('id')"
                            :class="[
                                'px-2.5 py-1 rounded-lg transition',
                                currentLang === 'id' ? 'bg-red-600 text-white shadow-xs' : 'text-neutral-400 hover:text-white',
                            ]"
                        >
                            ID
                        </button>
                        <button
                            type="button"
                            @click="setLanguage('en')"
                            :class="[
                                'px-2.5 py-1 rounded-lg transition',
                                currentLang === 'en' ? 'bg-red-600 text-white shadow-xs' : 'text-neutral-400 hover:text-white',
                            ]"
                        >
                            EN
                        </button>
                    </div>

                    <!-- Auth actions -->
                    <template v-if="user">
                        <Link
                            :href="user.role === 'admin' ? '/admin/transaksi' : '/transaksi/create'"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow-lg shadow-red-600/30 transition cursor-pointer"
                        >
                            {{ t('nav.dashboard') }}
                        </Link>
                    </template>
                    <template v-else>
                        <Link
                            href="/login"
                            class="hidden sm:inline-flex px-3 py-2 text-xs font-bold text-neutral-300 hover:text-white transition"
                        >
                            {{ t('nav.login') }}
                        </Link>
                        <Link
                            href="/register"
                            class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow-lg shadow-red-600/25 transition active:scale-95"
                        >
                            {{ t('nav.register') }}
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <!-- 1. HERO SECTION ("LUXURY LIFESTYLE RENTALS") -->
        <section class="relative bg-gradient-to-b from-[#070709] via-[#09090D] to-[#0D0D12] pt-12 pb-24 px-4 sm:px-6 lg:px-8 overflow-hidden">
            <!-- Red vertical accent block on left edge -->
            <div class="absolute left-0 top-16 w-3 h-32 bg-red-600 rounded-r-full shadow-lg shadow-red-600/50" />

            <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <!-- Left: Headline & Dual CTA Buttons (6 cols) -->
                <div class="lg:col-span-6 z-10 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-red-600/10 border border-red-500/30 text-red-500 text-[11px] font-extrabold uppercase tracking-widest">
                        <Sparkles class="w-3.5 h-3.5" />
                        {{ t('hero.tag') }}
                    </div>

                    <!-- Bold Stacked Headline -->
                    <h1 class="text-5xl sm:text-7xl font-black uppercase tracking-tight text-white leading-none">
                        {{ t('hero.title1') }}<br />
                        {{ t('hero.title2') }}<br />
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 via-rose-500 to-red-600">
                            {{ t('hero.title3') }}
                        </span>
                    </h1>

                    <p class="text-sm text-neutral-400 max-w-lg leading-relaxed font-normal">
                        {{ t('hero.subtitle') }}
                    </p>

                    <!-- Dual CTA Buttons -->
                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <a
                            href="#search-engine"
                            class="px-7 py-3.5 rounded-xl bg-red-600 hover:bg-red-700 active:scale-95 text-white text-xs font-black uppercase tracking-wider shadow-xl shadow-red-600/30 transition duration-200"
                        >
                            {{ t('hero.ctaPrimary') }}
                        </a>
                        <a
                            href="#specials"
                            class="px-7 py-3.5 rounded-xl bg-transparent hover:bg-neutral-800/80 border border-neutral-700 text-white text-xs font-black uppercase tracking-wider transition duration-200"
                        >
                            {{ t('hero.ctaSecondary') }}
                        </a>
                    </div>
                </div>

                <!-- Right: Audi Front with glowing LED headlights (6 cols) - Core LCP Candidate -->
                <div class="lg:col-span-6 relative flex items-center justify-center">
                    <div class="relative w-full rounded-3xl overflow-hidden shadow-2xl border border-neutral-800/60 bg-black aspect-16/10">
                        <picture>
                            <source srcset="/images/audi_front_dark.webp" type="image/webp" />
                            <img
                                src="/images/audi_front_dark.jpg"
                                alt="Audi Luxury Sedan Quantum Streamline"
                                class="w-full h-full object-cover transform hover:scale-102 transition duration-700"
                                fetchpriority="high"
                                width="600"
                                height="380"
                            />
                        </picture>
                        <!-- Vignette Gradient -->
                        <div class="absolute inset-0 bg-gradient-to-t from-[#070709] via-transparent to-transparent opacity-70 pointer-events-none" />
                    </div>
                </div>
            </div>

            <!-- Interactive Search Engine Box (Lepas Kunci vs Dengan Sopir) -->
            <div id="search-engine" class="max-w-6xl mx-auto mt-16 bg-neutral-900/90 border border-neutral-800 rounded-3xl p-6 sm:p-8 shadow-2xl backdrop-blur-xl">
                <!-- Service Type Toggle Tabs -->
                <div class="flex items-center gap-2 mb-6 border-b border-neutral-800 pb-4">
                    <button
                        type="button"
                        @click="serviceType = 'lepas_kunci'"
                        :class="[
                            'px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition flex items-center gap-2 cursor-pointer',
                            serviceType === 'lepas_kunci'
                                ? 'bg-red-600 text-white shadow-md shadow-red-600/30'
                                : 'bg-neutral-800 text-neutral-400 hover:text-white',
                        ]"
                    >
                        <Car class="w-4 h-4" />
                        {{ t('hero.selfDrive') }}
                    </button>

                    <button
                        type="button"
                        @click="serviceType = 'dengan_sopir'"
                        :class="[
                            'px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition flex items-center gap-2 cursor-pointer',
                            serviceType === 'dengan_sopir'
                                ? 'bg-red-600 text-white shadow-md shadow-red-600/30'
                                : 'bg-neutral-800 text-neutral-400 hover:text-white',
                        ]"
                    >
                        <Shield class="w-4 h-4" />
                        {{ t('hero.withDriver') }}
                    </button>
                </div>

                <!-- Input Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Pickup Location -->
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-400 mb-1.5">
                            {{ t('hero.pickupLocation') }}
                        </label>
                        <div class="relative">
                            <MapPin class="w-4 h-4 text-red-500 absolute left-3.5 top-1/2 -translate-y-1/2" />
                            <input
                                v-model="pickupLocation"
                                @input="validateLocation"
                                type="text"
                                :placeholder="t('hero.pickupPlaceholder')"
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-neutral-700 bg-neutral-950 text-xs font-medium text-white focus:ring-2 focus:ring-red-600 focus:border-red-600 transition"
                            />
                        </div>
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-400 mb-1.5">
                            {{ t('hero.startDate') }}
                        </label>
                        <div class="relative">
                            <Calendar class="w-4 h-4 text-red-500 absolute left-3.5 top-1/2 -translate-y-1/2" />
                            <input
                                v-model="startDate"
                                type="date"
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-neutral-700 bg-neutral-950 text-xs font-medium text-white focus:ring-2 focus:ring-red-600 focus:border-red-600 transition"
                            />
                        </div>
                    </div>

                    <!-- End Date -->
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-400 mb-1.5">
                            {{ t('hero.endDate') }}
                        </label>
                        <div class="relative">
                            <Calendar class="w-4 h-4 text-red-500 absolute left-3.5 top-1/2 -translate-y-1/2" />
                            <input
                                v-model="endDate"
                                type="date"
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-neutral-700 bg-neutral-950 text-xs font-medium text-white focus:ring-2 focus:ring-red-600 focus:border-red-600 transition"
                            />
                        </div>
                    </div>

                    <!-- Search Action -->
                    <div class="flex items-end">
                        <Link
                            :href="`/transaksi/create?layanan=${serviceType}&lokasi=${encodeURIComponent(pickupLocation)}`"
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-black uppercase tracking-wider shadow-lg shadow-red-600/30 transition duration-150 h-[42px]"
                        >
                            <Search class="w-4 h-4" />
                            {{ t('hero.searchNow') }}
                        </Link>
                    </div>
                </div>

                <!-- Service Coverage notice -->
                <p v-if="locationError" class="text-xs text-rose-400 mt-3 font-semibold">
                    {{ locationError }}
                </p>
                <p v-else class="text-[11px] text-neutral-500 mt-3">
                    {{ t('hero.areaNotice') }}
                </p>
            </div>
        </section>

        <!-- 2. "ABOUT US" SECTION ("Dedicated To Providing Unparalleled Customer Service") -->
        <section id="about" class="py-24 bg-[#070709] border-t border-neutral-800/80 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Left: Red Sports Convertible on Checkered Floor (6 cols) -->
                <div class="lg:col-span-6 relative">
                    <div class="rounded-3xl overflow-hidden border border-neutral-800 shadow-2xl bg-neutral-900 aspect-16/10">
                        <picture>
                            <source srcset="/images/red_sports_car.webp" type="image/webp" />
                            <img
                                src="/images/red_sports_car.jpg"
                                alt="Red Sports Convertible"
                                class="w-full h-full object-cover transform hover:scale-102 transition duration-700"
                                loading="lazy"
                                decoding="async"
                                width="600"
                                height="380"
                            />
                        </picture>
                    </div>
                    <!-- Red baseline accent line -->
                    <div class="h-1.5 w-36 bg-red-600 rounded-full mt-4 shadow-sm shadow-red-600" />
                </div>

                <!-- Right: Content & Bullet Features (6 cols) -->
                <div class="lg:col-span-6 space-y-6">
                    <span class="text-xs font-extrabold uppercase tracking-widest text-red-500 block">
                        {{ t('about.tag') }}
                    </span>

                    <h2 class="text-3xl sm:text-4xl font-black uppercase tracking-tight text-white leading-tight">
                        {{ t('about.title') }}
                    </h2>

                    <p class="text-sm text-neutral-400 leading-relaxed">
                        {{ t('about.desc') }}
                    </p>

                    <!-- Feature Checks -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 rounded-full bg-red-600/20 text-red-500 flex items-center justify-center shrink-0">
                                <Check class="w-3.5 h-3.5 stroke-[3]" />
                            </div>
                            <span class="text-xs font-bold text-neutral-200">{{ t('about.f1') }}</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 rounded-full bg-red-600/20 text-red-500 flex items-center justify-center shrink-0">
                                <Check class="w-3.5 h-3.5 stroke-[3]" />
                            </div>
                            <span class="text-xs font-bold text-neutral-200">{{ t('about.f2') }}</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 rounded-full bg-red-600/20 text-red-500 flex items-center justify-center shrink-0">
                                <Check class="w-3.5 h-3.5 stroke-[3]" />
                            </div>
                            <span class="text-xs font-bold text-neutral-200">{{ t('about.f3') }}</span>
                        </div>
                    </div>

                    <div class="pt-4">
                        <a
                            href="#specials"
                            class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-black uppercase tracking-wider shadow-lg shadow-red-600/25 transition cursor-pointer"
                        >
                            {{ t('about.btn') }}
                            <ArrowRight class="w-4 h-4" />
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. "TODAY SPECIALS" SECTION & FEATURED SHOWCASE -->
        <section id="specials" class="py-24 bg-[#0A0A0E] border-t border-neutral-800/80 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-center max-w-2xl mx-auto mb-10">
                    <h2 class="text-3xl sm:text-4xl font-black uppercase tracking-tight text-white">
                        {{ t('specials.title') }}
                    </h2>
                    <p class="text-xs sm:text-sm text-neutral-400 mt-2">
                        {{ t('specials.subtitle') }}
                    </p>

                    <!-- Category Pills -->
                    <div class="flex flex-wrap items-center justify-center gap-2.5 mt-6">
                        <button
                            type="button"
                            @click="activeSpecialCategory = 'all'"
                            :class="[
                                'px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition cursor-pointer',
                                activeSpecialCategory === 'all'
                                    ? 'bg-red-600 text-white shadow-md shadow-red-600/25'
                                    : 'bg-neutral-900 text-neutral-400 hover:text-white border border-neutral-800',
                            ]"
                        >
                            {{ t('specials.all') }}
                        </button>
                        <button
                            type="button"
                            @click="activeSpecialCategory = 'luxury'"
                            :class="[
                                'px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition cursor-pointer',
                                activeSpecialCategory === 'luxury'
                                    ? 'bg-red-600 text-white shadow-md shadow-red-600/25'
                                    : 'bg-neutral-900 text-neutral-400 hover:text-white border border-neutral-800',
                            ]"
                        >
                            {{ t('specials.sports') }}
                        </button>
                        <button
                            type="button"
                            @click="activeSpecialCategory = 'sedan'"
                            :class="[
                                'px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition cursor-pointer',
                                activeSpecialCategory === 'sedan'
                                    ? 'bg-red-600 text-white shadow-md shadow-red-600/25'
                                    : 'bg-neutral-900 text-neutral-400 hover:text-white border border-neutral-800',
                            ]"
                        >
                            {{ t('specials.sedan') }}
                        </button>
                        <button
                            type="button"
                            @click="activeSpecialCategory = 'mpv'"
                            :class="[
                                'px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition cursor-pointer',
                                activeSpecialCategory === 'mpv'
                                    ? 'bg-red-600 text-white shadow-md shadow-red-600/25'
                                    : 'bg-neutral-900 text-neutral-400 hover:text-white border border-neutral-800',
                            ]"
                        >
                            {{ t('specials.mpv') }}
                        </button>
                    </div>
                </div>

                <!-- Featured Large Showcase (Executive Yellow Sports / Muscle Car) -->
                <div class="bg-neutral-900/80 rounded-3xl border border-neutral-800 p-8 lg:p-10 mb-16 grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                    <div class="lg:col-span-5 space-y-5">
                        <span class="text-xs font-extrabold text-red-500 uppercase tracking-widest">
                            FEATURED HIGHLIGHT
                        </span>
                        <h3 class="text-3xl font-black uppercase text-white tracking-tight">
                            EXECUTIVE MUSCLE COUPE
                        </h3>

                        <div class="space-y-2.5 text-xs text-neutral-300">
                            <div class="flex items-center gap-2.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-600" />
                                <span>Kapasitas hingga 4 Penumpang</span>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-600" />
                                <span>Kursi kulit mewah Napa Leather</span>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-600" />
                                <span>Premium Sound System & Apple CarPlay</span>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-600" />
                                <span>Bagasi luas untuk 3 koper ukuran sedang</span>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-600" />
                                <span>Kaca film UV 99% & Dual Climate AC</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 pt-4">
                            <Link
                                :href="user ? '/transaksi/create' : '/login'"
                                class="px-6 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-black uppercase tracking-wider shadow-lg shadow-red-600/25 transition cursor-pointer"
                            >
                                {{ t('specials.reserveNow') }}
                            </Link>
                            <button
                                type="button"
                                @click="openCarModal(mobils[0])"
                                class="px-6 py-3 rounded-xl bg-neutral-800 hover:bg-neutral-700 border border-neutral-700 text-white text-xs font-black uppercase tracking-wider transition cursor-pointer"
                            >
                                {{ t('specials.moreDetail') }}
                            </button>
                        </div>
                    </div>

                    <div class="lg:col-span-7">
                        <div class="rounded-3xl overflow-hidden border border-neutral-800 shadow-2xl aspect-16/10">
                            <picture>
                                <source srcset="/images/yellow_muscle_car.webp" type="image/webp" />
                                <img
                                    src="/images/yellow_muscle_car.jpg"
                                    alt="Yellow Muscle Car"
                                    class="w-full h-full object-cover transform hover:scale-102 transition duration-700"
                                    loading="lazy"
                                    decoding="async"
                                    width="700"
                                    height="440"
                                />
                            </picture>
                        </div>
                    </div>
                </div>

                <!-- Car Cards Row (Reflecting the Behance card carousel with highlighted Red Card) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div
                        v-for="(mobil, idx) in mobils.slice(0, 4)"
                        :key="mobil.id"
                        :class="[
                            'rounded-3xl p-5 border transition-all duration-300 flex flex-col justify-between group',
                            idx === 1
                                ? 'bg-gradient-to-b from-red-600 to-rose-700 border-red-500 text-white shadow-2xl shadow-red-600/30 transform -translate-y-2'
                                : 'bg-neutral-900/90 border-neutral-800/80 hover:border-neutral-700 text-slate-200 hover:shadow-xl',
                        ]"
                    >
                        <div>
                            <!-- Thumbnail with Direct Link to Single Car Page -->
                            <Link
                                :href="`/mobil/${mobil.id}`"
                                :class="[
                                    'h-40 rounded-2xl overflow-hidden mb-4 flex items-center justify-center border block relative',
                                    idx === 1 ? 'bg-black/20 border-white/15' : 'bg-neutral-950 border-neutral-800',
                                ]"
                            >
                                <img
                                    v-if="mobil.gambar"
                                    :src="`/gambar_mobil/${mobil.gambar}`"
                                    :alt="`${mobil.merk} ${mobil.nama_mobil}`"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                    loading="lazy"
                                    decoding="async"
                                    width="300"
                                    height="180"
                                />
                                <Car v-else class="w-10 h-10 text-neutral-600" />
                            </Link>

                            <span
                                :class="[
                                    'text-[10px] font-bold uppercase tracking-wider block',
                                    idx === 1 ? 'text-white/80' : 'text-neutral-500',
                                ]"
                            >
                                {{ mobil.merk }}
                            </span>
                            <Link :href="`/mobil/${mobil.id}`">
                                <h4
                                    :class="[
                                        'text-base font-black uppercase tracking-tight mt-0.5 truncate hover:underline',
                                        idx === 1 ? 'text-white' : 'text-white',
                                    ]"
                                >
                                    {{ mobil.nama_mobil }}
                                </h4>
                            </Link>
                            <p
                                :class="[
                                    'text-[11px] mt-1 line-clamp-2',
                                    idx === 1 ? 'text-white/90' : 'text-neutral-400',
                                ]"
                            >
                                {{ mobil.tipe_kendaraan || 'Sedan Eksekutif' }} • {{ mobil.transmisi || 'Matic' }} • {{ mobil.kapasitas_penumpang || 5 }} Kursi
                            </p>
                        </div>

                        <div class="mt-6 pt-4 border-t border-white/10 flex items-center justify-between">
                            <div>
                                <span :class="['text-[10px] uppercase font-bold block', idx === 1 ? 'text-white/75' : 'text-neutral-500']">
                                    {{ t('specials.perDay') }}
                                </span>
                                <span class="text-sm font-black text-white">
                                    {{ formatRupiah(mobil.harga_per_hari) }}
                                </span>
                            </div>

                            <Link
                                :href="user ? `/transaksi/create?mobil_id=${mobil.id}` : '/login'"
                                :class="[
                                    'inline-flex items-center gap-1 text-xs font-black uppercase tracking-wider transition',
                                    idx === 1 ? 'text-white hover:underline' : 'text-red-500 hover:text-red-400',
                                ]"
                            >
                                {{ t('specials.driveNow') }} &rarr;
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. "CHOOSE YOUR EXPERIENCE" (Giant Watermark + Top-down Car Interior) -->
        <section id="experience" class="py-24 bg-[#070709] relative overflow-hidden border-t border-neutral-800/80 px-4 sm:px-6 lg:px-8">
            <!-- Giant Outline Watermark Typography behind car -->
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none overflow-hidden opacity-5">
                <span class="text-[14vw] font-black uppercase tracking-widest text-white">
                    EXPERIENCE
                </span>
            </div>

            <div class="max-w-7xl mx-auto text-center relative z-10">
                <h2 class="text-3xl sm:text-5xl font-black uppercase tracking-tight text-white">
                    {{ t('experience.title') }}
                </h2>
                <p class="text-xs sm:text-sm text-neutral-400 max-w-xl mx-auto mt-2 leading-relaxed">
                    {{ t('experience.subtitle') }}
                </p>

                <!-- Top-down view transparent interior car graphic -->
                <div class="mt-12 max-w-4xl mx-auto rounded-3xl overflow-hidden shadow-2xl border border-neutral-800/80 bg-black aspect-16/9">
                    <picture>
                        <source srcset="/images/car_top_down.webp" type="image/webp" />
                        <img
                            src="/images/car_top_down.jpg"
                            alt="Car Top Down Interior Experience"
                            class="w-full h-full object-cover"
                            loading="lazy"
                            decoding="async"
                            width="800"
                            height="450"
                        />
                    </picture>
                </div>
            </div>
        </section>

        <!-- 5. "OUR GALLERY" SECTION (Scenic Mountain Road Showcase) -->
        <section id="gallery" class="py-24 bg-[#0A0A0E] border-t border-neutral-800/80 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto text-center">
                <h2 class="text-3xl sm:text-4xl font-black uppercase tracking-tight text-white mb-2">
                    Our Gallery
                </h2>
                <p class="text-xs sm:text-sm text-neutral-400 max-w-lg mx-auto mb-12">
                    Keindahan armada luxury kami di setiap sudut rute dan momen perjalanan Anda.
                </p>

                <!-- Center Featured Mountain Road Bentley Image -->
                <div class="max-w-3xl mx-auto rounded-3xl overflow-hidden border border-neutral-800 shadow-2xl mb-8 aspect-16/10">
                    <picture>
                        <source srcset="/images/bentley_mountain.webp" type="image/webp" />
                        <img
                            src="/images/bentley_mountain.jpg"
                            alt="Bentley Mountain Road"
                            class="w-full h-full object-cover transform hover:scale-102 transition duration-700"
                            loading="lazy"
                            decoding="async"
                            width="750"
                            height="470"
                        />
                    </picture>
                </div>

                <Link
                    href="/transaksi/create"
                    class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-black uppercase tracking-wider shadow-xl shadow-red-600/30 transition cursor-pointer"
                >
                    {{ t('specials.moreDetail') }}
                    <ChevronRight class="w-4 h-4" />
                </Link>
            </div>
        </section>

        <!-- 6. CASE STUDIES & LEGAL PT CREDIBILITY (Social Proof & Trust) -->
        <section class="py-20 bg-[#0A0A0E] border-t border-neutral-800/80 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-12">
                <div class="text-center max-w-2xl mx-auto">
                    <span class="text-xs font-black uppercase tracking-widest text-red-500 block mb-1">
                        Portofolio & Rekam Jejak Resmi
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-black uppercase tracking-tight text-white">
                        Kepercayaan KTT VVIP & Korporat Tbk
                    </h2>
                    <p class="text-xs sm:text-sm text-neutral-400 mt-2">
                        Armada kami berpengalaman melayani pengawalan tamu kenegaraan, event eksklusif, hingga kontrak korporat jangka panjang.
                    </p>
                </div>

                <!-- Case Studies Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-[#0f1015] border border-neutral-800 rounded-3xl p-6 sm:p-8 space-y-4 hover:border-neutral-700 transition">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 bg-red-600/20 text-red-400 border border-red-500/30 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                Diplomatic & VVIP Escort
                            </span>
                            <span class="text-xs font-mono text-neutral-500">Jakarta & Bali</span>
                        </div>
                        <h3 class="text-xl font-extrabold text-white">Pengawalan Delegasi KTT & Tamu VVIP Negara</h3>
                        <p class="text-xs text-neutral-400 leading-relaxed">
                            Penyediaan 45 unit Toyota Alphard HEV & Land Cruiser Armor VVIP dilengkapi pengemudi terlatih sertifikasi protokoler kenegaraan dan pengawalan bebas hambatan.
                        </p>
                        <div class="pt-2 flex items-center gap-2 text-xs font-bold text-emerald-400">
                            <CheckCircle2 class="w-4 h-4" />
                            <span>100% On-Time Arrival & Garansi Unit Pengganti < 30 Menit</span>
                        </div>
                    </div>

                    <div class="bg-[#0f1015] border border-neutral-800 rounded-3xl p-6 sm:p-8 space-y-4 hover:border-neutral-700 transition">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 bg-blue-600/20 text-blue-400 border border-blue-500/30 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                Luxury Hotel & Wedding Escort
                            </span>
                            <span class="text-xs font-mono text-neutral-500">Hotel Bintang 5</span>
                        </div>
                        <h3 class="text-xl font-extrabold text-white">Armada Resmi Pernikahan Luxury & Executive Mobility</h3>
                        <p class="text-xs text-neutral-400 leading-relaxed">
                            Mitra penyedia armada mobil pengantin mewah Mercedes-Maybach, Rolls Royce Ghost, dan Porsche Panamera lengkap dengan dekorasi bunga serta karpet merah.
                        </p>
                        <div class="pt-2 flex items-center gap-2 text-xs font-bold text-emerald-400">
                            <CheckCircle2 class="w-4 h-4" />
                            <span>Asuransi All-Risk & Pengemudi Berbusana Formal Tuxedo</span>
                        </div>
                    </div>
                </div>

                <!-- Corporate Partners Badges -->
                <div class="pt-6 border-t border-neutral-800/80">
                    <p class="text-center text-[10px] font-black uppercase tracking-widest text-neutral-500 mb-6">
                        DIPERCAYA OLEH PERUSAHAAN TERKEMUKA DI INDONESIA
                    </p>
                    <div class="flex flex-wrap items-center justify-center gap-8 sm:gap-12 opacity-70 grayscale hover:grayscale-0 transition duration-500">
                        <div class="text-lg font-black text-white tracking-widest">BCA</div>
                        <div class="text-lg font-black text-white tracking-widest">MANDIRI</div>
                        <div class="text-lg font-black text-white tracking-widest">TELKOMSEL</div>
                        <div class="text-lg font-black text-white tracking-widest">ZURICH</div>
                        <div class="text-lg font-black text-white tracking-widest">ALLIANZ</div>
                        <div class="text-lg font-black text-white tracking-widest">ASTRA GUARD</div>
                    </div>
                </div>

                <!-- Legal PT Certification Footer Banner -->
                <div class="bg-neutral-900/90 border border-neutral-800 rounded-3xl p-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30">
                            <Shield class="w-5 h-5" />
                        </div>
                        <div>
                            <p class="font-bold text-white">LEGALITAS RESMI PT QUANTUM STREAMLINE RENT CAR</p>
                            <p class="text-neutral-400 text-[11px]">NIB: 128900049281 | KBLI 49221 (Angkutan Sewa Khusus Kemenhub RI) | Terdaftar UU PDP No. 27/2022</p>
                        </div>
                    </div>
                    <Link href="/privacy-policy" class="px-4 py-2 rounded-xl bg-neutral-800 hover:bg-neutral-700 text-white font-bold text-xs shrink-0 transition">
                        Kebijakan Privasi UU PDP &rarr;
                    </Link>
                </div>
            </div>
        </section>

        <!-- 7. INSTANT CONSULTATION FORM (4-COLUMN MICRO-INTERACTION) -->
        <section id="consultation" class="py-20 bg-[#070709] border-t border-neutral-800/80 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto bg-gradient-to-br from-[#0f1015] via-neutral-900 to-[#12131a] border border-neutral-800 rounded-3xl p-6 sm:p-10 shadow-2xl space-y-6">
                <div class="text-center max-w-lg mx-auto">
                    <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full bg-red-600/20 text-red-400 border border-red-500/30">
                        KONSULTASI ARMADA INSTAN
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black uppercase tracking-tight text-white mt-3">
                        Rencanakan Perjalanan Anda Dalam 1 Menit
                    </h2>
                    <p class="text-xs text-neutral-400 mt-1">
                        Isi form ringkas di bawah. Tim WhatsApp Concierge kami akan merespons ketersediaan unit & penawaran khusus secara instan.
                    </p>
                </div>

                <!-- Form or Thank You Screen -->
                <div v-if="consultSubmitted" class="bg-emerald-950/40 border border-emerald-500/40 rounded-2xl p-6 text-center space-y-4 animate-in fade-in zoom-in">
                    <div class="w-12 h-12 bg-emerald-500/20 text-emerald-400 rounded-full flex items-center justify-center mx-auto border border-emerald-500/30">
                        <CheckCircle2 class="w-6 h-6" />
                    </div>
                    <h3 class="text-lg font-bold text-white">Konsultasi Berhasil Terkirim!</h3>
                    <p class="text-xs text-slate-300 max-w-md mx-auto leading-relaxed">
                        {{ consultSuccessMessage }}
                    </p>
                    <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
                        <a
                            :href="`https://wa.me/6281234567890?text=Halo%20Quantum%20Streamline,%20saya%20${encodeURIComponent(consultForm.nama)}%20ingin%20konsultasi%20sewa%20${encodeURIComponent(consultForm.kendaraan)}%20untuk%20tanggal%20${encodeURIComponent(consultForm.tanggal)}`"
                            target="_blank"
                            class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg transition"
                        >
                            💬 Buka Chat WhatsApp Concierge Langsung
                        </a>
                        <button type="button" @click="consultSubmitted = false" class="px-4 py-3 text-xs text-neutral-400 hover:text-white font-bold">
                            Isi Form Lain
                        </button>
                    </div>
                </div>

                <form v-else @submit.prevent="submitConsultation" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-300 mb-1">
                            1. Nama Lengkap Anda*
                        </label>
                        <input
                            v-model="consultForm.nama"
                            type="text"
                            required
                            placeholder="misal: Bapak Budi Santoso"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white placeholder:text-neutral-600 focus:ring-2 focus:ring-red-600 transition"
                        />
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-300 mb-1">
                            2. Nomor WhatsApp / Telp*
                        </label>
                        <input
                            v-model="consultForm.whatsapp"
                            type="tel"
                            required
                            placeholder="081234567890"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white placeholder:text-neutral-600 focus:ring-2 focus:ring-red-600 transition"
                        />
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-300 mb-1">
                            3. Pilihan Armada / Layanan*
                        </label>
                        <select
                            v-model="consultForm.kendaraan"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                        >
                            <option value="Alphard Transformer VIP">Toyota Alphard Transformer VIP</option>
                            <option value="Camry Hybrid Executive">Toyota Camry Hybrid Executive</option>
                            <option value="Fortuner GR Sport 4x4">Toyota Fortuner GR Sport 4x4</option>
                            <option value="Supercar / Sportscar Exclusive">Supercar / Sportscar Exclusive</option>
                            <option value="Sewa Korporat Jangka Panjang">Sewa Korporat Jangka Panjang (B2B)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-300 mb-1">
                            4. Rencana Tanggal Sewa*
                        </label>
                        <input
                            v-model="consultForm.tanggal"
                            type="date"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                        />
                    </div>

                    <div class="sm:col-span-2 pt-2">
                        <button
                            type="submit"
                            :disabled="isSubmittingConsult"
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 text-white font-black text-xs uppercase tracking-wider shadow-xl shadow-red-600/30 transition cursor-pointer disabled:opacity-50"
                        >
                            <span v-if="isSubmittingConsult" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                            <span>{{ isSubmittingConsult ? 'Mengirim Permintaan...' : 'Kirim Konsultasi Instan via Concierge' }}</span>
                            <ArrowRight v-if="!isSubmittingConsult" class="w-4 h-4" />
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <!-- 8. TRUST & REPUTATION SECTION -->
        <section class="py-16 bg-[#070709] border-t border-neutral-800/80 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="p-6 bg-neutral-900/60 rounded-3xl border border-neutral-800">
                    <div class="flex items-center justify-center gap-1 text-amber-400 mb-2">
                        <Star v-for="i in 5" :key="i" class="w-5 h-5 fill-amber-400" />
                    </div>
                    <div class="text-2xl font-black text-white">4.9 / 5.0</div>
                    <p class="text-xs text-neutral-400 mt-1">{{ t('trust.ratingText') }}</p>
                </div>

                <div class="p-6 bg-neutral-900/60 rounded-3xl border border-neutral-800">
                    <div class="text-3xl font-black text-red-500 mb-1">2,850+</div>
                    <div class="text-sm font-bold text-white">{{ t('trust.tripsText') }}</div>
                    <p class="text-xs text-neutral-400 mt-1">Jabodetabek, Bali, Yogyakarta, Surabaya</p>
                </div>

                <div class="p-6 bg-neutral-900/60 rounded-3xl border border-neutral-800">
                    <div class="w-8 h-8 rounded-full bg-emerald-600/20 text-emerald-400 flex items-center justify-center mx-auto mb-2">
                        <Shield class="w-4 h-4" />
                    </div>
                    <div class="text-sm font-bold text-white">{{ t('trust.partnerText') }}</div>
                    <p class="text-xs text-neutral-400 mt-1">Zurich Insurance, Allianz, & Astra Guard</p>
                </div>
            </div>
        </section>

        <!-- 9. FOOTER ("QUANTUM STREAMLINE") -->
        <footer class="bg-black border-t border-neutral-800/80 pt-16 pb-24 sm:pb-12 px-4 sm:px-6 lg:px-8 text-xs text-neutral-400">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-12 gap-10 pb-12 border-b border-neutral-800">
                <!-- Brand & Newsletter (5 cols) -->
                <div class="md:col-span-5 space-y-4">
                    <div class="flex items-center gap-2">
                        <Car class="w-6 h-6 text-red-600" />
                        <span class="text-base font-black tracking-wider text-white uppercase">
                            QUANTUM <span class="text-red-600">STREAMLINE</span>
                        </span>
                    </div>
                    <p class="text-neutral-400 max-w-sm leading-relaxed">
                        Join Our Mailing List. Tinggalkan email Anda untuk mendapatkan penawaran eksklusif dan voucher rental mobil.
                    </p>

                    <!-- Newsletter Input -->
                    <div class="flex items-center max-w-md pt-2">
                        <input
                            type="email"
                            placeholder="Enter Your Email Address"
                            class="flex-1 px-4 py-2.5 rounded-l-xl bg-neutral-900 border border-neutral-700 text-xs text-white focus:outline-none focus:border-red-600"
                        />
                        <button
                            type="button"
                            class="px-5 py-2.5 rounded-r-xl bg-red-600 hover:bg-red-700 text-white font-black uppercase tracking-wider text-xs transition cursor-pointer"
                        >
                            Subscribe
                        </button>
                    </div>
                </div>

                <!-- Explore Links (2 cols) -->
                <div class="md:col-span-2 space-y-2.5">
                    <h4 class="text-xs font-black uppercase tracking-wider text-white">Explore</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-red-400 transition">Home</a></li>
                        <li><a href="#about" class="hover:text-red-400 transition">About Us</a></li>
                        <li><a href="#experience" class="hover:text-red-400 transition">Services</a></li>
                        <li><a href="#specials" class="hover:text-red-400 transition">Vehicles</a></li>
                    </ul>
                </div>

                <!-- Payment Partners (2 cols) -->
                <div class="md:col-span-2 space-y-2.5">
                    <h4 class="text-xs font-black uppercase tracking-wider text-white">Payment</h4>
                    <ul class="space-y-2">
                        <li>BCA Virtual Account</li>
                        <li>Mandiri Virtual Account</li>
                        <li>QRIS & GoPay</li>
                        <li>Visa & MasterCard</li>
                    </ul>
                </div>

                <!-- Contact Details (3 cols) -->
                <div class="md:col-span-3 space-y-2.5">
                    <h4 class="text-xs font-black uppercase tracking-wider text-white">Contact</h4>
                    <p>Hotline: +62 812-3456-7890</p>
                    <p>Alamat: Menara Sudirman Lt. 18, Jakarta Selatan</p>
                    <p>Email: support@quantumstreamline.com</p>
                </div>
            </div>

            <div class="max-w-7xl mx-auto pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-neutral-500">
                <span>&copy; {{ new Date().getFullYear() }} Quantum Streamline / RentalMobil. All Rights Reserved.</span>
                <div class="flex items-center gap-6">
                    <Link href="/privacy-policy" class="hover:text-white transition font-semibold">Privacy Policy (UU PDP)</Link>
                    <Link href="/terms" class="hover:text-white transition font-semibold">Terms And Conditions</Link>
                </div>
            </div>
        </footer>

        <!-- FLOATING MOBILE STICKY CTA BAR (md:hidden) -->
        <div class="fixed bottom-0 left-0 right-0 z-40 bg-black/95 backdrop-blur-md border-t border-neutral-800 p-3 flex items-center justify-between gap-3 md:hidden shadow-2xl">
            <div>
                <span class="text-[10px] text-neutral-400 block">Sewa Mobil Mewah</span>
                <span class="text-xs font-black text-white">Mulai Rp 450rb/hari</span>
            </div>
            <div class="flex items-center gap-2">
                <a
                    href="#consultation"
                    class="px-3 py-2 rounded-xl bg-neutral-800 text-white font-bold text-xs uppercase tracking-wider"
                >
                    Sewa Korporat
                </a>
                <Link
                    :href="user ? '/user/transaksi/create' : '/login'"
                    class="px-3.5 py-2 rounded-xl bg-gradient-to-r from-red-600 to-rose-600 text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-red-600/30"
                >
                    Pesan Armada
                </Link>
            </div>
        </div>

        <!-- FLOATING WHATSAPP CONCIERGE BUTTON WITH ONLINE GREEN PULSE ANIMATION -->
        <a
            href="https://wa.me/6281234567890?text=Halo%20Quantum%20Streamline,%20saya%20ingin%20tanya%20ketersediaan%20sewa%20mobil"
            target="_blank"
            class="fixed bottom-20 sm:bottom-6 right-6 z-40 p-3.5 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white shadow-2xl shadow-emerald-600/40 flex items-center gap-2 transition duration-300 transform hover:scale-105 active:scale-95 group"
            title="Chat WhatsApp Concierge 24/7"
        >
            <div class="relative flex items-center justify-center">
                <span class="absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75 animate-ping"></span>
                <MessageSquare class="w-6 h-6 relative z-10" />
            </div>
            <span class="hidden sm:inline text-xs font-black uppercase tracking-wider pr-1">
                WhatsApp Concierge
            </span>
        </a>

        <!-- Modal Detail Mobil (Quick Details) -->
        <Modal
            :show="carModalOpen"
            title="Spesifikasi & Rincian Unit"
            maxWidth="lg"
            @close="carModalOpen = false"
        >
            <div v-if="selectedCarForModal" class="space-y-4 text-slate-800">
                <div class="h-48 rounded-2xl bg-neutral-900 overflow-hidden flex items-center justify-center">
                    <img
                        v-if="selectedCarForModal.gambar"
                        :src="`/gambar_mobil/${selectedCarForModal.gambar}`"
                        :alt="selectedCarForModal.nama_mobil"
                        class="w-full h-full object-cover"
                    />
                </div>

                <div>
                    <span class="text-xs font-bold text-red-600 uppercase tracking-wider">
                        {{ selectedCarForModal.merk }}
                    </span>
                    <h3 class="text-xl font-black text-slate-900">
                        {{ selectedCarForModal.nama_mobil }}
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">
                        {{ selectedCarForModal.tipe_kendaraan }} • Transmisi {{ selectedCarForModal.transmisi }} • {{ selectedCarForModal.kapasitas_penumpang }} Kursi
                    </p>
                </div>

                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 text-xs space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600 font-medium">Tarif Lepas Kunci:</span>
                        <span class="font-black text-slate-900">{{ formatRupiah(selectedCarForModal.harga_per_hari) }} / hari</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600 font-medium">Biaya Layanan Sopir:</span>
                        <span class="font-black text-red-600">+{{ formatRupiah(selectedCarForModal.biaya_sopir_per_hari || 150000) }} / hari</span>
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-end gap-3">
                    <button
                        type="button"
                        @click="carModalOpen = false"
                        class="px-4 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-100"
                    >
                        Tutup
                    </button>
                    <Link
                        :href="user ? `/transaksi/create?mobil_id=${selectedCarForModal.id}` : '/login'"
                        class="px-5 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-black uppercase tracking-wider shadow-md shadow-red-600/30"
                    >
                        Pesan Sekarang
                    </Link>
                </div>
            </div>
        </Modal>
    </div>
</template>
