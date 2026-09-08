<script setup>
import { Head, Link } from '@inertiajs/vue3';
import {
    Activity,
    Shield,
    Clock,
    User,
    Terminal,
    Search,
} from 'lucide-vue-next';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    logs: {
        type: Object,
        default: () => ({ data: [] }),
    },
});

function formatDate(d) {
    if (!d) return '-';
    return new Date(d).toLocaleString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
    });
}
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Audit Trail Log Aktivitas - Quantum Streamline" />

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-2 h-2 rounded-full bg-red-600 animate-pulse" />
                <span class="text-[11px] font-black tracking-widest text-red-500 uppercase">
                    Security & Compliance Logging
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-white uppercase tracking-tight">
                Audit Trail Log Aktivitas Admin
            </h1>
            <p class="text-xs sm:text-sm text-neutral-400 mt-1">
                Rekam jejak seluruh perubahan inventaris, manipulasi tarif, pembuatan pesanan offline, dan login administrator.
            </p>
        </div>

        <!-- Table Card -->
        <div class="bg-[#0f1015] rounded-3xl border border-neutral-800 shadow-2xl overflow-hidden">
            <div v-if="logs.data.length === 0" class="p-12 text-center">
                <Activity class="w-10 h-10 text-neutral-600 mx-auto mb-2" />
                <p class="text-sm font-bold text-neutral-300">Belum ada log aktivitas</p>
            </div>

            <div v-else class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-neutral-800 bg-neutral-900/80 text-[10px] font-black uppercase tracking-widest text-neutral-400">
                            <th class="py-4 px-6">Waktu & IP Address</th>
                            <th class="py-4 px-6">Admin Bertugas</th>
                            <th class="py-4 px-6">Aksi / Event</th>
                            <th class="py-4 px-6">Detail Catatan Aktivitas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-800/80">
                        <tr
                            v-for="log in logs.data"
                            :key="log.id"
                            class="hover:bg-neutral-900/40 transition"
                        >
                            <!-- Timestamp & IP -->
                            <td class="py-4 px-6">
                                <span class="font-mono text-white text-[11px] block">
                                    {{ formatDate(log.created_at) }}
                                </span>
                                <span class="text-[10px] font-mono text-neutral-400">
                                    IP: {{ log.ip_address || '127.0.0.1' }}
                                </span>
                            </td>

                            <!-- Admin -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-red-950 border border-red-500/30 text-red-400 flex items-center justify-center font-black text-[10px]">
                                        {{ (log.user?.name || 'A').charAt(0) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-white block">
                                            {{ log.user?.name || 'System / Admin' }}
                                        </span>
                                        <span class="text-[10px] text-neutral-400">
                                            {{ log.user?.email || 'admin@internal' }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Action -->
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-md bg-neutral-900 border border-neutral-750 text-[10px] font-mono font-bold text-red-400 uppercase tracking-wider">
                                    {{ log.action }}
                                </span>
                            </td>

                            <!-- Description -->
                            <td class="py-4 px-6 text-neutral-300 max-w-md">
                                <p class="leading-relaxed">
                                    {{ log.description }}
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination if exists -->
            <div v-if="logs.links && logs.links.length > 3" class="p-4 border-t border-neutral-800 flex justify-end gap-1.5">
                <Link
                    v-for="link in logs.links"
                    :key="link.label"
                    :href="link.url || '#'"
                    v-html="link.label"
                    :class="[
                        'px-3 py-1 rounded-lg text-xs font-bold transition',
                        link.active
                            ? 'bg-red-600 text-white'
                            : (link.url ? 'text-neutral-400 hover:text-white bg-neutral-900' : 'text-neutral-600 opacity-50'),
                    ]"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
