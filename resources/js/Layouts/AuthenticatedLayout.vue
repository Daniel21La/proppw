<script setup>
import { ref, computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import {
    Car,
    FileText,
    BarChart3,
    Clock,
    PlusCircle,
    User,
    LogOut,
    Menu,
    X,
    Shield,
    Sparkles,
    Globe,
    CalendarDays,
    Tag,
    Activity,
} from 'lucide-vue-next';
import Toast from '@/Components/Toast.vue';
import Badge from '@/Components/Badge.vue';
import { useT } from '@/locales/translations';

const { t, currentLang, setLanguage } = useT();

const page = usePage();
const user = computed(() => page.props.auth?.user || {});
const isAdmin = computed(() => user.value.role === 'admin');

const mobileMenuOpen = ref(false);
const profileDropdownOpen = ref(false);

const currentUrl = computed(() => page.url);

const adminNav = [
    {
        name: 'Kalender Armada',
        href: '/admin/kalender',
        active: currentUrl.value.startsWith('/admin/kalender'),
        icon: CalendarDays,
    },
    {
        name: 'Kelola Mobil',
        href: '/admin/mobil',
        active: currentUrl.value.startsWith('/admin/mobil'),
        icon: Car,
    },
    {
        name: 'Harga Musiman',
        href: '/admin/harga-musiman',
        active: currentUrl.value.startsWith('/admin/harga-musiman'),
        icon: Tag,
    },
    {
        name: 'Pesanan Masuk',
        href: '/admin/transaksi',
        active: currentUrl.value.startsWith('/admin/transaksi'),
        icon: FileText,
    },
    {
        name: 'Audit Trail',
        href: '/admin/audit-logs',
        active: currentUrl.value.startsWith('/admin/audit-logs'),
        icon: Activity,
    },
    {
        name: 'Laporan',
        href: '/admin/laporan',
        active: currentUrl.value.startsWith('/admin/laporan'),
        icon: BarChart3,
    },
];

const userNav = [
    {
        name: 'Katalog & Reservasi',
        href: '/transaksi/create',
        active: currentUrl.value === '/transaksi/create',
        icon: PlusCircle,
    },
    {
        name: 'Pesanan & E-Voucher',
        href: '/transaksi',
        active: currentUrl.value === '/transaksi',
        icon: Clock,
    },
];

const navigation = computed(() => (isAdmin.value ? adminNav : userNav));

function logout() {
    router.post('/logout');
}
</script>

<template>
    <div class="min-h-screen bg-[#070709] text-neutral-100 flex flex-col font-sans selection:bg-red-600 selection:text-white">
        <Toast />

        <!-- Navigation Header -->
        <header class="sticky top-0 z-40 bg-[#070709]/90 backdrop-blur-md border-b border-neutral-800 transition-all">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <!-- Logo & Brand -->
                    <div class="flex items-center gap-8">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-7 bg-red-600 rounded-full hidden sm:block shadow-sm shadow-red-600" />
                            <Link
                                href="/"
                                class="flex items-center gap-2.5 group"
                            >
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-neutral-800 to-neutral-900 border border-neutral-700/60 flex items-center justify-center text-red-500 group-hover:scale-105 transition duration-200 shadow-md">
                                    <Car class="w-5 h-5" />
                                </div>
                                <div>
                                    <span class="text-base sm:text-lg font-black tracking-wider text-white uppercase flex items-center gap-1.5">
                                        QUANTUM <span class="text-red-600">STREAMLINE</span>
                                    </span>
                                    <span class="text-[9px] font-bold tracking-widest uppercase text-neutral-400 block -mt-1">
                                        Executive Rentals
                                    </span>
                                </div>
                            </Link>
                        </div>

                        <!-- Desktop Nav Links -->
                        <nav class="hidden md:flex items-center gap-1.5">
                            <Link
                                v-for="item in navigation"
                                :key="item.name"
                                :href="item.href"
                                :class="[
                                    'flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-150',
                                    item.active
                                        ? 'bg-neutral-800/80 text-white border border-neutral-700 shadow-xs'
                                        : 'text-neutral-400 hover:text-white hover:bg-neutral-850',
                                ]"
                            >
                                <component
                                    :is="item.icon"
                                    class="w-4 h-4"
                                    :class="item.active ? 'text-red-500' : 'text-neutral-500'"
                                />
                                {{ item.name }}
                            </Link>
                        </nav>
                    </div>

                    <!-- Right Controls: Language Switcher, Role Badge, Profile -->
                    <div class="flex items-center gap-3">
                        <!-- Language Switcher -->
                        <button
                            type="button"
                            @click="setLanguage(currentLang === 'id' ? 'en' : 'id')"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-neutral-900 border border-neutral-800 text-xs font-bold text-neutral-300 hover:text-white hover:border-neutral-700 transition"
                            title="Switch Language"
                        >
                            <Globe class="w-3.5 h-3.5 text-red-500" />
                            <span class="uppercase tracking-wider text-[11px]">{{ currentLang }}</span>
                        </button>

                        <div class="hidden sm:flex items-center gap-2 mr-1">
                            <Badge :status="user.role || 'user'" size="sm" />
                        </div>

                        <!-- Profile Dropdown -->
                        <div class="relative">
                            <button
                                @click="profileDropdownOpen = !profileDropdownOpen"
                                type="button"
                                class="flex items-center gap-2.5 p-1.5 rounded-full hover:bg-neutral-800/70 transition border border-neutral-800"
                            >
                                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-red-600 to-rose-700 flex items-center justify-center text-white font-bold text-xs uppercase shadow-sm">
                                    {{ (user.name || 'U').charAt(0) }}
                                </div>
                                <span class="hidden md:block text-xs font-bold text-neutral-200 pr-1 max-w-[130px] truncate">
                                    {{ user.name }}
                                </span>
                                <ChevronDown class="w-3 h-3 text-neutral-400 hidden md:block" />
                            </button>

                            <!-- Backdrop for dropdown -->
                            <div
                                v-if="profileDropdownOpen"
                                @click="profileDropdownOpen = false"
                                class="fixed inset-0 z-10"
                            />

                            <!-- Dropdown Menu -->
                            <transition
                                enter-active-class="transition ease-out duration-100"
                                enter-from-class="transform opacity-0 scale-95"
                                enter-to-class="transform opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-75"
                                leave-from-class="transform opacity-100 scale-100"
                                leave-to-class="transform opacity-0 scale-95"
                            >
                                <div
                                    v-if="profileDropdownOpen"
                                    class="absolute right-0 mt-2 w-56 rounded-2xl bg-[#121318] border border-neutral-800 shadow-2xl p-1.5 z-20 divide-y divide-neutral-800/80"
                                >
                                    <div class="px-3 py-2.5">
                                        <p class="text-xs font-extrabold text-white truncate">
                                            {{ user.name }}
                                        </p>
                                        <p class="text-[11px] text-neutral-400 truncate">
                                            {{ user.email }}
                                        </p>
                                    </div>
                                    <div class="py-1">
                                        <Link
                                            href="/profile"
                                            @click="profileDropdownOpen = false"
                                            class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-neutral-300 hover:text-white hover:bg-neutral-800/60 rounded-xl transition"
                                        >
                                            <User class="w-3.5 h-3.5 text-neutral-400" />
                                            Pengaturan Profil
                                        </Link>
                                    </div>
                                    <div class="py-1">
                                        <button
                                            @click="logout"
                                            type="button"
                                            class="w-full flex items-center gap-2 px-3 py-2 text-xs font-semibold text-red-400 hover:bg-red-950/40 hover:text-red-300 rounded-xl transition text-left cursor-pointer"
                                        >
                                            <LogOut class="w-3.5 h-3.5 text-red-500" />
                                            Keluar (Logout)
                                        </button>
                                    </div>
                                </div>
                            </transition>
                        </div>

                        <!-- Mobile Hamburger Button -->
                        <button
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            type="button"
                            class="md:hidden p-2 rounded-xl text-neutral-400 hover:text-white hover:bg-neutral-800 transition"
                        >
                            <Menu v-if="!mobileMenuOpen" class="w-6 h-6" />
                            <X v-else class="w-6 h-6" />
                        </button>
                    </div>
                </div>

                <!-- Mobile Menu Drawer -->
                <div
                    v-if="mobileMenuOpen"
                    class="md:hidden py-3 border-t border-neutral-800 space-y-1"
                >
                    <Link
                        v-for="item in navigation"
                        :key="item.name"
                        :href="item.href"
                        @click="mobileMenuOpen = false"
                        :class="[
                            'flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition',
                            item.active
                                ? 'bg-neutral-800 text-white'
                                : 'text-neutral-400 hover:bg-neutral-850 hover:text-white',
                        ]"
                    >
                        <component :is="item.icon" class="w-4 h-4 text-red-500" />
                        {{ item.name }}
                    </Link>
                </div>
            </div>
        </header>

        <!-- Main Page Content -->
        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-[#070709] border-t border-neutral-800/90 py-6 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-neutral-500">
                <div class="flex items-center gap-2">
                    <div class="w-1.5 h-4 bg-red-600 rounded-full" />
                    <span class="text-neutral-400">&copy; {{ new Date().getFullYear() }} <strong class="text-white">QUANTUM STREAMLINE</strong>. Executive Vehicle Fleet.</span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="inline-flex items-center gap-1.5 text-neutral-400 font-medium">
                        <Sparkles class="w-3.5 h-3.5 text-red-500" /> Vue 3 + Inertia E-Commerce
                    </span>
                    <span class="text-neutral-600">|</span>
                    <span class="text-neutral-400">UU PDP Protected</span>
                </div>
            </div>
        </footer>
    </div>
</template>
