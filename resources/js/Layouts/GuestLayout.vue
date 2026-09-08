<script setup>
import { Link } from '@inertiajs/vue3';
import { Car, Sparkles } from 'lucide-vue-next';
import Toast from '@/Components/Toast.vue';
</script>

<template>
    <div class="min-h-screen bg-slate-900 flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden font-sans text-slate-100">
        <Toast />

        <!-- Background Ambient Glows -->
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none" />
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-violet-600/20 rounded-full blur-3xl pointer-events-none" />

        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center z-10">
            <Link href="/" class="inline-flex items-center gap-3 group">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-500 to-violet-500 flex items-center justify-center text-white shadow-xl shadow-indigo-500/30 group-hover:scale-105 transition duration-300">
                    <Car class="w-6 h-6" />
                </div>
                <div class="text-left">
                    <span class="text-2xl font-black tracking-tight text-white block">
                        Rental<span class="text-indigo-400">Mobil</span>
                    </span>
                    <span class="text-[11px] font-semibold tracking-wider uppercase text-slate-400 block -mt-1">
                        Modern Car Rental Platform
                    </span>
                </div>
            </Link>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md z-10 px-4 sm:px-0">
            <div class="bg-white text-slate-900 py-8 px-6 shadow-2xl rounded-3xl sm:px-10 border border-slate-100">
                <slot />
            </div>

            <!-- Quick Navigation Back -->
            <div class="text-center mt-6">
                <Link
                    href="/"
                    class="text-xs font-semibold text-slate-400 hover:text-white transition inline-flex items-center gap-1.5"
                >
                    &larr; Kembali ke Beranda
                </Link>
            </div>
        </div>
    </div>
</template>
