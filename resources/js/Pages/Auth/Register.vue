<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import {
    Car,
    User,
    Mail,
    Phone,
    Lock,
    Eye,
    EyeOff,
    ArrowRight,
    ShieldCheck,
} from 'lucide-vue-next';

const showPassword = ref(false);
const showConfirmPassword = ref(false);
const agreeTerms = ref(false);

const form = useForm({
    name: '',
    email: '',
    phone: '',
    password: '',
    password_confirmation: '',
});

function submit() {
    if (!agreeTerms.value) {
        alert('Mohon setujui Syarat & Ketentuan serta Kebijakan Privasi.');
        return;
    }
    form.post('/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
}
</script>

<template>
    <Head title="Buat Akun Baru - QUANTUM STREAMLINE" />

    <div class="min-h-screen w-full bg-[#0a0a0a] text-white flex flex-col lg:flex-row font-sans selection:bg-[#e63946] selection:text-white">
        <!-- ========================================================
            SISI KIRI: Form Pendaftaran (~45% lebar layar desktop)
        ======================================================== -->
        <div class="w-full lg:w-[45%] min-h-screen flex flex-col justify-between p-6 sm:p-10 lg:p-14 z-10 overflow-y-auto">
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

            <!-- FORM CONTAINER -->
            <div class="my-auto py-6 max-w-md w-full mx-auto lg:mx-0">
                <!-- HEADER FORM -->
                <div class="mb-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#1a1a1a] border border-[#333333] text-[11px] font-bold text-neutral-300 uppercase tracking-widest mb-3">
                        <span class="w-2 h-2 rounded-full bg-[#e63946] animate-pulse" />
                        New Member Registration
                    </div>
                    
                    <h1 class="text-3xl sm:text-4xl font-black uppercase tracking-tight text-white leading-tight">
                        BUAT AKUN BARU
                    </h1>
                    <p class="text-xs sm:text-sm text-neutral-400 mt-2 font-normal leading-relaxed">
                        Daftarkan diri Anda untuk menikmati akses eksklusif ke seluruh koleksi armada mobil mewah kami.
                    </p>
                </div>

                <!-- FORM -->
                <form @submit.prevent="submit" class="space-y-4">
                    <!-- INPUT: NAMA LENGKAP -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1.5">
                            Nama Lengkap
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-neutral-500">
                                <User class="w-4 h-4" />
                            </div>
                            <input
                                v-model="form.name"
                                type="text"
                                required
                                autofocus
                                autocomplete="name"
                                placeholder="Nama Lengkap Anda"
                                class="w-full bg-[#1a1a1a] border border-[#333333] rounded-[14px] pl-11 pr-4 py-3 text-xs sm:text-sm text-white placeholder:text-neutral-500 focus:outline-none focus:border-[#e63946] focus:ring-1 focus:ring-[#e63946] transition-all duration-200"
                            />
                        </div>
                        <p v-if="form.errors.name" class="text-xs text-[#ff4d4d] font-medium mt-1">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- INPUT: EMAIL -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1.5">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-neutral-500">
                                <Mail class="w-4 h-4" />
                            </div>
                            <input
                                v-model="form.email"
                                type="email"
                                required
                                autocomplete="username"
                                placeholder="nama@email.com"
                                class="w-full bg-[#1a1a1a] border border-[#333333] rounded-[14px] pl-11 pr-4 py-3 text-xs sm:text-sm text-white placeholder:text-neutral-500 focus:outline-none focus:border-[#e63946] focus:ring-1 focus:ring-[#e63946] transition-all duration-200"
                            />
                        </div>
                        <p v-if="form.errors.email" class="text-xs text-[#ff4d4d] font-medium mt-1">
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <!-- INPUT: NOMOR HP (Opsional) -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1.5">
                            Nomor HP / WhatsApp
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-neutral-500">
                                <Phone class="w-4 h-4" />
                            </div>
                            <input
                                v-model="form.phone"
                                type="tel"
                                placeholder="081234567890"
                                class="w-full bg-[#1a1a1a] border border-[#333333] rounded-[14px] pl-11 pr-4 py-3 text-xs sm:text-sm text-white placeholder:text-neutral-500 focus:outline-none focus:border-[#e63946] focus:ring-1 focus:ring-[#e63946] transition-all duration-200"
                            />
                        </div>
                    </div>

                    <!-- INPUT: KATA SANDI -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1.5">
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-neutral-500">
                                <Lock class="w-4 h-4" />
                            </div>
                            <input
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                required
                                autocomplete="new-password"
                                placeholder="Minimal 8 karakter"
                                class="w-full bg-[#1a1a1a] border border-[#333333] rounded-[14px] pl-11 pr-11 py-3 text-xs sm:text-sm text-white placeholder:text-neutral-500 focus:outline-none focus:border-[#e63946] focus:ring-1 focus:ring-[#e63946] transition-all duration-200"
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

                    <!-- INPUT: KONFIRMASI KATA SANDI -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1.5">
                            Konfirmasi Kata Sandi
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-neutral-500">
                                <Lock class="w-4 h-4" />
                            </div>
                            <input
                                v-model="form.password_confirmation"
                                :type="showConfirmPassword ? 'text' : 'password'"
                                required
                                autocomplete="new-password"
                                placeholder="Ulangi kata sandi Anda"
                                class="w-full bg-[#1a1a1a] border border-[#333333] rounded-[14px] pl-11 pr-11 py-3 text-xs sm:text-sm text-white placeholder:text-neutral-500 focus:outline-none focus:border-[#e63946] focus:ring-1 focus:ring-[#e63946] transition-all duration-200"
                            />
                            <button
                                type="button"
                                @click="showConfirmPassword = !showConfirmPassword"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-neutral-400 hover:text-white transition-colors cursor-pointer"
                                title="Lihat / Sembunyikan Sandi"
                            >
                                <EyeOff v-if="showConfirmPassword" class="w-4 h-4" />
                                <Eye v-else class="w-4 h-4" />
                            </button>
                        </div>
                        <p v-if="form.errors.password_confirmation" class="text-xs text-[#ff4d4d] font-medium mt-1">
                            {{ form.errors.password_confirmation }}
                        </p>
                    </div>

                    <!-- CHECKBOX: SYARAT & KETENTUAN -->
                    <div class="pt-1">
                        <label class="flex items-start gap-2.5 cursor-pointer select-none">
                            <input
                                v-model="agreeTerms"
                                type="checkbox"
                                required
                                class="w-4 h-4 mt-0.5 rounded text-[#e63946] bg-[#1a1a1a] border-[#333333] focus:ring-[#e63946] focus:ring-offset-0 focus:outline-none transition cursor-pointer"
                            />
                            <span class="text-xs text-neutral-400 leading-relaxed">
                                Saya menyetujui
                                <a href="#" class="text-white hover:text-[#ff4d4d] underline underline-offset-2">Syarat & Ketentuan</a>
                                serta
                                <a href="#" class="text-white hover:text-[#ff4d4d] underline underline-offset-2">Kebijakan Privasi</a>
                                QUANTUM STREAMLINE.
                            </span>
                        </label>
                    </div>

                    <!-- TOMBOL UTAMA: DAFTAR (PILL SHAPED) -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full rounded-full py-4 px-8 bg-gradient-to-r from-[#e63946] to-[#ff4d4d] hover:from-[#ff4d4d] hover:to-[#ff6666] text-white text-xs sm:text-sm font-black uppercase tracking-wider shadow-lg shadow-[#e63946]/30 hover:shadow-xl hover:shadow-[#e63946]/45 hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-60"
                        >
                            <span v-if="form.processing" class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin" />
                            <template v-else>
                                <span>DAFTAR</span>
                                <ArrowRight class="w-4 h-4" />
                            </template>
                        </button>
                    </div>
                </form>

                <!-- TEKS BAWAH: SUDAH PUNYA AKUN -->
                <div class="mt-6 text-center">
                    <p class="text-xs sm:text-sm text-neutral-400">
                        Sudah punya akun?
                        <Link
                            href="/login"
                            class="font-bold text-[#ff4d4d] hover:text-[#ff6666] hover:underline underline-offset-4 transition-colors"
                        >
                            Masuk di sini
                        </Link>
                    </p>
                </div>
            </div>

            <!-- FOOTER INFORMASI KEAMANAN -->
            <div class="pt-4 border-t border-[#1a1a1a] flex items-center justify-between text-[11px] text-neutral-400">
                <div class="flex items-center gap-1.5">
                    <ShieldCheck class="w-3.5 h-3.5 text-[#ff4d4d]" />
                    <span>Terlindungi UU PDP No. 27/2022</span>
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
                alt="Quantum Streamline Luxury Fleet"
                class="absolute inset-0 w-full h-full object-cover object-center scale-105 hover:scale-100 transition-transform duration-1000"
            />

            <!-- OVERLAY 1: Vignette ke arah kiri (form) -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#0a0a0a] via-transparent to-transparent w-2/5 z-10" />

            <!-- OVERLAY 2: Gradien sudut merah-oranye tipis (#e63946 ke #ff4d4d) -->
            <div class="absolute -top-32 -right-32 w-96 h-96 bg-gradient-to-br from-[#e63946]/30 via-[#ff4d4d]/15 to-transparent rounded-full blur-3xl pointer-events-none z-10" />
            <div class="absolute -bottom-24 -right-24 w-80 h-80 bg-gradient-to-tl from-[#e63946]/25 to-transparent rounded-full blur-2xl pointer-events-none z-10" />

            <!-- OVERLAY 3: Dark contrast protection di bagian bawah -->
            <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/90 via-black/40 to-transparent z-10" />

            <!-- KONTEN OVERLAY BAWAH -->
            <div class="absolute bottom-12 left-12 right-12 z-20">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-[11px] font-bold text-white uppercase tracking-widest mb-3">
                    VIP Membership Perks
                </div>
                <h2 class="text-2xl xl:text-3xl font-black uppercase text-white tracking-wider drop-shadow-md">
                    EXPERIENCE THE THRILL OF PREMIUM MOBILITY
                </h2>
                <p class="text-xs text-neutral-300 mt-2 max-w-lg leading-relaxed drop-shadow">
                    Nikmati proses reservasi otomatis tanpa hambatan, asuransi komprehensif, dan armada terawat berstandar eksekutif.
                </p>
            </div>
        </div>
    </div>
</template>
