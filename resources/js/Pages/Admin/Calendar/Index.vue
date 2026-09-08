<script setup>
import { ref, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import {
    CalendarDays,
    Plus,
    Car,
    User,
    Phone,
    Clock,
    AlertTriangle,
    Shield,
    X,
    CheckCircle2,
    Calendar,
    ArrowRight,
} from 'lucide-vue-next';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    mobils: {
        type: Array,
        default: () => [],
    },
});

const offlineModalOpen = ref(false);

// Generate 14 days window from today
const today = new Date();
const daysWindow = computed(() => {
    const list = [];
    for (let i = 0; i < 14; i++) {
        const d = new Date(today);
        d.setDate(today.getDate() + i);
        list.push({
            dateStr: d.toISOString().split('T')[0],
            dayName: d.toLocaleDateString('id-ID', { weekday: 'short' }),
            dayNum: d.getDate(),
            monthName: d.toLocaleDateString('id-ID', { month: 'short' }),
            isToday: i === 0,
        });
    }
    return list;
});

const formOffline = useForm({
    mobil_id: props.mobils[0]?.id || '',
    nama_pelanggan_offline: '',
    no_hp_offline: '',
    tanggal_mulai: today.toISOString().split('T')[0],
    tanggal_selesai: new Date(Date.now() + 86400000).toISOString().split('T')[0],
    layanan: 'lepas_kunci',
    catatan_admin: '',
});

function openOfflineModal(preselectedCarId = null) {
    if (preselectedCarId) {
        formOffline.mobil_id = preselectedCarId;
    }
    offlineModalOpen.value = true;
}

function submitOffline() {
    formOffline.post('/admin/reservasi-offline', {
        onSuccess: () => {
            offlineModalOpen.value = false;
            formOffline.reset('nama_pelanggan_offline', 'no_hp_offline', 'catatan_admin');
        },
    });
}

function getBookingForDate(car, dateStr) {
    if (!car.transaksis) return null;
    return car.transaksis.find((t) => {
        const start = new Date(t.tanggal_mulai).toISOString().split('T')[0];
        const end = new Date(t.tanggal_selesai).toISOString().split('T')[0];
        return dateStr >= start && dateStr <= end;
    });
}
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Kalender Ketersediaan Armada - Quantum Streamline" />

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-2 h-2 rounded-full bg-red-600 animate-pulse" />
                    <span class="text-[11px] font-black tracking-widest text-red-500 uppercase">
                        Anti Double-Booking Engine
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white uppercase tracking-tight">
                    Kalender Ketersediaan Armada
                </h1>
                <p class="text-xs sm:text-sm text-neutral-400 mt-1">
                    Pantau okupansi armada online & input reservasi offline (walk-in/telepon) dengan proteksi bentrok jadwal otomatis.
                </p>
            </div>

            <div>
                <button
                    type="button"
                    @click="openOfflineModal()"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 active:scale-95 text-white text-xs font-black uppercase tracking-wider shadow-lg shadow-red-600/30 transition cursor-pointer"
                >
                    <Plus class="w-4 h-4" />
                    + Input Reservasi Offline Manual
                </button>
            </div>
        </div>

        <!-- Legend Bar -->
        <div class="bg-[#0f1015] border border-neutral-800 rounded-2xl p-4 mb-6 flex flex-wrap items-center justify-between gap-4 text-xs">
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500" />
                    <span class="text-neutral-300 font-bold">Tersedia (Ready)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-red-600" />
                    <span class="text-neutral-300 font-bold">Booking Online</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-amber-500" />
                    <span class="text-neutral-300 font-bold">Booking Offline / Walk-in</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-neutral-600" />
                    <span class="text-neutral-300 font-bold">Maintenance (Bengkel)</span>
                </div>
            </div>
            <div class="text-[11px] text-neutral-400">
                Menampilkan 14 Hari ke Depan
            </div>
        </div>

        <!-- Calendar Timeline Grid Table -->
        <div class="bg-[#0f1015] rounded-3xl border border-neutral-800 shadow-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-neutral-800 bg-neutral-900/90 text-neutral-400">
                            <!-- Car Name Col -->
                            <th class="py-4 px-4 min-w-[200px] sticky left-0 bg-[#121318] z-20 border-r border-neutral-800 text-[10px] font-black uppercase tracking-wider">
                                Unit Armada Mobil
                            </th>
                            <!-- 14 Days Header -->
                            <th
                                v-for="d in daysWindow"
                                :key="d.dateStr"
                                :class="[
                                    'py-3 px-2 text-center min-w-[65px] border-r border-neutral-800/80',
                                    d.isToday ? 'bg-red-950/40 text-red-400' : 'text-neutral-400',
                                ]"
                            >
                                <span class="text-[10px] uppercase font-bold block">{{ d.dayName }}</span>
                                <span class="text-xs font-black block">{{ d.dayNum }}</span>
                                <span class="text-[9px] uppercase font-medium text-neutral-500 block">{{ d.monthName }}</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-800/80">
                        <tr
                            v-for="car in mobils"
                            :key="car.id"
                            class="hover:bg-neutral-900/30 transition"
                        >
                            <!-- Car Column Sticky -->
                            <td class="py-3 px-4 sticky left-0 bg-[#0f1015] z-10 border-r border-neutral-800">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-7 rounded-lg bg-neutral-900 overflow-hidden shrink-0 border border-neutral-800 flex items-center justify-center">
                                        <img
                                            v-if="car.gambar"
                                            :src="`/gambar_mobil/${car.gambar}`"
                                            class="w-full h-full object-cover"
                                        />
                                        <Car v-else class="w-3.5 h-3.5 text-neutral-600" />
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-bold text-white text-xs block truncate">
                                            {{ car.nama_mobil }}
                                        </span>
                                        <span class="text-[9px] font-mono text-neutral-400 uppercase tracking-wider">
                                            {{ car.nopol || car.merk }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- 14 Days Cells -->
                            <td
                                v-for="d in daysWindow"
                                :key="d.dateStr"
                                class="p-1 border-r border-neutral-800/80 text-center"
                            >
                                <!-- If Car is in Maintenance -->
                                <div
                                    v-if="car.status === 'maintenance'"
                                    class="h-10 rounded-lg bg-neutral-800/80 border border-neutral-700/60 flex items-center justify-center text-[9px] font-bold text-neutral-400"
                                    title="Dalam Perbaikan / Maintenance"
                                >
                                    SERVIS
                                </div>

                                <!-- If Booked on this date -->
                                <div
                                    v-else-if="getBookingForDate(car, d.dateStr)"
                                    :class="[
                                        'h-10 rounded-lg p-1 flex flex-col justify-center items-center text-[9px] font-bold shadow-xs cursor-help transition',
                                        getBookingForDate(car, d.dateStr).sumber_pesanan === 'offline'
                                            ? 'bg-amber-950/70 border border-amber-500/50 text-amber-300'
                                            : 'bg-red-950/70 border border-red-500/50 text-red-300',
                                    ]"
                                    :title="`${getBookingForDate(car, d.dateStr).nomor_booking}: ${getBookingForDate(car, d.dateStr).user?.name || getBookingForDate(car, d.dateStr).nama_pelanggan_offline || 'Penyewa'}`"
                                >
                                    <span class="truncate max-w-[55px] font-black">
                                        {{ getBookingForDate(car, d.dateStr).nomor_booking.substring(0, 10) }}
                                    </span>
                                    <span class="text-[8px] opacity-80 uppercase">
                                        {{ getBookingForDate(car, d.dateStr).sumber_pesanan }}
                                    </span>
                                </div>

                                <!-- Empty / Ready Slot -->
                                <div
                                    v-else
                                    @click="openOfflineModal(car.id)"
                                    class="h-10 rounded-lg bg-emerald-950/20 hover:bg-emerald-950/50 border border-emerald-500/20 hover:border-emerald-500/50 flex items-center justify-center text-[10px] text-emerald-400 transition cursor-pointer group"
                                    title="Unit siap. Klik untuk input reservasi manual"
                                >
                                    <span class="opacity-40 group-hover:opacity-100 font-bold">+</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal: Input Offline Reservation -->
        <Modal :show="offlineModalOpen" @close="offlineModalOpen = false">
            <div class="p-6 sm:p-7 text-neutral-100 bg-[#0f1015]">
                <div class="flex items-center justify-between border-b border-neutral-800 pb-4 mb-5">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-red-500">
                            Manual Offline Reservation
                        </span>
                        <h3 class="text-base font-black uppercase text-white">
                            Input Pesanan Tamu / Telepon
                        </h3>
                    </div>
                    <button
                        @click="offlineModalOpen = false"
                        type="button"
                        class="p-1 rounded-lg text-neutral-400 hover:text-white"
                    >
                        <X class="w-5 h-5" />
                    </button>
                </div>

                <form @submit.prevent="submitOffline" class="space-y-4">
                    <!-- Unit Selection -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1.5">
                            Pilih Unit Kendaraan
                        </label>
                        <select
                            v-model="formOffline.mobil_id"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                        >
                            <option
                                v-for="m in mobils"
                                :key="m.id"
                                :value="m.id"
                                :disabled="m.status === 'maintenance'"
                            >
                                {{ m.nama_mobil }} ({{ m.merk }}) - {{ m.nopol || 'Tanpa Plat' }} {{ m.status === 'maintenance' ? '[MAINTENANCE]' : '' }}
                            </option>
                        </select>
                    </div>

                    <!-- Customer Info -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1">
                                Nama Tamu / Penyewa
                            </label>
                            <input
                                v-model="formOffline.nama_pelanggan_offline"
                                type="text"
                                required
                                placeholder="Contoh: Bpk. Hendra Wijaya"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                            />
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1">
                                Nomor WhatsApp / HP
                            </label>
                            <input
                                v-model="formOffline.no_hp_offline"
                                type="tel"
                                required
                                placeholder="081234567890"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                            />
                        </div>
                    </div>

                    <!-- Date Range -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1">
                                Mulai Sewa
                            </label>
                            <input
                                v-model="formOffline.tanggal_mulai"
                                type="date"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                            />
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1">
                                Selesai Sewa
                            </label>
                            <input
                                v-model="formOffline.tanggal_selesai"
                                type="date"
                                required
                                :min="formOffline.tanggal_mulai"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                            />
                        </div>
                    </div>

                    <!-- Layanan -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1">
                            Layanan
                        </label>
                        <select
                            v-model="formOffline.layanan"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                        >
                            <option value="lepas_kunci">Lepas Kunci (Self-Drive)</option>
                            <option value="dengan_sopir">Dengan Sopir (Chauffeur)</option>
                        </select>
                    </div>

                    <!-- Admin Notes -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1">
                            Catatan Khusus
                        </label>
                        <textarea
                            v-model="formOffline.catatan_admin"
                            rows="2"
                            placeholder="Catatan pembayaran tunai, KTP fisik sudah dititipkan di kantor, dll."
                            class="w-full px-3.5 py-2 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                        />
                    </div>

                    <div class="pt-3 border-t border-neutral-800 flex items-center justify-end gap-3">
                        <button
                            type="button"
                            @click="offlineModalOpen = false"
                            class="px-4 py-2 rounded-xl text-xs font-bold text-neutral-400 hover:text-white transition"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="formOffline.processing"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold uppercase tracking-wider shadow-lg shadow-red-600/30 transition disabled:opacity-50 cursor-pointer"
                        >
                            <span>{{ formOffline.processing ? 'Menyimpan...' : 'Simpan & Kunci Jadwal' }}</span>
                            <ArrowRight class="w-4 h-4" />
                        </button>
                    </div>
                </form>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
