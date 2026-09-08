<script setup>
import { computed } from 'vue';

const props = defineProps({
    status: {
        type: String,
        required: true,
    },
    size: {
        type: String,
        default: 'md',
    },
});

const config = computed(() => {
    const s = (props.status || '').toLowerCase();
    switch (s) {
        case 'tersedia':
            return {
                label: 'Tersedia',
                bg: 'bg-emerald-950/60 text-emerald-400 border-emerald-500/40 ring-emerald-500/20',
                dot: 'bg-emerald-400',
            };
        case 'disewa':
            return {
                label: 'Sedang Disewa',
                bg: 'bg-amber-950/60 text-amber-400 border-amber-500/40 ring-amber-500/20',
                dot: 'bg-amber-400',
            };
        case 'maintenance':
            return {
                label: 'Perawatan / Bengkel',
                bg: 'bg-rose-950/60 text-rose-400 border-rose-500/40 ring-rose-500/20',
                dot: 'bg-rose-500',
            };
        case 'baru':
        case 'pending':
            return {
                label: 'Pesanan Baru',
                bg: 'bg-yellow-950/60 text-yellow-400 border-yellow-500/40 ring-yellow-500/20',
                dot: 'bg-yellow-400',
            };
        case 'dikonfirmasi':
        case 'disetujui':
            return {
                label: 'Dikonfirmasi',
                bg: 'bg-blue-950/60 text-blue-400 border-blue-500/40 ring-blue-500/20',
                dot: 'bg-blue-400',
            };
        case 'berjalan':
            return {
                label: 'Unit Berjalan',
                bg: 'bg-purple-950/60 text-purple-400 border-purple-500/40 ring-purple-500/20',
                dot: 'bg-purple-400',
            };
        case 'selesai':
            return {
                label: 'Selesai / Kembali',
                bg: 'bg-emerald-950/60 text-emerald-400 border-emerald-500/40 ring-emerald-500/20',
                dot: 'bg-emerald-400',
            };
        case 'ditolak':
            return {
                label: 'Ditolak',
                bg: 'bg-rose-950/60 text-rose-400 border-rose-500/40 ring-rose-500/20',
                dot: 'bg-rose-400',
            };
        case 'dibatalkan':
            return {
                label: 'Dibatalkan',
                bg: 'bg-neutral-800 text-neutral-400 border-neutral-700 ring-neutral-600/20',
                dot: 'bg-neutral-500',
            };
        case 'admin':
            return {
                label: 'Administrator',
                bg: 'bg-red-950/70 text-red-400 border-red-500/40 ring-red-500/20',
                dot: 'bg-red-500',
            };
        case 'user':
            return {
                label: 'VIP Member',
                bg: 'bg-neutral-800/80 text-neutral-200 border-neutral-700 ring-neutral-600/20',
                dot: 'bg-red-500',
            };
        default:
            return {
                label: props.status,
                bg: 'bg-neutral-800 text-neutral-300 border-neutral-700 ring-neutral-600/20',
                dot: 'bg-neutral-400',
            };
    }
});

const sizeClasses = computed(() => {
    return props.size === 'sm'
        ? 'px-2 py-0.5 text-[10px] tracking-wider uppercase font-bold'
        : 'px-2.5 py-1 text-xs font-bold tracking-wide uppercase';
});
</script>

<template>
    <span
        :class="[
            'inline-flex items-center gap-1.5 rounded-full border ring-1 ring-inset backdrop-blur-xs shadow-sm',
            config.bg,
            sizeClasses,
        ]"
    >
        <span :class="['h-1.5 w-1.5 rounded-full animate-pulse', config.dot]" />
        {{ config.label }}
    </span>
</template>
