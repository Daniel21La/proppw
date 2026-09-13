<script setup>
import { ref, watch } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import {
    Car,
    Eye,
    EyeOff,
    Lock,
    Mail,
    User,
    Phone,
    X,
    ArrowRight,
    ShieldCheck,
    KeyRound,
    UserCheck,
    Sparkles,
} from 'lucide-vue-next';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    initialMode: {
        type: String,
        default: 'login', // 'login' | 'register'
    },
});

const emit = defineEmits(['close']);

const mode = ref(props.initialMode);
const showPassword = ref(false);
const showConfirmPassword = ref(false);
const agreeTerms = ref(true);

watch(() => props.initialMode, (newVal) => {
    mode.value = newVal;
});

// Login Form
const loginForm = useForm({
    email: '',
    password: '',
    remember: true,
});

// Register Form
const registerForm = useForm({
    name: '',
    email: '',
    phone: '',
    password: '',
    password_confirmation: '',
});

function submitLogin() {
    loginForm.post('/login', {
        onSuccess: () => {
            emit('close');
        },
        onFinish: () => loginForm.reset('password'),
    });
}

function submitRegister() {
    if (!agreeTerms.value) {
        alert('Mohon setujui Syarat & Ketentuan serta Kebijakan Privasi.');
        return;
    }
    registerForm.post('/register', {
        onSuccess: () => {
            emit('close');
        },
        onFinish: () => registerForm.reset('password', 'password_confirmation'),
    });
}

function fillDemo(email, pass) {
    mode.value = 'login';
    loginForm.email = email;
    loginForm.password = pass;
}

function closeModal() {
    emit('close');
}
</script>

<template>
    <Transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0 scale-95"
        enter-to-class="opacity-100 scale-100"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-95"
    >
        <div
            v-if="show"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md overflow-y-auto"
            @click.self="closeModal"
        >
            <div
                class="relative w-full max-w-md bg-[#0d0e12] border border-neutral-800 rounded-3xl p-6 sm:p-8 shadow-2xl shadow-black/80 text-white overflow-hidden my-8"
            >
                <!-- Decorative Red Glow -->
                <div class="absolute -top-20 -right-20 w-48 h-48 bg-red-600/20 rounded-full blur-3xl pointer-events-none" />
                <div class="absolute -bottom-20 -left-20 w-48 h-48 bg-rose-600/10 rounded-full blur-3xl pointer-events-none" />

                <!-- Close Button -->
                <button
                    type="button"
                    @click="closeModal"
                    class="absolute top-5 right-5 w-9 h-9 rounded-full bg-neutral-900 border border-neutral-800 flex items-center justify-center text-neutral-400 hover:text-white hover:border-red-500 transition duration-200"
                >
                    <X class="w-5 h-5" />
                </button>

                <!-- Modal Header & Logo -->
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-neutral-900 to-neutral-800 border border-neutral-700/60 flex items-center justify-center text-red-500 shadow-md">
                        <Car class="w-5 h-5" />
                    </div>
                    <div>
                        <span class="text-sm font-black tracking-wider uppercase text-white block">
                            QUANTUM <span class="text-red-600">STREAMLINE</span>
                        </span>
                        <span class="text-[9px] font-bold tracking-widest uppercase text-neutral-400 block -mt-0.5">
                            Auth Portal
                        </span>
                    </div>
                </div>

                <!-- Tab Switcher -->
                <div class="grid grid-cols-2 p-1 mb-6 rounded-2xl bg-neutral-900 border border-neutral-800 text-xs font-bold">
                    <button
                        type="button"
                        @click="mode = 'login'"
                        :class="[
                            'py-2.5 rounded-xl transition duration-200 uppercase tracking-wider',
                            mode === 'login'
                                ? 'bg-gradient-to-r from-red-600 to-rose-600 text-white shadow-lg shadow-red-600/30'
                                : 'text-neutral-400 hover:text-white',
                        ]"
                    >
                        Masuk / Login
                    </button>
                    <button
                        type="button"
                        @click="mode = 'register'"
                        :class="[
                            'py-2.5 rounded-xl transition duration-200 uppercase tracking-wider',
                            mode === 'register'
                                ? 'bg-gradient-to-r from-red-600 to-rose-600 text-white shadow-lg shadow-red-600/30'
                                : 'text-neutral-400 hover:text-white',
                        ]"
                    >
                        Daftar / Register
                    </button>
                </div>

                <!-- LOGIN FORM -->
                <div v-if="mode === 'login'" class="space-y-4">
                    <div>
                        <h2 class="text-xl font-black uppercase text-white tracking-tight">Selamat Datang Kembali</h2>
                        <p class="text-xs text-neutral-400 mt-1">Masuk ke akun Anda untuk mengelola transaksi sewa.</p>
                    </div>

                    <!-- Errors alert -->
                    <div v-if="Object.keys(loginForm.errors).length > 0" class="p-3 rounded-xl bg-red-950/80 border border-red-500/40 text-red-300 text-xs space-y-1">
                        <p v-for="(err, key) in loginForm.errors" :key="key">{{ err }}</p>
                    </div>

                    <form @submit.prevent="submitLogin" class="space-y-4 pt-2">
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase text-neutral-300 tracking-wider mb-1.5">Email</label>
                            <div class="relative">
                                <Mail class="w-4 h-4 text-neutral-500 absolute left-3.5 top-3.5" />
                                <input
                                    v-model="loginForm.email"
                                    type="email"
                                    required
                                    placeholder="nama@email.com"
                                    class="w-full pl-10 pr-4 py-2.5 bg-neutral-900/90 border border-neutral-800 rounded-xl text-xs text-white placeholder-neutral-500 focus:border-red-500 focus:ring-1 focus:ring-red-500 transition"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-extrabold uppercase text-neutral-300 tracking-wider mb-1.5">Kata Sandi</label>
                            <div class="relative">
                                <Lock class="w-4 h-4 text-neutral-500 absolute left-3.5 top-3.5" />
                                <input
                                    v-model="loginForm.password"
                                    :type="showPassword ? 'text' : 'password'"
                                    required
                                    placeholder="••••••••"
                                    class="w-full pl-10 pr-10 py-2.5 bg-neutral-900/90 border border-neutral-800 rounded-xl text-xs text-white placeholder-neutral-500 focus:border-red-500 focus:ring-1 focus:ring-red-500 transition"
                                />
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-3.5 top-3 text-neutral-500 hover:text-white"
                                >
                                    <Eye v-if="!showPassword" class="w-4 h-4" />
                                    <EyeOff v-else class="w-4 h-4" />
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-xs pt-1">
                            <label class="flex items-center gap-2 cursor-pointer text-neutral-400">
                                <input
                                    v-model="loginForm.remember"
                                    type="checkbox"
                                    class="rounded bg-neutral-900 border-neutral-700 text-red-600 focus:ring-red-500"
                                />
                                Ingat Saya
                            </label>
                        </div>

                        <button
                            type="submit"
                            :disabled="loginForm.processing"
                            class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-red-600/30 transition transform hover:-translate-y-0.5 active:scale-95 disabled:opacity-50 flex items-center justify-center gap-2"
                        >
                            <span>{{ loginForm.processing ? 'Memproses...' : 'Masuk Sekarang' }}</span>
                            <ArrowRight class="w-4 h-4" />
                        </button>
                    </form>

                    <!-- Quick Demo Accounts -->
                    <div class="pt-4 border-t border-neutral-800/80 text-center">
                        <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-wider block mb-2">⚡ Quick Demo Login</span>
                        <div class="grid grid-cols-2 gap-2">
                            <button
                                type="button"
                                @click="fillDemo('userbiasa@gmail.com', 'password')"
                                class="py-1.5 px-3 rounded-lg bg-neutral-900 hover:bg-neutral-800 border border-neutral-800 text-[11px] font-bold text-neutral-300 hover:text-white transition flex items-center justify-center gap-1.5"
                            >
                                <UserCheck class="w-3.5 h-3.5 text-emerald-400" />
                                User Demo
                            </button>
                            <button
                                type="button"
                                @click="fillDemo('adminrental@gmail.com', 'password')"
                                class="py-1.5 px-3 rounded-lg bg-neutral-900 hover:bg-neutral-800 border border-neutral-800 text-[11px] font-bold text-neutral-300 hover:text-white transition flex items-center justify-center gap-1.5"
                            >
                                <KeyRound class="w-3.5 h-3.5 text-red-400" />
                                Admin Demo
                            </button>
                        </div>
                    </div>
                </div>

                <!-- REGISTER FORM -->
                <div v-else class="space-y-4">
                    <div>
                        <h2 class="text-xl font-black uppercase text-white tracking-tight">Buat Akun Baru</h2>
                        <p class="text-xs text-neutral-400 mt-1">Bergabunglah untuk menikmati layanan rental mobil mewah.</p>
                    </div>

                    <!-- Errors alert -->
                    <div v-if="Object.keys(registerForm.errors).length > 0" class="p-3 rounded-xl bg-red-950/80 border border-red-500/40 text-red-300 text-xs space-y-1">
                        <p v-for="(err, key) in registerForm.errors" :key="key">{{ err }}</p>
                    </div>

                    <form @submit.prevent="submitRegister" class="space-y-3.5 pt-2">
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase text-neutral-300 tracking-wider mb-1">Nama Lengkap</label>
                            <div class="relative">
                                <User class="w-4 h-4 text-neutral-500 absolute left-3.5 top-3.5" />
                                <input
                                    v-model="registerForm.name"
                                    type="text"
                                    required
                                    placeholder="Nama Sesuai KTP"
                                    class="w-full pl-10 pr-4 py-2.5 bg-neutral-900/90 border border-neutral-800 rounded-xl text-xs text-white placeholder-neutral-500 focus:border-red-500 focus:ring-1 focus:ring-red-500 transition"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-extrabold uppercase text-neutral-300 tracking-wider mb-1">Email</label>
                            <div class="relative">
                                <Mail class="w-4 h-4 text-neutral-500 absolute left-3.5 top-3.5" />
                                <input
                                    v-model="registerForm.email"
                                    type="email"
                                    required
                                    placeholder="nama@email.com"
                                    class="w-full pl-10 pr-4 py-2.5 bg-neutral-900/90 border border-neutral-800 rounded-xl text-xs text-white placeholder-neutral-500 focus:border-red-500 focus:ring-1 focus:ring-red-500 transition"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-extrabold uppercase text-neutral-300 tracking-wider mb-1">Nomor WhatsApp / Telepon</label>
                            <div class="relative">
                                <Phone class="w-4 h-4 text-neutral-500 absolute left-3.5 top-3.5" />
                                <input
                                    v-model="registerForm.phone"
                                    type="tel"
                                    required
                                    placeholder="081234567890"
                                    class="w-full pl-10 pr-4 py-2.5 bg-neutral-900/90 border border-neutral-800 rounded-xl text-xs text-white placeholder-neutral-500 focus:border-red-500 focus:ring-1 focus:ring-red-500 transition"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-extrabold uppercase text-neutral-300 tracking-wider mb-1">Kata Sandi</label>
                            <div class="relative">
                                <Lock class="w-4 h-4 text-neutral-500 absolute left-3.5 top-3.5" />
                                <input
                                    v-model="registerForm.password"
                                    :type="showPassword ? 'text' : 'password'"
                                    required
                                    placeholder="Minimal 8 karakter"
                                    class="w-full pl-10 pr-10 py-2.5 bg-neutral-900/90 border border-neutral-800 rounded-xl text-xs text-white placeholder-neutral-500 focus:border-red-500 focus:ring-1 focus:ring-red-500 transition"
                                />
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-3.5 top-3 text-neutral-500 hover:text-white"
                                >
                                    <Eye v-if="!showPassword" class="w-4 h-4" />
                                    <EyeOff v-else class="w-4 h-4" />
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-extrabold uppercase text-neutral-300 tracking-wider mb-1">Konfirmasi Kata Sandi</label>
                            <div class="relative">
                                <Lock class="w-4 h-4 text-neutral-500 absolute left-3.5 top-3.5" />
                                <input
                                    v-model="registerForm.password_confirmation"
                                    :type="showConfirmPassword ? 'text' : 'password'"
                                    required
                                    placeholder="Ulangi kata sandi"
                                    class="w-full pl-10 pr-10 py-2.5 bg-neutral-900/90 border border-neutral-800 rounded-xl text-xs text-white placeholder-neutral-500 focus:border-red-500 focus:ring-1 focus:ring-red-500 transition"
                                />
                                <button
                                    type="button"
                                    @click="showConfirmPassword = !showConfirmPassword"
                                    class="absolute right-3.5 top-3 text-neutral-500 hover:text-white"
                                >
                                    <Eye v-if="!showConfirmPassword" class="w-4 h-4" />
                                    <EyeOff v-else class="w-4 h-4" />
                                </button>
                            </div>
                        </div>

                        <div class="text-[11px] text-neutral-400 pt-1">
                            <label class="flex items-start gap-2 cursor-pointer">
                                <input
                                    v-model="agreeTerms"
                                    type="checkbox"
                                    class="mt-0.5 rounded bg-neutral-900 border-neutral-700 text-red-600 focus:ring-red-500"
                                />
                                <span>
                                    Saya menyetujui <span class="text-white font-bold">Syarat & Ketentuan</span> serta perlindungan data sesuai <span class="text-red-400 font-bold">UU PDP No. 27/2022</span>.
                                </span>
                            </label>
                        </div>

                        <button
                            type="submit"
                            :disabled="registerForm.processing"
                            class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-red-600/30 transition transform hover:-translate-y-0.5 active:scale-95 disabled:opacity-50 flex items-center justify-center gap-2 mt-2"
                        >
                            <span>{{ registerForm.processing ? 'Mendaftarkan...' : 'Daftar Akun Baru' }}</span>
                            <ArrowRight class="w-4 h-4" />
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </Transition>
</template>
