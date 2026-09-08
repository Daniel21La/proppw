<script setup>
import { Head, useForm, router } from '@inertiajs/vue3';
import { User, Mail, Lock, Trash2, CheckCircle2, ShieldAlert, Sparkles } from 'lucide-vue-next';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    status: String,
});

const profileForm = useForm({
    name: props.user.name,
    email: props.user.email,
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

function updateProfile() {
    profileForm.patch('/profile', {
        preserveScroll: true,
    });
}

function updatePassword() {
    passwordForm.put('/password', {
        preserveScroll: true,
        onSuccess: () => passwordForm.reset(),
    });
}
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Pengaturan Profil - Quantum Streamline" />

        <!-- Header -->
        <div class="max-w-4xl mx-auto mb-8">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-2 h-2 rounded-full bg-red-600 animate-pulse" />
                <span class="text-[11px] font-black tracking-widest text-red-500 uppercase">
                    Account Security & Profile
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-white uppercase tracking-tight">
                Pengaturan Profil & Keamanan
            </h1>
            <p class="text-xs sm:text-sm text-neutral-400 mt-1">
                Perbarui data akun VIP, email resmi, dan lindungi kredensial login Anda.
            </p>
        </div>

        <div class="max-w-4xl mx-auto space-y-8">
            <!-- Profile Info Form -->
            <div class="bg-[#0f1015] rounded-3xl p-6 sm:p-8 border border-neutral-800 shadow-2xl">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-neutral-800">
                    <div class="w-10 h-10 rounded-2xl bg-neutral-900 border border-neutral-800 text-red-500 flex items-center justify-center shrink-0">
                        <User class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-sm font-black uppercase tracking-wider text-white">
                            Informasi Profil
                        </h2>
                        <p class="text-xs text-neutral-400">
                            Ubah nama akun dan alamat email terdaftar Anda.
                        </p>
                    </div>
                </div>

                <form @submit.prevent="updateProfile" class="space-y-4 max-w-xl">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-neutral-300 mb-1.5">
                            Nama Lengkap
                        </label>
                        <input
                            v-model="profileForm.name"
                            type="text"
                            required
                            class="w-full px-4 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                        />
                        <p v-if="profileForm.errors.name" class="text-xs text-red-500 font-medium mt-1">
                            {{ profileForm.errors.name }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-neutral-300 mb-1.5">
                            Alamat Email
                        </label>
                        <input
                            v-model="profileForm.email"
                            type="email"
                            required
                            class="w-full px-4 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                        />
                        <p v-if="profileForm.errors.email" class="text-xs text-red-500 font-medium mt-1">
                            {{ profileForm.errors.email }}
                        </p>
                    </div>

                    <div class="pt-2">
                        <button
                            type="submit"
                            :disabled="profileForm.processing"
                            class="px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 active:scale-95 text-white text-xs font-black uppercase tracking-wider shadow-lg shadow-red-600/30 transition disabled:opacity-50 cursor-pointer"
                        >
                            {{ profileForm.processing ? 'Menyimpan...' : 'Simpan Profil' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Password Update Form -->
            <div class="bg-[#0f1015] rounded-3xl p-6 sm:p-8 border border-neutral-800 shadow-2xl">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-neutral-800">
                    <div class="w-10 h-10 rounded-2xl bg-neutral-900 border border-neutral-800 text-yellow-500 flex items-center justify-center shrink-0">
                        <Lock class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-sm font-black uppercase tracking-wider text-white">
                            Perbarui Kata Sandi
                        </h2>
                        <p class="text-xs text-neutral-400">
                            Pastikan kata sandi baru Anda unik dan menggunakan kombinasi yang kuat.
                        </p>
                    </div>
                </div>

                <form @submit.prevent="updatePassword" class="space-y-4 max-w-xl">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-neutral-300 mb-1.5">
                            Kata Sandi Saat Ini
                        </label>
                        <input
                            v-model="passwordForm.current_password"
                            type="password"
                            required
                            class="w-full px-4 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                        />
                        <p v-if="passwordForm.errors.current_password" class="text-xs text-red-500 font-medium mt-1">
                            {{ passwordForm.errors.current_password }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-neutral-300 mb-1.5">
                            Kata Sandi Baru
                        </label>
                        <input
                            v-model="passwordForm.password"
                            type="password"
                            required
                            class="w-full px-4 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                        />
                        <p v-if="passwordForm.errors.password" class="text-xs text-red-500 font-medium mt-1">
                            {{ passwordForm.errors.password }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-neutral-300 mb-1.5">
                            Konfirmasi Sandi Baru
                        </label>
                        <input
                            v-model="passwordForm.password_confirmation"
                            type="password"
                            required
                            class="w-full px-4 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                        />
                        <p v-if="passwordForm.errors.password_confirmation" class="text-xs text-red-500 font-medium mt-1">
                            {{ passwordForm.errors.password_confirmation }}
                        </p>
                    </div>

                    <div class="pt-2">
                        <button
                            type="submit"
                            :disabled="passwordForm.processing"
                            class="px-6 py-2.5 rounded-xl bg-neutral-800 hover:bg-neutral-700 text-white text-xs font-black uppercase tracking-wider border border-neutral-700 transition disabled:opacity-50 cursor-pointer"
                        >
                            {{ passwordForm.processing ? 'Menyimpan...' : 'Perbarui Kata Sandi' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
