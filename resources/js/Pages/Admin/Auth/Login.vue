<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import {
    Shield,
    Lock,
    Mail,
    ArrowRight,
    Eye,
    EyeOff,
    Car,
    AlertCircle,
} from 'lucide-vue-next';

const showPassword = ref(false);

const form = useForm({
    email: 'adminrental@gmail.com',
    password: 'password',
    remember: false,
});

function submit() {
    form.post('/admin/login', {
        onFinish: () => form.reset('password'),
    });
}
</script>

<template>
    <Head title="Login Portal Administrator - QUANTUM STREAMLINE" />

    <div class="min-h-screen w-full bg-[#070709] text-white flex items-center justify-center p-4 relative overflow-hidden font-sans selection:bg-[#e63946] selection:text-white">
        <!-- Background Ambient Glow -->
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-red-600/15 rounded-full blur-3xl pointer-events-none" />
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-rose-600/10 rounded-full blur-3xl pointer-events-none" />

        <div class="max-w-md w-full z-10">
            <!-- Brand Logo -->
            <div class="text-center mb-8">
                <Link href="/" class="inline-flex items-center gap-3 group">
                    <div class="w-12 h-12 rounded-2xl bg-neutral-900 border border-neutral-800 flex items-center justify-center text-red-500 shadow-xl group-hover:border-red-500/50 transition">
                        <Car class="w-6 h-6" />
                    </div>
                    <div class="text-left">
                        <span class="text-lg font-black tracking-wider uppercase text-white block">
                            QUANTUM <span class="text-red-500">STREAMLINE</span>
                        </span>
                        <span class="text-[10px] font-bold tracking-widest uppercase text-neutral-400 block -mt-1">
                            Operations CMS
                        </span>
                    </div>
                </Link>
            </div>

            <!-- Login Card -->
            <div class="bg-[#0f1015] border border-neutral-800 rounded-3xl p-7 sm:p-9 shadow-2xl space-y-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-950/70 border border-red-500/30 text-[10px] font-black uppercase tracking-widest text-red-400 mb-2">
                        <Shield class="w-3.5 h-3.5" />
                        Restricted Operations Portal
                    </div>
                    <h1 class="text-2xl font-black uppercase tracking-tight text-white">
                        Masuk Administrator
                    </h1>
                    <p class="text-xs text-neutral-400 mt-1 leading-relaxed">
                        Akses khusus tim operasional untuk manajemen armada mobil, kalender ketersediaan, dan audit transaksi.
                    </p>
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1.5">
                            Email Administrator
                        </label>
                        <div class="relative">
                            <Mail class="w-4 h-4 text-neutral-500 absolute left-3.5 top-1/2 -translate-y-1/2" />
                            <input
                                v-model="form.email"
                                type="email"
                                required
                                autofocus
                                placeholder="admin@quantumstreamline.com"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-neutral-800 bg-[#070709] text-xs sm:text-sm text-white focus:outline-none focus:border-red-600 focus:ring-1 focus:ring-red-600 transition"
                            />
                        </div>
                        <p v-if="form.errors.email" class="text-xs text-red-500 font-medium mt-1">
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1.5">
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <Lock class="w-4 h-4 text-neutral-500 absolute left-3.5 top-1/2 -translate-y-1/2" />
                            <input
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                required
                                placeholder="••••••••"
                                class="w-full pl-10 pr-11 py-3 rounded-xl border border-neutral-800 bg-[#070709] text-xs sm:text-sm text-white focus:outline-none focus:border-red-600 focus:ring-1 focus:ring-red-600 transition"
                            />
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-neutral-400 hover:text-white transition cursor-pointer"
                            >
                                <EyeOff v-if="showPassword" class="w-4 h-4" />
                                <Eye v-else class="w-4 h-4" />
                            </button>
                        </div>
                        <p v-if="form.errors.password" class="text-xs text-red-500 font-medium mt-1">
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <!-- Remember -->
                    <div class="flex items-center justify-between text-xs">
                        <label class="flex items-center gap-2 text-neutral-400 cursor-pointer">
                            <input
                                v-model="form.remember"
                                type="checkbox"
                                class="w-4 h-4 rounded text-red-600 bg-neutral-900 border-neutral-700 focus:ring-red-500"
                            />
                            <span>Ingat sesi admin</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-full bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 active:scale-98 text-white text-xs font-black uppercase tracking-wider shadow-xl shadow-red-600/30 transition duration-200 cursor-pointer disabled:opacity-50"
                    >
                        <span v-if="form.processing">Memvalidasi Akses...</span>
                        <template v-else>
                            <span>Masuk ke Dashboard CMS</span>
                            <ArrowRight class="w-4 h-4" />
                        </template>
                    </button>
                </form>

                <div class="p-3.5 rounded-xl bg-neutral-900/80 border border-neutral-800 text-[11px] text-neutral-400 leading-relaxed flex items-center gap-2">
                    <AlertCircle class="w-4 h-4 text-red-400 shrink-0" />
                    <span>Setiap aktivitas login dan perubahan data di portal ini tercatat secara otomatis pada sistem <strong>Audit Trail</strong>.</span>
                </div>
            </div>

            <div class="mt-6 text-center text-xs text-neutral-400">
                <Link href="/" class="hover:text-white transition">&larr; Kembali ke Website Publik</Link>
            </div>
        </div>
    </div>
</template>
