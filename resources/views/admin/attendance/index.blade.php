@extends('layouts.app')

@section('content')
<style>
    /* CSS UTAMA KONSISTEN CMS */
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap');
    :root { 
        --bg-main: #000000; 
        --bg-surface: rgba(13, 15, 23, 0.65); 
        --bg-input: rgba(5, 7, 12, 0.6); 
        --border-soft: rgba(59, 130, 246, 0.15); 
        --border-strong: rgba(59, 130, 246, 0.35); 
        --text-primary: #ffffff; 
        --text-secondary: #3b82f6; 
        --text-tertiary: #0369a1; 
        --accent-blue: #3b82f6; 
        --accent-indigo: #3b82f6; 
        --success: #10b981; 
        --warning: #f59e0b; 
        --danger: #ef4444; 
        --radius-lg: 20px; 
        --radius-sm: 10px; 
        --font-sans: 'Plus Jakarta Sans', sans-serif; 
        --font-mono: 'JetBrains Mono', monospace;
    }
    html:not(.dark) { 
        --bg-main: #f0f9ff; 
        --bg-surface: rgba(255, 255, 255, 0.85); 
        --bg-input: #ffffff; 
        --border-soft: rgba(14, 165, 233, 0.15); 
        --border-strong: rgba(14, 165, 233, 0.35); 
        --text-primary: #0f172a; 
        --text-secondary: #0369a1; 
        --accent-blue: #0ea5e9; 
    }
    *, *::before, *::after { box-sizing: border-box; } 
    body { margin: 0; padding: 0; }
    
    @keyframes spin-slow { 100% { transform: rotate(360deg); } }
    @keyframes float-up { 0% { opacity: 0; transform: translateY(30px) scale(0.98); } 100% { opacity: 1; transform: translateY(0) scale(1); } }
    @keyframes floatOrb { 0% { transform: translate(0, 0) scale(1) rotate(0deg); } 33% { transform: translate(8vw, -6vh) scale(1.1) rotate(5deg); } 66% { transform: translate(-6vw, 8vh) scale(0.9) rotate(-5deg); } 100% { transform: translate(0, 0) scale(1) rotate(0deg); } }
    .animate-float { animation: float-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; } 
    .d-1 { animation-delay: 0.1s; } .d-2 { animation-delay: 0.2s; }
    
    .ambient-bg { position: fixed; inset: 0; z-index: -5; background: var(--bg-main); transition: background 0.5s ease; }
    .ambient-orb { position: absolute; border-radius: 50%; filter: blur(100px); opacity: 0.35; animation: floatOrb 25s infinite alternate cubic-bezier(0.4, 0, 0.2, 1); pointer-events: none; }
    html:not(.dark) .ambient-orb { opacity: 0.6; filter: blur(100px); mix-blend-mode: hard-light; }
    .orb-1 { width: 55vw; height: 55vw; max-width: 800px; max-height: 800px; background: var(--accent-blue); top: -10%; left: -10%; } 
    .orb-2 { width: 45vw; height: 45vw; max-width: 700px; max-height: 700px; background: #3b82f6; bottom: -10%; right: -5%; animation-delay: -5s; } 
    .orb-3 { width: 35vw; height: 35vw; max-width: 600px; max-height: 600px; background: var(--warning); top: 30%; left: 30%; animation-delay: -10s; opacity: 0.15; }
    html:not(.dark) .orb-1 { background: rgba(59, 130, 246, 0.4); } 
    html:not(.dark) .orb-2 { background: rgba(59, 130, 246, 0.3); } 
    html:not(.dark) .orb-3 { background: rgba(245, 158, 11, 0.3); opacity: 0.2; }
    #ug-canvas { position: fixed; inset: 0; z-index: 0; pointer-events: none; opacity: 0.45; }

    .dashboard-layout { position: relative; z-index: 2; width: 100%; min-height: 100vh; display: flex; font-family: var(--font-sans); color: var(--text-primary); }
    .cms-layout { display: grid; grid-template-columns: 280px 1fr; gap: 28px; align-items: start; width: 100%; }
    .cms-main { flex: 1; display: flex; flex-direction: column; gap: 32px; padding: 32px 40px; max-width: calc(100vw - 280px); }

    /* ── LEFT SIDEBAR (FULL HEIGHT CMS STYLE) ── */
    .cms-sidebar { width: 280px; flex-shrink: 0; background: var(--bg-surface); backdrop-filter: blur(35px); border-right: 1px solid var(--border-soft); display: flex; flex-direction: column; position: sticky; top: 0; height: 100vh; padding: 28px 24px; z-index: 50; overflow-y: auto; scrollbar-width: none; }
    .cms-sidebar::-webkit-scrollbar { display: none; }
    .sidebar-brand { display: flex; align-items: center; gap: 12px; margin-bottom: 30px; padding-bottom: 24px; border-bottom: 1px dashed var(--border-soft); font-size: 24px; font-weight: 800; } .sidebar-brand i { color: #0ea5e9; font-size: 28px; animation: glow-pulse 2s infinite; }
    .sidebar-profile { display: flex; align-items: center; gap: 16px; margin-bottom: 30px; } .sidebar-avatar { width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, var(--danger), var(--accent-indigo)); display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 800; color: #fff; position: relative; box-shadow: 0 0 15px rgba(239, 68, 68, 0.4); flex-shrink: 0; } .sidebar-avatar::before { content: ''; position: absolute; inset: -4px; border-radius: 50%; border: 2px dashed var(--danger); animation: spin-slow 10s linear infinite; opacity: 0.2; } .sidebar-greeting { font-size: 12px; color: var(--text-secondary); font-weight: 600; text-transform: uppercase; margin-bottom: 2px;} .sidebar-name { font-size: 16px; font-weight: 800; color: var(--text-primary); }
    .sidebar-header { font-size: 12px; font-weight: 700; color: var(--text-tertiary); margin-bottom: 12px; letter-spacing: 1px; text-transform: uppercase; font-family: var(--font-mono);}
    .sidebar-menus { display: flex; flex-direction: column; gap: 6px; flex: 1;}
    .sidebar-item { display: flex; align-items: center; gap: 14px; padding: 12px 16px; border-radius: 12px; text-decoration: none; transition: all 0.3s; position: relative; color: var(--text-secondary); border: 1px solid transparent;}
    .sidebar-item.active { background: var(--bg-input); border-color: var(--border-soft); color: var(--text-primary); border-left: 4px solid var(--accent-blue); }
    .sidebar-item:hover { border-color: #0ea5e9; background: rgba(59, 130, 246, 0.05); color: var(--text-primary); transform: translateX(6px); } html:not(.dark) .sidebar-item:hover { background: rgba(14, 165, 233, 0.05); }
    .sidebar-item i.icon { font-size: 16px; transition: all 0.3s; width: 24px; text-align: center; color: var(--text-tertiary);} .sidebar-item.active i.icon { color: #0ea5e9; } .sidebar-item:hover i.icon { color: #0ea5e9; transform: scale(1.1); }
    .sidebar-item .title { font-size: 14px; font-weight: 700; flex: 1; }
    .logout-box { margin-top: auto; padding-top: 24px; } .btn-logout { display: flex; align-items: center; gap: 12px; padding: 14px 16px; border-radius: 14px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: var(--danger); font-size: 14px; font-weight: 700; border: none; cursor: pointer; transition: all 0.3s; width: 100%;} .btn-logout:hover { background: var(--danger); color: #fff; transform: translateY(-2px); }
    
    .glass-card { background: var(--bg-surface); backdrop-filter: blur(25px); border: 1px solid var(--border-soft); border-radius: var(--radius-lg); position: relative; }
    .topbar { display: flex; justify-content: space-between; align-items: center; padding: 20px 32px; border-radius: 16px; margin-bottom: 4px;} 
    .topbar-left { display: flex; align-items: center; gap: 16px; } 
    .topbar-title { font-size: 18px; font-weight: 800; display: flex; align-items: center; gap: 12px; }
    .topbar-right { display: flex; align-items: center; gap: 24px; } 
    .clock-display { font-family: var(--font-mono); font-size: 13px; font-weight: 700; padding: 8px 16px; border-radius: 10px; border: 1px dashed var(--border-soft); background: var(--bg-input); }
    
    /* Table Panel */
    .table-panel { padding: 0; display: flex; flex-direction: column; border-radius: 20px;}
    .table-toolbar { padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed var(--border-soft); }
    .panel-header { font-size: 16px; font-weight: 800; display: flex; align-items: center; gap: 10px; margin: 0; }
    .table-wrapper { overflow-x: auto; padding: 10px 20px 20px; }
    .data-table { width: 100%; border-collapse: separate; border-spacing: 0 10px; text-align: left; }
    .data-table th { padding: 12px 20px; font-size: 11px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; font-family: var(--font-mono); }
    .data-table td { padding: 14px 20px; font-size: 14px; background: rgba(255,255,255,0.03); vertical-align: middle; border-top: 1px solid transparent; border-bottom: 1px solid transparent;} 
    html:not(.dark) .data-table td { background: rgba(255,255,255,0.5); }
    .data-table tr td:first-child { border-radius: 12px 0 0 12px; border-left: 3px solid transparent; } 
    .data-table tr td:last-child { border-radius: 0 12px 12px 0; border-right: 3px solid transparent; }
    .data-table tbody tr:hover td { background: rgba(59, 130, 246, 0.08); } 
    .data-table tbody tr:hover td:first-child { border-left-color: #0ea5e9; }
    
    .td-primary { font-weight: 700; font-size: 15px; color: var(--text-primary); }
    .td-location { color: #0ea5e9; font-weight: 600; }
    .td-time { font-family: var(--font-mono); font-size: 12px; color: var(--text-secondary); background: var(--bg-input); padding: 4px 8px; border-radius: 6px; border: 1px solid var(--border-soft); display: inline-block;}

    .btn-action { background: rgba(59, 130, 246, 0.1); color: #0ea5e9; border: 1px solid rgba(59, 130, 246, 0.3); padding: 6px 14px; border-radius: 8px; font-size: 12px; font-weight: 700; text-decoration: none; transition: all 0.3s; display: inline-block;}
    .btn-action:hover { background: var(--accent-blue); color: #000; transform: scale(1.05); }
    .btn-danger-outline { background: rgba(239, 68, 68, 0.1); color: var(--danger); border: 1px solid rgba(239, 68, 68, 0.3); }
    .btn-danger-outline:hover { background: var(--danger); color: #fff; }
    
    /* Photo Thumbnail styling */
    .scan-thumb {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid var(--border-soft);
        cursor: zoom-in;
        transition: transform 0.25s ease, border-color 0.25s ease;
    }
    .scan-thumb:hover {
        transform: scale(1.15) rotate(2deg);
        border-color: var(--accent-blue);
    }
    
    .empty-state { text-align: center; padding: 60px 20px; color: var(--text-tertiary); }
    .empty-state i { font-size: 40px; margin-bottom: 12px; display: block; opacity: 0.2; color: var(--accent-blue); }

    @media (max-width: 1024px) { 
        .dashboard-layout { flex-direction: column; } 
        .cms-layout { display: flex !important; flex-direction: column !important; }
        .cms-sidebar { width: 100%; height: auto; position: relative; border-right: none;} 
        .cms-main { max-width: 100%; padding: 24px 20px; margin-left: 0; } 
    }

    @media (max-width: 767px) {
        .dashboard-layout {
            flex-direction: column !important;
        }
        .cms-layout {
            display: flex !important;
            flex-direction: column !important;
            gap: 0 !important;
        }
        .cms-main {
            max-width: 100% !important;
            width: 100% !important;
            padding: 16px !important;
            gap: 16px !important;
        }
        .topbar {
            padding: 14px 16px !important;
            flex-wrap: wrap;
            gap: 8px;
        }
        .topbar-title {
            font-size: 14px !important;
        }
        .table-toolbar {
            padding: 14px 16px !important;
            flex-direction: column;
            gap: 10px;
            align-items: flex-start !important;
        }
        .panel-header {
            font-size: 14px !important;
        }
        .table-wrapper {
            padding: 8px 10px 16px !important;
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
        }
        .data-table {
            min-width: 700px;
        }
        .data-table th {
            padding: 8px 12px !important;
            font-size: 10px !important;
        }
        .data-table td {
            padding: 10px 12px !important;
            font-size: 12px !important;
        }
        .scan-thumb {
            width: 40px !important;
            height: 40px !important;
        }
        .glass-card {
            border-radius: 16px !important;
        }
        .td-time {
            font-size: 10px !important;
            padding: 3px 6px !important;
        }
    }
</style>

<div class="ambient-bg">
    <div class="ambient-orb orb-1"></div>
    <div class="ambient-orb orb-2"></div>
    <div class="ambient-orb orb-3"></div>
</div>
<canvas id="ug-canvas"></canvas>

<div class="dashboard-layout">
        
        {{-- SIDEBAR PARTIAL --}}
        @include('admin.partials.sidebar', ['activeMenu' => 'attendance'])

        {{-- MAIN CONTENT --}}
        <main class="cms-main">
            <header class="glass-card topbar animate-float d-1">
                <div class="topbar-left">
                    <button class="sidebar-toggle-btn" aria-label="Buka Menu"><i class="fas fa-bars"></i></button>
                    <div class="topbar-title">Log Presensi & Kehadiran Dosen</div>
                </div>
                <div class="topbar-right">
                    <div class="clock-display hidden lg:block" id="ug-clock">--:--:-- WIB</div>
                    <div class="top-profile"><span>{{ Auth::user()->name }}</span></div>
                </div>
            </header>

            {{-- TABEL DATA KEHADIRAN --}}
            <div class="glass-card table-panel animate-float d-2">
                <div class="table-toolbar">
                    <div class="panel-header">
                        <i class="fas fa-id-card text-blue-500"></i> Riwayat Presensi Wajah Dosen FIKTI
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Foto Scan</th>
                                <th>Nama Dosen</th>
                                <th>Email Dosen</th>
                                <th>Waktu Presensi</th>
                                <th>Lokasi / Deteksi GPS</th>
                                <th style="text-align:right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $attendance)
                            <tr>
                                <td>
                                    <img src="{{ $attendance->scan_photo }}" 
                                         alt="Scan Wajah" 
                                         class="scan-thumb" 
                                         data-bs-toggle="modal" 
                                         data-bs-target="#photoModal" 
                                         data-photo="{{ $attendance->scan_photo }}"
                                         data-name="{{ $attendance->user->name }}"
                                         data-time="{{ \Carbon\Carbon::parse($attendance->created_at)->translatedFormat('l, d F Y - H:i:s') }} WIB">
                                </td>
                                <td>
                                    <div class="td-primary">{{ $attendance->user->name }}</div>
                                </td>
                                <td class="text-slate-500 dark:text-slate-400 font-medium">{{ $attendance->user->email }}</td>
                                <td>
                                    <div class="td-time">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($attendance->created_at)->translatedFormat('d M Y - H:i') }} WIB
                                    </div>
                                </td>
                                <td class="td-location">
                                    @php
                                        $loc = $attendance->location ?? 'GPS Tidak Terdeteksi';
                                        $mapUrl = null;
                                        if (preg_match('/(-?\d+\.\d+),\s*(-?\d+\.\d+)/', $loc, $matches)) {
                                            $mapUrl = "https://www.google.com/maps/search/?api=1&query={$matches[1]},{$matches[2]}";
                                        }
                                    @endphp
                                    @if($mapUrl)
                                        <a href="{{ $mapUrl }}" target="_blank" class="hover:underline flex items-center text-sky-500 font-semibold" title="Buka di Google Maps" style="text-decoration: none;">
                                            <i class="bi bi-geo-alt-fill text-danger me-1.5"></i>
                                            <span>{{ $loc }}</span>
                                            <i class="bi bi-box-arrow-up-right text-[10px] ms-1.5 opacity-70"></i>
                                        </a>
                                    @else
                                        <div class="flex items-center text-slate-500 dark:text-slate-400 font-medium">
                                            <i class="bi bi-geo-alt-fill text-slate-400 me-1.5"></i>
                                            <span>{{ $loc }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td style="text-align:right">
                                    <form action="{{ route('admin.attendance.destroy', $attendance->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus log kehadiran ini? Dosen yang bersangkutan dapat melakukan scan presensi ulang hari ini.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-danger-outline" style="border-radius: 10px; font-size: 11px; padding: 6px 12px; cursor: pointer; font-weight: 700;">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="fas fa-id-card-clip"></i>
                                        <p>Belum ada log presensi kehadiran dosen hari ini.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
</div>

{{-- MODAL SHOW FULL PHOTO --}}
<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-slate-900 text-white border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
            <div class="modal-header border-bottom border-slate-800/80 px-4 py-3 justify-between items-center d-flex">
                <div>
                    <h5 class="modal-title font-extrabold text-lg text-slate-100" id="modalLecturerName">Detail Foto Scan</h5>
                    <p class="text-[10px] text-slate-400 font-semibold mb-0" id="modalScanTime"></p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="box-shadow: none;"></button>
            </div>
            <div class="modal-body p-0 flex justify-center items-center bg-black">
                <img id="modalFullImage" src="" alt="Full Scan" class="w-full h-auto max-h-[75vh] object-contain">
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    /* ── LIVE CLOCK ── */
    setInterval(() => {
        const now = new Date(); const pad = v => String(v).padStart(2, '0');
        const clockEl = document.getElementById('ug-clock');
        if(clockEl) clockEl.textContent = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())} WIB`;
    }, 1000);

    /* ── PHOTO MODAL EVENT LISTENER ── */
    const photoModal = document.getElementById('photoModal');
    if (photoModal) {
        document.body.appendChild(photoModal);
        photoModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const photoSrc = button.getAttribute('data-photo');
            const lecturerName = button.getAttribute('data-name');
            const scanTime = button.getAttribute('data-time');

            const modalImage = document.getElementById('modalFullImage');
            const modalName = document.getElementById('modalLecturerName');
            const modalTime = document.getElementById('modalScanTime');

            modalImage.src = photoSrc;
            modalName.textContent = lecturerName;
            modalTime.textContent = scanTime;
        });
    }

    /* ── INTERACTIVE PARTICLE CANVAS ── */
    const canvas = document.getElementById('ug-canvas');
    if(!canvas) return; const ctx = canvas.getContext('2d');
    let W, H, particles = []; 
    const isDark = () => document.documentElement.classList.contains('dark');
    function resize() { W = canvas.width = window.innerWidth; H = canvas.height = window.innerHeight; }
    resize(); window.addEventListener('resize', resize);
    
    class Particle {
        constructor() { this.reset(); }
        reset() { this.x = Math.random() * W; this.y = Math.random() * H; this.r = Math.random() * 1.5 + 0.5; this.vx = (Math.random() - .5) * 0.4; this.vy = (Math.random() - .5) * 0.4; this.alpha = Math.random() * 0.4 + 0.1; }
        update() { this.x += this.vx; this.y += this.vy; if(this.x < 0 || this.x > W || this.y < 0 || this.y > H) this.reset(); }
        draw() { ctx.beginPath(); ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2); ctx.fillStyle = isDark() ? `rgba(255,255,255,${this.alpha})` : `rgba(59, 130, 246,${this.alpha})`; ctx.fill(); }
    }
    for(let i = 0; i < 80; i++) particles.push(new Particle());
    function frame() { ctx.clearRect(0, 0, W, H); particles.forEach(p => { p.update(); p.draw(); }); requestAnimationFrame(frame); }
    frame();
});
</script>
@endsection
