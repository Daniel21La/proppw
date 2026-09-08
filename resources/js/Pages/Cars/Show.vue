<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import {
    Car,
    Shield,
    Clock,
    Users,
    Fuel,
    Gauge,
    Sparkles,
    Calendar,
    ArrowLeft,
    CheckCircle2,
    MessageSquare,
    Share2,
    Check,
    PhoneCall,
    CreditCard,
    AlertCircle,
} from 'lucide-vue-next';

const props = defineProps({
    mobil: {
        type: Object,
        required: true,
    },
    activeSeasonal: {
        type: Object,
        default: null,
    },
    relatedCars: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const user = computed(() => page.props.auth?.user);

function formatRupiah(num) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(num || 0);
}

// Current effective price
const effectivePrice = computed(() => {
    if (props.activeSeasonal && props.activeSeasonal.tarif_per_hari) {
        return Number(props.activeSeasonal.tarif_per_hari);
    }
    return Number(props.mobil.harga_per_hari);
});

// Full absolute URL for OpenGraph
const currentUrl = computed(() => {
    if (typeof window !== 'undefined') {
        return window.location.href;
    }
    return `/mobil/${props.mobil.id}`;
});

const ogImageUrl = computed(() => {
    if (props.mobil.gambar) {
        if (props.mobil.gambar.startsWith('http')) return props.mobil.gambar;
        if (typeof window !== 'undefined') {
            return `${window.location.origin}/gambar_mobil/${props.mobil.gambar}`;
        }
        return `/gambar_mobil/${props.mobil.gambar}`;
    }
    return '/images/audi_front_dark.webp';
});

// JSON-LD Structured Data for Google Rich Snippets
const jsonLd = computed(() => {
    return JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'Product',
        name: `${props.mobil.merk} ${props.mobil.nama_mobil}`,
        image: [ogImageUrl.value],
        description: `Sewa ${props.mobil.merk} ${props.mobil.nama_mobil} lepas kunci atau dengan sopir mulai ${formatRupiah(effectivePrice.value)}/hari. Layanan premium Quantum Streamline.`,
        brand: {
            '@type': 'Brand',
            name: props.mobil.merk,
        },
        offers: {
            '@type': 'Offer',
            url: currentUrl.value,
            priceCurrency: 'IDR',
            price: effectivePrice.value,
            priceValidUntil: '2026-12-31',
            availability: props.mobil.status === 'tersedia' ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            seller: {
                '@type': 'AutoRental',
                name: 'QUANTUM STREAMLINE',
            },
        },
    });
});

function copyShareLink() {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(window.location.href);
        alert('Tautan mobil berhasil disalin ke clipboard!');
    }
}
</script>

<template>
    <!-- Dynamic Head & Social OpenGraph Tags -->
    <Head>
        <title>{{ `${mobil.merk} ${mobil.nama_mobil} - Rental Mobil Mewah | QUANTUM STREAMLINE` }}</title>
        <meta
            name="description"
            :content="`Sewa mobil mewah ${mobil.merk} ${mobil.nama_mobil} lepas kunci atau dengan sopir mulai ${formatRupiah(effectivePrice)}/hari. Unit terawat, bersih, siap jalan.`"
        />
        <meta name="keywords" :content="`sewa ${mobil.nama_mobil}, rental ${mobil.merk}, rental mobil jakarta, rental mobil mewah, quantum streamline`" />
        <link rel="canonical" :href="currentUrl" />

        <!-- OpenGraph (Facebook, WhatsApp, LinkedIn) -->
        <meta property="og:type" content="product" />
        <meta property="og:title" :content="`${mobil.merk} ${mobil.nama_mobil} | QUANTUM STREAMLINE`" />
        <meta
            property="og:description"
            :content="`Sewa ${mobil.merk} ${mobil.nama_mobil} mulai ${formatRupiah(effectivePrice)}/hari. Layanan Lepas Kunci & Dengan Sopir.`"
        />
        <meta property="og:image" :content="ogImageUrl" />
        <meta property="og:url" :content="currentUrl" />
        <meta property="og:site_name" content="QUANTUM STREAMLINE" />

        <!-- Twitter Cards -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="`${mobil.merk} ${mobil.nama_mobil} | QUANTUM STREAMLINE`" />
        <meta
            name="twitter:description"
            :content="`Sewa ${mobil.merk} ${mobil.nama_mobil} mulai ${formatRupiah(effectivePrice)}/hari. Unit luxury siap jalan.`"
        />
        <meta name="twitter:image" :content="ogImageUrl" />

        <!-- JSON-LD Structured Data Schema -->
        <component :is="'script'" type="application/ld+json" v-html="jsonLd" />
    </Head>

    <div class="min-h-screen bg-[#070709] text-white font-sans selection:bg-red-600 selection:text-white flex flex-col">
        <!-- Top Navigation Bar -->
        <header class="sticky top-0 z-40 bg-[#070709]/90 backdrop-blur-md border-b border-neutral-800/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-8 bg-red-600 rounded-full hidden sm:block shadow-sm shadow-red-600" />
                    <Link href="/" class="flex items-center gap-2.5 group">
                        <span class="text-xl sm:text-2xl font-black uppercase tracking-wider text-white group-hover:text-red-500 transition">
                            QUANTUM <span class="text-red-600">STREAMLINE</span>
                        </span>
                    </Link>
                </div>

                <div class="flex items-center gap-3">
                    <Link
                        href="/"
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-neutral-300 hover:text-white transition px-3 py-2"
                    >
                        <ArrowLeft class="w-4 h-4 text-red-500" />
                        Kembali ke Katalog
                    </Link>

                    <button
                        @click="copyShareLink"
                        class="p-2 rounded-xl bg-neutral-900 border border-neutral-800 text-neutral-300 hover:text-white transition cursor-pointer"
                        title="Bagikan Tautan Mobil"
                    >
                        <Share2 class="w-4 h-4" />
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-neutral-400 mb-8 font-medium">
                <Link href="/" class="hover:text-white transition">Beranda</Link>
                <span>/</span>
                <Link href="/#specials" class="hover:text-white transition">Armada Kami</Link>
                <span>/</span>
                <span class="text-red-500 font-bold uppercase">{{ mobil.merk }} {{ mobil.nama_mobil }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
                <!-- Left: Big Visual Showcase (7 cols) -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="relative rounded-3xl overflow-hidden bg-neutral-950 border border-neutral-800 shadow-2xl aspect-16/10 flex items-center justify-center">
                        <img
                            v-if="mobil.gambar"
                            :src="`/gambar_mobil/${mobil.gambar}`"
                            :alt="`${mobil.merk} ${mobil.nama_mobil}`"
                            class="w-full h-full object-cover"
                            fetchpriority="high"
                            width="700"
                            height="440"
                        />
                        <div v-else class="flex flex-col items-center justify-center text-neutral-600">
                            <Car class="w-20 h-20 mb-2 stroke-1" />
                            <span class="text-xs uppercase font-bold tracking-wider">Foto Unit Segera Hadir</span>
                        </div>

                        <!-- Status badge over image -->
                        <div class="absolute top-4 left-4">
                            <span
                                v-if="mobil.status === 'tersedia'"
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-emerald-950/80 border border-emerald-500/50 text-emerald-400 backdrop-blur-md"
                            >
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse" />
                                Siap Dirental
                            </span>
                            <span
                                v-else-if="mobil.status === 'disewa'"
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-amber-950/80 border border-amber-500/50 text-amber-400 backdrop-blur-md"
                            >
                                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse" />
                                Sedang Disewa
                            </span>
                        </div>
                    </div>

                    <!-- Trust & Guarantee Highlights -->
                    <div class="grid grid-cols-3 gap-4 pt-2">
                        <div class="bg-[#0f1015] p-4 rounded-2xl border border-neutral-800 text-center">
                            <Shield class="w-5 h-5 text-red-500 mx-auto mb-1.5" />
                            <span class="block text-[11px] font-bold text-white uppercase">Asuransi All-Risk</span>
                            <span class="text-[10px] text-neutral-400">Proteksi Penuh</span>
                        </div>

                        <div class="bg-[#0f1015] p-4 rounded-2xl border border-neutral-800 text-center">
                            <Sparkles class="w-5 h-5 text-red-500 mx-auto mb-1.5" />
                            <span class="block text-[11px] font-bold text-white uppercase">Higienis & Wangi</span>
                            <span class="text-[10px] text-neutral-400">Deep Cleaning</span>
                        </div>

                        <div class="bg-[#0f1015] p-4 rounded-2xl border border-neutral-800 text-center">
                            <Clock class="w-5 h-5 text-red-500 mx-auto mb-1.5" />
                            <span class="block text-[11px] font-bold text-white uppercase">Layanan 24/7</span>
                            <span class="text-[10px] text-neutral-400">Roadside Assist</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Specification, Pricing & Booking Action (5 cols) -->
                <div class="lg:col-span-5 bg-[#0f1015] border border-neutral-800 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-black text-red-500 uppercase tracking-widest">
                                {{ mobil.merk }}
                            </span>
                            <span class="text-neutral-600">&bull;</span>
                            <span class="text-xs font-bold text-neutral-400 uppercase">
                                {{ mobil.tipe_kendaraan || 'Luxury Fleet' }}
                            </span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-black text-white uppercase tracking-tight">
                            {{ mobil.nama_mobil }}
                        </h1>
                    </div>

                    <!-- Pricing Banner -->
                    <div class="bg-neutral-900/90 rounded-2xl p-4 border border-neutral-800">
                        <span class="text-[10px] font-black uppercase tracking-wider text-neutral-400 block">
                            Tarif Sewa Harian
                        </span>
                        <div class="flex items-baseline gap-2 mt-1">
                            <span class="text-3xl font-black text-white">
                                {{ formatRupiah(effectivePrice) }}
                            </span>
                            <span class="text-xs text-neutral-400">/ 24 Jam</span>
                        </div>

                        <!-- Active Seasonal Notice -->
                        <div
                            v-if="activeSeasonal"
                            class="mt-3 p-2.5 rounded-xl bg-red-950/40 border border-red-800/60 flex items-center gap-2 text-xs text-red-300"
                        >
                            <Sparkles class="w-4 h-4 text-red-400 shrink-0" />
                            <span>
                                <b>{{ activeSeasonal.nama_event }}</b> aktif hingga {{ activeSeasonal.tanggal_selesai }}.
                            </span>
                        </div>
                    </div>

                    <!-- Specifications Table -->
                    <div class="space-y-3 pt-2">
                        <span class="text-xs font-black uppercase tracking-wider text-neutral-300 block">
                            Spesifikasi Teknis
                        </span>
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div class="bg-[#070709] p-3 rounded-xl border border-neutral-800">
                                <span class="text-[10px] text-neutral-500 uppercase font-bold block">Transmisi</span>
                                <span class="font-bold text-white">{{ mobil.transmisi || 'Automatic (A/T)' }}</span>
                            </div>
                            <div class="bg-[#070709] p-3 rounded-xl border border-neutral-800">
                                <span class="text-[10px] text-neutral-500 uppercase font-bold block">Kapasitas</span>
                                <span class="font-bold text-white">{{ mobil.kapasitas_penumpang || 5 }} Penumpang</span>
                            </div>
                            <div class="bg-[#070709] p-3 rounded-xl border border-neutral-800">
                                <span class="text-[10px] text-neutral-500 uppercase font-bold block">Bahan Bakar</span>
                                <span class="font-bold text-white">Bensin / Hybrid</span>
                            </div>
                            <div class="bg-[#070709] p-3 rounded-xl border border-neutral-800">
                                <span class="text-[10px] text-neutral-500 uppercase font-bold block">Opsi Layanan</span>
                                <span class="font-bold text-red-400">Lepas Kunci / Sopir</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 space-y-3">
                        <Link
                            :href="user ? `/transaksi/create?mobil_id=${mobil.id}` : `/login`"
                            class="w-full inline-flex items-center justify-center gap-2 py-4 px-6 rounded-2xl bg-red-600 hover:bg-red-500 text-white font-black text-xs uppercase tracking-wider shadow-xl shadow-red-600/30 transition transform active:scale-98 cursor-pointer"
                        >
                            <Calendar class="w-4 h-4" />
                            Pesan Unit Ini Sekarang
                        </Link>

                        <a
                            :href="`https://wa.me/6281234567890?text=Halo%20Quantum%20Streamline,%20saya%20tertarik%20untuk%20sewa%20mobil%20${encodeURIComponent(mobil.merk + ' ' + mobil.nama_mobil)}`"
                            target="_blank"
                            class="w-full inline-flex items-center justify-center gap-2 py-3 px-6 rounded-2xl bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xs uppercase tracking-wider border border-neutral-700 transition cursor-pointer"
                        >
                            <MessageSquare class="w-4 h-4 text-emerald-400" />
                            Konsultasi via WhatsApp
                        </a>
                    </div>
                </div>
            </div>

            <!-- Related Cars Section -->
            <section v-if="relatedCars.length > 0" class="mt-20 pt-12 border-t border-neutral-800/80">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <span class="text-xs font-black text-red-500 uppercase tracking-widest block">
                            Pilihan Lainnya
                        </span>
                        <h2 class="text-2xl font-black uppercase text-white tracking-tight mt-1">
                            Armada Serupa
                        </h2>
                    </div>
                    <Link href="/#specials" class="text-xs font-bold text-red-400 hover:text-red-300">
                        Lihat Semua &rarr;
                    </Link>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div
                        v-for="rc in relatedCars"
                        :key="rc.id"
                        class="bg-[#0f1015] border border-neutral-800 rounded-3xl p-5 hover:border-neutral-700 transition duration-300 flex flex-col justify-between group"
                    >
                        <div>
                            <div class="h-40 rounded-2xl overflow-hidden mb-4 bg-neutral-950 flex items-center justify-center border border-neutral-800">
                                <img
                                    v-if="rc.gambar"
                                    :src="`/gambar_mobil/${rc.gambar}`"
                                    :alt="rc.nama_mobil"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                    loading="lazy"
                                    width="300"
                                    height="180"
                                />
                                <Car v-else class="w-8 h-8 text-neutral-600" />
                            </div>

                            <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-500 block">
                                {{ rc.merk }}
                            </span>
                            <h3 class="text-base font-black uppercase text-white truncate mt-0.5">
                                {{ rc.nama_mobil }}
                            </h3>
                            <span class="text-xs font-black text-red-400 mt-2 block">
                                {{ formatRupiah(rc.harga_per_hari) }} <span class="text-[10px] text-neutral-500 font-normal">/hari</span>
                            </span>
                        </div>

                        <div class="mt-6 pt-4 border-t border-neutral-800">
                            <Link
                                :href="`/mobil/${rc.id}`"
                                class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-4 rounded-xl bg-neutral-900 hover:bg-red-600 text-white text-xs font-bold transition"
                            >
                                Detail Armada &rarr;
                            </Link>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>
