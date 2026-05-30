@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap');

    /* ── CYBER-CASUAL TOKENS ── */
    :root {
        /* Dark Theme */
        --bg-main:         #000000;
        --bg-surface:      rgba(13, 15, 23, 0.65);
        --bg-surface-hover:rgba(20, 24, 38, 0.85);
        --bg-input:        rgba(5, 7, 12, 0.6);
        
        --border-soft:     rgba(59, 130, 246, 0.15);
        --border-strong:   rgba(59, 130, 246, 0.35);
        --border-focus:    #3b82f6;
        
        --text-primary:    #ffffff;
        --text-secondary:  #3b82f6;
        --text-tertiary:   #0369a1;
        
        --accent-blue:     #3b82f6;
        --accent-indigo:   #3b82f6;
        --success:         #10b981;
        --warning:         #f59e0b;
        --danger:          #ef4444;

        --radius-lg:       20px;
        --radius-md:       14px;
        --radius-sm:       10px;

        --font-sans:       'Plus Jakarta Sans', system-ui, sans-serif;
        --font-mono:       'JetBrains Mono', monospace;
        
        --shadow-card:     0 10px 40px rgba(0, 0, 0, 0.5);
        --shadow-glow:     0 0 25px rgba(59, 130, 246, 0.15);
    }

    html:not(.dark) {
        /* Light Theme */
        --bg-main:         #f0f9ff;
        --bg-surface:      rgba(255, 255, 255, 0.85);
        --bg-surface-hover:rgba(255, 255, 255, 0.95);
        --bg-input:        #ffffff;
        
        --border-soft:     rgba(14, 165, 233, 0.15);
        --border-strong:   rgba(14, 165, 233, 0.35);
        --border-focus:    #0ea5e9;
        
        --text-primary:    #0f172a;
        --text-secondary:  #0369a1;
        --text-tertiary:   #3b82f6;
        
        --accent-blue:     #0ea5e9;
        --accent-indigo:   #0ea5e9;
        
        --shadow-card:     0 10px 40px rgba(14, 165, 233, 0.08);
        --shadow-glow:     0 5px 20px rgba(14, 165, 233, 0.15);
    }

    *, *::before, *::after { box-sizing: border-box; }

    /* ── ANIMATIONS ── */
    @keyframes spin-slow { 100% { transform: rotate(360deg); } }
    @keyframes float-up { 
        0% { opacity: 0; transform: translateY(30px) scale(0.98); } 
        100% { opacity: 1; transform: translateY(0) scale(1); } 
    }
    @keyframes floatOrb {
        0% { transform: translate(0, 0) scale(1) rotate(0deg); }
        33% { transform: translate(8vw, -6vh) scale(1.1) rotate(5deg); }
        66% { transform: translate(-6vw, 8vh) scale(0.9) rotate(-5deg); }
        100% { transform: translate(0, 0) scale(1) rotate(0deg); }
    }

    .animate-float { animation: float-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    .d-1 { animation-delay: 0.1s; } .d-2 { animation-delay: 0.2s; } .d-3 { animation-delay: 0.3s; }

    /* =========================================
       AMBIENT AURORA BACKGROUND 
    ========================================= */
    .ambient-bg {
        position: fixed;
        inset: 0;
        z-index: -5; 
        overflow: hidden;
        background: var(--bg-main);
    }
    .ambient-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(100px);
        opacity: 0.15;
        animation: floatOrb 20s infinite alternate cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
    }
    
    /* FIX: Cahaya efek-efek dekat pohon di Light Mode dibuat lebih HIDUP & NYATA */
    html:not(.dark) .ambient-orb {
        opacity: 0.05; 
        filter: blur(120px);
        mix-blend-mode: normal;
    }
    html:not(.dark) .orb-1 { background: rgba(59, 130, 246, 0.2); } /* Ice Blue Cerah */
    html:not(.dark) .orb-2 { background: rgba(59, 130, 246, 0.15); } /* Soft Violet Nyala */
    html:not(.dark) .orb-3 { background: rgba(59, 130, 246, 0.08); opacity: 0.1; } /* Kuning Cahaya Matahari */

    .orb-1 {
        width: 60vw; height: 60vw; max-width: 800px; max-height: 800px;
        background: var(--accent-blue);
        top: -10%; left: -10%;
    }
    .orb-2 {
        width: 50vw; height: 50vw; max-width: 700px; max-height: 700px;
        background: #3b82f6; 
        bottom: -10%; right: -5%;
        animation-delay: -5s;
    }
    .orb-3 {
        width: 40vw; height: 40vw; max-width: 600px; max-height: 600px;
        background: var(--warning); 
        top: 30%; left: 30%;
        animation-delay: -10s;
        opacity: 0.2;
    }

    /* ── BACKGROUND FOTO ATAS (HERO) ── */
    .hero-photo-bg {
        position: absolute; top: 0; left: 0; right: 0; height: 100vh;
        background-image: url('https://2.bp.blogspot.com/-ah30_c4lnYE/Tsia8ZNlT-I/AAAAAAAAAC4/avMnlc5l9x8/w1200-h630-p-k-no-nu/KAMPUS+E.jpg'); 
        background-size: cover; background-position: center 30%;
        z-index: -2; 
        mask-image: linear-gradient(to bottom, black 85%, transparent 100%);
        -webkit-mask-image: linear-gradient(to bottom, black 85%, transparent 100%);
        opacity: 0.45; transition: opacity 0.5s ease;
        pointer-events: none;
        transform: scale(1.08);
    }
    html:not(.dark) .hero-photo-bg { 
        opacity: 0.65; 
        filter: brightness(0.35) contrast(1.15); 
        mask-image: linear-gradient(to bottom, black 85%, transparent 100%);
        -webkit-mask-image: linear-gradient(to bottom, black 85%, transparent 100%);
    }

    .cyber-grid-overlay {
        position: absolute; inset: 0; z-index: -1; pointer-events: none;
        background-image: 
            linear-gradient(var(--border-soft) 1px, transparent 1px),
            linear-gradient(90deg, var(--border-soft) 1px, transparent 1px);
        background-size: 40px 40px; opacity: 0.3;
    }

    #ug-canvas { position: fixed; inset: 0; z-index: 0; pointer-events: none; opacity: 0.05; }

    /* ── LAYOUT WRAPPER ── */
    .dashboard-layout {
        position: relative; z-index: 2; max-width: 1300px; margin: 0 auto;
        padding: 24px; font-family: var(--font-sans); color: var(--text-primary);
        display: flex; flex-direction: column; gap: 28px;
    }

    /* ── CANGGIH GLASS CARD ── */
    .glass-card {
        background: var(--bg-surface); backdrop-filter: blur(25px); -webkit-backdrop-filter: blur(25px);
        border: 1px solid var(--border-soft); border-radius: var(--radius-lg);
        box-shadow: var(--shadow-card); transition: border-color 0.4s ease, transform 0.4s ease;
        position: relative;
        transform-style: preserve-3d;
    }
    .glass-card:hover { border-color: var(--border-strong); box-shadow: var(--shadow-glow); }

    /* ── EFEK TRANSISI STATUS SERVER KHUSUS (HILANG DI LIGHT, MUNCUL DI DARK) ── */
    .server-status-transition-wrapper {
        transition: opacity 0.8s ease, transform 0.8s cubic-bezier(0.34, 1.56, 0.64, 1), filter 0.8s ease;
        transform-origin: top center;
    }
    html:not(.dark) .server-status-transition-wrapper {
        opacity: 0;
        visibility: hidden;
        transform: translateY(40px) scale(0.85);
        filter: blur(10px);
        pointer-events: none;
    }
    html.dark .server-status-transition-wrapper {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
        filter: blur(0);
        pointer-events: auto;
    }

    /* Cahaya efek interaktif mengikuti Mouse di Kartu */
    .glass-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        background: radial-gradient(600px circle at var(--mouse-x, -500px) var(--mouse-y, -500px), rgba(59, 130, 246, 0.08), transparent 40%);
        z-index: 0; pointer-events: none; transition: opacity 0.3s ease; opacity: 0;
    }
    html:not(.dark) .glass-card::before {
        background: radial-gradient(800px circle at var(--mouse-x, -500px) var(--mouse-y, -500px), rgba(14, 165, 233, 0.15), transparent 45%);
    }
    .glass-card:hover::before { opacity: 1; }

    /* CLEAN DARK MODE PERFORMANCE OPTIMIZATIONS */
    .dark .ambient-orb,
    .dark .cyber-grid-overlay,
    .dark #ug-canvas {
        display: none !important;
        opacity: 0 !important;
        visibility: hidden !important;
        animation: none !important;
    }
    .dark .glass-card::before {
        display: none !important;
        content: none !important;
    }
    .dark .hero-photo-bg {
        transform: scale(1.08) !important;
        transition: none !important;
    }

    /* ── TOPBAR ── */
    .header-panel { display: flex; justify-content: space-between; align-items: center; padding: 24px 32px; }
    
    .user-info { display: flex; align-items: center; gap: 20px; }
    .user-avatar {
        width: 56px; height: 56px; border-radius: 12px;
        background: linear-gradient(135deg, var(--accent-blue), var(--accent-indigo));
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; font-weight: 800; color: #fff;
        position: relative; box-shadow: 0 0 15px rgba(59, 130, 246, 0.4);
    }
    .user-avatar::before { content: '['; position: absolute; left: -12px; color: #0ea5e9; opacity: 0.6; font-family: var(--font-mono); font-size: 24px; font-weight: 300; }
    .user-avatar::after { content: ']'; position: absolute; right: -12px; color: #0ea5e9; opacity: 0.6; font-family: var(--font-mono); font-size: 24px; font-weight: 300; }

    /* ── TOMBOL GLOW ── */
    .btn-glow {
        background: linear-gradient(135deg, #3b82f6, #3b82f6) !important;
        color: #ffffff !important; 
        border: 1px solid rgba(255,255,255,0.2) !important;
        border-radius: 12px;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        z-index: 1;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3) !important;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }
    .btn-glow::after {
        content: '';
        position: absolute;
        top: 0; left: -100%; width: 50%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transform: skewX(-25deg);
        transition: all 0.6s ease;
        z-index: -1;
    }
    .btn-glow:hover::after { left: 200%; }
    .btn-glow:hover {
        transform: translateY(-3px) translateZ(10px);
        box-shadow: 0 15px 35px rgba(59, 130, 246, 0.6) !important;
        color: #ffffff !important;
    }
</style>

<div class="ambient-bg">
    <div class="ambient-orb orb-1"></div>
    <div class="ambient-orb orb-2"></div>
    <div class="ambient-orb orb-3"></div>
</div>

<div class="hero-photo-bg"></div>

<section class="min-h-[calc(100vh-80px)] flex items-center justify-center px-6 lg:px-16 relative z-10 perspective-container">
    <div class="max-w-7xl w-full grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
        
        <div class="lg:col-span-7 space-y-8 animate-float active">
            <div class="inline-flex items-center gap-3 px-5 py-2.5 rounded-full bg-white/60 dark:bg-white/10 border border-[#3b82f6]/30 backdrop-blur-md shadow-[0_0_20px_rgba(14,165,233,0.15)] transition-colors duration-500">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#3b82f6] opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-[#3b82f6]"></span>
                </span>
                <span class="text-xs font-bold tracking-widest text-slate-800 dark:text-white uppercase drop-shadow-md">Server FIKTI • Aktif & Terhubung</span>
            </div>
            
            <h2 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tight text-white drop-shadow-lg leading-[1.15] transition-colors duration-500">
                Sistem Manajemen <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-amber-400 to-orange-500 drop-shadow-xl">
                    Fasilitas Kampus
                </span>
            </h2>
            
            <p class="text-white/95 drop-shadow-md leading-relaxed text-lg lg:text-xl max-w-2xl font-medium transition-colors duration-500">
                Platform terpadu untuk mempermudah civitas akademika FIKTI Universitas Gunadarma. Pesan ruang lab, pantau jadwal kelas, dan kelola aset Kampus J1 secara <i>real-time</i> dengan mudah dan aman.
            </p>

            <div class="pt-6 flex flex-wrap gap-5 transform-style-3d">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-glow px-8 py-4 text-lg font-bold flex items-center gap-3 text-decoration-none">
                        Akses Dashboard <i class="bi bi-arrow-right"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-glow px-8 py-4 text-lg font-bold flex items-center gap-3 text-decoration-none">
                        Login ke Sistem <i class="bi bi-shield-check"></i>
                    </a>
                    <a href="#fitur" class="px-8 py-4 rounded-xl text-lg font-bold text-white border border-white/50 hover:border-[#3b82f6] hover:text-[#3b82f6] transition-all duration-300 flex items-center gap-3 bg-slate-900/30 backdrop-blur-sm text-decoration-none">
                        Pelajari Fitur <i class="bi bi-arrow-down"></i>
                    </a>
                @endauth
            </div>
        </div>

        <div class="lg:col-span-5 hidden md:block animate-float active d-2">
            <div class="server-status-transition-wrapper">
                <div class="glass-card p-10 w-full relative overflow-hidden">
                    <div class="absolute -top-16 -right-16 w-52 h-52 bg-[#3b82f6] opacity-20 blur-[60px] rounded-full"></div>
                    <div class="absolute -bottom-16 -left-16 w-52 h-52 bg-blue-600 opacity-20 blur-[60px] rounded-full"></div>
                    
                    <div class="tilt-content">
                        <div class="flex justify-between items-start mb-8">
                            <div class="w-16 h-16 rounded-2xl bg-[#3b82f6]/10 flex items-center justify-center text-[#3b82f6] text-3xl shadow-[0_0_20px_rgba(14,165,233,0.2)] border border-[#3b82f6]/30">
                                <i class="bi bi-hdd-network"></i>
                            </div>
                            <span class="px-3 py-1 bg-green-500/10 text-green-600 dark:text-green-400 border border-green-500/30 rounded-full text-[10px] font-bold tracking-widest uppercase shadow-sm">Live System</span>
                        </div>

                        <h4 class="text-3xl font-bold mb-3 text-white drop-shadow-md transition-colors duration-500">Status Server FIKTI</h4>
                        <p class="text-white/90 drop-shadow-sm mb-10 leading-relaxed text-sm font-medium transition-colors duration-500">
                            Sistem memantau seluruh aktivitas penggunaan ruang di Kampus J1 secara otomatis untuk memastikan tidak ada bentrok jadwal perkuliahan.
                        </p>
                        
                        <div class="space-y-4">
                            <div class="bg-white/10 p-4 rounded-xl border border-white/20 flex items-center gap-4 backdrop-blur-sm transition-colors duration-500">
                                <div class="w-3 h-3 rounded-full bg-green-500 shadow-[0_0_10px_#22c55e]"></div>
                                <div class="flex flex-col">
                                    <span class="text-xs text-white/80 uppercase tracking-wider font-bold">Jaringan</span>
                                    <span class="text-sm text-white font-bold">Terhubung & Stabil</span>
                                </div>
                            </div>
                            <div class="bg-white/10 p-4 rounded-xl border border-white/20 flex items-center gap-4 backdrop-blur-sm transition-colors duration-500">
                                <div class="w-3 h-3 rounded-full bg-blue-600 shadow-[0_0_10px_#FFC107] animate-pulse"></div>
                                <div class="flex flex-col">
                                    <span class="text-xs text-white/80 uppercase tracking-wider font-bold">Sinkronisasi Jadwal</span>
                                    <span class="text-sm text-[#FFC107] font-bold">Berjalan Lancar</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
    <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce text-white/80 text-3xl drop-shadow-md">
        <i class="bi bi-chevron-compact-down"></i>
    </div>
</section>

<section id="fitur" class="py-32 px-6 lg:px-16 relative z-10">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-20 animate-float">
            <h3 class="text-[#3b82f6] font-bold tracking-widest uppercase text-sm mb-3">Keunggulan Platform</h3>
            <h2 class="text-4xl lg:text-5xl font-extrabold text-slate-900 dark:text-white transition-colors duration-500">Fitur Utama UGFORCE</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="glass-card p-8 animate-float">
                <div class="tilt-content">
                    <div class="w-16 h-16 rounded-2xl bg-white/50 dark:bg-white/10 flex items-center justify-center text-amber-500 dark:text-[#FFC107] text-3xl mb-8 shadow-lg border border-slate-200 dark:border-white/20">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-slate-900 dark:text-white mb-4 transition-colors duration-500">Booking Ruang Pintar</h4>
                    <p class="text-slate-600 dark:text-white/90 text-base font-medium leading-relaxed transition-colors duration-500">
                        Pesan ruangan kelas atau lab dengan mudah melalui kalender interaktif. Sistem kami secara otomatis mencegah pemesanan ganda di waktu yang sama.
                    </p>
                </div>
            </div>

            <div class="glass-card p-8 animate-float d-1">
                <div class="tilt-content">
                    <div class="w-16 h-16 rounded-2xl bg-white/50 dark:bg-white/10 flex items-center justify-center text-[#3b82f6] text-3xl mb-8 shadow-lg border border-slate-200 dark:border-white/20">
                        <i class="bi bi-display"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-slate-900 dark:text-white mb-4 transition-colors duration-500">Pemantauan Fasilitas</h4>
                    <p class="text-slate-600 dark:text-white/90 text-base font-medium leading-relaxed transition-colors duration-500">
                        Lihat status ketersediaan ruangan, spesifikasi komputer lab, dan alat pendukung secara langsung sebelum Anda memutuskan untuk datang ke kampus.
                    </p>
                </div>
            </div>

            <div class="glass-card p-8 animate-float d-2">
                <div class="tilt-content">
                    <div class="w-16 h-16 rounded-2xl bg-white/50 dark:bg-white/10 flex items-center justify-center text-indigo-500 dark:text-purple-400 text-3xl mb-8 shadow-lg border border-slate-200 dark:border-white/20">
                        <i class="bi bi-check-all"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-slate-900 dark:text-white mb-4 transition-colors duration-500">Persetujuan Terpusat</h4>
                    <p class="text-slate-600 dark:text-white/90 text-base font-medium leading-relaxed transition-colors duration-500">
                        Pengajuan peminjaman langsung terkirim ke pihak admin Fakultas. Anda dapat memantau status persetujuan secara transparan kapan saja.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="cara-kerja" class="py-32 px-6 lg:px-16 bg-slate-200/30 dark:bg-white/[0.03] border-y border-slate-300 dark:border-white/10 relative z-10 transition-colors duration-500">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-20">
        
        <div class="lg:w-5/12 space-y-8 animate-float">
            <h3 class="text-[#3b82f6] font-bold tracking-widest uppercase text-sm">Alur Penggunaan</h3>
            <h2 class="text-4xl lg:text-5xl font-extrabold text-slate-900 dark:text-white leading-tight transition-colors duration-500">Cara Kerja Sistem.</h2>
            <p class="text-slate-600 dark:text-white/90 text-lg font-medium leading-relaxed transition-colors duration-500">
                Proses peminjaman ruangan dan pengecekan fasilitas kini 100% digital. Sangat cepat, transparan, dan Anda tidak perlu lagi mengisi formulir kertas yang merepotkan.
            </p>
        </div>

        <div class="lg:w-7/12 space-y-6 w-full">
            <div class="glass-card p-6 flex items-center gap-6 animate-float hover:border-amber-400 dark:hover:border-[#FFC107]/50 transition-colors duration-500">
                <div class="flex items-center gap-8 w-full">
                    <h1 class="text-6xl font-black text-slate-300 dark:text-white/20 transition-colors duration-500">01</h1>
                    <div>
                        <h4 class="text-xl font-bold text-slate-900 dark:text-white transition-colors duration-500">Akses Akun</h4>
                        <p class="text-base font-medium text-slate-600 dark:text-white/90 mt-2">Gunakan email student atau kredensial Gunadarma Anda untuk login ke portal.</p>
                    </div>
                </div>
            </div>
            <div class="glass-card p-6 flex items-center gap-6 animate-float d-1 lg:ml-12 border-[#3b82f6]/40 shadow-[0_0_30px_rgba(14,165,233,0.15)]">
                <div class="flex items-center gap-8 w-full">
                    <h1 class="text-6xl font-black text-[#3b82f6]/30 dark:text-[#3b82f6]/40 transition-colors duration-500">02</h1>
                    <div>
                        <h4 class="text-xl font-bold text-[#3b82f6]">Pilih Ruangan</h4>
                        <p class="text-base font-medium text-slate-600 dark:text-white/90 mt-2">Tentukan tanggal, waktu, dan cek ruangan kosong melalui kalender interaktif.</p>
                    </div>
                </div>
            </div>
            <div class="glass-card p-6 flex items-center gap-6 animate-float d-2 lg:ml-24 hover:border-amber-400 dark:hover:border-[#FFC107]/50 transition-colors duration-500">
                <div class="flex items-center gap-8 w-full">
                    <h1 class="text-6xl font-black text-slate-300 dark:text-white/20 transition-colors duration-500">03</h1>
                    <div>
                        <h4 class="text-xl font-bold text-slate-900 dark:text-white transition-colors duration-500">Selesai & Gunakan</h4>
                        <p class="text-base font-medium text-slate-600 dark:text-white/90 mt-2">Tunggu persetujuan admin. Notifikasi akan masuk dan ruang siap digunakan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-40 px-6 lg:px-16 text-center relative z-10 perspective-container">
    <div class="max-w-4xl mx-auto glass-card p-16 animate-float">
        <div class="tilt-content">
            <div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-br from-blue-600 to-sky-500 flex items-center justify-center text-[#000000] text-4xl mb-8 shadow-[0_0_30px_rgba(255,193,7,0.4)]">
                <i class="bi bi-rocket-takeoff-fill"></i>
            </div>
            <h2 class="text-4xl lg:text-5xl font-extrabold text-slate-900 dark:text-white mb-6 transition-colors duration-500">Mulai Gunakan UGFORCE</h2>
            <p class="text-slate-600 dark:text-white/90 text-lg lg:text-xl font-medium mb-12 max-w-2xl mx-auto transition-colors duration-500">
                Nikmati kemudahan mengatur jadwal, memesan lab, dan melihat ketersediaan fasilitas akademik dalam satu genggaman.
            </p>
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-glow inline-block px-8 py-3.5 text-xl font-bold shadow-lg text-decoration-none">
                    Masuk ke Dashboard Utama
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-glow inline-block px-8 py-3.5 text-xl font-bold tracking-wide shadow-lg text-decoration-none">
                    Login Sekarang
                </a>
            @endauth
        </div>
    </div>
</section>

<script>
    // Intersection Observer for Scroll Animations
    document.addEventListener('DOMContentLoaded', () => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.animate-float').forEach((el) => {
            el.style.animationPlayState = 'paused';
            observer.observe(el);
        });

        /* ── 3D HOVER EFFECT PADA KARTU & BACKGROUND PARALLAX ── */
        if(window.innerWidth > 768) {
            const cards = document.querySelectorAll('.glass-card');
            cards.forEach(card => {
                card.addEventListener('mousemove', e => {
                    if (document.documentElement.classList.contains('dark')) {
                        card.style.transform = 'none';
                        return;
                    }
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;
                    const rotateX = ((y - centerY) / centerY) * -5; 
                    const rotateY = ((x - centerX) / centerX) * 5;  
                    
                    card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
                });
                
                card.addEventListener('mouseleave', () => {
                    if (document.documentElement.classList.contains('dark')) {
                        card.style.transform = 'none';
                        return;
                    }
                    card.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)`;
                });
            });

            // Efek Interaktif Parallax 3D pada Background Foto (Disabled)
        }
    });
</script>

@endsection