@extends('layouts.app')

@section('content')
<style>
    /* CSS UTAMA KITA PANGGIL ULANG SUPAYA KONSISTEN DI SELURUH CMS */
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap');
    :root { --bg-main: #000000; --bg-surface: rgba(13, 15, 23, 0.65); --bg-input: rgba(5, 7, 12, 0.6); --border-soft: rgba(59, 130, 246, 0.15); --border-strong: rgba(59, 130, 246, 0.35); --text-primary: #ffffff; --text-secondary: #3b82f6; --text-tertiary: #0369a1; --accent-blue: #3b82f6; --accent-indigo: #3b82f6; --success: #10b981; --warning: #f59e0b; --danger: #ef4444; --radius-lg: 20px; --radius-sm: 10px; --font-sans: 'Plus Jakarta Sans', sans-serif; --font-mono: 'JetBrains Mono', monospace;}
    html:not(.dark) { --bg-main: #f0f9ff; --bg-surface: rgba(255, 255, 255, 0.85); --bg-input: #ffffff; --border-soft: rgba(14, 165, 233, 0.15); --border-strong: rgba(14, 165, 233, 0.35); --text-primary: #0f172a; --text-secondary: #0369a1; --accent-blue: #0ea5e9; }
    *, *::before, *::after { box-sizing: border-box; } body { margin: 0; padding: 0; }
    
    /* Animations */
    @keyframes spin-slow { 100% { transform: rotate(360deg); } }
    @keyframes float-up { 0% { opacity: 0; transform: translateY(30px) scale(0.98); } 100% { opacity: 1; transform: translateY(0) scale(1); } }
    @keyframes floatOrb { 0% { transform: translate(0, 0) scale(1) rotate(0deg); } 33% { transform: translate(8vw, -6vh) scale(1.1) rotate(5deg); } 66% { transform: translate(-6vw, 8vh) scale(0.9) rotate(-5deg); } 100% { transform: translate(0, 0) scale(1) rotate(0deg); } }
    .animate-float { animation: float-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; } .d-1 { animation-delay: 0.1s; } .d-2 { animation-delay: 0.2s; } .d-3 { animation-delay: 0.3s; }

    /* Ambient Background */
    .ambient-bg { position: fixed; inset: 0; z-index: -5; background: var(--bg-main); transition: background 0.5s ease; }
    .ambient-orb { position: absolute; border-radius: 50%; filter: blur(100px); opacity: 0.35; animation: floatOrb 25s infinite alternate cubic-bezier(0.4, 0, 0.2, 1); pointer-events: none; }
    html:not(.dark) .ambient-orb { opacity: 0.6; filter: blur(100px); mix-blend-mode: hard-light; }
    .orb-1 { width: 55vw; height: 55vw; max-width: 800px; max-height: 800px; background: var(--accent-blue); top: -10%; left: -10%; } .orb-2 { width: 45vw; height: 45vw; max-width: 700px; max-height: 700px; background: #3b82f6; bottom: -10%; right: -5%; animation-delay: -5s; } .orb-3 { width: 35vw; height: 35vw; max-width: 600px; max-height: 600px; background: var(--warning); top: 30%; left: 30%; animation-delay: -10s; opacity: 0.15; }
    html:not(.dark) .orb-1 { background: rgba(59, 130, 246, 0.4); } html:not(.dark) .orb-2 { background: rgba(59, 130, 246, 0.3); } html:not(.dark) .orb-3 { background: rgba(245, 158, 11, 0.3); opacity: 0.2; }
    #ug-canvas { position: fixed; inset: 0; z-index: 0; pointer-events: none; opacity: 0.45; }

    /* Layout */
    .dashboard-layout { position: relative; z-index: 2; width: 100%; min-height: 100vh; display: flex; font-family: var(--font-sans); color: var(--text-primary); }
    .cms-layout { display: grid; grid-template-columns: 280px 1fr; gap: 28px; align-items: start; }
    
    /* Sidebar */
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

    /* Main Content */
    .cms-main { flex: 1; display: flex; flex-direction: column; gap: 32px; padding: 32px 40px; max-width: calc(100vw - 280px); }
    .glass-card { background: var(--bg-surface); backdrop-filter: blur(25px); border: 1px solid var(--border-soft); border-radius: var(--radius-lg); position: relative; }
    
    /* Topbar */
    .topbar { display: flex; justify-content: space-between; align-items: center; padding: 20px 32px; border-radius: 16px; margin-bottom: 4px;} .topbar-left { display: flex; align-items: center; gap: 16px; } .topbar-left i.fa-bars { font-size: 20px; padding: 8px; border-radius: 8px; cursor: pointer;} .topbar-title { font-size: 18px; font-weight: 800; display: flex; align-items: center; gap: 12px; }
    .topbar-right { display: flex; align-items: center; gap: 24px; } .clock-display { font-family: var(--font-mono); font-size: 13px; font-weight: 700; padding: 8px 16px; border-radius: 10px; border: 1px dashed var(--border-soft); background: var(--bg-input); } .top-profile { display: flex; align-items: center; gap: 12px; padding: 6px 12px; border-radius: 12px;} .top-profile span { font-size: 14px; font-weight: 700; }

    /* Table Styles */
    .table-panel { padding: 0; display: flex; flex-direction: column; border-radius: 20px;}
    .table-toolbar { padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed var(--border-soft); }
    .panel-header { font-size: 16px; font-weight: 800; display: flex; align-items: center; gap: 10px; margin: 0; }
    .table-wrapper { overflow-x: auto; padding: 10px 20px 20px; }
    .data-table { width: 100%; border-collapse: separate; border-spacing: 0 10px; text-align: left; }
    .data-table th { padding: 12px 20px; font-size: 11px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; font-family: var(--font-mono); }
    .data-table td { padding: 16px 20px; font-size: 14px; background: rgba(255,255,255,0.03); vertical-align: middle; border-top: 1px solid transparent; border-bottom: 1px solid transparent;} html:not(.dark) .data-table td { background: rgba(255,255,255,0.5); }
    .data-table tr td:first-child { border-radius: 12px 0 0 12px; border-left: 3px solid transparent; } .data-table tr td:last-child { border-radius: 0 12px 12px 0; border-right: 3px solid transparent; }
    .data-table tbody tr:hover td { background: rgba(59, 130, 246, 0.08); } .data-table tbody tr:hover td:first-child { border-left-color: #0ea5e9; }
    
    .td-primary { font-weight: 700; font-size: 15px; color: var(--text-primary); }
    .td-room { color: #0ea5e9; font-weight: 600; }
    .td-time { font-family: var(--font-mono); font-size: 12px; color: var(--text-secondary); background: var(--bg-input); padding: 4px 8px; border-radius: 6px; border: 1px solid var(--border-soft); display: inline-block;}
    
    /* Badges & Buttons */
    .badge-cyber { padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid transparent; display: inline-flex; align-items: center; gap: 6px; }
    .badge-warning { background: rgba(245, 158, 11, 0.1); color: var(--warning); border-color: rgba(245, 158, 11, 0.3); }
    .badge-success { background: rgba(16, 185, 129, 0.1); color: var(--success); border-color: rgba(16, 185, 129, 0.3); }
    .badge-danger { background: rgba(239, 68, 68, 0.1); color: var(--danger); border-color: rgba(239, 68, 68, 0.3); }
    .badge-neutral { background: rgba(148, 163, 184, 0.1); color: var(--text-secondary); border-color: rgba(148, 163, 184, 0.3); }

    .btn-action { background: rgba(59, 130, 246, 0.1); color: #0ea5e9; border: 1px solid rgba(59, 130, 246, 0.3); padding: 8px 14px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.3s; display: inline-flex; align-items: center; gap: 6px;}
    .btn-action:hover { transform: scale(1.05); }
    .btn-success-action { background: rgba(16, 185, 129, 0.1); color: var(--success); border-color: rgba(16, 185, 129, 0.3); }
    .btn-success-action:hover { background: var(--success); color: #fff; }
    .btn-danger-action { background: rgba(239, 68, 68, 0.1); color: var(--danger); border-color: rgba(239, 68, 68, 0.3); }
    .btn-danger-action:hover { background: var(--danger); color: #fff; }

    /* Empty State */
    .empty-state { text-align: center; padding: 60px 20px; color: var(--text-tertiary); }
    .empty-state i { font-size: 40px; margin-bottom: 12px; display: block; opacity: 0.2; color: var(--success); }

    @media (max-width: 1024px) { .dashboard-layout { flex-direction: column; } .cms-sidebar { width: 100%; height: auto; position: relative; border-right: none;} .cms-main { max-width: 100%; padding: 24px 20px; margin-left: 0; } }
</style>

<div class="ambient-bg"><div class="ambient-orb orb-1"></div><div class="ambient-orb orb-2"></div><div class="ambient-orb orb-3"></div></div>
<canvas id="ug-canvas"></canvas>

<div class="dashboard-layout">
    <div class="cms-layout">
        
        @include('admin.partials.sidebar', ['activeMenu' => 'bookings'])

        {{-- MAIN CONTENT --}}
        <main class="cms-main">
            <header class="glass-card topbar animate-float d-1">
                <div class="topbar-left"><button class="sidebar-toggle-btn" aria-label="Buka Menu"><i class="fas fa-bars"></i></button><div class="topbar-title hidden md:flex">Verifikasi Peminjaman Kampus J1</div></div>
                <div class="topbar-right"><div class="clock-display hidden lg:block" id="ug-clock">--:--:-- WIB</div><div class="top-profile"><span>{{ Auth::user()->name }}</span></div></div>
            </header>

            {{-- Alert Notifikasi Keren --}}
            @if(session('success'))
                <div class="glass-card p-4 animate-float d-2" style="background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.3); color: var(--success); font-weight: 700;">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif

            {{-- TABEL VERIFIKASI PEMINJAMAN --}}
            <div class="glass-card table-panel animate-float d-2">
                <div class="table-toolbar">
                    <div class="panel-header"><i class="fas fa-clipboard-list text-[#10b981]"></i> Daftar Antrean Reservasi Ruangan</div>
                </div>

                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Profil Peminjam</th>
                                <th>Target Ruangan</th>
                                <th>Tanggal Eksekusi</th>
                                <th>Jam Pakai</th>
                                <th>Status Izin</th>
                                <th style="text-align:center">Aksi Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                            <tr>
                                <td>
                                    <div class="td-primary"><i class="fas fa-user-circle me-2 opacity-50 text-[#3b82f6]"></i> {{ $booking->user->name }}</div>
                                </td>
                                <td class="td-room">{{ $booking->room->name }}</td>
                                <td class="td-primary">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</td>
                                <td>
                                    <div class="td-time">{{ $booking->start_time }} - {{ $booking->end_time }}</div>
                                </td>
                                <td>
                                    @if($booking->status == 'pending')
                                        <span class="badge-cyber badge-warning"><i class="fas fa-spinner fa-spin"></i> Menunggu ACC</span>
                                    @elseif($booking->status == 'approved')
                                        <span class="badge-cyber badge-success"><i class="fas fa-check-double"></i> Disetujui</span>
                                    @else
                                        <span class="badge-cyber badge-danger"><i class="fas fa-ban"></i> Ditolak</span>
                                    @endif
                                </td>
                                <td style="text-align:center;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        @if($booking->status == 'pending')
                                            {{-- Tombol Setujui --}}
                                            <form action="{{ route('admin.bookings.approve', $booking) }}" method="POST" style="display:inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn-action btn-success-action">
                                                    <i class="fas fa-check"></i> Setujui
                                                </button>
                                            </form>

                                            {{-- Tombol Tolak --}}
                                            <form action="{{ route('admin.bookings.reject', $booking) }}" method="POST" style="display:inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn-action btn-danger-action">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge-cyber badge-neutral"><i class="fas fa-lock"></i> Selesai Diproses</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="fas fa-check-circle"></i>
                                        <p>Semua antrean sudah bersih diproses. Kerja bagus, Min!</p>
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

    /* ── INTERACTIVE PARTICLE CANVAS ── */
    const canvas = document.getElementById('ug-canvas');
    if(!canvas) return; const ctx = canvas.getContext('2d');
    let W, H, particles = []; let mouse = { x: -1000, y: -1000 };
    const isDark = () => document.documentElement.classList.contains('dark');
    function resize() { W = canvas.width = window.innerWidth; H = canvas.height = window.innerHeight; }
    resize(); window.addEventListener('resize', resize);
    window.addEventListener('mousemove', e => { mouse.x = e.clientX; mouse.y = e.clientY; });
    
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