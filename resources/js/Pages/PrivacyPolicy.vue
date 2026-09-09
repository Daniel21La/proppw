<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { Shield, ArrowLeft, Lock, FileCheck, CheckCircle2, Phone, Mail, Clock } from 'lucide-vue-next';
</script>

<template>
    <Head title="Kebijakan Privasi & Perlindungan Data (UU PDP No. 27/2022) - Quantum Streamline" />

    <div class="min-h-screen bg-[#070709] text-slate-100 py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto space-y-8">
            <!-- Header Navigation -->
            <div class="flex items-center justify-between">
                <Link href="/" class="inline-flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-white transition">
                    <ArrowLeft class="w-4 h-4" />
                    Kembali ke Beranda
                </Link>
                <div class="flex items-center gap-2 bg-emerald-500/10 border border-emerald-500/30 px-3 py-1 rounded-full text-xs font-semibold text-emerald-400">
                    <Shield class="w-3.5 h-3.5" />
                    Patuh UU PDP No. 27/2022
                </div>
            </div>

            <!-- Page Title Card -->
            <div class="bg-gradient-to-r from-blue-950/60 via-slate-900 to-[#0f1015] border border-blue-500/30 rounded-3xl p-6 sm:p-8 shadow-2xl">
                <div class="inline-block px-3 py-1 bg-blue-500/20 rounded-full text-xs font-bold text-blue-300 uppercase tracking-wider mb-3">
                    LEGAL & PRIVACY COMPLIANCE
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                    Kebijakan Privasi & Perlindungan Data Pribadi
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 mt-2 leading-relaxed">
                    Komitmen resmi PT Quantum Streamline Rent Car dalam menjaga, mengenkripsi, dan mengelola data identitas pelanggan sesuai dengan kerangka hukum Undang-Undang Perlindungan Data Pribadi (UU PDP) No. 27 Tahun 2022 Republik Indonesia.
                </p>
                <div class="flex flex-wrap items-center gap-4 mt-4 pt-4 border-t border-slate-800 text-xs text-slate-400 font-mono">
                    <span>Terakhir diperbarui: 9 September 2026</span>
                    <span>•</span>
                    <span>Versi Dokumen: 2.1-PDP</span>
                </div>
            </div>

            <!-- Policy Content Sections -->
            <div class="space-y-6 text-sm text-slate-300 leading-relaxed">
                <!-- Section 1 -->
                <div class="bg-[#0f1015] border border-neutral-800 rounded-3xl p-6 sm:p-7 space-y-3">
                    <h3 class="text-base font-bold text-white uppercase tracking-wider flex items-center gap-2">
                        <Lock class="w-4 h-4 text-cyan-400" />
                        1. Pengumpulan & Tipe Data Pribadi
                    </h3>
                    <p class="text-xs text-slate-400">
                        Untuk kepentingan validasi keamanan armada dan kelayakan sewa kendaraan (khususnya layanan Lepas Kunci), kami mengumpulkan informasi terbatas berupa:
                    </p>
                    <ul class="list-disc pl-5 space-y-1.5 text-xs text-slate-300">
                        <li class="font-medium"><b>Identitas Utama:</b> Nama lengkap sesuai e-KTP dan alamat email aktif.</li>
                        <li class="font-medium"><b>Kontak Komunikasi:</b> Nomor WhatsApp / HP aktif untuk pengiriman kode OTP verifikasi & E-Voucher.</li>
                        <li class="font-medium"><b>Dokumen Verifikasi (Lepas Kunci):</b> Salinan digital foto e-KTP asli dan SIM A resmi yang masih berlaku.</li>
                        <li class="font-medium"><b>Data Logistik:</b> Alamat/lokasi penjemputan unit kendaraan dan koordinat pengantaran.</li>
                    </ul>
                </div>

                <!-- Section 2 -->
                <div class="bg-[#0f1015] border border-neutral-800 rounded-3xl p-6 sm:p-7 space-y-3">
                    <h3 class="text-base font-bold text-white uppercase tracking-wider flex items-center gap-2">
                        <Shield class="w-4 h-4 text-emerald-400" />
                        2. Enkripsi & Metode Penyimpanan (Private Disk Storage)
                    </h3>
                    <p class="text-xs text-slate-400">
                        Seluruh salinan dokumen KTP & SIM yang diunggah oleh pelanggan <b>TIDAK PERNAH</b> disimpan di folder publik webserver.
                    </p>
                    <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 space-y-2 text-xs">
                        <p class="font-semibold text-emerald-300 flex items-center gap-1.5">
                            <CheckCircle2 class="w-4 h-4" /> Penyimpanan Terisolasi (Storage Private Disk)
                        </p>
                        <p class="text-slate-400">
                            Berkas dokumen fisik disimpan di jalur direktori private terenkripsi (`storage/app/private/`). Akses langsung via URL publik diblokir penuh (HTTP 403 Forbidden). Hanya Sistem Otorisasi Internal yang dapat mengakses streaming berkas khusus untuk Admin Verifikator atau Pemilik Transaksi yang sah.
                        </p>
                    </div>
                </div>

                <!-- Section 3 -->
                <div class="bg-[#0f1015] border border-neutral-800 rounded-3xl p-6 sm:p-7 space-y-3">
                    <h3 class="text-base font-bold text-white uppercase tracking-wider flex items-center gap-2">
                        <Clock class="w-4 h-4 text-amber-400" />
                        3. Kebijakan Retensi Data & Pembersihan Otomatis (30-Day Auto-Purge)
                    </h3>
                    <p class="text-xs text-slate-400">
                        Sesuai azas pembatasan penyimpanan UU PDP No. 27/2022, data dokumen sensitif tidak disimpan tanpa batas waktu.
                    </p>
                    <div class="p-4 rounded-2xl bg-amber-950/40 border border-amber-500/30 space-y-2 text-xs">
                        <p class="font-bold text-amber-300">📌 Skema Auto-Purge 30 Hari:</p>
                        <p class="text-slate-300">
                            Salinan berkas e-KTP dan SIM A fisik akan <b>DIHAPUS SECARA PERMANEN DAN OTOMATIS</b> dari sistem server dalam waktu tepat 30 hari setelah unit kendaraan dikembalikan (`tanggal_kembali_aktual`).
                        </p>
                        <p class="text-slate-400 text-[11px] italic">
                            *Pengecualian: Apabila timbul klaim sengketa hukum, pelanggaran lalu lintas berat, atau investigasi kepolisian, Admin berhak mengaktifkan pembekuan dokumen (`is_disputed = true`) sampai proses penyelidikan dinyatakan selesai.
                        </p>
                    </div>
                </div>

                <!-- Section 4 -->
                <div class="bg-[#0f1015] border border-neutral-800 rounded-3xl p-6 sm:p-7 space-y-3">
                    <h3 class="text-base font-bold text-white uppercase tracking-wider flex items-center gap-2">
                        <FileCheck class="w-4 h-4 text-indigo-400" />
                        4. Hak-Hak Subjek Data Pribadi
                    </h3>
                    <p class="text-xs text-slate-400">
                        Sebagai pemilik data pribadi, Anda memiliki hak-hak yang dijamin Undang-Undang meliputi:
                    </p>
                    <ul class="list-disc pl-5 space-y-1 text-xs text-slate-300">
                        <li>Hak mengakses dan memperoleh salinan data pribadi yang kami simpan.</li>
                        <li>Hak memperbarui atau memperbaiki keliruan data identitas Anda.</li>
                        <li>Hak meminta penghentian pemrosesan atau penghapusan data sebelum masa retensi (apabila tidak ada transaksi aktif yang mengikat).</li>
                    </ul>
                </div>

                <!-- Section 5 -->
                <div class="bg-gradient-to-r from-neutral-900 to-slate-900 border border-neutral-800 rounded-3xl p-6 sm:p-7 space-y-3">
                    <h3 class="text-base font-bold text-white uppercase tracking-wider">
                        5. Kontak Pejabat Data Protection Officer (DPO)
                    </h3>
                    <p class="text-xs text-slate-400">
                        Jika Anda memiliki pertanyaan seputar Kebijakan Privasi atau permohonan hak subjek data, silakan hubungi tim legal kami:
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 text-xs">
                        <div class="bg-slate-950 p-3.5 rounded-xl border border-slate-800">
                            <span class="text-slate-500 font-bold block">EMAIL DPO / PRIVACY CS</span>
                            <span class="text-white font-mono font-bold">privacy@quantumrent.co.id</span>
                        </div>
                        <div class="bg-slate-950 p-3.5 rounded-xl border border-slate-800">
                            <span class="text-slate-500 font-bold block">WHATSAPP LEGAL CONCIERGE</span>
                            <span class="text-emerald-400 font-mono font-bold">+62 812-3456-7890</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Back Link -->
            <div class="text-center pt-6">
                <Link href="/" class="inline-flex items-center px-6 py-3 rounded-2xl bg-neutral-800 hover:bg-neutral-700 text-white font-bold text-xs uppercase tracking-wider transition">
                    Kembali ke Beranda Utama
                </Link>
            </div>
        </div>
    </div>
</template>
