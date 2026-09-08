<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import {
    ArrowLeft,
    Car,
    UploadCloud,
    Save,
    Image as ImageIcon,
    Sparkles,
} from 'lucide-vue-next';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    mobil: {
        type: Object,
        default: null,
    },
});

const isEdit = !!props.mobil?.id;

const form = useForm({
    merk: props.mobil?.merk || '',
    nama_mobil: props.mobil?.nama_mobil || '',
    nopol: props.mobil?.nopol || '',
    tipe_kendaraan: props.mobil?.tipe_kendaraan || 'Sedan Premium',
    transmisi: props.mobil?.transmisi || 'Otomatis',
    kapasitas_penumpang: props.mobil?.kapasitas_penumpang || 5,
    harga_per_hari: props.mobil?.harga_per_hari || '',
    biaya_sopir_per_hari: props.mobil?.biaya_sopir_per_hari || 250000,
    status: props.mobil?.status || 'tersedia',
    gambar: null,
});

const previewUrl = ref(
    props.mobil?.gambar ? `/gambar_mobil/${props.mobil.gambar}` : null
);

function handleFileSelect(e) {
    const file = e.target.files[0];
    if (file) {
        form.gambar = file;
        previewUrl.value = URL.createObjectURL(file);
    }
}

function submit() {
    const url = isEdit
        ? `/admin/mobil/form/${props.mobil.id}`
        : '/admin/mobil/form';

    form.post(url, {
        forceFormData: true,
    });
}
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="isEdit ? 'Edit Unit Mobil - Quantum Streamline' : 'Tambah Mobil Baru - Quantum Streamline'" />

        <!-- Header -->
        <div class="max-w-3xl mx-auto mb-6">
            <Link
                href="/admin/mobil"
                class="inline-flex items-center gap-2 text-xs font-bold text-neutral-400 hover:text-white mb-3 transition"
            >
                <ArrowLeft class="w-4 h-4" />
                Kembali ke Daftar Mobil
            </Link>

            <div class="flex items-center gap-2 mb-1">
                <div class="w-2 h-2 rounded-full bg-red-600" />
                <span class="text-[10px] font-black uppercase tracking-widest text-red-500">
                    Fleet Catalog Entry
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-white uppercase tracking-tight">
                {{ isEdit ? 'Edit Data Unit Armada' : 'Tambah Unit Armada Baru' }}
            </h1>
            <p class="text-xs text-neutral-400 mt-1">
                Lengkapi spesifikasi teknis, transmisi, kapasitas penumpang, serta tarif harian dan layanan sopir.
            </p>
        </div>

        <!-- Form Card -->
        <div class="max-w-3xl mx-auto bg-[#0f1015] rounded-3xl p-6 sm:p-8 border border-neutral-800 shadow-2xl">
            <form @submit.prevent="submit" class="space-y-6">
                <!-- Merk & Nama Mobil Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-neutral-300 mb-1.5">
                            Merk Mobil <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.merk"
                            type="text"
                            required
                            placeholder="Contoh: Audi, BMW, Toyota, Porsche"
                            class="w-full px-4 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white placeholder:text-neutral-600 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition"
                        />
                        <p v-if="form.errors.merk" class="text-xs text-red-500 font-medium mt-1">
                            {{ form.errors.merk }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-neutral-300 mb-1.5">
                            Nama / Seri Mobil <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.nama_mobil"
                            type="text"
                            required
                            placeholder="Contoh: Audi RS7 Sportback, Camry Hybrid"
                            class="w-full px-4 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white placeholder:text-neutral-600 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition"
                        />
                        <p v-if="form.errors.nama_mobil" class="text-xs text-red-500 font-medium mt-1">
                            {{ form.errors.nama_mobil }}
                        </p>
                    </div>
                </div>

                <!-- Plat Nomor & Tipe Kendaraan -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-neutral-300 mb-1.5">
                            Plat Nomor Kendaraan
                        </label>
                        <input
                            v-model="form.nopol"
                            type="text"
                            placeholder="Contoh: B 1234 RFS"
                            class="w-full px-4 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white placeholder:text-neutral-600 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition uppercase"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-neutral-300 mb-1.5">
                            Kategori / Tipe Kendaraan
                        </label>
                        <select
                            v-model="form.tipe_kendaraan"
                            class="w-full px-4 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                        >
                            <option value="Sedan Premium">Sedan Premium</option>
                            <option value="Sports Coupe">Sports Coupe</option>
                            <option value="Luxury SUV">Luxury SUV</option>
                            <option value="Executive MPV">Executive MPV</option>
                            <option value="Electric Vehicle">Electric Vehicle</option>
                        </select>
                    </div>
                </div>

                <!-- Transmisi & Kapasitas Penumpang -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-neutral-300 mb-1.5">
                            Transmisi
                        </label>
                        <select
                            v-model="form.transmisi"
                            class="w-full px-4 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                        >
                            <option value="Otomatis">Otomatis (Automatic)</option>
                            <option value="Manual">Manual</option>
                            <option value="Dual-Clutch">Dual-Clutch Paddle Shift</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-neutral-300 mb-1.5">
                            Kapasitas Kursi Penumpang
                        </label>
                        <input
                            v-model="form.kapasitas_penumpang"
                            type="number"
                            min="2"
                            max="12"
                            class="w-full px-4 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                        />
                    </div>
                </div>

                <!-- Harga Per Hari & Biaya Sopir -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-neutral-300 mb-1.5">
                            Tarif Sewa Lepas Kunci (Rp/Hari) <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.harga_per_hari"
                            type="number"
                            min="0"
                            required
                            placeholder="Contoh: 750000"
                            class="w-full px-4 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white placeholder:text-neutral-600 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition"
                        />
                        <p v-if="form.errors.harga_per_hari" class="text-xs text-red-500 font-medium mt-1">
                            {{ form.errors.harga_per_hari }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-neutral-300 mb-1.5">
                            Biaya Sopir Tambahan (Rp/Hari)
                        </label>
                        <input
                            v-model="form.biaya_sopir_per_hari"
                            type="number"
                            min="0"
                            placeholder="Contoh: 250000"
                            class="w-full px-4 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white placeholder:text-neutral-600 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition"
                        />
                    </div>
                </div>

                <!-- Status Ketersediaan Unit -->
                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-neutral-300 mb-1.5">
                        Status Ketersediaan Armada
                    </label>
                    <select
                        v-model="form.status"
                        class="w-full px-4 py-2.5 rounded-xl border border-neutral-800 bg-[#070709] text-xs text-white focus:ring-2 focus:ring-red-600 transition"
                    >
                        <option value="tersedia">Tersedia (Ready di Garasi)</option>
                        <option value="disewa">Sedang Disewa (Active Rent)</option>
                    </select>
                </div>

                <!-- Gambar Upload -->
                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-neutral-300 mb-1.5">
                        Foto / Banner Mobil
                    </label>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-5 p-4 rounded-2xl bg-[#070709] border border-neutral-800">
                        <div class="w-32 h-24 rounded-xl bg-neutral-900 border border-neutral-800 overflow-hidden flex items-center justify-center shrink-0">
                            <img
                                v-if="previewUrl"
                                :src="previewUrl"
                                alt="Preview"
                                class="w-full h-full object-cover"
                            />
                            <ImageIcon v-else class="w-8 h-8 text-neutral-600" />
                        </div>

                        <div class="flex-1 w-full text-center sm:text-left">
                            <input
                                type="file"
                                id="car_image"
                                accept="image/*"
                                @change="handleFileSelect"
                                class="hidden"
                            />
                            <label
                                for="car_image"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-neutral-800 hover:bg-neutral-700 text-white text-xs font-bold uppercase tracking-wider transition cursor-pointer border border-neutral-700"
                            >
                                <UploadCloud class="w-4 h-4 text-red-500" />
                                Pilih File Gambar
                            </label>
                            <p class="text-[11px] text-neutral-500 mt-2">
                                Format didukung: JPG, PNG, WEBP. Maksimal 3MB.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-neutral-800 flex items-center justify-end gap-3">
                    <Link
                        href="/admin/mobil"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider text-neutral-400 hover:text-white transition"
                    >
                        Batal
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 active:scale-95 text-white text-xs font-black uppercase tracking-wider shadow-lg shadow-red-600/30 transition duration-150 disabled:opacity-50 cursor-pointer"
                    >
                        <Save class="w-4 h-4" />
                        {{ form.processing ? 'Menyimpan...' : (isEdit ? 'Simpan Perubahan' : 'Terbitkan Mobil') }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
