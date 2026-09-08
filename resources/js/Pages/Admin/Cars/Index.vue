<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import {
    Car,
    Plus,
    Edit3,
    Trash2,
    Search,
    Filter,
    AlertTriangle,
    SlidersHorizontal,
    Sparkles,
} from 'lucide-vue-next';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Badge from '@/Components/Badge.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    mobils: {
        type: Array,
        default: () => [],
    },
});

const searchQuery = ref('');
const statusFilter = ref('all');
const deleteModalOpen = ref(false);
const carToDelete = ref(null);

const filteredMobils = computed(() => {
    return props.mobils.filter((m) => {
        const matchesStatus =
            statusFilter.value === 'all' || m.status === statusFilter.value;
        const matchesSearch =
            !searchQuery.value ||
            m.nama_mobil?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            m.merk?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            m.nopol?.toLowerCase().includes(searchQuery.value.toLowerCase());
        return matchesStatus && matchesSearch;
    });
});

function formatRupiah(num) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(num || 0);
}

const statusModalOpen = ref(false);
const carToChangeStatus = ref(null);
const targetStatus = ref('tersedia');
const statusReason = ref('');

function openStatusModal(car) {
    carToChangeStatus.value = car;
    targetStatus.value = car.status;
    statusReason.value = '';
    statusModalOpen.value = true;
}

function submitStatusChange() {
    if (!carToChangeStatus.value) return;
    router.post(`/admin/mobil/${carToChangeStatus.value.id}/status`, {
        status: targetStatus.value,
        alasan: statusReason.value,
    }, {
        onSuccess: () => {
            statusModalOpen.value = false;
            carToChangeStatus.value = null;
        },
    });
}

function confirmDelete(car) {
    carToDelete.value = car;
    deleteModalOpen.value = true;
}

function deleteCar() {
    if (!carToDelete.value) return;
    router.delete(`/admin/mobil/${carToDelete.value.id}`, {
        onSuccess: () => {
            deleteModalOpen.value = false;
            carToDelete.value = null;
        },
    });
}
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Manajemen Unit Mobil - Quantum Streamline" />

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-2 h-2 rounded-full bg-red-600 animate-pulse" />
                    <span class="text-[11px] font-black tracking-widest text-red-500 uppercase">
                        Executive Fleet Manager
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white uppercase tracking-tight">
                    Manajemen Armada Mobil
                </h1>
                <p class="text-xs sm:text-sm text-neutral-400 mt-1">
                    Kelola seluruh unit kendaraan, status ketersediaan, spesifikasi armada, dan tarif sewa.
                </p>
            </div>

            <div>
                <Link
                    href="/admin/mobil/form"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 active:scale-95 text-white text-xs font-black uppercase tracking-wider shadow-lg shadow-red-600/30 transition cursor-pointer"
                >
                    <Plus class="w-4 h-4" />
                    Tambah Mobil Baru
                </Link>
            </div>
        </div>

        <!-- Filter and Search Card -->
        <div class="bg-[#0f1015] rounded-3xl p-4 sm:p-5 border border-neutral-800 shadow-xl mb-6">
            <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
                <!-- Search Input -->
                <div class="relative flex-1">
                    <Search class="w-4 h-4 text-neutral-500 absolute left-3.5 top-1/2 -translate-y-1/2" />
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Cari merk, tipe, atau plat nomor mobil..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white placeholder:text-neutral-500 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition"
                    />
                </div>

                <!-- Status Filter Pills -->
                <div class="flex items-center gap-1.5 p-1 bg-neutral-900 rounded-xl shrink-0 overflow-x-auto border border-neutral-800">
                    <button
                        @click="statusFilter = 'all'"
                        type="button"
                        :class="[
                            'px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider transition shrink-0 cursor-pointer',
                            statusFilter === 'all'
                                ? 'bg-red-600 text-white'
                                : 'text-neutral-400 hover:text-white',
                        ]"
                    >
                        Semua
                    </button>
                    <button
                        @click="statusFilter = 'tersedia'"
                        type="button"
                        :class="[
                            'px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider transition shrink-0 cursor-pointer',
                            statusFilter === 'tersedia'
                                ? 'bg-red-600 text-white'
                                : 'text-neutral-400 hover:text-white',
                        ]"
                    >
                        Tersedia
                    </button>
                    <button
                        @click="statusFilter = 'disewa'"
                        type="button"
                        :class="[
                            'px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider transition shrink-0 cursor-pointer',
                            statusFilter === 'disewa'
                                ? 'bg-red-600 text-white'
                                : 'text-neutral-400 hover:text-white',
                        ]"
                    >
                        Disewa
                    </button>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-[#0f1015] rounded-3xl border border-neutral-800 shadow-2xl overflow-hidden">
            <div v-if="filteredMobils.length === 0" class="p-12 text-center">
                <Car class="w-10 h-10 text-neutral-600 mx-auto mb-2" />
                <p class="text-sm font-bold text-neutral-300">Tidak ada mobil yang cocok</p>
                <p class="text-xs text-neutral-500 mt-1">Coba sesuaikan pencarian Anda atau tambahkan armada baru.</p>
            </div>

            <div v-else class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-neutral-800 bg-neutral-900/80 text-[10px] font-black uppercase tracking-widest text-neutral-400">
                            <th class="py-4 px-6">Armada Mobil</th>
                            <th class="py-4 px-6">Plat Nomor</th>
                            <th class="py-4 px-6">Transmisi & Kursi</th>
                            <th class="py-4 px-6">Tarif Sewa/Hari</th>
                            <th class="py-4 px-6">Biaya Sopir</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-800/80">
                        <tr
                            v-for="car in filteredMobils"
                            :key="car.id"
                            class="hover:bg-neutral-900/40 transition duration-150"
                        >
                            <!-- Car info -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-14 h-10 rounded-xl bg-neutral-900 overflow-hidden shrink-0 border border-neutral-800 flex items-center justify-center">
                                        <img
                                            v-if="car.gambar"
                                            :src="`/gambar_mobil/${car.gambar}`"
                                            :alt="car.nama_mobil"
                                            class="w-full h-full object-cover"
                                        />
                                        <Car v-else class="w-4 h-4 text-neutral-600" />
                                    </div>
                                    <div>
                                        <span class="font-extrabold text-white text-xs block">
                                            {{ car.nama_mobil }}
                                        </span>
                                        <span class="text-[10px] text-neutral-400 uppercase tracking-wider font-bold">
                                            {{ car.merk }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Nopol -->
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-lg bg-neutral-900 border border-neutral-800 text-[11px] font-mono font-bold text-neutral-300">
                                    {{ car.nopol }}
                                </span>
                            </td>

                            <!-- Transmisi & Kapasitas -->
                            <td class="py-4 px-6 font-semibold text-neutral-300">
                                <span>{{ car.transmisi || 'Otomatis' }}</span>
                                <span class="text-neutral-600 mx-1.5">•</span>
                                <span>{{ car.kapasitas_penumpang || 5 }} Kursi</span>
                            </td>

                            <!-- Price -->
                            <td class="py-4 px-6 font-black text-red-400 text-xs">
                                {{ formatRupiah(car.harga_per_hari) }}
                            </td>

                            <!-- Driver fee -->
                            <td class="py-4 px-6 text-neutral-400">
                                {{ formatRupiah(car.biaya_sopir_per_hari || 250000) }}
                            </td>

                            <!-- Status with Clickable Switcher -->
                            <td class="py-4 px-6">
                                <button
                                    type="button"
                                    @click="openStatusModal(car)"
                                    class="group inline-flex items-center gap-1.5 p-1 rounded-xl hover:bg-neutral-800 transition cursor-pointer"
                                    title="Klik untuk ubah status unit secara real-time"
                                >
                                    <Badge :status="car.status" />
                                    <span class="text-[10px] text-neutral-500 group-hover:text-red-400 font-bold">Ubah</span>
                                </button>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <Link
                                        :href="`/admin/mobil/form?id=${car.id}`"
                                        class="p-2 rounded-xl text-neutral-400 hover:text-white hover:bg-neutral-800 transition"
                                        title="Edit Mobil"
                                    >
                                        <Edit3 class="w-4 h-4" />
                                    </Link>
                                    <button
                                        @click="confirmDelete(car)"
                                        type="button"
                                        class="p-2 rounded-xl text-neutral-400 hover:text-rose-400 hover:bg-neutral-800 transition cursor-pointer"
                                        title="Hapus Mobil"
                                    >
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Real-Time Status Modal -->
        <Modal :show="statusModalOpen" @close="statusModalOpen = false">
            <div class="p-6 text-neutral-100 bg-[#0f1015]">
                <div class="flex items-center justify-between border-b border-neutral-800 pb-3 mb-4">
                    <h3 class="text-sm font-black uppercase text-white">
                        Ubah Status Armada Real-Time
                    </h3>
                    <span class="text-xs font-bold text-red-500">{{ carToChangeStatus?.nama_mobil }}</span>
                </div>

                <form @submit.prevent="submitStatusChange" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1.5">
                            Pilih Status Baru
                        </label>
                        <select
                            v-model="targetStatus"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                        >
                            <option value="tersedia">Tersedia (Ready di Garasi / Katalog Publik)</option>
                            <option value="disewa">Disewa (Sedang Berjalan di Jalan)</option>
                            <option value="maintenance">Maintenance (Bengkel / Perawatan - Sembunyi dari Publik)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1.5">
                            Catatan Perubahan (Tercatat di Audit Trail)
                        </label>
                        <input
                            v-model="statusReason"
                            type="text"
                            placeholder="Contoh: Jadwal servis berkala 20.000km, ganti oli mesin"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                        />
                    </div>

                    <div class="pt-3 border-t border-neutral-800 flex items-center justify-end gap-3">
                        <button
                            type="button"
                            @click="statusModalOpen = false"
                            class="px-4 py-2 rounded-xl text-xs font-bold text-neutral-400 hover:text-white transition"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold uppercase tracking-wider shadow-lg shadow-red-600/30 transition cursor-pointer"
                        >
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Delete Modal Confirmation -->
        <Modal :show="deleteModalOpen" @close="deleteModalOpen = false">
            <div class="p-6 text-neutral-100 bg-[#0f1015]">
                <div class="w-12 h-12 rounded-2xl bg-rose-950/60 border border-rose-500/30 text-rose-500 flex items-center justify-center mx-auto mb-4">
                    <AlertTriangle class="w-6 h-6" />
                </div>
                <h3 class="text-base font-black uppercase tracking-wider text-center text-white">
                    Hapus Unit Armada?
                </h3>
                <p class="text-xs text-neutral-400 text-center mt-2 leading-relaxed">
                    Tindakan ini akan menghapus data mobil
                    <strong class="text-white">{{ carToDelete?.nama_mobil }}</strong>
                    dari katalog secara permanen.
                </p>

                <div class="flex items-center justify-end gap-3 mt-6">
                    <button
                        @click="deleteModalOpen = false"
                        type="button"
                        class="px-4 py-2 rounded-xl text-xs font-bold text-neutral-400 hover:text-white transition"
                    >
                        Batal
                    </button>
                    <button
                        @click="deleteCar"
                        type="button"
                        class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold uppercase tracking-wider shadow-lg shadow-red-600/30 transition cursor-pointer"
                    >
                        Hapus Sekarang
                    </button>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
