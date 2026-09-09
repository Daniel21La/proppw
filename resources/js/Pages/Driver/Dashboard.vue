
<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    tasks: {
        type: Array,
        default: () => [],
    },
    driverName: {
        type: String,
        default: 'Driver',
    }
});

const selectedTask = ref(null);
const inspectionType = ref('pre_trip'); // 'pre_trip' or 'post_trip'
const showModal = ref(false);

const form = useForm({
    transaksi_id: null,
    tipe_inspeksi: 'pre_trip',
    odometer: 0,
    level_bbm: 100,
    kondisi_fisik: 'mulus',
    kebersihan_interior: true,
    ban_serap_dan_jack: true,
    catatan: '',
});

const openInspectionModal = (task, type) => {
    selectedTask.value = task;
    inspectionType.value = type;

    const existing = type === 'pre_trip' ? task.pre_trip : task.post_trip;

    form.transaksi_id = task.id;
    form.tipe_inspeksi = type;
    form.odometer = existing ? existing.odometer : 0;
    form.level_bbm = existing ? existing.level_bbm : 100;
    form.kondisi_fisik = existing ? existing.kondisi_fisik : 'mulus';
    form.kebersihan_interior = existing ? Boolean(existing.kebersihan_interior) : true;
    form.ban_serap_dan_jack = existing ? Boolean(existing.ban_serap_dan_jack) : true;
    form.catatan = existing ? existing.catatan : '';

    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    selectedTask.value = null;
};

const submitInspection = () => {
    form.post(route('driver.inspection.store'), {
        onSuccess: () => {
            closeModal();
        }
    });
};
</script>

<template>
    <Head title="Driver Portal & Checklist Inspeksi" />

    <div class="min-h-screen bg-slate-950 text-slate-100 py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Driver Header Banner -->
            <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 border border-blue-500/30 rounded-2xl p-6 shadow-xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <div class="inline-flex items-center px-3 py-1 bg-blue-500/20 border border-blue-400/30 rounded-full text-xs font-semibold text-blue-300 mb-2">
                        👨‍✈️ DRIVER OPERATIONAL PORTAL
                    </div>
                    <h1 class="text-2xl font-bold text-white">Selamat Datang, {{ driverName }}</h1>
                    <p class="text-slate-300 text-xs sm:text-sm mt-1">Daftar penugasan penjemputan & form inspeksi kondisi armada kendaraan.</p>
                </div>
                <div class="bg-slate-800/80 px-4 py-2 rounded-xl border border-slate-700 text-right">
                    <span class="text-xs text-slate-400 block font-medium">TOTAL TUGAS ACTIVE</span>
                    <span class="text-2xl font-bold text-cyan-400">{{ tasks.length }} Unit</span>
                </div>
            </div>

            <!-- Task List Cards -->
            <div v-if="tasks.length === 0" class="bg-slate-900/60 border border-slate-800 rounded-2xl p-12 text-center">
                <div class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">🚘</div>
                <h3 class="text-lg font-bold text-slate-200">Belum Ada Penugasan Aktif</h3>
                <p class="text-sm text-slate-400 mt-1">Saat ini belum ada jadwal penjemputan armada yang ditugaskan ke akun Anda.</p>
            </div>

            <div v-else class="space-y-4">
                <div v-for="task in tasks" :key="task.id" class="bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-2xl p-5 sm:p-6 shadow-lg transition">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-4 border-b border-slate-800">
                        <div>
                            <span class="text-xs font-mono font-bold text-cyan-400 bg-cyan-500/10 px-2.5 py-1 rounded-md border border-cyan-500/20">
                                #{{ task.nomor_booking }}
                            </span>
                            <h3 class="text-lg font-bold text-white mt-2">{{ task.mobil ? task.mobil.nama_mobil : 'Kendaraan' }}</h3>
                            <p class="text-xs text-slate-400 font-mono">{{ task.mobil ? task.mobil.nopol : '-' }}</p>
                        </div>
                        <div class="text-left sm:text-right">
                            <span class="text-xs px-3 py-1 rounded-full font-bold uppercase tracking-wider bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                {{ task.status }}
                            </span>
                            <p class="text-xs text-slate-400 mt-1">Jadwal: {{ task.tanggal_mulai }} ({{ task.jam_mulai }})</p>
                        </div>
                    </div>

                    <div class="py-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="bg-slate-950/60 p-3.5 rounded-xl border border-slate-800">
                            <p class="text-xs text-slate-400 font-semibold uppercase">NAMA TAMU / PELANGGAN</p>
                            <p class="font-bold text-slate-100 mt-0.5">{{ task.pelanggan.nama }}</p>
                            <a :href="'https://wa.me/' + task.pelanggan.no_hp.replace(/[^0-9]/g, '')" target="_blank" class="inline-flex items-center text-xs text-emerald-400 hover:underline mt-1 font-semibold">
                                💬 WhatsApp: {{ task.pelanggan.no_hp }}
                            </a>
                        </div>
                        <div class="bg-slate-950/60 p-3.5 rounded-xl border border-slate-800">
                            <p class="text-xs text-slate-400 font-semibold uppercase">LOKASI PENJEMPUTAN</p>
                            <p class="font-bold text-slate-100 mt-0.5">{{ task.lokasi_jemput }}</p>
                            <p v-if="task.catatan_sopir" class="text-xs text-amber-300 mt-1 italic">Catatan: {{ task.catatan_sopir }}</p>
                        </div>
                    </div>

                    <!-- Inspection Status & Buttons -->
                    <div class="pt-2 flex flex-wrap items-center justify-between gap-3 border-t border-slate-800/80">
                        <div class="flex items-center space-x-2 text-xs">
                            <span class="font-semibold text-slate-400">Status Inspeksi:</span>
                            <span :class="task.pre_trip ? 'text-emerald-400 font-bold' : 'text-slate-500'">
                                Pre-Trip: {{ task.pre_trip ? '✅ Selesai (' + task.pre_trip.odometer + ' km)' : '❌ Belum' }}
                            </span>
                            <span class="text-slate-600">|</span>
                            <span :class="task.post_trip ? 'text-emerald-400 font-bold' : 'text-slate-500'">
                                Post-Trip: {{ task.post_trip ? '✅ Selesai (' + task.post_trip.odometer + ' km)' : '❌ Belum' }}
                            </span>
                        </div>

                        <div class="flex items-center space-x-2">
                            <button @click="openInspectionModal(task, 'pre_trip')" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold rounded-xl shadow transition">
                                {{ task.pre_trip ? '✏️ Edit Pre-Trip' : '📋 Pre-Trip Inspection' }}
                            </button>
                            <button @click="openInspectionModal(task, 'post_trip')" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-xl shadow transition">
                                {{ task.post_trip ? '✏️ Edit Post-Trip' : '🏁 Post-Trip Inspection' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inspection Modal Form -->
            <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
                <div class="bg-slate-900 border border-slate-700 w-full max-w-lg rounded-2xl shadow-2xl p-6 space-y-4 max-h-[90vh] overflow-y-auto">
                    <div class="flex justify-between items-center pb-3 border-b border-slate-800">
                        <div>
                            <span class="text-xs font-bold text-cyan-400 uppercase tracking-wider">FORM CHECKLIST KONDISI ARMADA</span>
                            <h3 class="text-lg font-bold text-white">
                                {{ inspectionType === 'pre_trip' ? 'Pre-Trip Inspection (Sebelum Perjalanan)' : 'Post-Trip Inspection (Setelan Kembali)' }}
                            </h3>
                        </div>
                        <button @click="closeModal" class="text-slate-400 hover:text-white text-xl">✕</button>
                    </div>

                    <form @submit.prevent="submitInspection" class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">Odometer Kendaraan (KM)*</label>
                            <input v-model.number="form.odometer" type="number" min="0" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:ring-2 focus:ring-cyan-500 font-mono" placeholder="misal: 45210" />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">Level Bahan Bakar / BBM (%) : {{ form.level_bbm }}%*</label>
                            <input v-model.number="form.level_bbm" type="range" min="0" max="100" step="5" class="w-full h-2 bg-slate-800 rounded-lg appearance-none cursor-pointer accent-cyan-500" />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">Kondisi Fisik Body Kendaraan*</label>
                            <select v-model="form.kondisi_fisik" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:ring-2 focus:ring-cyan-500">
                                <option value="mulus">Mulus (Tanpa Goresan Baru)</option>
                                <option value="goresan_ringan">Goresan Ringan / Minor</option>
                                <option value="goresan_sedang">Goresan Sedang / Penyok Halus</option>
                                <option value="kerusakan_perlu_perhatian">Kerusakan Butuh Perhatian Khusus</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <label class="flex items-center space-x-2 bg-slate-950 p-3 rounded-xl border border-slate-800 cursor-pointer">
                                <input v-model="form.kebersihan_interior" type="checkbox" class="rounded border-slate-800 text-cyan-500 focus:ring-cyan-500" />
                                <span class="text-xs text-slate-300 font-medium">Interior Bersih & Harum</span>
                            </label>
                            <label class="flex items-center space-x-2 bg-slate-950 p-3 rounded-xl border border-slate-800 cursor-pointer">
                                <input v-model="form.ban_serap_dan_jack" type="checkbox" class="rounded border-slate-800 text-cyan-500 focus:ring-cyan-500" />
                                <span class="text-xs text-slate-300 font-medium">Ban Serep & Dongkrak Lengkap</span>
                            </label>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">Catatan Tambahan Driver</label>
                            <textarea v-model="form.catatan" rows="3" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:ring-2 focus:ring-cyan-500" placeholder="Catatan kondisi ban, bensin awal, kelengkapan surat STNK..."></textarea>
                        </div>

                        <div class="flex justify-end space-x-3 pt-3 border-t border-slate-800">
                            <button type="button" @click="closeModal" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-xl">Batal</button>
                            <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-400 text-slate-950 text-xs font-bold rounded-xl shadow transition">
                                Simpan Checklist Inspeksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
