@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;700&display=swap');

    /* ── DESIGN SYSTEM TOKENS ── */
    :root {
        --bg-main:         #f0f9ff;
        --bg-surface:      #ffffff;
        --border-soft:     #e2e8f0;
        --border-strong:   #cbd5e1;
        --border-focus:    #0ea5e9;
        --text-primary:    #0f172a;
        --text-secondary:  #0369a1;
        --text-tertiary:   #3b82f6;
        --accent-blue:     #0ea5e9;
        --accent-indigo:   #3b82f6;
        --success:         #10b981;
        --warning:         #f59e0b;
        --danger:          #ef4444;
        --radius-lg:       24px;
        --radius-md:       16px;
        --radius-sm:       10px;
        --font-sans:       'Outfit', system-ui, sans-serif;
    }

    .dark {
        --bg-main:         #020617;
        --bg-surface:      rgba(15, 23, 42, 0.65);
        --border-soft:     rgba(255, 255, 255, 0.08);
        --border-strong:   rgba(255, 255, 255, 0.15);
        --border-focus:    #3b82f6;
        --text-primary:    #ffffff;
        --text-secondary:  #38bdf8;
        --text-tertiary:   #0ea5e9;
        --accent-blue:     #0ea5e9;
        --accent-indigo:   #3b82f6;
    }

    body {
        font-family: var(--font-sans);
        background: var(--bg-main);
    }

    /* ── BACKGROUND LAYER ── */
    .hero-photo-bg {
        position: absolute; top: 0; left: 0; right: 0; height: 420px;
        background-image: url('https://2.bp.blogspot.com/-ah30_c4lnYE/Tsia8ZNlT-I/AAAAAAAAAC4/avMnlc5l9x8/w1200-h630-p-k-no-nu/KAMPUS+E.jpg'); 
        background-size: cover; background-position: center 30%;
        z-index: -2; 
        mask-image: linear-gradient(to bottom, black 40%, transparent 100%);
        -webkit-mask-image: linear-gradient(to bottom, black 40%, transparent 100%);
        opacity: 0.18; transition: opacity 0.5s ease;
        filter: grayscale(10%) blur(1px);
    }
    html.dark .hero-photo-bg { opacity: 0.08; }

    /* ── PREMIUM GLASS CARD ── */
    .glass-card-premium {
        background: var(--bg-surface);
        border: 1px solid var(--border-soft);
        backdrop-filter: blur(25px);
        border-radius: var(--radius-lg);
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.04);
        padding: 32px;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }
    .glass-card-premium:hover {
        border-color: var(--border-strong);
        box-shadow: 0 30px 60px rgba(15, 23, 42, 0.08);
        transform: translateY(-2px);
    }
    .dark .glass-card-premium {
        background: rgba(13, 18, 30, 0.7);
        border-color: rgba(255, 255, 255, 0.06);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
    }
    .dark .glass-card-premium:hover {
        border-color: rgba(255, 255, 255, 0.12);
        box-shadow: 0 35px 70px rgba(0, 0, 0, 0.5);
    }

    /* ── STATS CARD ── */
    .stat-card {
        padding: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-radius: var(--radius-md);
        background: var(--bg-surface);
        border: 1px solid var(--border-soft);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .stat-card:hover {
        transform: scale(1.02) translateY(-2px);
        box-shadow: 0 15px 30px rgba(15, 23, 42, 0.06);
    }
    .dark .stat-card {
        background: rgba(13, 18, 30, 0.5);
        border-color: rgba(255, 255, 255, 0.05);
    }
    .dark .stat-card:hover {
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }

    /* ── TIMELINE SCHEDULE ITEM ── */
    .schedule-item-card {
        position: relative;
        padding: 24px;
        border-radius: var(--radius-md);
        background: rgba(255, 255, 255, 0.6);
        border: 1px solid var(--border-soft);
        transition: all 0.3s ease;
        display: grid;
        grid-template-columns: 140px 1fr auto;
        align-items: center;
        gap: 20px;
    }
    .dark .schedule-item-card {
        background: rgba(255, 255, 255, 0.02);
    }
    .schedule-item-card:hover {
        background: rgba(255, 255, 255, 0.9);
        border-color: var(--accent-blue);
        box-shadow: 0 12px 24px rgba(14, 165, 233, 0.06);
        transform: translateX(4px);
    }
    .dark .schedule-item-card:hover {
        background: rgba(255, 255, 255, 0.04);
        border-color: var(--accent-blue);
        box-shadow: 0 12px 30px rgba(56, 189, 248, 0.15);
    }

    /* Active Teaching State Glow */
    .schedule-item-card.active-class {
        border-color: var(--success);
        background: rgba(16, 185, 129, 0.03);
        box-shadow: 0 0 25px rgba(16, 185, 129, 0.08);
    }
    .dark .schedule-item-card.active-class {
        background: rgba(16, 185, 129, 0.05);
        border-color: rgba(16, 185, 129, 0.4);
        box-shadow: 0 0 35px rgba(16, 185, 129, 0.15);
    }
    
    .schedule-item-card.active-class::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        background: var(--success);
        border-radius: var(--radius-sm) 0 0 var(--radius-sm);
    }

    /* Completed State card style */
    .schedule-item-card.completed-class {
        opacity: 0.75;
    }
    .schedule-item-card.completed-class::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        background: var(--border-strong);
        border-radius: var(--radius-sm) 0 0 var(--radius-sm);
    }

    /* Custom buttons with micro animations */
    .btn-action-confirm {
        font-weight: 700;
        font-size: 12px;
        padding: 10px 20px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .btn-action-confirm.start {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
        border: none;
    }
    .btn-action-confirm.start:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.5);
    }
    
    .btn-action-confirm.finish {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        box-shadow: 0 4px 14px rgba(239, 68, 68, 0.3);
        border: none;
    }
    .btn-action-confirm.finish:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.5);
    }

    /* WhatsApp button customization */
    .wa-button-brand {
        background: linear-gradient(135deg, #25D366, #128C7E);
        box-shadow: 0 6px 20px rgba(37, 211, 102, 0.25);
        border: none;
        transition: all 0.3s ease;
    }
    .wa-button-brand:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(37, 211, 102, 0.4);
    }

    /* Preloader logo styling */
    .ug-logo-shadow {
        filter: drop-shadow(0 8px 16px rgba(14, 165, 233, 0.15));
    }
    
    @media (max-width: 991px) {
        .schedule-item-card {
            grid-template-columns: 1fr;
            text-align: center;
            gap: 16px;
        }
        .schedule-item-card .time-col {
            margin: 0 auto;
        }
        .schedule-item-card .action-col {
            justify-self: center;
            width: 100%;
        }
        .schedule-item-card .action-col form,
        .schedule-item-card .action-col button {
            width: 100%;
        }
    }
</style>

<div class="hero-photo-bg"></div>

@include('partials.biometric-gate')

<script>
    if (sessionStorage.getItem('biometric_verified') === 'true') {
        const gate = document.getElementById('biometric-gate');
        if (gate) gate.style.display = 'none';
        const navbar = document.querySelector('nav');
        if (navbar) navbar.style.display = 'flex';
    }
</script>

<canvas id="ug-canvas"></canvas>

<!-- ── MAIN DOSEN DASHBOARD PAGE ── -->
<div class="container py-8 z-10 relative" style="max-width: 1200px; margin-top: 70px;">
    
    <!-- Dashboard Header Card -->
    <div class="glass-card-premium mb-8">
        <div class="flex flex-col lg:flex-row justify-between items-center gap-6">
            <div class="flex flex-col md:flex-row items-center gap-6 flex-grow">
                <!-- Profile Avatar frame with gradient ring -->
                <div class="relative">
                    <div class="absolute inset-0 rounded-full bg-gradient-to-tr from-sky-400 to-indigo-500 blur-sm opacity-60"></div>
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" alt="Foto Dosen" class="relative w-24 h-24 rounded-full object-cover border-4 border-white dark:border-slate-900 shadow-xl z-10">
                    @else
                        <div class="relative w-24 h-24 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-600 flex items-center justify-center text-white text-3xl font-black border-4 border-white dark:border-slate-900 shadow-xl z-10 uppercase">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                    @endif
                    <div class="absolute bottom-0 right-0 w-7 h-7 bg-green-500 border-4 border-white dark:border-slate-900 rounded-full z-20 shadow-md"></div>
                </div>
                
                <div class="flex-grow text-center md:text-left">
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mb-2">
                        <span class="px-3 py-1 rounded-full bg-green-500/10 border border-green-500/30 text-green-600 dark:text-green-400 text-[10px] font-black uppercase tracking-wider">
                            <i class="bi bi-shield-fill-check me-1"></i> Wajah Terverifikasi
                        </span>
                        <span class="px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-blue-600 dark:text-sky-400 text-[10px] font-black uppercase tracking-wider">
                            Dosen FIKTI
                        </span>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-1.5 text-slate-900 dark:text-white leading-tight">
                        Selamat Datang, <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-sky-400 dark:to-yellow-400">{{ Auth::user()->name }}</span>
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mb-0 max-w-2xl">
                        Akses layanan kehadiran biometrik, jadwal mengajar, dan kendali ruang kelas pintar terpusat Kampus FIKTI Universitas Gunadarma.
                    </p>
                </div>
            </div>
            
            <!-- Lecturer info capsule -->
            <div class="flex-shrink-0 w-full lg:w-auto">
                <div class="flex gap-4 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 p-4 rounded-2xl shadow-sm justify-between lg:justify-start w-full lg:w-auto">
                    <div class="text-start">
                        <p class="text-[9px] font-black text-slate-400 dark:text-white/40 uppercase tracking-wider mb-1">NIDN / Kode Dosen</p>
                        <p class="text-sm font-black text-slate-800 dark:text-white mb-0">0426057002 / BDS</p>
                    </div>
                    <div class="w-px bg-slate-200 dark:bg-white/10 self-stretch"></div>
                    <div class="text-start">
                        <p class="text-[9px] font-black text-slate-400 dark:text-white/40 uppercase tracking-wider mb-1">Program Studi</p>
                        <p class="text-sm font-black text-slate-800 dark:text-white mb-0">Sistem Informasi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Statistics Row -->
    <div class="row g-4 mb-8">
        <div class="col-md-4">
            <div class="stat-card">
                <div>
                    <span class="text-slate-400 dark:text-white/50 text-[10px] font-black tracking-wider uppercase block mb-1">Jadwal Kelas Hari Ini</span>
                    <h3 class="text-2xl font-black mb-0 text-slate-900 dark:text-white">{{ $schedules->count() }} Jadwal Mengajar</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500 dark:text-sky-400 text-xl border border-blue-500/20 shadow-inner">
                    <i class="bi bi-calendar3"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div>
                    <span class="text-slate-400 dark:text-white/50 text-[10px] font-black tracking-wider uppercase block mb-1">Status Kehadiran Dosen</span>
                    @if($alreadyAttended)
                        <h3 class="text-2xl font-black text-green-500 dark:text-green-400 mb-0 flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></span> Hadir Mengajar
                        </h3>
                    @else
                        <h3 class="text-2xl font-black text-amber-500 dark:text-amber-400 mb-0 flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span> Belum Presensi
                        </h3>
                    @endif
                </div>
                <div class="w-12 h-12 rounded-xl bg-green-500/10 flex items-center justify-center text-green-500 text-xl border border-green-500/20 shadow-inner">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div>
                    <span class="text-slate-400 dark:text-white/50 text-[10px] font-black tracking-wider uppercase block mb-1">Sistem Otentikasi</span>
                    <h3 class="text-2xl font-black text-blue-500 dark:text-sky-400 mb-0">OpenCV FaceID</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-500 dark:text-indigo-400 text-xl border border-indigo-500/20 shadow-inner">
                    <i class="bi bi-shield-fill-check"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-5">
        <!-- Lecturer Schedule Section (Ditetapkan Admin) -->
        <div class="col-lg-8">
            <div class="glass-card-premium">
                <!-- Section Title -->
                <div class="flex justify-between items-center mb-8 flex-wrap gap-4">
                    <div>
                        <h4 class="text-2xl font-extrabold mb-1 flex items-center gap-2.5 text-slate-900 dark:text-white">
                            <i class="bi bi-journal-bookmark-fill text-blue-500"></i> Agenda Mengajar Hari Ini
                        </h4>
                        <p class="text-slate-500 dark:text-slate-400 text-xs mb-0">Kelola dan konfirmasi status ruang kelas secara langsung.</p>
                    </div>
                    <span class="px-3.5 py-1.5 rounded-xl bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-[10px] font-black text-slate-600 dark:text-white uppercase tracking-wider flex items-center gap-2">
                        <i class="bi bi-info-circle text-ug-gold"></i> Semester Ganjil 2025/2026
                    </span>
                </div>

                @if($schedules->isEmpty())
                    <div class="text-center py-16">
                        <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-4 text-slate-400 dark:text-slate-600">
                            <i class="bi bi-calendar-x text-3xl"></i>
                        </div>
                        <h5 class="text-lg font-bold mb-1 text-slate-700 dark:text-slate-300">Tidak Ada Jadwal Hari Ini</h5>
                        <p class="text-slate-500 dark:text-slate-400 text-sm max-w-sm mx-auto mb-0">Belum ada jadwal mengajar hari ini. Jika ada kekeliruan, silakan hubungi Sekretariat FIKTI.</p>
                    </div>
                @else
                    <!-- Timeline Card Stack -->
                    <div class="flex flex-col gap-4">
                        @foreach($schedules as $schedule)
                            @php
                                $classStateClass = '';
                                if ($schedule->status === 'selesai') {
                                    $classStateClass = 'active-class';
                                } elseif ($schedule->status === 'selesai_selesai') {
                                    $classStateClass = 'completed-class';
                                }
                            @endphp
                            <div class="schedule-item-card {{ $classStateClass }}">
                                <!-- Time display column -->
                                <div class="time-col bg-slate-50 dark:bg-white/5 border border-slate-200/60 dark:border-white/5 px-3 py-2 rounded-xl text-center flex flex-col justify-center">
                                    <span class="text-[10px] font-black text-slate-400 dark:text-white/40 uppercase tracking-wider">{{ $schedule->day_of_week }}</span>
                                    <span class="font-mono text-sm font-extrabold text-slate-700 dark:text-slate-300 my-0.5">{{ substr($schedule->start_time, 0, 5) }}</span>
                                    <div class="w-3 h-px bg-slate-300 dark:bg-white/20 mx-auto"></div>
                                    <span class="font-mono text-xs text-slate-500 dark:text-slate-500 mt-0.5">{{ substr($schedule->end_time, 0, 5) }}</span>
                                </div>

                                <!-- Class Details Column -->
                                <div>
                                    <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                        <span class="px-2 py-0.5 rounded bg-blue-500/10 border border-blue-500/20 text-blue-600 dark:text-sky-400 text-[10px] font-black tracking-wider uppercase">
                                            {{ $schedule->class_name }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-[#FFC107] text-[10px] font-black tracking-wider">
                                            Ruang {{ $schedule->room->name }} (Lt. {{ $schedule->room->lantai ?? '1' }})
                                        </span>
                                    </div>
                                    <h5 class="text-lg font-bold text-slate-900 dark:text-white mb-0">{{ $schedule->subject }}</h5>
                                </div>

                                <!-- Actions Column -->
                                <div class="action-col flex items-center justify-center">
                                    @if($schedule->status === 'ready' || empty($schedule->status))
                                        <div class="flex flex-col items-center gap-2">
                                            <span class="text-[10px] font-black text-amber-500 uppercase tracking-wider flex items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Belum Dimulai
                                            </span>
                                            <form method="POST" action="{{ route('lecturer.schedules.confirm', $schedule->id) }}" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn-action-confirm start">
                                                    <i class="bi bi-play-fill text-lg leading-none"></i> Mulai Mengajar
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($schedule->status === 'selesai')
                                        <div class="flex flex-col items-center gap-2">
                                            <span class="text-[10px] font-black text-green-500 uppercase tracking-wider flex items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-ping"></span> Mengajar Aktif
                                            </span>
                                            <form method="POST" action="{{ route('lecturer.schedules.finish', $schedule->id) }}" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn-action-confirm finish">
                                                    <i class="bi bi-stop-fill text-lg leading-none"></i> Akhiri Kelas
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="px-3.5 py-1.5 rounded-xl bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-xs font-bold text-slate-400 dark:text-slate-500 flex items-center gap-1.5">
                                            <i class="bi bi-check2-all text-base leading-none text-green-500"></i> Kelas Selesai
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Side: Quick Actions & Instructions -->
        <div class="col-lg-4">
            <!-- Smart Room Control Assistance -->
            <div class="glass-card-premium mb-6 bg-gradient-to-br from-blue-600/5 to-indigo-600/5 border-blue-500/20">
                <h4 class="text-xl font-bold mb-3 flex items-center gap-2 text-slate-900 dark:text-white">
                    <i class="bi bi-cpu-fill text-blue-500"></i> Kontrol Kelas Pintar
                </h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4 leading-relaxed">
                    Sistem otomatisasi mengontrol fasilitas kelas Anda. AC dan Proyektor menyala otomatis berdasarkan deteksi kehadiran biometrik.
                </p>
                <div class="bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl p-3.5 mb-4 space-y-2.5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-extrabold text-slate-500 dark:text-white/60">Pintu Ruangan:</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-green-500/10 border border-green-500/20 text-green-600 dark:text-green-400 font-extrabold uppercase text-[9px]">Terbuka (Auto)</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-extrabold text-slate-500 dark:text-white/60">AC & Proyektor:</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-green-500/10 border border-green-500/20 text-green-600 dark:text-green-400 font-extrabold uppercase text-[9px]">Aktif</span>
                    </div>
                </div>
                <button class="w-full py-2.5 rounded-xl border border-blue-500/20 dark:border-sky-400/20 text-blue-600 dark:text-sky-400 bg-blue-500/5 hover:bg-blue-500/10 dark:bg-sky-400/5 dark:hover:bg-sky-400/10 transition-all duration-300 font-bold flex items-center justify-center gap-2 text-xs">
                    <i class="bi bi-broadcast"></i> Refresh Status Perangkat
                </button>
            </div>

            <!-- WhatsApp Support Help -->
            <div class="glass-card-premium">
                <div class="w-12 h-12 rounded-xl bg-green-500/10 flex items-center justify-center text-green-500 text-2xl border border-green-500/20 mb-4">
                    <i class="bi bi-chat-text-fill"></i>
                </div>
                <h5 class="text-lg font-bold mb-1.5 text-slate-900 dark:text-white">Butuh Bantuan Kelas?</h5>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4 leading-relaxed">Hubungi Sekretariat FIKTI via WhatsApp untuk bantuan teknis darurat (AC mati, proyektor error, kunci manual).</p>
                <a href="https://wa.me/6281380372893?text=Halo%20UGFORCE%20Support,%20saya%20dosen%20ingin%20bertanya%20mengenai%20masalah%20kelas..." target="_blank" class="w-full py-3 font-bold flex items-center justify-center gap-2 text-white rounded-xl text-xs wa-button-brand text-decoration-none">
                    <i class="bi bi-whatsapp text-sm"></i> WhatsApp Support FIKTI
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
