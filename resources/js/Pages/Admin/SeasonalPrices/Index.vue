<script setup>
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import {
    Tag,
    Plus,
    Calendar,
    Trash2,
    CheckCircle2,
    Car,
    Sparkles,
    AlertCircle,
    X,
} from 'lucide-vue-next';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    prices: {
        type: Array,
        default: () => [],
    },
    mobils: {
        type: Array,
        default: () => [],
    },
});

const createModalOpen = ref(false);

const form = useForm({
    rental_mobil_id: '',
    nama_event: '',
    tarif_per_hari: '',
    tanggal_mulai: '',
    tanggal_selesai: '',
});

function submit() {
    form.post('/admin/harga-musiman', {
        onSuccess: () => {
            createModalOpen.value = false;
            form.reset();
        },
    });
}

function deletePrice(id) {
    if (confirm('Hapus aturan tarif musiman ini?')) {
        router.delete(`/admin/harga-musiman/${id}`, {
            preserveScroll: true,
        });
    }
}

function formatRupiah(num) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(num || 0);
}

function formatDate(d) {
    if (!d) return '-';
    return new Date(d).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    });
}

function isCurrentActive(start, end) {
    const today = new Date().toISOString().split('T')[0];
    return today >= start && today <= end;
}
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Tarif & Harga Musiman - Quantum Streamline" />

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-2 h-2 rounded-full bg-red-600 animate-pulse" />
                    <span class="text-[11px] font-black tracking-widest text-red-500 uppercase">
                        Dynamic Revenue Engine
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white uppercase tracking-tight">
                    Pengaturan Tarif Musiman & Promo
                </h1>
                <p class="text-xs sm:text-sm text-neutral-400 mt-1">
                    Kelola kenaikan tarif khusus Lebaran, libur Tahun Baru, atau event promo dengan rentang tanggal aktif otomatis.
                </p>
            </div>

            <div>
                <button
                    type="button"
                    @click="createModalOpen = true"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 active:scale-95 text-white text-xs font-black uppercase tracking-wider shadow-lg shadow-red-600/30 transition cursor-pointer"
                >
                    <Plus class="w-4 h-4" />
                    Tambah Tarif Musiman
                </button>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-[#0f1015] rounded-3xl border border-neutral-800 shadow-2xl overflow-hidden">
            <div v-if="prices.length === 0" class="p-12 text-center">
                <Tag class="w-10 h-10 text-neutral-600 mx-auto mb-2" />
                <p class="text-sm font-bold text-neutral-300">Belum ada aturan harga musiman</p>
                <p class="text-xs text-neutral-500 mt-1">Semua unit mobil saat ini menggunakan tarif harian standar.</p>
            </div>

            <div v-else class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-neutral-800 bg-neutral-900/80 text-[10px] font-black uppercase tracking-widest text-neutral-400">
                            <th class="py-4 px-6">Nama Event / Musim</th>
                            <th class="py-4 px-6">Target Armada</th>
                            <th class="py-4 px-6">Tarif Khusus/Hari</th>
                            <th class="py-4 px-6">Rentang Tanggal Aktif</th>
                            <th class="py-4 px-6">Status Periode</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-800/80">
                        <tr
                            v-for="p in prices"
                            :key="p.id"
                            class="hover:bg-neutral-900/40 transition"
                        >
                            <!-- Nama Event -->
                            <td class="py-4 px-6">
                                <span class="font-extrabold text-white text-sm block">
                                    {{ p.nama_event }}
                                </span>
                                <span class="text-[10px] text-neutral-400">
                                    Dibuat pada {{ formatDate(p.created_at) }}
                                </span>
                            </td>

                            <!-- Target Mobil -->
                            <td class="py-4 px-6">
                                <span
                                    v-if="p.mobil"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-neutral-900 border border-neutral-800 text-neutral-200 font-bold text-xs"
                                >
                                    <Car class="w-3.5 h-3.5 text-red-500" />
                                    {{ p.mobil.nama_mobil }} ({{ p.mobil.merk }})
                                </span>
                                <span
                                    v-else
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-neutral-850 border border-neutral-750 text-neutral-300 font-bold text-xs"
                                >
                                    <Sparkles class="w-3.5 h-3.5 text-yellow-400" />
                                    Seluruh Armada Mobil
                                </span>
                            </td>

                            <!-- Tarif -->
                            <td class="py-4 px-6 font-black text-red-400 text-sm">
                                {{ formatRupiah(p.tarif_per_hari) }}
                            </td>

                            <!-- Dates -->
                            <td class="py-4 px-6 text-neutral-300 font-semibold">
                                <div class="flex items-center gap-1.5">
                                    <Calendar class="w-3.5 h-3.5 text-red-500" />
                                    <span>{{ formatDate(p.tanggal_mulai) }}</span>
                                    <span>&rarr;</span>
                                    <span>{{ formatDate(p.tanggal_selesai) }}</span>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6">
                                <span
                                    v-if="isCurrentActive(p.tanggal_mulai, p.tanggal_selesai)"
                                    class="px-2.5 py-1 rounded-full bg-red-950/70 text-red-400 border border-red-500/40 text-[10px] font-black uppercase tracking-wider animate-pulse"
                                >
                                    Sedang Aktif Hari Ini
                                </span>
                                <span
                                    v-else
                                    class="px-2.5 py-1 rounded-full bg-neutral-800 text-neutral-400 border border-neutral-700 text-[10px] font-bold uppercase tracking-wider"
                                >
                                    Terjadwal
                                </span>
                            </td>

                            <!-- Action -->
                            <td class="py-4 px-6 text-right">
                                <button
                                    @click="deletePrice(p.id)"
                                    type="button"
                                    class="p-2 rounded-xl text-neutral-400 hover:text-rose-400 hover:bg-neutral-800 transition cursor-pointer"
                                    title="Hapus Aturan Harga"
                                >
                                    <Trash2 class="w-4 h-4" />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Tambah Tarif Musiman -->
        <Modal :show="createModalOpen" @close="createModalOpen = false">
            <div class="p-6 sm:p-7 text-neutral-100 bg-[#0f1015]">
                <div class="flex items-center justify-between border-b border-neutral-800 pb-4 mb-5">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-red-500">
                            Dynamic Pricing Configuration
                        </span>
                        <h3 class="text-base font-black uppercase text-white">
                            Tambah Aturan Tarif Musiman
                        </h3>
                    </div>
                    <button
                        @click="createModalOpen = false"
                        type="button"
                        class="p-1 rounded-lg text-neutral-400 hover:text-white"
                    >
                        <X class="w-5 h-5" />
                    </button>
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1.5">
                            Nama Event / Musim
                        </label>
                        <input
                            v-model="form.nama_event"
                            type="text"
                            required
                            placeholder="Contoh: Peak Season Lebaran 1447H, Libur Natal & Tahun Baru"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1.5">
                            Berlaku Untuk Armada
                        </label>
                        <select
                            v-model="form.rental_mobil_id"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                        >
                            <option value="">Seluruh Armada Mobil (Global)</option>
                            <option
                                v-for="m in mobils"
                                :key="m.id"
                                :value="m.id"
                            >
                                Hanya {{ m.nama_mobil }} ({{ m.merk }}) - Normal: {{ formatRupiah(m.harga_per_hari) }}
                            </option>
                        </select>
                        <p class="text-[10px] text-neutral-500 mt-1">
                            Pilih unit spesifik, atau kosongkan jika ingin berlaku ke seluruh unit garasi.
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1.5">
                            Tarif Khusus Musiman (Rp/Hari)
                        </label>
                        <input
                            v-model="form.tarif_per_hari"
                            type="number"
                            min="0"
                            required
                            placeholder="Contoh: 1250000"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1">
                                Tanggal Mulai Berlaku
                            </label>
                            <input
                                v-model="form.tanggal_mulai"
                                type="date"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                            />
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1">
                                Tanggal Selesai Berlaku
                            </label>
                            <input
                                v-model="form.tanggal_selesai"
                                type="date"
                                required
                                :min="form.tanggal_mulai"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                            />
                        </div>
                    </div>

                    <div class="pt-3 border-t border-neutral-800 flex items-center justify-end gap-3">
                        <button
                            type="button"
                            @click="createModalOpen = false"
                            class="px-4 py-2 rounded-xl text-xs font-bold text-neutral-400 hover:text-white transition"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold uppercase tracking-wider shadow-lg shadow-red-600/30 transition disabled:opacity-50 cursor-pointer"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Terbitkan Aturan Tarif' }}
                        </button>
                    </div>
                </form>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
