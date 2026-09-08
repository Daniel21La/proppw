import React, { useState } from 'react';
import { Car, Eye, EyeOff, Lock, Mail, User, Phone, ArrowRight, ShieldCheck, Check } from 'lucide-react';

export default function Register() {
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);
  
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [phone, setPhone] = useState('');
  const [password, setPassword] = useState('');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');
  const [agreeTerms, setAgreeTerms] = useState(false);
  const [isLoading, setIsLoading] = useState(false);

  const handleSubmit = (e) => {
    e.preventDefault();
    if (password !== passwordConfirmation) {
      alert('Konfirmasi kata sandi tidak cocok.');
      return;
    }
    if (!agreeTerms) {
      alert('Mohon setujui Syarat & Ketentuan.');
      return;
    }

    setIsLoading(true);
    setTimeout(() => {
      setIsLoading(false);
      alert(`Pendaftaran berhasil untuk: ${name} (${email})`);
    }, 1000);
  };

  return (
    <div className="min-h-screen w-full bg-[#0a0a0a] text-white flex flex-col lg:flex-row font-['Poppins',_sans-serif] selection:bg-[#e63946] selection:text-white">
      
      {/* ========================================================
          SISI KIRI: Form Pendaftaran (~45% lebar layar desktop)
      ======================================================== */}
      <div className="w-full lg:w-[45%] min-h-screen flex flex-col justify-between p-6 sm:p-10 lg:p-14 z-10 overflow-y-auto">
        
        {/* LOGO: QUANTUM STREAMLINE */}
        <div className="flex items-center gap-3">
          <div className="w-10 h-10 rounded-xl bg-[#141414] border border-[#2a2a2a] flex items-center justify-center shadow-lg shadow-black/50">
            <Car className="w-5 h-5 text-[#ff4d4d]" />
          </div>
          <div>
            <span className="text-base sm:text-lg font-black tracking-wider uppercase text-white block">
              QUANTUM <span className="text-transparent bg-clip-text bg-gradient-to-r from-[#e63946] to-[#ff4d4d]">STREAMLINE</span>
            </span>
            <span className="text-[9px] font-bold tracking-widest uppercase text-neutral-400 block -mt-1">
              Luxury Car Rental
            </span>
          </div>
        </div>

        {/* FORM CONTAINER */}
        <div className="my-auto py-6 max-w-md w-full mx-auto lg:mx-0">
          
          {/* HEADER FORM */}
          <div className="mb-6">
            <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#1a1a1a] border border-[#333333] text-[11px] font-bold text-neutral-300 uppercase tracking-widest mb-3">
              <span className="w-2 h-2 rounded-full bg-[#e63946] animate-pulse" />
              New Member Registration
            </div>
            
            <h1 className="text-3xl sm:text-4xl font-black uppercase tracking-tight text-white leading-tight">
              BUAT AKUN BARU
            </h1>
            <p className="text-xs sm:text-sm text-neutral-400 mt-2 font-normal leading-relaxed">
              Daftarkan diri Anda untuk menikmati akses eksklusif ke seluruh koleksi kendaraan mewah kami.
            </p>
          </div>

          {/* FORM INPUTS */}
          <form onSubmit={handleSubmit} className="space-y-4">
            
            {/* INPUT: NAMA LENGKAP */}
            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1.5">
                Nama Lengkap
              </label>
              <div className="relative">
                <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-neutral-500">
                  <User className="w-4 h-4" />
                </div>
                <input
                  type="text"
                  required
                  value={name}
                  onChange={(e) => setName(e.target.value)}
                  placeholder="Nama Lengkap Anda"
                  className="w-full bg-[#1a1a1a] border border-[#333333] rounded-[14px] pl-11 pr-4 py-3 text-xs sm:text-sm text-white placeholder:text-neutral-500 focus:outline-none focus:border-[#e63946] focus:ring-1 focus:ring-[#e63946] transition-all duration-200"
                />
              </div>
            </div>

            {/* INPUT: EMAIL */}
            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1.5">
                Alamat Email
              </label>
              <div className="relative">
                <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-neutral-500">
                  <Mail className="w-4 h-4" />
                </div>
                <input
                  type="email"
                  required
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  placeholder="nama@email.com"
                  className="w-full bg-[#1a1a1a] border border-[#333333] rounded-[14px] pl-11 pr-4 py-3 text-xs sm:text-sm text-white placeholder:text-neutral-500 focus:outline-none focus:border-[#e63946] focus:ring-1 focus:ring-[#e63946] transition-all duration-200"
                />
              </div>
            </div>

            {/* INPUT: NOMOR HP */}
            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1.5">
                Nomor HP / WhatsApp
              </label>
              <div className="relative">
                <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-neutral-500">
                  <Phone className="w-4 h-4" />
                </div>
                <input
                  type="tel"
                  required
                  value={phone}
                  onChange={(e) => setPhone(e.target.value)}
                  placeholder="081234567890"
                  className="w-full bg-[#1a1a1a] border border-[#333333] rounded-[14px] pl-11 pr-4 py-3 text-xs sm:text-sm text-white placeholder:text-neutral-500 focus:outline-none focus:border-[#e63946] focus:ring-1 focus:ring-[#e63946] transition-all duration-200"
                />
              </div>
            </div>

            {/* INPUT: KATA SANDI */}
            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1.5">
                Kata Sandi
              </label>
              <div className="relative">
                <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-neutral-500">
                  <Lock className="w-4 h-4" />
                </div>
                <input
                  type={showPassword ? 'text' : 'password'}
                  required
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  placeholder="Minimal 8 karakter"
                  className="w-full bg-[#1a1a1a] border border-[#333333] rounded-[14px] pl-11 pr-11 py-3 text-xs sm:text-sm text-white placeholder:text-neutral-500 focus:outline-none focus:border-[#e63946] focus:ring-1 focus:ring-[#e63946] transition-all duration-200"
                />
                <button
                  type="button"
                  onClick={() => setShowPassword(!showPassword)}
                  className="absolute inset-y-0 right-0 pr-4 flex items-center text-neutral-400 hover:text-white transition-colors cursor-pointer"
                  title="Lihat / Sembunyikan Sandi"
                >
                  {showPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                </button>
              </div>
            </div>

            {/* INPUT: KONFIRMASI KATA SANDI */}
            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-1.5">
                Konfirmasi Kata Sandi
              </label>
              <div className="relative">
                <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-neutral-500">
                  <Lock className="w-4 h-4" />
                </div>
                <input
                  type={showConfirmPassword ? 'text' : 'password'}
                  required
                  value={passwordConfirmation}
                  onChange={(e) => setPasswordConfirmation(e.target.value)}
                  placeholder="Ulangi kata sandi Anda"
                  className="w-full bg-[#1a1a1a] border border-[#333333] rounded-[14px] pl-11 pr-11 py-3 text-xs sm:text-sm text-white placeholder:text-neutral-500 focus:outline-none focus:border-[#e63946] focus:ring-1 focus:ring-[#e63946] transition-all duration-200"
                />
                <button
                  type="button"
                  onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                  className="absolute inset-y-0 right-0 pr-4 flex items-center text-neutral-400 hover:text-white transition-colors cursor-pointer"
                  title="Lihat / Sembunyikan Sandi"
                >
                  {showConfirmPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                </button>
              </div>
            </div>

            {/* CHECKBOX: SYARAT & KETENTUAN */}
            <div className="pt-1">
              <label className="flex items-start gap-2.5 cursor-pointer select-none">
                <input
                  type="checkbox"
                  required
                  checked={agreeTerms}
                  onChange={(e) => setAgreeTerms(e.target.checked)}
                  className="w-4 h-4 mt-0.5 rounded text-[#e63946] bg-[#1a1a1a] border-[#333333] focus:ring-[#e63946] focus:ring-offset-0 focus:outline-none transition cursor-pointer"
                />
                <span className="text-xs text-neutral-400 leading-relaxed">
                  Saya menyetujui{' '}
                  <a href="#syarat" className="text-white hover:text-[#ff4d4d] underline underline-offset-2">
                    Syarat & Ketentuan
                  </a>{' '}
                  serta{' '}
                  <a href="#privasi" className="text-white hover:text-[#ff4d4d] underline underline-offset-2">
                    Kebijakan Privasi
                  </a>{' '}
                  QUANTUM STREAMLINE.
                </span>
              </label>
            </div>

            {/* TOMBOL UTAMA: DAFTAR (PILL SHAPED DENGAN GRADIENT) */}
            <div className="pt-2">
              <button
                type="submit"
                disabled={isLoading}
                className="w-full rounded-full py-4 px-8 bg-gradient-to-r from-[#e63946] to-[#ff4d4d] hover:from-[#ff4d4d] hover:to-[#ff6666] text-white text-xs sm:text-sm font-black uppercase tracking-wider shadow-lg shadow-[#e63946]/30 hover:shadow-xl hover:shadow-[#e63946]/45 hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-60"
              >
                {isLoading ? (
                  <span className="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin" />
                ) : (
                  <>
                    <span>DAFTAR</span>
                    <ArrowRight className="w-4 h-4" />
                  </>
                )}
              </button>
            </div>
          </form>

          {/* TEKS BAWAH: SUDAH PUNYA AKUN */}
          <div className="mt-6 text-center">
            <p className="text-xs sm:text-sm text-neutral-400">
              Sudah punya akun?{' '}
              <a
                href="login.html"
                className="font-bold text-[#ff4d4d] hover:text-[#ff6666] hover:underline underline-offset-4 transition-colors"
              >
                Masuk di sini
              </a>
            </p>
          </div>
        </div>

        {/* FOOTER INFORMASI KEAMANAN */}
        <div className="pt-4 border-t border-[#1a1a1a] flex items-center justify-between text-[11px] text-neutral-400">
          <div className="flex items-center gap-1.5">
            <ShieldCheck className="w-3.5 h-3.5 text-[#ff4d4d]" />
            <span>Terlindungi UU PDP No. 27/2022</span>
          </div>
          <span>&copy; {new Date().getFullYear()} QUANTUM STREAMLINE</span>
        </div>
      </div>

      {/* ========================================================
          SISI KANAN: Visual Mobil & Overlay (~55% lebar layar desktop)
      ======================================================== */}
      <div className="hidden lg:block lg:w-[55%] relative min-h-screen overflow-hidden bg-black">
        
        {/* GAMBAR MOBIL: Dark car with glowing headlights */}
        <img
          src="/images/audi_front_dark.jpg"
          alt="Quantum Streamline Luxury Fleet"
          className="absolute inset-0 w-full h-full object-cover object-center scale-105 hover:scale-100 transition-transform duration-1000"
          onError={(e) => {
            e.target.src = "https://images.unsplash.com/photo-1617814076367-b759c7d7e738?q=80&w=1600&auto=format&fit=crop";
          }}
        />

        {/* OVERLAY 1: Vignette & gradien hitam pekat ke arah form */}
        <div className="absolute inset-0 bg-gradient-to-r from-[#0a0a0a] via-transparent to-transparent w-2/5 z-10" />

        {/* OVERLAY 2: Gradien sudut merah-oranye tipis (#e63946 ke #ff4d4d) */}
        <div className="absolute -top-32 -right-32 w-96 h-96 bg-gradient-to-br from-[#e63946]/30 via-[#ff4d4d]/15 to-transparent rounded-full blur-3xl pointer-events-none z-10" />
        <div className="absolute -bottom-24 -right-24 w-80 h-80 bg-gradient-to-tl from-[#e63946]/25 to-transparent rounded-full blur-2xl pointer-events-none z-10" />

        {/* OVERLAY 3: Dark contrast protection di bagian bawah */}
        <div className="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/90 via-black/40 to-transparent z-10" />

        {/* KONTEN OVERLAY BAWAH (TAGLINE EKSEKUTIF) */}
        <div className="absolute bottom-12 left-12 right-12 z-20">
          <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-[11px] font-bold text-white uppercase tracking-widest mb-3">
            VIP Membership Perks
          </div>
          <h2 className="text-2xl xl:text-3xl font-black uppercase text-white tracking-wider drop-shadow-md">
            EXPERIENCE THE THRILL OF PREMIUM MOBILITY
          </h2>
          <p className="text-xs text-neutral-300 mt-2 max-w-lg leading-relaxed drop-shadow">
            Nikmati proses reservasi otomatis tanpa hambatan, asuransi komprehensif, dan armada terawat berstandar eksekutif.
          </p>
        </div>
      </div>

    </div>
  );
}
