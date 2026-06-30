<!DOCTYPE html>
<html lang="id" class="scroll-smooth dark" id="html-root">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'UGFORCE') }} - Sistem Manajemen Fasilitas Kampus FIKTI</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:300,400,500,600,700,800" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tailwind CSS & Konfigurasi Dark Mode -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        ug: { gold: '#fbbf24', purple: '#2563eb', dark: '#000000', blue: '#0ea5e9' }
                    },
                    fontFamily: {
                        sans: ['Instrument Sans', 'sans-serif'],
                    }
                }
            }
        }
        
        // Immediately apply theme class to prevent flashing
        if (localStorage.theme === 'light' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: light)').matches)) {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }

        // Immediately check preloader to prevent flashing when navigating links
        if (sessionStorage.getItem('ugforce_loaded')) {
            var style = document.createElement('style');
            style.id = 'preloader-skip-style';
            style.innerHTML = `
                #loader-wrapper { display: none !important; }
                #app-perspective-wrapper { transform: none !important; opacity: 1 !important; filter: none !important; }
                nav.desktop-nav { transform: translateY(0px) !important; opacity: 1 !important; }
            `;
            document.head.appendChild(style);
        }
    </script>

    <style>
        .text-ug-gold {
            color: #fbbf24 !important;
        }

        :root {
            --bg-color: #f8fafc;
            --border-soft: rgba(0, 0, 0, 0.05);
            --border-strong: rgba(0, 0, 0, 0.12);
            --bg-surface: rgba(255, 255, 255, 0.7);
            --accent-blue: #0ea5e9;
        }

        .dark {
            --bg-color: #020617;
            --border-soft: rgba(255, 255, 255, 0.08);
            --border-strong: rgba(255, 255, 255, 0.15);
            --bg-surface: rgba(13, 17, 28, 0.95);
            --accent-blue: #38bdf8;
        }

        body {
            font-family: 'Instrument Sans', sans-serif;
            overflow-x: hidden;
            margin: 0;
            transition: background-color 0.5s ease, color 0.5s ease;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: var(--bg-color) !important;
            background-color: var(--bg-color) !important;
        }

        /* CUSTOM SCROLLBAR MEWAH */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #e2e8f0; }
        .dark ::-webkit-scrollbar-track { background: #000000; }
        ::-webkit-scrollbar-thumb { background: rgba(255, 193, 7, 0.8); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255, 193, 7, 1); }

        /* ── BACKGROUND ORBS & STARS (DISABLED GLOBALLY) ── */
        .ambient-bg {
            position: fixed !important;
            top: 0 !important; left: 0 !important; width: 100vw !important; height: 100vh !important;
            background: var(--bg-color) !important;
            background-color: var(--bg-color) !important;
            z-index: -10 !important;
            transition: background 0.6s ease !important;
        }
        
        .glow-orb,
        .cyber-stars,
        .ambient-orb,
        .cyber-grid-overlay,
        #ug-canvas {
            display: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
            animation: none !important;
        }

        /* ── NAVBAR DESKTOP ── */
        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            transition: all 0.4s ease;
        }
        .dark .glass-nav {
            background: rgba(5, 1, 13, 0.95);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
        }
        .nav-pill-link {
            position: relative;
            padding: 8px 16px;
            border-radius: 12px;
            color: #475569;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
        }
        .dark .nav-pill-link {
            color: #cbd5e1;
        }
        .nav-pill-link:hover,
        .nav-pill-link.active {
            color: var(--accent-blue) !important;
            background: rgba(14, 165, 233, 0.08);
        }
        .dark .nav-pill-link:hover,
        .dark .nav-pill-link.active {
            background: rgba(56, 189, 248, 0.1);
        }

        .user-capsule {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 6px 14px 6px 6px;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none !important;
        }
        .dark .user-capsule {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .user-avatar-circle {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            color: white;
            font-weight: 800;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-transform: uppercase;
        }

        /* ── MOBILE NAV BAR & APP HEADER ── */
        .top-app-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            z-index: 50;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--border-soft);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
        }
        .dark .top-app-bar {
            background: rgba(2, 6, 23, 0.85);
        }

        .bottom-tab-bar {
            position: fixed;
            bottom: 16px;
            left: 16px;
            right: 16px;
            height: 64px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(0, 0, 0, 0.06);
            border-radius: 20px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 4px 8px;
            box-shadow: 0 12px 30px -10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }
        .dark .bottom-tab-bar {
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 12px 35px -10px rgba(0, 0, 0, 0.4);
        }
        
        /* Scrollable modifier specifically for admin with 8 modules */
        .bottom-tab-bar.scrollable-tabs {
            justify-content: flex-start !important;
            overflow-x: auto;
            scrollbar-width: none;
        }
        .bottom-tab-bar.scrollable-tabs::-webkit-scrollbar {
            display: none;
        }
        
        @media (max-width: 767px) {
            .bottom-tab-bar.scrollable-tabs {
                gap: 6px;
                padding-left: 8px;
                padding-right: 8px;
            }
        }

        .tab-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #64748b;
            text-decoration: none !important;
            font-size: 10px;
            font-weight: 700;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            gap: 2px;
            flex: 1;
            height: 100%;
            border-radius: 14px;
            padding: 4px 10px;
            min-width: 0;
            flex-shrink: 1;
        }
        @media (max-width: 767px) {
            .bottom-tab-bar.scrollable-tabs .tab-item {
                flex-shrink: 0 !important;
                flex: none !important;
                min-width: 70px;
            }
        }
        .dark .tab-item {
            color: #94a3b8;
        }
        .tab-item:hover {
            color: var(--accent-blue) !important;
            background: rgba(14, 165, 233, 0.04);
        }
        .dark .tab-item:hover {
            background: rgba(56, 189, 248, 0.04);
        }
        .tab-item.active {
            color: var(--accent-blue) !important;
            background: rgba(14, 165, 233, 0.08);
        }
        .dark .tab-item.active {
            background: rgba(56, 189, 248, 0.12);
            color: #38bdf8 !important;
        }
        .tab-item i {
            font-size: 18px;
            transition: transform 0.2s ease;
        }

        /* ── RESPONSIVE DISPLAY LOGIC ── */
        @media (min-width: 768px) {
            .desktop-only { display: flex !important; }
            .desktop-nav { display: flex !important; }
            .mobile-only { display: none !important; }
            .app-content {
                padding-top: 100px !important;
                padding-bottom: 40px !important;
            }
        }

        @media (max-width: 767px) {
            .desktop-only { display: none !important; }
            .desktop-nav { display: none !important; }
            .mobile-only { display: flex !important; }
            .app-content {
                padding-top: 80px !important;
                padding-bottom: 90px !important;
            }
            .glass-card, .glass-card-premium {
                padding: 20px !important;
            }
            h1, h2 { font-size: 1.8rem !important; }
            footer { display: none !important; }
        }

        /* Cinematic Preloader Styles */
        @keyframes float3D {
            0% { transform: perspective(1000px) rotateX(0deg) rotateY(0deg) translateY(0px) scale(1); }
            50% { transform: perspective(1000px) rotateX(8deg) rotateY(-8deg) translateY(-5px) scale(1.04); }
            100% { transform: perspective(1000px) rotateX(0deg) rotateY(0deg) translateY(0px) scale(1); }
        }
        .bg-radial-glow {
            background: radial-gradient(circle, rgba(251, 191, 36, 0.18) 0%, rgba(37, 99, 235, 0.04) 50%, transparent 100%);
        }
        .shine-text-gold {
            background: linear-gradient(90deg, #fbbf24 0%, #fffbeb 50%, #fbbf24 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: textShine 3s linear infinite;
            filter: drop-shadow(0 0 15px rgba(251, 191, 36, 0.35));
        }
        @keyframes textShine {
            to { background-position: 200% center; }
        }
        @keyframes grid-drift {
            0% { transform: perspective(600px) rotateX(60deg) translateY(0); }
            100% { transform: perspective(600px) rotateX(60deg) translateY(40px); }
        }
        .cinematic-grid {
            position: absolute; inset: 0;
            background-image: linear-gradient(rgba(251, 191, 36, 0.04) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(251, 191, 36, 0.04) 1px, transparent 1px);
            background-size: 40px 40px;
            transform-origin: center top;
            transform: perspective(600px) rotateX(60deg);
            animation: grid-drift 15s linear infinite;
            opacity: 0.45;
            z-index: 0;
        }
        .cinematic-stars {
            position: absolute; inset: 0;
            background-image: radial-gradient(white 1px, transparent 0);
            background-size: 24px 24px;
            opacity: 0.12;
            z-index: 0;
        }

        .pulse-badge {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.2);
            color: #15803d;
        }
        .dark .pulse-badge {
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #4ade80;
        }
    </style>
</head>
<body class="text-slate-900 dark:text-white transition-colors duration-500 min-h-screen flex flex-col">

    <!-- PRELOADER SCREEN (Full Viewport) -->
    <div id="loader-wrapper" style="position: fixed; inset: 0; z-index: 99999; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #020617; transition: transform 1.5s cubic-bezier(0.85, 0, 0.15, 1), opacity 1.5s cubic-bezier(0.85, 0, 0.15, 1), filter 1.5s ease;">
        <div class="cinematic-grid"></div>
        <div class="cinematic-stars"></div>
        
        <div class="flex flex-col items-center gap-5 relative z-10">
            <div class="relative flex items-center justify-center mb-4" style="transform-style: preserve-3d; animation: float3D 5s ease-in-out infinite;">
                <div class="absolute w-80 h-80 rounded-full bg-radial-glow -z-10 animate-pulse"></div>
                <div class="relative w-32 h-32 flex items-center justify-center bg-gradient-to-br from-slate-900 to-slate-950 rounded-3xl p-3 border border-amber-400/30 shadow-[0_0_60px_rgba(245,158,11,0.25)] z-10">
                    <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/5 to-transparent rounded-3xl pointer-events-none"></div>
                    <img src="{{ asset('logo.png') }}" alt="Logo Gunadarma" class="w-22 h-22 object-contain filter drop-shadow-[0_0_15px_rgba(245,158,11,0.45)]">
                </div>
            </div>
            
            <div class="text-center">
                <h2 class="text-4xl sm:text-5xl font-black tracking-[0.25em] mb-2 shine-text-gold">UG<span class="text-ug-gold">FORCE</span></h2>
                <p class="text-[9px] font-bold tracking-[0.3em] text-[#fbbf24]/75 dark:text-[#fbbf24]/75 uppercase">Menginisialisasi Portal...</p>
            </div>
            <div class="w-72 h-1 bg-slate-800 rounded-full overflow-hidden relative border border-white/5">
                <div id="loader-progress-bar" class="h-full bg-gradient-to-r from-amber-500 via-yellow-400 to-amber-500 w-0 transition-all duration-75 shadow-[0_0_8px_#f59e0b]"></div>
            </div>
            <div class="font-mono text-lg font-black text-amber-400/90 tracking-widest">
                <span id="loader-percent">0</span>%
            </div>
        </div>
    </div>

    <!-- ── 1. NAVBAR DESKTOP (Only shown on screen >= 768px) ── -->
    <nav class="fixed top-0 w-full z-50 glass-nav px-5 lg:px-16 py-3.5 flex justify-between items-center desktop-nav" style="transform: translateY(-20px); opacity: 0; transition: transform 1.2s cubic-bezier(0.16, 1, 0.3, 1), opacity 1.2s ease;">
        <a href="{{ url('/') }}" class="flex items-center gap-3 cursor-pointer text-decoration-none">
            <div class="w-10 h-10 lg:w-11 lg:h-11 rounded-xl overflow-hidden flex items-center justify-center bg-white border border-slate-200 p-1 shadow-sm transition transform hover:scale-105 duration-300">
                <img src="{{ asset('logo.png') }}" alt="Logo Gunadarma" class="w-8 h-8 lg:w-9 lg:h-9 object-contain">
            </div>
            <div>
                <h1 class="text-lg lg:text-xl font-extrabold tracking-widest text-slate-900 dark:text-white leading-none transition-colors duration-500 mb-0">UG<span class="text-ug-gold">FORCE</span></h1>
                <p class="text-[8px] lg:text-[9px] font-bold tracking-[0.15em] text-slate-500 dark:text-white/60 uppercase mt-0.5 mb-0 hidden lg:block">Fakultas Ilmu Komputer & TI</p>
            </div>
        </a>
        
        <div class="flex items-center gap-1 lg:gap-2 flex-wrap justify-end">
            <a href="{{ url('/') }}#fitur" class="nav-pill-link">
                <i class="bi bi-star"></i> Fitur Sistem
            </a>
            <a href="{{ url('/') }}#cara-kerja" class="nav-pill-link">
                <i class="bi bi-question-circle"></i> Cara Kerja
            </a>
            
            <div class="w-px h-5 bg-slate-300 dark:bg-white/20 mx-1"></div>
 
            @guest
                <!-- THEME TOGGLE BUTTON -->
                <button id="theme-toggle" class="w-9 h-9 rounded-xl bg-slate-200/50 dark:bg-white/10 flex items-center justify-center text-slate-800 dark:text-yellow-400 border border-slate-300 dark:border-white/20 shadow-sm hover:scale-110 transition-all duration-300 border-0">
                    <i class="bi bi-moon-fill dark:hidden"></i>
                    <i class="bi bi-sun-fill hidden dark:block"></i>
                </button>
                
                <div class="w-px h-5 bg-slate-300 dark:bg-white/20 mx-1"></div>
 
                <a href="{{ route('login') }}" class="nav-pill-link">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </a>
                <a href="{{ route('register') }}" class="nav-pill-link">
                    <i class="bi bi-person-plus"></i> Daftar
                </a>
            @else
                <!-- Authenticated links -->
                @if(Auth::user()->role === 'student')
                    <a href="{{ url('/dashboard') }}" class="nav-pill-link {{ Request::is('dashboard*') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i> Dashboard
                    </a>
                    <a href="{{ route('bookings.create') }}" class="nav-pill-link {{ Request::routeIs('bookings.create') ? 'active' : '' }}">
                        <i class="bi bi-plus-square-fill"></i> Pinjam Ruang
                    </a>
                    <a href="{{ route('bookings.index') }}" class="nav-pill-link {{ Request::routeIs('bookings.index') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> Riwayat
                    </a>
                @elseif(Auth::user()->role === 'lecturer')
                    <a href="{{ route('lecturer.dashboard') }}" class="nav-pill-link {{ Request::is('lecturer/dashboard*') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i> Dashboard Dosen
                    </a>
                    <a href="{{ route('bookings.create') }}" class="nav-pill-link {{ Request::routeIs('bookings.create') ? 'active' : '' }}">
                        <i class="bi bi-plus-square-fill"></i> Pinjam Ruang
                    </a>
                    <a href="{{ route('bookings.index') }}" class="nav-pill-link {{ Request::routeIs('bookings.index') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> Riwayat
                    </a>
                    <span class="pulse-badge"><i class="bi bi-patch-check-fill text-green-500"></i> FaceID Verified</span>
                @else
                    <a href="{{ route('admin.dashboard') }}" class="nav-pill-link {{ Request::is('admin*') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Panel Admin
                    </a>
                @endif
                
                <div class="w-px h-5 bg-slate-300 dark:bg-white/20 mx-1"></div>
 
                <!-- THEME TOGGLE BUTTON -->
                <button id="theme-toggle" class="w-9 h-9 rounded-xl bg-slate-200/50 dark:bg-white/10 flex items-center justify-center text-slate-800 dark:text-yellow-400 border border-slate-300 dark:border-white/20 shadow-sm hover:scale-110 transition-all duration-300 border-0">
                    <i class="bi bi-moon-fill dark:hidden"></i>
                    <i class="bi bi-sun-fill hidden dark:block"></i>
                </button>
                
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.profile') }}" class="user-capsule" title="{{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})">
                @else
                    <a href="{{ route('profile') }}" class="user-capsule" title="{{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})">
                @endif
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" alt="Avatar" class="w-7 h-7 rounded-full object-cover shadow-sm">
                    @else
                        <div class="user-avatar-circle">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                    @endif
                    <span class="text-xs font-bold text-slate-800 dark:text-white hidden lg:inline max-w-[120px] truncate">
                        {{ Auth::user()->name }}
                    </span>
                </a>
 
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="nav-pill-link text-red-500 hover:text-red-700 dark:hover:text-red-400 bg-transparent border-0 p-0">
                        <i class="bi bi-box-arrow-right"></i> Keluar
                    </button>
                </form>
            @endguest
        </div>
    </nav>

    <!-- ── 2. MOBILE TOP APP BAR (Only shown on screen < 768px) ── -->
    <header class="top-app-bar mobile-only">
        <div class="flex items-center gap-2">
            @if(Auth::check() && Auth::user()->role === 'admin')
                <button class="sidebar-toggle-btn me-1" aria-label="Buka Menu" style="color: var(--text-primary); background: none; border: none; font-size: 24px; padding: 4px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-list"></i>
                </button>
            @endif
            <a href="{{ url('/') }}" class="flex items-center gap-2 text-decoration-none">
                <div class="w-8 h-8 rounded-lg overflow-hidden flex items-center justify-center bg-white border border-slate-200 p-0.5 shadow-sm">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="w-6 h-6 object-contain">
                </div>
                <span class="text-sm font-black tracking-wider text-slate-900 dark:text-white">UG<span class="text-ug-gold">FORCE</span></span>
            </a>
        </div>
        
        <div class="flex items-center gap-2">
            <!-- THEME TOGGLE BUTTON -->
            <button id="theme-toggle-mobile" class="w-8 h-8 rounded-lg bg-slate-200/50 dark:bg-white/10 flex items-center justify-center text-slate-800 dark:text-yellow-400 border border-slate-300 dark:border-white/20 shadow-sm border-0">
                <i class="bi bi-moon-fill dark:hidden"></i>
                <i class="bi bi-sun-fill hidden dark:block"></i>
            </button>

            @auth
                <!-- QUICK LOGOUT -->
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-500/10 dark:bg-red-500/20 flex items-center justify-center text-red-500 border-0">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            @endauth
        </div>
    </header>

    <!-- ── 3. APP PERSPECTIVE WRAPPER (Full Viewport Content Container) ── -->
    <div id="app-perspective-wrapper" style="transform: scale(0.88) translateY(30px) translateZ(-150px); opacity: 0; filter: blur(15px); transition: transform 2s cubic-bezier(0.16, 1, 0.3, 1), opacity 1.6s cubic-bezier(0.16, 1, 0.3, 1), filter 1.6s cubic-bezier(0.16, 1, 0.3, 1); display: flex; flex-direction: column;" class="flex-grow">
        
        <!-- Ambient background visuals -->
        <div class="ambient-bg"></div>
        <div class="glow-orb orb-1"></div>
        <div class="glow-orb orb-2"></div>
        <div class="glow-orb orb-3"></div>
        <div class="cyber-stars stars-1"></div>
        <div class="cyber-stars stars-2"></div>

        <main class="app-content flex-grow flex flex-col">
            @yield('content')
        </main>

        <!-- ── 4. FOOTER (Only shown on screen >= 768px) ── -->
        <footer class="w-full py-12 border-t border-slate-300 dark:border-white/10 bg-slate-100 dark:bg-[#030008] z-20 relative transition-colors duration-500 mt-auto desktop-footer">
            <div class="max-w-7xl mx-auto px-6 lg:px-16 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-12 h-12 bg-white dark:bg-white/10 rounded-xl flex items-center justify-center text-blue-500 dark:text-ug-gold border border-slate-300 dark:border-white/20 shadow-md">
                        <i class="bi bi-building text-2xl"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-slate-800 dark:text-white text-sm tracking-widest font-bold mb-1 transition-colors duration-500">
                            &copy; 2026 UGFORCE &bull; UNIVERSITAS GUNADARMA
                        </p>
                        <p class="text-slate-500 dark:text-white/80 font-medium text-xs transition-colors duration-500 mb-0">
                            Dikembangkan eksklusif untuk Fakultas Ilmu Komputer dan Teknologi Informasi (FIKTI).
                        </p>
                    </div>
                </div>
                <div class="flex gap-6 text-slate-400 dark:text-white/80 text-2xl">
                    <a href="#" class="hover:text-ug-blue dark:hover:text-ug-gold hover:-translate-y-1 transition transform duration-300"><i class="bi bi-globe"></i></a>
                    <a href="#" class="hover:text-ug-blue dark:hover:text-ug-gold hover:-translate-y-1 transition transform duration-300"><i class="bi bi-envelope"></i></a>
                    <a href="#" class="hover:text-ug-blue dark:hover:text-ug-gold hover:-translate-y-1 transition transform duration-300"><i class="bi bi-info-circle-fill"></i></a>
                </div>
            </div>
        </footer>
        
    </div>

    <!-- ── 5. MOBILE BOTTOM TAB NAVIGATION (Only shown on screen < 768px) ── -->
    <nav class="bottom-tab-bar mobile-only {{ Auth::check() && Auth::user()->role === 'admin' ? 'scrollable-tabs' : '' }}">
        @guest
            <a href="{{ url('/') }}" class="tab-item {{ Request::is('/') ? 'active' : '' }}">
                <i class="bi bi-house-door-fill"></i>
                <span>Welcome</span>
            </a>
            <a href="{{ route('login') }}" class="tab-item {{ Request::routeIs('login') ? 'active' : '' }}">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Masuk</span>
            </a>
            <a href="{{ route('register') }}" class="tab-item {{ Request::routeIs('register') ? 'active' : '' }}">
                <i class="bi bi-person-plus-fill"></i>
                <span>Daftar</span>
            </a>
        @else
            @if(Auth::user()->role === 'student')
                <a href="{{ url('/dashboard') }}" class="tab-item {{ Request::is('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('bookings.create') }}" class="tab-item {{ Request::routeIs('bookings.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span>Pinjam</span>
                </a>
                <a href="{{ route('bookings.index') }}" class="tab-item {{ Request::routeIs('bookings.index') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i>
                    <span>Riwayat</span>
                </a>
                <a href="{{ route('profile') }}" class="tab-item {{ Request::routeIs('profile') ? 'active' : '' }}">
                    <i class="bi bi-person-fill"></i>
                    <span>Profil</span>
                </a>
            @elseif(Auth::user()->role === 'lecturer')
                <a href="{{ route('lecturer.dashboard') }}" class="tab-item {{ Request::is('lecturer/dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('bookings.create') }}" class="tab-item {{ Request::routeIs('bookings.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span>Pinjam</span>
                </a>
                <a href="{{ route('bookings.index') }}" class="tab-item {{ Request::routeIs('bookings.index') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i>
                    <span>Riwayat</span>
                </a>
                <a href="{{ route('profile') }}" class="tab-item {{ Request::routeIs('profile') ? 'active' : '' }}">
                    <i class="bi bi-person-fill"></i>
                    <span>Profil</span>
                </a>
            @else
                <!-- Admin -->
                <a href="{{ route('admin.dashboard') }}" class="tab-item {{ Request::is('admin/dashboard*') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Admin</span>
                </a>
                <a href="{{ route('admin.rooms') }}" class="tab-item {{ Request::is('admin/rooms*') ? 'active' : '' }}">
                    <i class="bi bi-door-closed-fill"></i>
                    <span>Ruang</span>
                </a>
                <a href="{{ route('admin.bookings') }}" class="tab-item {{ Request::is('admin/bookings*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard-check-fill"></i>
                    <span>Antrean</span>
                </a>
                <a href="{{ route('admin.reports') }}" class="tab-item {{ Request::is('admin/reports*') ? 'active' : '' }}">
                    <i class="bi bi-pie-chart-fill"></i>
                    <span>Laporan</span>
                </a>
                <a href="{{ route('admin.users') }}" class="tab-item {{ Request::is('admin/users*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    <span>User</span>
                </a>
                <a href="{{ route('admin.schedules') }}" class="tab-item {{ Request::is('admin/schedules*') ? 'active' : '' }}">
                    <i class="bi bi-calendar3"></i>
                    <span>Jadwal</span>
                </a>
                <a href="{{ route('admin.attendance') }}" class="tab-item {{ Request::is('admin/attendance*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check-fill"></i>
                    <span>Absen</span>
                </a>
                <a href="{{ route('admin.profile') }}" class="tab-item {{ Request::routeIs('admin.profile') ? 'active' : '' }}">
                    <i class="bi bi-person-badge-fill"></i>
                    <span>Profil</span>
                </a>
            @endif
        @endguest
    </nav>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JAVASCRIPT: Preloader & Theme Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            
            /* =======================================
               PRELOADER ANIMATION LOGIC (Responsive)
            ======================================= */
            const wrapper = document.getElementById('loader-wrapper');
            const appWrapper = document.getElementById('app-perspective-wrapper');
            const navbar = document.querySelector('nav.desktop-nav');
            
            if (sessionStorage.getItem('ugforce_loaded')) {
                if (wrapper) wrapper.style.display = 'none';
                if (appWrapper) {
                    appWrapper.style.transform = 'none';
                    appWrapper.style.opacity = '1';
                    appWrapper.style.filter = 'none';
                }
                if (navbar) {
                    navbar.style.transform = 'translateY(0px)';
                    navbar.style.opacity = '1';
                }
            } else {
                const bar = document.getElementById('loader-progress-bar');
                const pct = document.getElementById('loader-percent');
                let progress = 0;
                
                const timer = setInterval(() => {
                    progress += Math.floor(Math.random() * 12) + 4;
                    if (progress >= 100) {
                        progress = 100;
                        clearInterval(timer);
                        
                        if (bar) bar.style.width = '100%';
                        if (pct) pct.innerText = '100';
                        
                        sessionStorage.setItem('ugforce_loaded', 'true');
                        
                        setTimeout(() => {
                            if (wrapper) {
                                wrapper.style.transform = 'scale(2.5)';
                                wrapper.style.filter = 'blur(15px)';
                                wrapper.style.opacity = '0';
                            }
                            
                            if (appWrapper) {
                                appWrapper.style.transform = 'scale(1) translateY(0px) translateZ(0px)';
                                appWrapper.style.opacity = '1';
                                appWrapper.style.filter = 'blur(0px)';
                                
                                setTimeout(() => {
                                    appWrapper.style.transform = '';
                                    appWrapper.style.filter = '';
                                }, 2000);
                            }

                            if (navbar) {
                                navbar.style.transform = 'translateY(0px)';
                                navbar.style.opacity = '1';
                            }
                            
                            document.body.style.overflow = '';
                            
                            setTimeout(() => {
                                if (wrapper) wrapper.style.display = 'none';
                            }, 1200);
                        }, 300);
                    } else {
                        if (bar) bar.style.width = progress + '%';
                        if (pct) pct.innerText = progress;
                    }
                }, 40);
            }

            /* =======================================
               DARK/LIGHT MODE TOGGLE LOGIC
            ======================================= */
            const htmlElement = document.getElementById('html-root');
            
            const registerThemeToggle = (btnId) => {
                const btn = document.getElementById(btnId);
                if (btn) {
                    btn.addEventListener('click', () => {
                        htmlElement.classList.toggle('dark');
                        if (htmlElement.classList.contains('dark')) {
                            localStorage.theme = 'dark';
                        } else {
                            localStorage.theme = 'light';
                        }
                    });
                }
            };
            registerThemeToggle('theme-toggle');
            registerThemeToggle('theme-toggle-mobile');
        });
    </script>
</body>
</html>