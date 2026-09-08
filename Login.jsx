import React, { useState } from 'react';
import { Car, Eye, EyeOff, Lock, Mail, ArrowRight, ShieldCheck } from 'lucide-react';

export default function Login() {
  const [showPassword, setShowPassword] = useState(false);
  const [identifier, setIdentifier] = useState('');
  const [password, setPassword] = useState('');
  const [rememberMe, setRememberMe] = useState(false);
  const [isLoading, setIsLoading] = useState(false);

  const handleSubmit = (e) => {
    e.preventDefault();
    setIsLoading(true);
    // Simulasi aksi login
    setTimeout(() => {
      setIsLoading(false);
      alert(`Login diajukan untuk: ${identifier}`);
    }, 1000);
  };

  return (
    <div className="min-h-screen w-full bg-[#0a0a0a] text-white flex flex-col lg:flex-row font-['Poppins',_sans-serif] selection:bg-[#e63946] selection:text-white">
      
      {/* ========================================================
          SISI KIRI: Form Login (~45% layar desktop)
      ======================================================== */}
      <div className="w-full lg:w-[45%] min-h-screen flex flex-col justify-between p-6 sm:p-10 lg:p-14 z-10">
        
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
        <div className="my-auto py-8 max-w-md w-full mx-auto lg:mx-0">
          
          {/* HEADER FORM */}
          <div className="mb-8">
            <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#1a1a1a] border border-[#333333] text-[11px] font-bold text-neutral-300 uppercase tracking-widest mb-3">
              <span className="w-2 h-2 rounded-full bg-[#e63946] animate-pulse" />
              Member Access
            </div>
            
            <h1 className="text-3xl sm:text-4xl font-black uppercase tracking-tight text-white leading-tight">
              MASUK KE AKUN ANDA
            </h1>
            <p className="text-xs sm:text-sm text-neutral-400 mt-2 font-normal leading-relaxed">
              Selamat datang kembali. Masuk untuk mengelola reservasi dan menikmati armada mobil mewah kami.
            </p>
          </div>

          {/* FORM */}
          <form onSubmit={handleSubmit} className="space-y-5">
            
            {/* INPUT: EMAIL / NOMOR HP */}
            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-2">
                Email atau Nomor HP
              </label>
              <div className="relative">
                <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-neutral-500">
                  <Mail className="w-4 h-4" />
                </div>
                <input
                  type="text"
                  required
                  value={identifier}
                  onChange={(e) => setIdentifier(e.target.value)}
                  placeholder="nama@email.com atau 081234567890"
                  className="w-full bg-[#1a1a1a] border border-[#333333] rounded-[14px] pl-11 pr-4 py-3.5 text-xs sm:text-sm text-white placeholder:text-neutral-500 focus:outline-none focus:border-[#e63946] focus:ring-1 focus:ring-[#e63946] transition-all duration-200"
                />
              </div>
            </div>

            {/* INPUT: PASSWORD */}
            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-2">
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
                  placeholder="Masukkan kata sandi Anda"
                  className="w-full bg-[#1a1a1a] border border-[#333333] rounded-[14px] pl-11 pr-11 py-3.5 text-xs sm:text-sm text-white placeholder:text-neutral-500 focus:outline-none focus:border-[#e63946] focus:ring-1 focus:ring-[#e63946] transition-all duration-200"
                />
                <button
                  type="button"
                  onClick={() => setShowPassword(!showPassword)}
                  className="absolute inset-y-0 right-0 pr-4 flex items-center text-neutral-400 hover:text-white transition-colors cursor-pointer"
                >
                  {showPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                </button>
              </div>
            </div>

            {/* CHECKBOX INGAT SAYA + LINK LUPA PASSWORD */}
            <div className="flex items-center justify-between pt-1">
              <label className="flex items-center gap-2.5 cursor-pointer select-none">
                <input
                  type="checkbox"
                  checked={rememberMe}
                  onChange={(e) => setRememberMe(e.target.checked)}
                  className="w-4 h-4 rounded text-[#e63946] bg-[#1a1a1a] border-[#333333] focus:ring-[#e63946] focus:ring-offset-0 focus:outline-none transition"
                />
                <span className="text-xs text-neutral-400 hover:text-neutral-300 transition-colors">
                  Ingat saya
                </span>
              </label>

              <a
                href="#forgot-password"
                className="text-xs font-bold text-[#ff4d4d] hover:text-[#ff6666] transition-colors"
              >
                Lupa password?
              </a>
            </div>

            {/* TOMBOL UTAMA: MASUK (PILL SHAPED) */}
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
                    <span>MASUK</span>
                    <ArrowRight className="w-4 h-4" />
                  </>
                )}
              </button>
            </div>

            {/* SEPARATOR "ATAU" */}
            <div className="relative flex items-center justify-center py-2">
              <div className="border-t border-[#333333] w-full" />
              <span className="bg-[#0a0a0a] px-4 text-[11px] font-bold uppercase tracking-wider text-neutral-400">
                atau
              </span>
              <div className="border-t border-[#333333] w-full" />
            </div>

            {/* TOMBOL SOSIAL: GOOGLE (OUTLINE STYLE) */}
            <button
              type="button"
              onClick={() => alert('Login dengan Google')}
              className="w-full rounded-[14px] py-3.5 px-4 bg-transparent border border-[#333333] hover:border-neutral-500 hover:bg-[#1a1a1a] text-white text-xs sm:text-sm font-bold flex items-center justify-center gap-3 transition-all duration-200 cursor-pointer"
            >
              <svg className="w-4 h-4" viewBox="0 0 24 24">
                <path
                  fill="#EA4335"
                  d="M12 5c1.6 0 3 .6 4.1 1.6l3.1-3.1C17.3 1.7 14.8 1 12 1 7.5 1 3.7 3.6 1.9 7.3l3.7 2.9C6.5 7.3 9 5 12 5z"
                />
                <path
                  fill="#4285F4"
                  d="M23.5 12.3c0-.8-.1-1.6-.2-2.3H12v4.6h6.5c-.3 1.5-1.1 2.8-2.4 3.7l3.7 2.9c2.2-2 3.7-5 3.7-8.9z"
                />
                <path
                  fill="#FBBC05"
                  d="M5.6 14.8c-.2-.7-.4-1.5-.4-2.8s.2-2.1.4-2.8L1.9 6.3C.7 8.7 0 10.8 0 12s.7 3.3 1.9 5.7l3.7-2.9z"
                />
                <path
                  fill="#34A853"
                  d="M12 23c3.2 0 6-1.1 8-3l-3.7-2.9c-1.1.7-2.5 1.2-4.3 1.2-3 0-5.5-2.3-6.4-5.2L1.9 16c1.8 3.7 5.6 7 10.1 7z"
                />
              </svg>
              <span>Masuk dengan Google</span>
            </button>
          </form>

          {/* TEKS BAWAH: BELUM PUNYA AKUN */}
          <div className="mt-8 text-center">
            <p className="text-xs sm:text-sm text-neutral-400">
              Belum punya akun?{' '}
              <a
                href="#daftar"
                className="font-bold text-[#ff4d4d] hover:text-[#ff6666] hover:underline underline-offset-4 transition-colors"
              >
                Daftar sekarang
              </a>
            </p>
          </div>
        </div>

        {/* FOOTER INFORMASI KEAMANAN */}
        <div className="pt-4 border-t border-[#1a1a1a] flex items-center justify-between text-[11px] text-neutral-400">
          <div className="flex items-center gap-1.5">
            <ShieldCheck className="w-3.5 h-3.5 text-[#ff4d4d]" />
            <span>Enkripsi 256-bit SSL</span>
          </div>
          <span>&copy; {new Date().getFullYear()} QUANTUM STREAMLINE</span>
        </div>
      </div>

      {/* ========================================================
          SISI KANAN: Visual Mobil & Overlay (~55% layar desktop)
      ======================================================== */}
      <div className="hidden lg:block lg:w-[55%] relative min-h-screen overflow-hidden bg-black">
        
        {/* GAMBAR MOBIL: Dark car with glowing headlights */}
        <img
          src="/images/audi_front_dark.jpg"
          alt="Quantum Streamline Luxury Car"
          className="absolute inset-0 w-full h-full object-cover object-center scale-105 hover:scale-100 transition-transform duration-1000"
          onError={(e) => {
            // Fallback online jika file lokal belum dimuat
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
            Pure Precision Driving
          </div>
          <h2 className="text-2xl xl:text-3xl font-black uppercase text-white tracking-wider drop-shadow-md">
            THE PINNACLE OF LUXURY & PERFORMANCE
          </h2>
          <p className="text-xs text-neutral-300 mt-2 max-w-lg leading-relaxed drop-shadow">
            Nikmati armada kendaraan eksklusif dengan layanan supir profesional atau kemudahan lepas kunci mandiri.
          </p>
        </div>
      </div>

    </div>
  );
}
