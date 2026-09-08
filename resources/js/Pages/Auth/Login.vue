<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import {
    Car,
    Eye,
    EyeOff,
    Lock,
    Mail,
    ArrowRight,
    ShieldCheck,
    KeyRound,
    UserCheck,
} from 'lucide-vue-next';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const showPassword = ref(false);

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

function submit() {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
}

function fillDemo(email, pass) {
    form.email = email;
    form.password = pass;
}
</script>

<template>
    <Head title="Masuk ke Akun Anda - QUANTUM STREAMLINE" />

    <div class="min-h-screen w-full bg-[#0a0a0a] text-white flex flex-col lg:flex-row font-sans selection:bg-[#e63946] selection:text-white">
        <!-- ========================================================
            SISI KIRI: Form Login (~45% lebar layar desktop)
        ======================================================== -->
        <div class="w-full lg:w-[45%] min-h-screen flex flex-col justify-between p-6 sm:p-10 lg:p-14 z-10">
            <!-- LOGO: QUANTUM STREAMLINE -->
            <div class="flex items-center gap-3">
                <Link href="/" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-[#141414] border border-[#2a2a2a] flex items-center justify-center shadow-lg shadow-black/50 group-hover:border-[#e63946]/60 transition">
                        <Car class="w-5 h-5 text-[#ff4d4d]" />
                    </div>
                    <div>
                        <span class="text-base sm:text-lg font-black tracking-wider uppercase text-white block">
                            QUANTUM <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#e63946] to-[#ff4d4d]">STREAMLINE</span>
                        </span>
                        <span class="text-[9px] font-bold tracking-widest uppercase text-neutral-400 block -mt-1">
                            Luxury Car Rental
                        </span>
                    </div>
                </Link>
            </div>

            <!-- FORM WRAPPER -->
            <div class="my-auto py-8 max-w-md w-full mx-auto lg:mx-0">
                <!-- HEADER FORM -->
                <div class="mb-8">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#1a1a1a] border border-[#333333] text-[11px] font-bold text-neutral-300 uppercase tracking-widest mb-3">
                        <span class="w-2 h-2 rounded-full bg-[#e63946] animate-pulse" />
                        Member Access
                    </div>
                    
                    <h1 class="text-3xl sm:text-4xl font-black uppercase tracking-tight text-white leading-tight">
                        MASUK KE AKUN ANDA
                    </h1>
                    <p class="text-xs sm:text-sm text-neutral-400 mt-2 font-normal leading-relaxed">
                        Selamat datang kembali. Masuk untuk mengelola reservasi dan menikmati armada mobil mewah kami.
                    </p>
                </div>

                <div
                    v-if="status"
                    class="mb-4 text-xs font-semibold text-emerald-400 bg-emerald-950/60 p-3 rounded-xl border border-emerald-500/30"
                >
                    {{ status }}
                </div>

                <!-- FORM -->
                <form @submit.prevent="submit" class="space-y-5">
                    <!-- INPUT: EMAIL / NOMOR HP -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-2">
                            Email atau Nomor HP
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-neutral-500">
                                <Mail class="w-4 h-4" />
                            </div>
                            <input
                                v-model="form.email"
                                type="text"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="nama@email.com atau 081234567890"
                                class="w-full bg-[#1a1a1a] border border-[#333333] rounded-[14px] pl-11 pr-4 py-3.5 text-xs sm:text-sm text-white placeholder:text-neutral-500 focus:outline-none focus:border-[#e63946] focus:ring-1 focus:ring-[#e63946] transition-all duration-200"
                            />
                        </div>
                        <p v-if="form.errors.email" class="text-xs text-[#ff4d4d] font-medium mt-1">
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <!-- INPUT: PASSWORD -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300">
                                Kata Sandi
                            </label>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-neutral-500">
                                <Lock class="w-4 h-4" />
                            </div>
                            <input
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan kata sandi Anda"
                                class="w-full bg-[#1a1a1a] border border-[#333333] rounded-[14px] pl-11 pr-11 py-3.5 text-xs sm:text-sm text-white placeholder:text-neutral-500 focus:outline-none focus:border-[#e63946] focus:ring-1 focus:ring-[#e63946] transition-all duration-200"
                            />
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-neutral-400 hover:text-white transition-colors cursor-pointer"
                                title="Lihat / Sembunyikan Sandi"
                            >
                                <EyeOff v-if="showPassword" class="w-4 h-4" />
                                <Eye v-else class="w-4 h-4" />
                            </button>
                        </div>
                        <p v-if="form.errors.password" class="text-xs text-[#ff4d4d] font-medium mt-1">
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <!-- CHECKBOX INGAT SAYA + LINK LUPA PASSWORD -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2.5 cursor-pointer select-none">
                            <input
                                v-model="form.remember"
                                type="checkbox"
                                class="w-4 h-4 rounded text-[#e63946] bg-[#1a1a1a] border-[#333333] focus:ring-[#e63946] focus:ring-offset-0 focus:outline-none transition cursor-pointer"
                            />
                            <span class="text-xs text-neutral-400 hover:text-neutral-300 transition-colors">
                                Ingat saya
                            </span>
                        </label>

                        <Link
                            v-if="canResetPassword"
                            href="/forgot-password"
                            class="text-xs font-bold text-[#ff4d4d] hover:text-[#ff6666] transition-colors"
                        >
                            Lupa password?
                        </Link>
                    </div>

                    <!-- TOMBOL UTAMA: MASUK (PILL SHAPED) -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full rounded-full py-4 px-8 bg-gradient-to-r from-[#e63946] to-[#ff4d4d] hover:from-[#ff4d4d] hover:to-[#ff6666] text-white text-xs sm:text-sm font-black uppercase tracking-wider shadow-lg shadow-[#e63946]/30 hover:shadow-xl hover:shadow-[#e63946]/45 hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-60"
                        >
                            <span v-if="form.processing" class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin" />
                            <template v-else>
                                <span>MASUK</span>
                                <ArrowRight class="w-4 h-4" />
                            </template>
                        </button>
                    </div>

                    <!-- SEPARATOR "ATAU" -->
                    <div class="relative flex items-center justify-center py-2">
                        <div class="border-t border-[#333333] w-full" />
                        <span class="bg-[#0a0a0a] px-4 text-[11px] font-bold uppercase tracking-wider text-neutral-400">
                            atau
                        </span>
                        <div class="border-t border-[#333333] w-full" />
                    </div>

                    <!-- TOMBOL SOSIAL: GOOGLE (OUTLINE STYLE) -->
                    <button
                        type="button"
                        @click="fillDemo('userbiasa@gmail.com', 'password')"
                        class="w-full rounded-[14px] py-3.5 px-4 bg-transparent border border-[#333333] hover:border-neutral-500 hover:bg-[#1a1a1a] text-white text-xs sm:text-sm font-bold flex items-center justify-center gap-3 transition-all duration-200 cursor-pointer"
                    >
                        <svg class="w-4 h-4" viewBox="0 0 24 24">
                            <path fill="#EA4335" d="M12 5c1.6 0 3 .6 4.1 1.6l3.1-3.1C17.3 1.7 14.8 1 12 1 7.5 1 3.7 3.6 1.9 7.3l3.7 2.9C6.5 7.3 9 5 12 5z" />
                            <path fill="#4285F4" d="M23.5 12.3c0-.8-.1-1.6-.2-2.3H12v4.6h6.5c-.3 1.5-1.1 2.8-2.4 3.7l3.7 2.9c2.2-2 3.7-5 3.7-8.9z" />
                            <path fill="#FBBC05" d="M5.6 14.8c-.2-.7-.4-1.5-.4-2.8s.2-2.1.4-2.8L1.9 6.3C.7 8.7 0 10.8 0 12s.7 3.3 1.9 5.7l3.7-2.9z" />
                            <path fill="#34A853" d="M12 23c3.2 0 6-1.1 8-3l-3.7-2.9c-1.1.7-2.5 1.2-4.3 1.2-3 0-5.5-2.3-6.4-5.2L1.9 16c1.8 3.7 5.6 7 10.1 7z" />
                        </svg>
                        <span>Masuk dengan Google</span>
                    </button>
                </form>

                <!-- DEMO ACCOUNTS QUICK-FILL (MEMUDAHKAN TESTING) -->
                <div class="mt-6 p-3.5 rounded-2xl bg-[#141414] border border-[#2a2a2a]">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-400 flex items-center gap-1.5 mb-2">
                        <KeyRound class="w-3.5 h-3.5 text-[#ff4d4d]" />
                        Akun Uji Coba Cepat
                    </span>
                    <div class="grid grid-cols-2 gap-2 text-left">
                        <button
                            type="button"
                            @click="fillDemo('adminrental@gmail.com', 'password')"
                            class="p-2 rounded-xl bg-[#1a1a1a] hover:bg-neutral-800 border border-[#333333] transition cursor-pointer text-left"
                        >
                            <span class="text-[10px] font-bold text-white block">Admin</span>
                            <span class="text-[9px] text-neutral-400 block truncate">adminrental@gmail.com</span>
                        </button>
                        <button
                            type="button"
                            @click="fillDemo('userbiasa@gmail.com', 'password')"
                            class="p-2 rounded-xl bg-[#1a1a1a] hover:bg-neutral-800 border border-[#333333] transition cursor-pointer text-left"
                        >
                            <span class="text-[10px] font-bold text-white block">Pelanggan</span>
                            <span class="text-[9px] text-neutral-400 block truncate">userbiasa@gmail.com</span>
                        </button>
                    </div>
                </div>

                <!-- TEKS BAWAH: BELUM PUNYA AKUN -->
                <div class="mt-6 text-center">
                    <p class="text-xs sm:text-sm text-neutral-400">
                        Belum punya akun?
                        <Link
                            href="/register"
                            class="font-bold text-[#ff4d4d] hover:text-[#ff6666] hover:underline underline-offset-4 transition-colors"
                        >
                            Daftar sekarang
                        </Link>
                    </p>
                </div>
            </div>

            <!-- FOOTER INFORMASI KEAMANAN -->
            <div class="pt-4 border-t border-[#1a1a1a] flex items-center justify-between text-[11px] text-neutral-400">
                <div class="flex items-center gap-1.5">
                    <ShieldCheck class="w-3.5 h-3.5 text-[#ff4d4d]" />
                    <span>Enkripsi 256-bit SSL</span>
                </div>
                <span>&copy; {{ new Date().getFullYear() }} QUANTUM STREAMLINE</span>
            </div>
        </div>

        <!-- ========================================================
            SISI KANAN: Visual Mobil & Overlay (~55% lebar layar)
        ======================================================== -->
        <div class="hidden lg:block lg:w-[55%] relative min-h-screen overflow-hidden bg-black">
            <!-- GAMBAR MOBIL: Dark car with glowing headlights -->
            <img
                src="/images/audi_front_dark.jpg"
                alt="Quantum Streamline Luxury Car"
                class="absolute inset-0 w-full h-full object-cover object-center scale-105 hover:scale-100 transition-transform duration-1000"
            />

            <!-- OVERLAY 1: Vignette & gradien hitam pekat ke arah form -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#0a0a0a] via-transparent to-transparent w-2/5 z-10" />

            <!-- OVERLAY 2: Gradien sudut merah-oranye tipis (#e63946 ke #ff4d4d) -->
            <div class="absolute -top-32 -right-32 w-96 h-96 bg-gradient-to-br from-[#e63946]/30 via-[#ff4d4d]/15 to-transparent rounded-full blur-3xl pointer-events-none z-10" />
            <div class="absolute -bottom-24 -right-24 w-80 h-80 bg-gradient-to-tl from-[#e63946]/25 to-transparent rounded-full blur-2xl pointer-events-none z-10" />

            <!-- OVERLAY 3: Dark contrast protection di bagian bawah -->
            <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/90 via-black/40 to-transparent z-10" />

            <!-- KONTEN OVERLAY BAWAH (TAGLINE EKSEKUTIF) -->
            <div class="absolute bottom-12 left-12 right-12 z-20">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-[11px] font-bold text-white uppercase tracking-widest mb-3">
                    Pure Precision Driving
                </div>
                <h2 class="text-2xl xl:text-3xl font-black uppercase text-white tracking-wider drop-shadow-md">
                    THE PINNACLE OF LUXURY & PERFORMANCE
                </h2>
                <p class="text-xs text-neutral-300 mt-2 max-w-lg leading-relaxed drop-shadow">
                    Nikmati armada kendaraan eksklusif dengan layanan supir profesional atau kemudahan lepas kunci mandiri.
                </p>
            </div>
        </div>
    </div>
</template>
