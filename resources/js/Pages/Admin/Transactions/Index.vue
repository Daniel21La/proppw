<script setup>
import { ref, computed } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import {
    FileText,
    CheckCircle2,
    XCircle,
    Calendar,
    User,
    Car,
    Clock,
    Search,
    AlertCircle,
    Shield,
    CreditCard,
    Phone,
    Globe,
    Store,
    KeyRound,
    CheckCheck,
    MessageSquare,
    X,
    Send,
    Mail,
    MessageCircle,
    UserCheck,
    UserPlus,
    RefreshCw,
} from 'lucide-vue-next';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Badge from '@/Components/Badge.vue';

const props = defineProps({
    transaksis: {
        type: Array,
        default: () => [],
    },
});

const searchQuery = ref('');
const statusFilter = ref('all');
const sourceFilter = ref('all');

// Modal State for Status Update
const isStatusModalOpen = ref(false);
const selectedTrx = ref(null);
const statusForm = useForm({
    status: '',
    catatan_admin: '',
});

// Point 3: Modal State for Document Review & Dispute Freeze (UU PDP)
const isDocModalOpen = ref(false);
const selectedDocTrx = ref(null);
const disputeForm = useForm({
    is_disputed: false,
    dispute_reason: '',
});

// Point 4: Modal State for VIP Driver Assignment
const isDriverModalOpen = ref(false);
const selectedDriverTrx = ref(null);
const driverForm = useForm({
    nama_sopir: '',
    no_hp_sopir: '',
    notify_customer: true,
});

function openDriverModal(trx) {
    selectedDriverTrx.value = trx;
    driverForm.nama_sopir = trx.nama_sopir_assigned || '';
    driverForm.no_hp_sopir = trx.no_hp_sopir_assigned || '';
    driverForm.notify_customer = true;
    isDriverModalOpen.value = true;
}

function submitDriverAssign() {
    if (!selectedDriverTrx.value) return;
    driverForm.post(`/admin/transaksi/${selectedDriverTrx.value.id}/assign-driver`, {
        preserveScroll: true,
        onSuccess: () => {
            isDriverModalOpen.value = false;
            selectedDriverTrx.value = null;
        },
    });
}

// Point 4: Modal State for Resending Notifications (WhatsApp / Email)
const isResendModalOpen = ref(false);
const selectedResendTrx = ref(null);
const resendForm = useForm({
    channel: 'all',
});

function openResendModal(trx) {
    selectedResendTrx.value = trx;
    resendForm.channel = 'all';
    isResendModalOpen.value = true;
}

function submitResendNotification() {
    if (!selectedResendTrx.value) return;
    resendForm.post(`/admin/transaksi/${selectedResendTrx.value.id}/resend-notification`, {
        preserveScroll: true,
        onSuccess: () => {
            isResendModalOpen.value = false;
            selectedResendTrx.value = null;
        },
    });
}

function openDocModal(trx) {
    selectedDocTrx.value = trx;
    disputeForm.is_disputed = !!trx.is_disputed;
    disputeForm.dispute_reason = trx.dispute_reason || '';
    isDocModalOpen.value = true;
}

function submitDisputeToggle() {
    if (!selectedDocTrx.value) return;
    disputeForm.post(`/admin/transaksi/${selectedDocTrx.value.id}/toggle-dispute`, {
        preserveScroll: true,
        onSuccess: () => {
            isDocModalOpen.value = false;
            selectedDocTrx.value = null;
        },
    });
}

function openStatusModal(trx) {
    selectedTrx.value = trx;
    statusForm.status = trx.status;
    statusForm.catatan_admin = trx.catatan_admin || '';
    isStatusModalOpen.value = true;
}

function submitStatusUpdate() {
    if (!selectedTrx.value) return;
    statusForm.post(`/admin/transaksi/${selectedTrx.value.id}/update-status`, {
        preserveScroll: true,
        onSuccess: () => {
            isStatusModalOpen.value = false;
            selectedTrx.value = null;
        },
    });
}

function quickUpdate(trx, newStatus) {
    router.post(`/admin/transaksi/${trx.id}/update-status`, {
        status: newStatus,
        catatan_admin: trx.catatan_admin || '',
    }, {
        preserveScroll: true,
    });
}

const stats = computed(() => {
    const total = props.transaksis.length;
    const baru = props.transaksis.filter((t) => t.status === 'baru' || t.status === 'pending').length;
    const dikonfirmasi = props.transaksis.filter((t) => t.status === 'dikonfirmasi' || t.status === 'disetujui').length;
    const berjalan = props.transaksis.filter((t) => t.status === 'berjalan').length;
    const selesai = props.transaksis.filter((t) => t.status === 'selesai').length;
    const dibatalkan = props.transaksis.filter((t) => t.status === 'dibatalkan' || t.status === 'ditolak').length;
    return { total, baru, dikonfirmasi, berjalan, selesai, dibatalkan };
});

const filteredTransactions = computed(() => {
    return props.transaksis.filter((t) => {
        const matchesStatus =
            statusFilter.value === 'all' ||
            (statusFilter.value === 'baru' && (t.status === 'baru' || t.status === 'pending')) ||
            (statusFilter.value === 'dikonfirmasi' && (t.status === 'dikonfirmasi' || t.status === 'disetujui')) ||
            t.status === statusFilter.value;

        const matchesSource =
            sourceFilter.value === 'all' ||
            (t.sumber_pesanan || 'online') === sourceFilter.value;

        const q = searchQuery.value.toLowerCase();
        const matchesSearch =
            !q ||
            t.nomor_booking?.toLowerCase().includes(q) ||
            t.user?.name?.toLowerCase().includes(q) ||
            t.user?.email?.toLowerCase().includes(q) ||
            t.nama_pelanggan_offline?.toLowerCase().includes(q) ||
            t.no_hp_offline?.toLowerCase().includes(q) ||
            t.mobil?.nama_mobil?.toLowerCase().includes(q) ||
            t.mobil?.nopol?.toLowerCase().includes(q) ||
            t.mobil?.merk?.toLowerCase().includes(q);

        return matchesStatus && matchesSource && matchesSearch;
    });
});

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
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Pesanan & Reservasi - Quantum Streamline" />

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-2 h-2 rounded-full bg-red-600 animate-pulse" />
                <span class="text-[11px] font-black tracking-widest text-red-500 uppercase">
                    Point 1 CMS &bull; Fleet Order Control
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-white uppercase tracking-tight">
                Daftar Pesanan & Status Armada
            </h1>
            <p class="text-xs sm:text-sm text-neutral-400 mt-1">
                Kelola 4 siklus pemesanan (Baru &rarr; Dikonfirmasi &rarr; Berjalan &rarr; Selesai). Sinkronisasi status unit mobil secara real-time.
            </p>
        </div>

        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-8">
            <div class="bg-[#0f1015] rounded-2xl p-4 border border-neutral-800 shadow-xl">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-neutral-400">Total</span>
                    <FileText class="w-4 h-4 text-neutral-400" />
                </div>
                <div class="text-2xl font-black text-white mt-1">
                    {{ stats.total }}
                </div>
            </div>

            <div class="bg-[#0f1015] rounded-2xl p-4 border border-neutral-800 shadow-xl">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-yellow-400">Baru</span>
                    <Clock class="w-4 h-4 text-yellow-500" />
                </div>
                <div class="text-2xl font-black text-yellow-400 mt-1">
                    {{ stats.baru }}
                </div>
            </div>

            <div class="bg-[#0f1015] rounded-2xl p-4 border border-neutral-800 shadow-xl">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-blue-400">Dikonfirmasi</span>
                    <CheckCircle2 class="w-4 h-4 text-blue-500" />
                </div>
                <div class="text-2xl font-black text-blue-400 mt-1">
                    {{ stats.dikonfirmasi }}
                </div>
            </div>

            <div class="bg-[#0f1015] rounded-2xl p-4 border border-neutral-800 shadow-xl">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-purple-400">Unit Berjalan</span>
                    <KeyRound class="w-4 h-4 text-purple-500" />
                </div>
                <div class="text-2xl font-black text-purple-400 mt-1">
                    {{ stats.berjalan }}
                </div>
            </div>

            <div class="bg-[#0f1015] rounded-2xl p-4 border border-neutral-800 shadow-xl">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-emerald-400">Selesai</span>
                    <CheckCheck class="w-4 h-4 text-emerald-500" />
                </div>
                <div class="text-2xl font-black text-emerald-400 mt-1">
                    {{ stats.selesai }}
                </div>
            </div>

            <div class="bg-[#0f1015] rounded-2xl p-4 border border-neutral-800 shadow-xl">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-rose-400">Dibatalkan</span>
                    <XCircle class="w-4 h-4 text-rose-500" />
                </div>
                <div class="text-2xl font-black text-rose-400 mt-1">
                    {{ stats.dibatalkan }}
                </div>
            </div>
        </div>

        <!-- Filter & Search Controls -->
        <div class="bg-[#0f1015] rounded-3xl p-4 sm:p-5 border border-neutral-800 shadow-xl mb-6">
            <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
                <div class="relative flex-1">
                    <Search class="w-4 h-4 text-neutral-500 absolute left-3.5 top-1/2 -translate-y-1/2" />
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Cari no booking, nama pelanggan, nopol, email, atau mobil..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white placeholder:text-neutral-500 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition"
                    />
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <!-- Source filter -->
                    <div class="flex items-center gap-1 p-1 bg-neutral-900 rounded-xl border border-neutral-800">
                        <button
                            @click="sourceFilter = 'all'"
                            type="button"
                            :class="[
                                'px-2.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider transition cursor-pointer',
                                sourceFilter === 'all' ? 'bg-red-600 text-white' : 'text-neutral-400 hover:text-white'
                            ]"
                        >
                            Semua Sumber
                        </button>
                        <button
                            @click="sourceFilter = 'online'"
                            type="button"
                            :class="[
                                'px-2.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider transition cursor-pointer',
                                sourceFilter === 'online' ? 'bg-red-600 text-white' : 'text-neutral-400 hover:text-white'
                            ]"
                        >
                            Online
                        </button>
                        <button
                            @click="sourceFilter = 'offline'"
                            type="button"
                            :class="[
                                'px-2.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider transition cursor-pointer',
                                sourceFilter === 'offline' ? 'bg-red-600 text-white' : 'text-neutral-400 hover:text-white'
                            ]"
                        >
                            Offline / Walk-in
                        </button>
                    </div>

                    <!-- Status filter -->
                    <div class="flex items-center gap-1 p-1 bg-neutral-900 rounded-xl shrink-0 overflow-x-auto border border-neutral-800">
                        <button
                            @click="statusFilter = 'all'"
                            type="button"
                            :class="[
                                'px-2.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider transition shrink-0 cursor-pointer',
                                statusFilter === 'all' ? 'bg-neutral-800 text-white' : 'text-neutral-400 hover:text-white'
                            ]"
                        >
                            Semua Status
                        </button>
                        <button
                            @click="statusFilter = 'baru'"
                            type="button"
                            :class="[
                                'px-2.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider transition shrink-0 cursor-pointer',
                                statusFilter === 'baru' ? 'bg-yellow-600 text-white' : 'text-neutral-400 hover:text-white'
                            ]"
                        >
                            Baru
                        </button>
                        <button
                            @click="statusFilter = 'dikonfirmasi'"
                            type="button"
                            :class="[
                                'px-2.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider transition shrink-0 cursor-pointer',
                                statusFilter === 'dikonfirmasi' ? 'bg-blue-600 text-white' : 'text-neutral-400 hover:text-white'
                            ]"
                        >
                            Dikonfirmasi
                        </button>
                        <button
                            @click="statusFilter = 'berjalan'"
                            type="button"
                            :class="[
                                'px-2.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider transition shrink-0 cursor-pointer',
                                statusFilter === 'berjalan' ? 'bg-purple-600 text-white' : 'text-neutral-400 hover:text-white'
                            ]"
                        >
                            Berjalan
                        </button>
                        <button
                            @click="statusFilter = 'selesai'"
                            type="button"
                            :class="[
                                'px-2.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider transition shrink-0 cursor-pointer',
                                statusFilter === 'selesai' ? 'bg-emerald-600 text-white' : 'text-neutral-400 hover:text-white'
                            ]"
                        >
                            Selesai
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table View -->
        <div class="bg-[#0f1015] rounded-3xl border border-neutral-800 shadow-2xl overflow-hidden">
            <div v-if="filteredTransactions.length === 0" class="p-12 text-center">
                <FileText class="w-10 h-10 text-neutral-600 mx-auto mb-2" />
                <p class="text-sm font-bold text-neutral-300">Tidak ada transaksi yang cocok</p>
                <p class="text-xs text-neutral-500 mt-1">Coba sesuaikan filter status atau pencarian Anda.</p>
            </div>

            <div v-else class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-neutral-800 bg-neutral-900/80 text-[10px] font-black uppercase tracking-widest text-neutral-400">
                            <th class="py-4 px-6">ID & Booking</th>
                            <th class="py-4 px-6">Pelanggan</th>
                            <th class="py-4 px-6">Armada</th>
                            <th class="py-4 px-6">Dokumen & Legalitas</th>
                            <th class="py-4 px-6">Notifikasi &amp; Driver</th>
                            <th class="py-4 px-6">Jadwal Sewa</th>
                            <th class="py-4 px-6">Total Biaya</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6 text-right">Workflow & Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-800/80">
                        <tr
                            v-for="t in filteredTransactions"
                            :key="t.id"
                            class="hover:bg-neutral-900/40 transition"
                        >
                            <!-- ID & Booking Code -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-1.5 mb-1">
                                    <span v-if="t.sumber_pesanan === 'offline'" class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-amber-950/80 text-amber-400 border border-amber-800/60">
                                        <Store class="w-2.5 h-2.5" /> Offline
                                    </span>
                                    <span v-else class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-blue-950/80 text-blue-400 border border-blue-800/60">
                                        <Globe class="w-2.5 h-2.5" /> Online
                                    </span>
                                </div>
                                <span class="font-black text-white block text-sm">
                                    {{ t.nomor_booking || `#TRX-${t.id}` }}
                                </span>
                                <span class="text-[10px] text-neutral-400">
                                    {{ formatDate(t.created_at) }}
                                </span>
                            </td>

                            <!-- User Info -->
                            <td class="py-4 px-6">
                                <div v-if="t.sumber_pesanan === 'offline'">
                                    <span class="font-bold text-white block">
                                        {{ t.nama_pelanggan_offline || 'Walk-in Guest' }}
                                    </span>
                                    <span class="text-[11px] text-neutral-400 flex items-center gap-1 mt-0.5">
                                        <Phone class="w-3 h-3 text-red-500" />
                                        {{ t.no_hp_offline || '-' }}
                                    </span>
                                </div>
                                <div v-else class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-neutral-800 border border-neutral-700 flex items-center justify-center text-red-500 font-bold text-xs uppercase shrink-0">
                                        {{ (t.user?.name || 'U').charAt(0) }}
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-bold text-white block truncate">
                                            {{ t.user?.name || 'Pelanggan Dihapus' }}
                                        </span>
                                        <span class="text-[10px] text-neutral-400 truncate block">
                                            {{ t.user?.email || '-' }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Car Info -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-10 h-8 rounded-lg bg-neutral-850 overflow-hidden shrink-0 border border-neutral-750 flex items-center justify-center">
                                        <img
                                            v-if="t.mobil?.gambar"
                                            :src="`/gambar_mobil/${t.mobil.gambar}`"
                                            :alt="t.mobil?.nama_mobil"
                                            class="w-full h-full object-cover"
                                        />
                                        <Car v-else class="w-4 h-4 text-neutral-500" />
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-bold text-white block truncate">
                                            {{ t.mobil?.nama_mobil || 'Unit Dihapus' }}
                                        </span>
                                        <span class="text-[10px] text-neutral-400 block font-mono">
                                            {{ t.mobil?.nopol || t.mobil?.merk }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Point 3: Documents & UU PDP Status -->
                            <td class="py-4 px-6">
                                <div v-if="t.layanan === 'lepas_kunci'" class="space-y-1">
                                    <div v-if="t.dokumen_purged_at" class="inline-flex items-center gap-1 text-[10px] font-bold text-neutral-400 bg-neutral-900 px-2 py-0.5 rounded border border-neutral-800">
                                        <Shield class="w-2.5 h-2.5 text-neutral-500" />
                                        Terhapus UU PDP
                                    </div>
                                    <div v-else-if="t.is_disputed" class="inline-flex items-center gap-1 text-[10px] font-black text-rose-400 bg-rose-950/80 px-2 py-0.5 rounded border border-rose-800/60 animate-pulse">
                                        <AlertCircle class="w-3 h-3" />
                                        SENGKETA (FREEZE)
                                    </div>
                                    <div v-else-if="t.ktp_path || t.sim_path" class="flex items-center gap-1">
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-400 bg-emerald-950/60 px-2 py-0.5 rounded border border-emerald-500/30">
                                            <CheckCircle2 class="w-2.5 h-2.5" /> KTP & SIM OK
                                        </span>
                                    </div>
                                    <div v-else class="text-[10px] text-yellow-400 font-bold">
                                        Tanpa Berkas
                                    </div>

                                    <!-- Review button -->
                                    <button
                                        v-if="t.ktp_path || t.sim_path || t.is_disputed || t.dokumen_purged_at"
                                        @click="openDocModal(t)"
                                        class="text-[10px] text-red-400 hover:text-red-300 font-bold block underline cursor-pointer"
                                    >
                                        Periksa Berkas & Sengketa &rarr;
                                    </button>
                                </div>
                                <div v-else class="text-[10px] text-neutral-500 font-semibold">
                                    Dengan Sopir (Aman)
                                </div>
                            </td>

                            <!-- Point 4: Notification & VIP Driver Status -->
                            <td class="py-4 px-6 space-y-1.5">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <!-- WhatsApp Notification Badge -->
                                    <span
                                        v-if="t.whatsapp_notified_at"
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-950/80 text-emerald-400 border border-emerald-500/30"
                                        :title="'Terkirim pada: ' + formatDate(t.whatsapp_notified_at)"
                                    >
                                        <MessageCircle class="w-3 h-3" /> WA Sent
                                    </span>
                                    <span
                                        v-else
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-medium bg-neutral-900 text-neutral-500 border border-neutral-800"
                                    >
                                        <Clock class="w-3 h-3" /> WA Pending
                                    </span>

                                    <!-- Email Notification Badge -->
                                    <span
                                        v-if="t.email_notified_at"
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-blue-950/80 text-blue-400 border border-blue-500/30"
                                        :title="'Terkirim pada: ' + formatDate(t.email_notified_at)"
                                    >
                                        <Mail class="w-3 h-3" /> Email Sent
                                    </span>
                                </div>

                                <!-- Driver Assignment if Dengan Sopir -->
                                <div v-if="t.layanan === 'dengan_sopir'" class="pt-0.5">
                                    <div v-if="t.nama_sopir_assigned" class="flex items-center justify-between gap-1 bg-neutral-900/80 px-2 py-1 rounded-lg border border-neutral-800 text-[10px]">
                                        <div class="min-w-0">
                                            <span class="text-neutral-400 block text-[9px] uppercase font-bold">Driver VIP:</span>
                                            <span class="font-bold text-white truncate block">{{ t.nama_sopir_assigned }}</span>
                                        </div>
                                        <button
                                            @click="openDriverModal(t)"
                                            type="button"
                                            class="text-[9px] text-red-400 hover:text-red-300 font-bold underline shrink-0 cursor-pointer ml-1"
                                            title="Ganti atau hubungi driver"
                                        >
                                            Ubah
                                        </button>
                                    </div>
                                    <button
                                        v-else
                                        @click="openDriverModal(t)"
                                        type="button"
                                        class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-amber-950/80 hover:bg-amber-900 text-amber-300 border border-amber-800/80 transition cursor-pointer"
                                    >
                                        <UserPlus class="w-3 h-3" /> Tugaskan Sopir
                                    </button>
                                </div>

                                <!-- Resend Notification Trigger -->
                                <div>
                                    <button
                                        @click="openResendModal(t)"
                                        type="button"
                                        class="inline-flex items-center gap-1 text-[10px] text-neutral-400 hover:text-red-400 font-semibold transition cursor-pointer"
                                    >
                                        <Send class="w-2.5 h-2.5" /> Kirim Ulang E-Voucher
                                    </button>
                                </div>
                            </td>

                            <!-- Dates & Service -->
                            <td class="py-4 px-6 font-semibold text-neutral-300">
                                <div class="flex items-center gap-1 text-[11px] mb-1">
                                    <span class="uppercase tracking-wider font-bold text-red-400">
                                        {{ t.layanan === 'dengan_sopir' ? 'Dgn Sopir' : 'Lepas Kunci' }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-1 text-neutral-400 text-[11px]">
                                    <Calendar class="w-3.5 h-3.5 text-neutral-500 shrink-0" />
                                    <span>{{ formatDate(t.tanggal_mulai) }}</span>
                                    <span>&rarr;</span>
                                    <span>{{ formatDate(t.tanggal_selesai) }}</span>
                                </div>
                            </td>

                            <!-- Total -->
                            <td class="py-4 px-6 font-black text-red-400 text-sm">
                                {{ formatRupiah(t.total_harga) }}
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6">
                                <Badge :status="t.status || 'baru'" />
                                <div v-if="t.catatan_admin" class="mt-1 text-[10px] text-neutral-400 bg-neutral-900 px-2 py-1 rounded border border-neutral-800 max-w-[160px] truncate" :title="t.catatan_admin">
                                    &bull; {{ t.catatan_admin }}
                                </div>
                            </td>

                            <!-- Actions / Workflow -->
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                    <!-- Context action buttons depending on state -->
                                    <button
                                        v-if="t.status === 'baru' || t.status === 'pending'"
                                        @click="quickUpdate(t, 'dikonfirmasi')"
                                        type="button"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-bold transition text-[11px] cursor-pointer"
                                        title="Konfirmasi Pesanan"
                                    >
                                        <CheckCircle2 class="w-3 h-3" /> Konfirmasi
                                    </button>

                                    <button
                                        v-if="t.status === 'dikonfirmasi' || t.status === 'disetujui'"
                                        @click="quickUpdate(t, 'berjalan')"
                                        type="button"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-purple-600 hover:bg-purple-500 text-white font-bold transition text-[11px] cursor-pointer"
                                        title="Mulai Sewa / Serah Terima Kunci"
                                    >
                                        <KeyRound class="w-3 h-3" /> Serah Terima
                                    </button>

                                    <button
                                        v-if="t.status === 'berjalan'"
                                        @click="quickUpdate(t, 'selesai')"
                                        type="button"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold transition text-[11px] cursor-pointer"
                                        title="Tandai Mobil Kembali / Selesai Sewa"
                                    >
                                        <CheckCheck class="w-3 h-3" /> Unit Kembali
                                    </button>

                                    <!-- Open Custom Update Modal -->
                                    <button
                                        @click="openStatusModal(t)"
                                        type="button"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-neutral-800 hover:bg-neutral-700 text-neutral-300 border border-neutral-700 font-bold transition text-[11px] cursor-pointer"
                                    >
                                        Kelola
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL: Update Status & Admin Notes -->
        <div
            v-if="isStatusModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-xs"
        >
            <div class="bg-[#0f1015] border border-neutral-800 rounded-3xl p-6 w-full max-w-md shadow-2xl relative">
                <button
                    @click="isStatusModalOpen = false"
                    class="absolute top-5 right-5 text-neutral-400 hover:text-white cursor-pointer"
                >
                    <X class="w-5 h-5" />
                </button>

                <h3 class="text-lg font-black text-white uppercase tracking-tight mb-1">
                    Ubah Status Pemesanan
                </h3>
                <p class="text-xs text-neutral-400 mb-5">
                    No. Booking: <span class="text-white font-mono font-bold">{{ selectedTrx?.nomor_booking || `#TRX-${selectedTrx?.id}` }}</span>
                    &bull; Unit: <span class="text-red-400 font-bold">{{ selectedTrx?.mobil?.nama_mobil }}</span>
                </p>

                <form @submit.prevent="submitStatusUpdate" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-neutral-300 uppercase tracking-wider text-[10px] mb-1.5">
                            Status Alur Kerja
                        </label>
                        <select
                            v-model="statusForm.status"
                            class="w-full bg-[#070709] border border-neutral-800 rounded-xl p-3 text-white focus:ring-2 focus:ring-red-600 focus:border-red-600 cursor-pointer"
                        >
                            <option value="baru">Baru / Pending (Menunggu Konfirmasi)</option>
                            <option value="dikonfirmasi">Dikonfirmasi (Siap Jalan / DP Diterima)</option>
                            <option value="berjalan">Berjalan (Unit Keluar / Sedang Disewa)</option>
                            <option value="selesai">Selesai (Unit Kembali / Selesai Sewa)</option>
                            <option value="dibatalkan">Dibatalkan (Batal / Tolak)</option>
                        </select>
                        <p class="text-[10px] text-neutral-400 mt-1">
                            *Memilih 'Berjalan' otomatis mengubah status mobil jadi <b class="text-amber-400">Disewa</b>. Memilih 'Selesai' otomatis mengembalikan mobil ke <b class="text-emerald-400">Tersedia</b>.
                        </p>
                    </div>

                    <div>
                        <label class="block font-bold text-neutral-300 uppercase tracking-wider text-[10px] mb-1.5">
                            Catatan Internal Admin (Opsional)
                        </label>
                        <textarea
                            v-model="statusForm.catatan_admin"
                            rows="3"
                            placeholder="Misal: Sudah serah terima kunci, bensin full, kondisi mulus..."
                            class="w-full bg-[#070709] border border-neutral-800 rounded-xl p-3 text-white focus:ring-2 focus:ring-red-600 focus:border-red-600"
                        ></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-neutral-800">
                        <button
                            type="button"
                            @click="isStatusModalOpen = false"
                            class="px-4 py-2 rounded-xl bg-neutral-800 text-neutral-400 hover:text-white font-bold cursor-pointer transition"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="statusForm.processing"
                            class="px-5 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white font-black tracking-wider uppercase shadow-lg shadow-red-600/30 cursor-pointer transition disabled:opacity-50"
                        >
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: Point 3 Document Review & Dispute Freeze (UU PDP) -->
        <div
            v-if="isDocModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/85 backdrop-blur-xs"
        >
            <div class="bg-[#0f1015] border border-neutral-800 rounded-3xl p-6 w-full max-w-2xl shadow-2xl relative max-h-[90vh] overflow-y-auto">
                <button
                    @click="isDocModalOpen = false"
                    class="absolute top-5 right-5 text-neutral-400 hover:text-white cursor-pointer"
                >
                    <X class="w-5 h-5" />
                </button>

                <div class="flex items-center gap-2 mb-1">
                    <Shield class="w-5 h-5 text-red-500" />
                    <h3 class="text-lg font-black text-white uppercase tracking-tight">
                        Verifikasi Berkas KTP / SIM & UU PDP
                    </h3>
                </div>
                <p class="text-xs text-neutral-400 mb-6">
                    Booking: <span class="text-white font-mono font-bold">{{ selectedDocTrx?.nomor_booking }}</span> &bull; Penyewa: <span class="text-white font-bold">{{ selectedDocTrx?.user?.name || selectedDocTrx?.nama_pelanggan_offline }}</span>
                </p>

                <!-- Document Status Notice -->
                <div v-if="selectedDocTrx?.dokumen_purged_at" class="p-4 rounded-2xl bg-neutral-900 border border-neutral-800 text-xs text-neutral-300 mb-6">
                    <p class="font-bold text-white mb-1">🔒 Berkas Telah Dihapus Otomatis (Kepatuhan UU PDP)</p>
                    <p class="text-neutral-400">
                        Berkas KTP & SIM transaksi ini telah dihapus permanen dari server pada {{ formatDate(selectedDocTrx.dokumen_purged_at) }} setelah melewati masa retensi 30 hari unit kembali.
                    </p>
                </div>

                <!-- Document Links Grid if not purged -->
                <div v-else class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <!-- KTP Card -->
                    <div class="p-4 rounded-2xl bg-[#070709] border border-neutral-800 text-center space-y-3">
                        <span class="text-xs font-bold uppercase text-neutral-300 block">Berkas e-KTP Pemesan</span>
                        <div v-if="selectedDocTrx?.ktp_path" class="space-y-2">
                            <a
                                :href="`/admin/dokumen/${selectedDocTrx.id}/ktp`"
                                target="_blank"
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs uppercase tracking-wider transition cursor-pointer"
                            >
                                Buka Foto e-KTP &rarr;
                            </a>
                            <span class="text-[10px] text-neutral-500 block">Tersimpan di private disk terenkripsi</span>
                        </div>
                        <span v-else class="text-xs text-neutral-500 italic block">KTP belum diunggah</span>
                    </div>

                    <!-- SIM Card -->
                    <div class="p-4 rounded-2xl bg-[#070709] border border-neutral-800 text-center space-y-3">
                        <span class="text-xs font-bold uppercase text-neutral-300 block">Berkas SIM A Pengemudi</span>
                        <div v-if="selectedDocTrx?.sim_path" class="space-y-2">
                            <a
                                :href="`/admin/dokumen/${selectedDocTrx.id}/sim`"
                                target="_blank"
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs uppercase tracking-wider transition cursor-pointer"
                            >
                                Buka Foto SIM A &rarr;
                            </a>
                            <span class="text-[10px] text-neutral-500 block">Tersimpan di private disk terenkripsi</span>
                        </div>
                        <span v-else class="text-xs text-neutral-500 italic block">SIM belum diunggah</span>
                    </div>
                </div>

                <!-- UU PDP Consent Audit Details -->
                <div class="p-4 rounded-2xl bg-neutral-900/80 border border-neutral-800 text-xs space-y-2 mb-6">
                    <span class="text-[10px] font-black uppercase tracking-wider text-neutral-400 block">
                        Jejak Kepatuhan UU PDP No. 27/2022
                    </span>
                    <div class="grid grid-cols-2 gap-2 text-neutral-300 text-[11px]">
                        <div>Persetujuan PDP: <b class="text-emerald-400">{{ selectedDocTrx?.pdp_consent ? 'Disetujui Pelanggan' : 'Manual / Belum' }}</b></div>
                        <div>Waktu Consent: <b class="text-white">{{ formatDate(selectedDocTrx?.pdp_consent_at) }}</b></div>
                        <div>No. WhatsApp Verified: <b class="text-white">{{ selectedDocTrx?.no_hp_pelanggan || '-' }}</b></div>
                        <div>IP Address: <b class="text-white font-mono">{{ selectedDocTrx?.pdp_consent_ip || 'Local/Internal' }}</b></div>
                    </div>
                </div>

                <!-- Dispute Freeze Switch (Criminal / Theft Investigation Protection) -->
                <form @submit.prevent="submitDisputeToggle" class="border-t border-neutral-800 pt-5 space-y-4">
                    <div class="bg-rose-950/30 border border-rose-800/50 rounded-2xl p-4 space-y-3">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input
                                v-model="disputeForm.is_disputed"
                                type="checkbox"
                                class="mt-1 w-4 h-4 rounded text-red-600 bg-neutral-900 border-neutral-700 focus:ring-red-500 cursor-pointer"
                            />
                            <div>
                                <span class="text-xs font-black uppercase tracking-wider text-rose-400 block">
                                    Tandai Status Sengketa / Investigasi Polisi (FREEZE AUTO-PURGE)
                                </span>
                                <p class="text-[11px] text-neutral-300 mt-1 leading-relaxed">
                                    Jika opsi ini dicentang, berkas KTP & SIM <b>TIDAK AKAN PERNAH DIHAPUS</b> secara otomatis oleh sistem scheduler 30 hari, untuk keperluan barang bukti tindak pidana penggelapan / kecelakaan hukum.
                                </p>
                            </div>
                        </label>

                        <div v-if="disputeForm.is_disputed">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-300 mb-1">
                                Catatan Kasus / No. Laporan Polisi (Opsional)
                            </label>
                            <textarea
                                v-model="disputeForm.dispute_reason"
                                rows="2"
                                placeholder="Contoh: Unit belum kembali per 3 hari, telah dilaporkan ke Polsek setempat..."
                                class="w-full bg-[#070709] border border-neutral-800 rounded-xl p-2.5 text-xs text-white"
                            ></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button
                            type="button"
                            @click="isDocModalOpen = false"
                            class="px-4 py-2 rounded-xl bg-neutral-800 text-neutral-400 hover:text-white font-bold text-xs cursor-pointer"
                        >
                            Tutup
                        </button>
                        <button
                            type="submit"
                            :disabled="disputeForm.processing"
                            class="px-5 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-red-600/30 cursor-pointer transition disabled:opacity-50"
                        >
                            Simpan Pengaturan Sengketa
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: Point 4 VIP Driver Assignment -->
        <div
            v-if="isDriverModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/85 backdrop-blur-xs"
        >
            <div class="bg-[#0f1015] border border-neutral-800 rounded-3xl p-6 w-full max-w-lg shadow-2xl relative">
                <button
                    @click="isDriverModalOpen = false"
                    class="absolute top-5 right-5 text-neutral-400 hover:text-white cursor-pointer"
                >
                    <X class="w-5 h-5" />
                </button>

                <div class="flex items-center gap-2 mb-1">
                    <UserCheck class="w-5 h-5 text-red-500" />
                    <h3 class="text-lg font-black text-white uppercase tracking-tight">
                        Alokasi Driver VIP &amp; Dispatch WA
                    </h3>
                </div>
                <p class="text-xs text-neutral-400 mb-5">
                    Booking: <span class="text-white font-mono font-bold">{{ selectedDriverTrx?.nomor_booking }}</span> &bull; Armada: <span class="text-white font-bold">{{ selectedDriverTrx?.mobil?.nama_mobil }}</span>
                </p>

                <div class="p-3 bg-neutral-900/80 rounded-xl border border-neutral-800 text-xs mb-5 space-y-1 text-neutral-300">
                    <div>Penyewa: <b class="text-white">{{ selectedDriverTrx?.user?.name || selectedDriverTrx?.nama_pelanggan_offline }}</b></div>
                    <div>WhatsApp Tamu: <b class="text-emerald-400">{{ selectedDriverTrx?.no_hp_pelanggan || selectedDriverTrx?.no_hp_offline || '-' }}</b></div>
                    <div>Lokasi Jemput: <span class="text-neutral-400">{{ selectedDriverTrx?.lokasi_jemput }}</span></div>
                </div>

                <form @submit.prevent="submitDriverAssign" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1">
                            Nama Lengkap Driver VIP *
                        </label>
                        <input
                            v-model="driverForm.nama_sopir"
                            type="text"
                            required
                            placeholder="Contoh: Budi Santoso (VIP Chauffeur)"
                            class="w-full bg-[#070709] border border-neutral-800 rounded-xl p-3 text-xs text-white placeholder-neutral-600 focus:border-red-500 focus:outline-none"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1">
                            No. WhatsApp / Telepon Driver *
                        </label>
                        <input
                            v-model="driverForm.no_hp_sopir"
                            type="tel"
                            required
                            placeholder="Contoh: 081234567890"
                            class="w-full bg-[#070709] border border-neutral-800 rounded-xl p-3 text-xs text-white placeholder-neutral-600 focus:border-red-500 focus:outline-none"
                        />
                    </div>

                    <div class="bg-red-950/20 border border-red-900/40 rounded-xl p-3">
                        <label class="flex items-start gap-2.5 cursor-pointer">
                            <input
                                v-model="driverForm.notify_customer"
                                type="checkbox"
                                class="mt-0.5 w-4 h-4 rounded text-red-600 bg-neutral-900 border-neutral-700 focus:ring-red-500 cursor-pointer"
                            />
                            <span class="text-[11px] text-neutral-300 leading-snug">
                                <b class="text-white">Kirim WhatsApp Otomatis:</b> Kirim pesan rincian kontak driver, armada, dan estimasi waktu standby langsung ke nomor tamu.
                            </span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3">
                        <button
                            type="button"
                            @click="isDriverModalOpen = false"
                            class="px-4 py-2 rounded-xl bg-neutral-800 text-neutral-400 hover:text-white font-bold text-xs cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="driverForm.processing"
                            class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-red-600/30 cursor-pointer transition disabled:opacity-50 flex items-center gap-1.5"
                        >
                            <UserCheck class="w-4 h-4" />
                            Simpan &amp; Dispatch Driver
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: Point 4 Resend Automated E-Voucher Notification -->
        <div
            v-if="isResendModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/85 backdrop-blur-xs"
        >
            <div class="bg-[#0f1015] border border-neutral-800 rounded-3xl p-6 w-full max-w-md shadow-2xl relative">
                <button
                    @click="isResendModalOpen = false"
                    class="absolute top-5 right-5 text-neutral-400 hover:text-white cursor-pointer"
                >
                    <X class="w-5 h-5" />
                </button>

                <div class="flex items-center gap-2 mb-1">
                    <Send class="w-5 h-5 text-red-500" />
                    <h3 class="text-lg font-black text-white uppercase tracking-tight">
                        Kirim Ulang E-Voucher
                    </h3>
                </div>
                <p class="text-xs text-neutral-400 mb-4">
                    Kirim konfirmasi resmi &amp; tiket digital untuk booking <span class="text-white font-mono font-bold">{{ selectedResendTrx?.nomor_booking }}</span>.
                </p>

                <div class="p-3 bg-neutral-900/80 rounded-xl border border-neutral-800 text-xs mb-5 space-y-1 text-neutral-300">
                    <div>Penerima: <b class="text-white">{{ selectedResendTrx?.user?.name || selectedResendTrx?.nama_pelanggan_offline }}</b></div>
                    <div>No. WhatsApp: <b class="text-emerald-400">{{ selectedResendTrx?.no_hp_pelanggan || selectedResendTrx?.no_hp_offline || '-' }}</b></div>
                    <div>Email: <b class="text-blue-400">{{ selectedResendTrx?.user?.email || '-' }}</b></div>
                </div>

                <form @submit.prevent="submitResendNotification" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-2">
                            Pilih Saluran Pengiriman:
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 p-2.5 rounded-xl border border-neutral-800 bg-[#070709] cursor-pointer hover:border-neutral-700">
                                <input
                                    type="radio"
                                    value="all"
                                    v-model="resendForm.channel"
                                    class="text-red-600 bg-neutral-900 border-neutral-700 focus:ring-red-500"
                                />
                                <div class="text-xs">
                                    <span class="font-bold text-white block">Semua Saluran (WhatsApp + Fallback Email)</span>
                                    <span class="text-[11px] text-neutral-500">Direkomendasikan untuk memastikan tamu menerima tiket</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-2.5 rounded-xl border border-neutral-800 bg-[#070709] cursor-pointer hover:border-neutral-700">
                                <input
                                    type="radio"
                                    value="whatsapp"
                                    v-model="resendForm.channel"
                                    class="text-red-600 bg-neutral-900 border-neutral-700 focus:ring-red-500"
                                />
                                <div class="text-xs">
                                    <span class="font-bold text-white block">WhatsApp Saja</span>
                                    <span class="text-[11px] text-neutral-500">Format teks rapi dengan link deep-voucher</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-2.5 rounded-xl border border-neutral-800 bg-[#070709] cursor-pointer hover:border-neutral-700">
                                <input
                                    type="radio"
                                    value="email"
                                    v-model="resendForm.channel"
                                    class="text-red-600 bg-neutral-900 border-neutral-700 focus:ring-red-500"
                                />
                                <div class="text-xs">
                                    <span class="font-bold text-white block">Email Saja</span>
                                    <span class="text-[11px] text-neutral-500">Luxury HTML template dengan rincian biaya</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3">
                        <button
                            type="button"
                            @click="isResendModalOpen = false"
                            class="px-4 py-2 rounded-xl bg-neutral-800 text-neutral-400 hover:text-white font-bold text-xs cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="resendForm.processing"
                            class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-red-600/30 cursor-pointer transition disabled:opacity-50 flex items-center gap-1.5"
                        >
                            <Send class="w-4 h-4" />
                            Kirim Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
