{{-- resources/views/admin/reports/index.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
    /* ── CSS UTAMA TETAP SAMA ── */
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap');
    :root { --bg-main: #000000; --bg-surface: rgba(13, 15, 23, 0.65); --bg-input: rgba(5, 7, 12, 0.6); --border-soft: rgba(59, 130, 246, 0.15); --border-strong: rgba(59, 130, 246, 0.35); --text-primary: #ffffff; --text-secondary: #3b82f6; --text-tertiary: #0369a1; --accent-blue: #3b82f6; --accent-indigo: #3b82f6; --success: #10b981; --warning: #f59e0b; --danger: #ef4444; --radius-lg: 20px; --radius-sm: 10px; --font-sans: 'Plus Jakarta Sans', sans-serif; --font-mono: 'JetBrains Mono', monospace;}
    html:not(.dark) { --bg-main: #f0f9ff; --bg-surface: rgba(255, 255, 255, 0.85); --bg-input: #ffffff; --border-soft: rgba(14, 165, 233, 0.15); --border-strong: rgba(14, 165, 233, 0.35); --text-primary: #0f172a; --text-secondary: #0369a1; --accent-blue: #0ea5e9; }
    *, *::before, *::after { box-sizing: border-box; } body { margin: 0; padding: 0; }
    
    /* ANIMATIONS & AMBIENT */
    @keyframes spin-slow { 100% { transform: rotate(360deg); } }
    @keyframes float-up { 0% { opacity: 0; transform: translateY(30px) scale(0.98); } 100% { opacity: 1; transform: translateY(0) scale(1); } }
    @keyframes floatOrb { 0% { transform: translate(0, 0) scale(1) rotate(0deg); } 33% { transform: translate(8vw, -6vh) scale(1.1) rotate(5deg); } 66% { transform: translate(-6vw, 8vh) scale(0.9) rotate(-5deg); } 100% { transform: translate(0, 0) scale(1) rotate(0deg); } }
    .animate-float { animation: float-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; } .d-1 { animation-delay: 0.1s; } .d-2 { animation-delay: 0.2s; } .d-3 { animation-delay: 0.3s; }

    .ambient-bg { position: fixed; inset: 0; z-index: -5; background: var(--bg-main); transition: background 0.5s ease; }
    .ambient-orb { position: absolute; border-radius: 50%; filter: blur(100px); opacity: 0.35; animation: floatOrb 25s infinite alternate cubic-bezier(0.4, 0, 0.2, 1); pointer-events: none; }
    html:not(.dark) .ambient-orb { opacity: 0.6; mix-blend-mode: hard-light; }
    .orb-1 { width: 55vw; height: 55vw; max-width: 800px; max-height: 800px; background: var(--accent-blue); top: -10%; left: -10%; }
    .orb-2 { width: 45vw; height: 45vw; max-width: 700px; max-height: 700px; background: #3b82f6; bottom: -10%; right: -5%; animation-delay: -5s; }
    .orb-3 { width: 35vw; height: 35vw; max-width: 600px; max-height: 600px; background: var(--warning); top: 30%; left: 30%; animation-delay: -10s; opacity: 0.15; }
    html:not(.dark) .orb-1 { background: rgba(59, 130, 246, 0.4); } html:not(.dark) .orb-2 { background: rgba(59, 130, 246, 0.3); } html:not(.dark) .orb-3 { background: rgba(245, 158, 11, 0.3); opacity: 0.2; }
    #ug-canvas { position: fixed; inset: 0; z-index: 0; pointer-events: none; opacity: 0.45; }

    /* LAYOUT & SIDEBAR */
    .dashboard-layout { position: relative; z-index: 2; width: 100%; min-height: 100vh; display: flex; font-family: var(--font-sans); color: var(--text-primary); }
    .cms-sidebar { width: 280px; flex-shrink: 0; background: var(--bg-surface); backdrop-filter: blur(35px); border-right: 1px solid var(--border-soft); display: flex; flex-direction: column; position: sticky; top: 0; height: 100vh; padding: 28px 24px; z-index: 50; overflow-y: auto; scrollbar-width: none; }
    .cms-sidebar::-webkit-scrollbar { display: none; }
    .sidebar-brand { display: flex; align-items: center; gap: 12px; margin-bottom: 30px; padding-bottom: 24px; border-bottom: 1px dashed var(--border-soft); font-size: 24px; font-weight: 800; } .sidebar-brand i { color: #0ea5e9; font-size: 28px; animation: glow-pulse 2s infinite;}
    .sidebar-profile { display: flex; align-items: center; gap: 16px; margin-bottom: 30px; } .sidebar-avatar { width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, var(--danger), var(--accent-indigo)); display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 800; color: #fff; position: relative; box-shadow: 0 0 15px rgba(239, 68, 68, 0.4); flex-shrink: 0;} .sidebar-avatar::before { content: ''; position: absolute; inset: -4px; border-radius: 50%; border: 2px dashed var(--danger); animation: spin-slow 10s linear infinite; opacity: 0.2; } .sidebar-name { font-size: 16px; font-weight: 800; color: var(--text-primary); } .sidebar-greeting { font-size: 12px; color: var(--text-secondary); font-weight: 600; text-transform: uppercase; margin-bottom: 2px;}
    .sidebar-header { font-size: 12px; font-weight: 700; color: var(--text-tertiary); margin-bottom: 12px; letter-spacing: 1px; text-transform: uppercase; font-family: var(--font-mono);}
    .sidebar-menus { display: flex; flex-direction: column; gap: 6px; flex: 1;}
    .sidebar-item { display: flex; align-items: center; gap: 14px; padding: 12px 16px; border-radius: 12px; text-decoration: none; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); color: var(--text-secondary); border: 1px solid transparent; position: relative; overflow: hidden;}
    .sidebar-item.active { background: var(--bg-input); border-color: var(--border-soft); color: var(--text-primary); border-left: 4px solid var(--accent-blue); }
    .sidebar-item:hover { border-color: #0ea5e9; background: rgba(59, 130, 246, 0.05); color: var(--text-primary); transform: translateX(6px); box-shadow: var(--shadow-glow);} html:not(.dark) .sidebar-item:hover { background: rgba(14, 165, 233, 0.05); }
    .sidebar-item i.icon { font-size: 16px; width: 24px; text-align: center; color: var(--text-tertiary); transition: all 0.3s ease;} .sidebar-item.active i.icon { color: #0ea5e9; } .sidebar-item:hover i.icon { color: #0ea5e9; transform: scale(1.1); }
    .sidebar-item .title { font-size: 14px; font-weight: 700; flex: 1; }
    .logout-box { margin-top: auto; padding-top: 24px; } .btn-logout { display: flex; align-items: center; gap: 12px; padding: 14px 16px; border-radius: 14px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: var(--danger); font-size: 14px; font-weight: 700; width: 100%; cursor: pointer; transition: all 0.3s; border: none;} .btn-logout:hover { background: var(--danger); color: #fff; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(239, 68, 68, 0.4); }

    /* MAIN CONTENT */
    .cms-main { flex: 1; display: flex; flex-direction: column; gap: 32px; padding: 32px 40px; max-width: calc(100vw - 280px); }
    .glass-card { background: var(--bg-surface); backdrop-filter: blur(25px); border: 1px solid var(--border-soft); border-radius: var(--radius-lg); position: relative; overflow: hidden;}
    .glass-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: radial-gradient(600px circle at var(--mouse-x, -500px) var(--mouse-y, -500px), rgba(59, 130, 246, 0.08), transparent 40%); z-index: 0; pointer-events: none; transition: opacity 0.3s ease; opacity: 0; } html:not(.dark) .glass-card::before { background: radial-gradient(800px circle at var(--mouse-x, -500px) var(--mouse-y, -500px), rgba(14, 165, 233, 0.12), transparent 45%); } .glass-card:hover::before { opacity: 1; }
    
    .topbar { display: flex; justify-content: space-between; align-items: center; padding: 20px 32px; border-radius: 16px;} .topbar::before { content: ''; position: absolute; inset: 0; background: linear-gradient(90deg, var(--bg-main) 0%, transparent 100%); z-index: 1; opacity: 0.9; pointer-events: none; } html:not(.dark) .topbar::before { background: linear-gradient(90deg, rgba(239, 246, 255, 0.95) 0%, rgba(239, 246, 255, 0.3) 100%); } .topbar > * { position: relative; z-index: 2; }
    .topbar-left { display: flex; align-items: center; gap: 16px; } .topbar-left i.fa-bars { font-size: 20px; cursor: pointer; padding: 8px; border-radius: 8px; transition: background 0.3s;} .topbar-left i.fa-bars:hover { background: var(--bg-input); color: #0ea5e9; } .topbar-title { font-size: 18px; font-weight: 800; display: flex; align-items: center; gap: 12px; letter-spacing: -0.5px;}
    .topbar-right { display: flex; align-items: center; gap: 24px; } .clock-display { font-family: var(--font-mono); font-size: 13px; font-weight: 700; padding: 8px 16px; border-radius: 10px; border: 1px dashed var(--border-soft); background: var(--bg-input); } .top-profile { display: flex; align-items: center; gap: 12px; padding: 6px 12px; border-radius: 12px; cursor: pointer; transition: background 0.3s;} .top-profile:hover { background: var(--bg-input); } .top-profile span { font-size: 14px; font-weight: 700; }

    /* ── CHART & REPORT STYLES ── */
    .chart-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 24px; z-index: 2;}
    .chart-container { padding: 24px; border-radius: 20px; position: relative; height: 350px;}
    .report-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;}
    .report-title { font-size: 16px; font-weight: 800; display: flex; align-items: center; gap: 10px;}
    
    .export-buttons { display: flex; gap: 16px; align-items: center; }
    .btn-export { 
        padding: 12px 24px; 
        border-radius: 14px; 
        font-size: 14px; 
        font-weight: 700; 
        text-decoration: none; 
        display: inline-flex; 
        align-items: center; 
        gap: 10px; 
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); 
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }
    .btn-excel { 
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05)); 
        color: var(--success); 
        border: 1px solid rgba(16, 185, 129, 0.25); 
    }
    .btn-excel:hover { 
        background: linear-gradient(135deg, #10b981, #059669); 
        color: #fff; 
        transform: translateY(-2px); 
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        border-color: transparent;
    }
    .btn-pdf { 
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05)); 
        color: var(--danger); 
        border: 1px solid rgba(239, 68, 68, 0.25); 
    }
    .btn-pdf:hover { 
        background: linear-gradient(135deg, #ef4444, #dc2626); 
        color: #fff; 
        transform: translateY(-2px); 
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
        border-color: transparent;
    }

    .stat-summary { 
        display: flex; 
        justify-content: space-between; 
        padding: 28px 32px; 
        background: var(--bg-surface); 
        border: 1px solid var(--border-soft); 
        border-radius: var(--radius-lg); 
        margin-bottom: 24px; 
        align-items: center; 
        z-index: 2;
        gap: 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    }
    .stat-item h4 { font-size: 12px; color: var(--text-secondary); text-transform: uppercase; margin: 0 0 5px 0; font-family: var(--font-mono); font-weight: 700; letter-spacing: 1px;}
    .stat-item h2 { font-size: 38px; color: var(--text-primary); margin: 0; font-family: var(--font-mono); font-weight: 800; line-height: 1; text-shadow: 0 4px 20px rgba(0,0,0,0.2);}

    @media (max-width: 768px) {
        .stat-summary {
            flex-direction: column;
            text-align: center;
            align-items: stretch;
            padding: 24px;
            gap: 20px;
        }
        .stat-item.text-right {
            text-align: center !important;
        }
        .export-buttons {
            justify-content: center;
            width: 100%;
            gap: 12px;
        }
        .btn-export {
            flex: 1;
            justify-content: center;
            padding: 12px 16px;
        }
    }

    @media (max-width: 1024px) { .dashboard-layout { flex-direction: column; } .cms-sidebar { width: 100%; height: auto; position: relative; border-right: none; border-bottom: 1px dashed var(--border-soft); z-index: 10;} .sidebar-menus { flex-direction: row; flex-wrap: wrap; } .sidebar-item { flex: 1; min-width: 150px;} .cms-main { max-width: 100%; padding: 24px 20px; margin-left: 0;} .chart-grid { grid-template-columns: 1fr; } }
</style>

<div class="ambient-bg">
    <div class="ambient-orb orb-1"></div>
    <div class="ambient-orb orb-2"></div>
    <div class="ambient-orb orb-3"></div>
</div>
<canvas id="ug-canvas"></canvas>

<div class="dashboard-layout">
    @include('admin.partials.sidebar', ['activeMenu' => 'reports'])

    {{-- MAIN CONTENT --}}
    <main class="cms-main">
        <header class="glass-card topbar animate-float d-1">
            <div class="topbar-left"><button class="sidebar-toggle-btn" aria-label="Buka Menu"><i class="fas fa-bars"></i></button><div class="topbar-title hidden md:flex">Analitik & Laporan Peminjaman</div></div>
            <div class="topbar-right">
                <div class="clock-display hidden lg:block" id="ug-clock">--:--:-- WIB</div>
                <div class="top-profile"><span>{{ Auth::user()->name ?? 'Admin' }}</span></div>
            </div>
        </header>

        @if(session('success'))
            <div class="glass-card p-3 animate-float d-2" style="background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.3); color: var(--success); font-weight: 700;">
                <i class="fas fa-info-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="stat-summary glass-card animate-float d-2">
            <div>
                <div class="report-title text-[#3b82f6] mb-2"><i class="fas fa-database"></i> Ringkasan Total Data</div>
                <p style="font-size: 14px; color: var(--text-secondary); margin:0;">Total reservasi ruangan yang tercatat di sistem UGFORCE sejauh ini.</p>
            </div>
            <div class="stat-item text-right">
                <h4>Total Transaksi Peminjaman</h4>
                <h2>{{ $totalBookings ?? 0 }} <span style="font-size: 14px; color: var(--text-secondary); font-family: var(--font-sans); font-weight: 600; text-transform:none;">Tiket</span></h2>
            </div>
            <div class="export-buttons">
                <a href="{{ route('admin.reports.excel') }}" class="btn-export btn-excel"><i class="fas fa-file-excel"></i> Cetak Excel</a>
                <a href="{{ route('admin.reports.pdf') }}" class="btn-export btn-pdf"><i class="fas fa-file-pdf"></i> Cetak PDF</a>
            </div>
        </div>

        <div class="chart-grid">
            {{-- LINE CHART --}}
            <div class="glass-card chart-container animate-float d-3">
                <div class="report-header">
                    <div class="report-title"><i class="fas fa-chart-line text-[#3b82f6]"></i> Tren Peminjaman (Tahun Ini)</div>
                </div>
                <canvas id="lineChart"></canvas>
            </div>

            {{-- BAR CHART --}}
            <div class="glass-card chart-container animate-float d-3" style="animation-delay: 0.4s;">
                <div class="report-header">
                    <div class="report-title"><i class="fas fa-chart-bar text-[#f59e0b]"></i> Ruangan Terfavorit</div>
                </div>
                <canvas id="barChart"></canvas>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    /* ── MAGNETIC GLOW TRACKER ── */
    const cards = document.querySelectorAll('.glass-card');
    cards.forEach(card => {
        card.addEventListener('mousemove', e => {
            const rect = card.getBoundingClientRect();
            card.style.setProperty('--mouse-x', `${e.clientX - rect.left}px`);
            card.style.setProperty('--mouse-y', `${e.clientY - rect.top}px`);
        });
    });

    /* ── LIVE CLOCK ── */
    setInterval(() => {
        const now = new Date(); const pad = v => String(v).padStart(2, '0');
        const clockEl = document.getElementById('ug-clock');
        if(clockEl) clockEl.textContent = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())} WIB`;
    }, 1000);

    /* ── CHART.JS SETUP ── */
    const isDark = document.documentElement.classList.contains('dark') || true;
    const textColor = isDark ? '#3b82f6' : '#0369a1';
    const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';

    // LINE CHART (DATA DARI DATABASE)
    const lineCtx = document.getElementById('lineChart').getContext('2d');
    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($lineLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']) !!},
            datasets: [{
                label: 'Jumlah Booking',
                data: {!! json_encode($lineData ?? [0, 0, 0, 0, 0, 0]) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3, tension: 0.4, fill: true, pointBackgroundColor: '#000000', pointBorderColor: '#3b82f6', pointRadius: 5, pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { backgroundColor: 'rgba(13, 15, 23, 0.9)', titleColor: '#fff', bodyColor: '#3b82f6', borderColor: 'rgba(59, 130, 246, 0.3)', borderWidth: 1 } },
            scales: {
                y: { grid: { color: gridColor }, ticks: { color: textColor, precision: 0 } },
                x: { grid: { display: false }, ticks: { color: textColor } }
            }
        }
    });

    // BAR CHART (HISTOGRAM DATA RUANGAN)
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($barLabels ?? ['Belum ada data']) !!},
            datasets: [{
                label: 'Frekuensi Dipakai',
                data: {!! json_encode($barData ?? [0]) !!},
                backgroundColor: [ '#3b82f6', '#3b82f6', '#f59e0b', '#10b981', '#ef4444' ],
                borderRadius: 6,
                borderWidth: 1,
                borderColor: 'rgba(255,255,255,0.1)'
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { backgroundColor: 'rgba(13, 15, 23, 0.9)', titleColor: '#fff', bodyColor: '#f59e0b', borderColor: 'rgba(245, 158, 11, 0.3)', borderWidth: 1 } },
            scales: {
                y: { grid: { color: gridColor }, ticks: { color: textColor, precision: 0 } },
                x: { grid: { display: false }, ticks: { color: textColor } }
            }
        }
    });

    /* ── INTERACTIVE PARTICLE CANVAS (EFEK RASI BINTANG) ── */
    const canvas = document.getElementById('ug-canvas');
    if(!canvas) return; const ctx = canvas.getContext('2d');
    let W, H, particles = []; let mouse = { x: -1000, y: -1000 };

    function resize() { W = canvas.width = window.innerWidth; H = canvas.height = window.innerHeight; }
    resize(); window.addEventListener('resize', resize);
    window.addEventListener('mousemove', e => { mouse.x = e.clientX; mouse.y = e.clientY; });
    window.addEventListener('mouseout', () => { mouse.x = -1000; mouse.y = -1000; });

    class Particle {
        constructor() { this.reset(); }
        reset() { this.x = Math.random() * W; this.y = Math.random() * H; this.r = Math.random() * 1.5 + 0.5; this.vx = (Math.random() - .5) * 0.4; this.vy = (Math.random() - .5) * 0.4; this.alpha = Math.random() * 0.4 + 0.1; }
        update() { 
            let dx = mouse.x - this.x; let dy = mouse.y - this.y; let distance = Math.sqrt(dx * dx + dy * dy);
            if (distance < 120) { let force = (120 - distance) / 120; this.vx -= (dx / distance) * force * 0.2; this.vy -= (dy / distance) * force * 0.2; }
            this.x += this.vx; this.y += this.vy; this.vx *= 0.99; this.vy *= 0.99;
            if(Math.abs(this.vx) < 0.2) this.vx += (Math.random() - 0.5) * 0.1; if(Math.abs(this.vy) < 0.2) this.vy += (Math.random() - 0.5) * 0.1;
            if(this.x < 0 || this.x > W || this.y < 0 || this.y > H) this.reset();
        }
        draw() { ctx.beginPath(); ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2); ctx.fillStyle = isDark ? `rgba(255,255,255,${this.alpha})` : `rgba(59, 130, 246,${this.alpha})`; ctx.fill(); }
    }
    for(let i = 0; i < 80; i++) particles.push(new Particle());
    function drawConnections() {
        const dist = 140;
        for(let i = 0; i < particles.length; i++) {
            for(let j = i + 1; j < particles.length; j++) {
                const dx = particles[i].x - particles[j].x, dy = particles[i].y - particles[j].y; const d = Math.sqrt(dx * dx + dy * dy);
                if(d < dist) { const alpha = (1 - d / dist) * 0.05; ctx.beginPath(); ctx.moveTo(particles[i].x, particles[i].y); ctx.lineTo(particles[j].x, particles[j].y); ctx.strokeStyle = isDark ? `rgba(255,255,255,${alpha})` : `rgba(59, 130, 246,${alpha})`; ctx.lineWidth = 0.6; ctx.stroke(); }
            }
        }
    }
    function frame() { ctx.clearRect(0, 0, W, H); particles.forEach(p => { p.update(); p.draw(); }); drawConnections(); requestAnimationFrame(frame); }
    frame();
});
</script>
@endsection