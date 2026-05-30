@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap');
    
    :root { 
        --bg-main: #000000; --bg-surface: rgba(13, 15, 23, 0.65); --bg-input: rgba(5, 7, 12, 0.6); 
        --border-soft: rgba(59, 130, 246, 0.15); --border-strong: rgba(59, 130, 246, 0.35); 
        --text-primary: #ffffff; --text-secondary: #3b82f6; --text-tertiary: #0369a1; 
        --accent-blue: #3b82f6; --accent-indigo: #3b82f6; --success: #10b981; --warning: #f59e0b; --danger: #ef4444; 
        --radius-lg: 20px; --radius-sm: 10px; 
        --font-sans: 'Plus Jakarta Sans', sans-serif; --font-mono: 'JetBrains Mono', monospace;
    }
    html:not(.dark) { 
        --bg-main: #f0f9ff; --bg-surface: rgba(255, 255, 255, 0.85); --bg-input: #ffffff; 
        --border-soft: rgba(14, 165, 233, 0.15); --border-strong: rgba(14, 165, 233, 0.35); 
        --text-primary: #0f172a; --text-secondary: #0369a1; --accent-blue: #0ea5e9; 
    }
    *, *::before, *::after { box-sizing: border-box; } body { margin: 0; padding: 0; }
    
    /* ANIMATIONS & AMBIENT */
    @keyframes spin-slow { 100% { transform: rotate(360deg); } }
    @keyframes float-up { 0% { opacity: 0; transform: translateY(30px) scale(0.98); } 100% { opacity: 1; transform: translateY(0) scale(1); } }
    @keyframes floatOrb { 0% { transform: translate(0, 0) scale(1) rotate(0deg); } 33% { transform: translate(8vw, -6vh) scale(1.1) rotate(5deg); } 66% { transform: translate(-6vw, 8vh) scale(0.9) rotate(-5deg); } 100% { transform: translate(0, 0) scale(1) rotate(0deg); } }
    .animate-float { animation: float-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; } .d-1 { animation-delay: 0.1s; } .d-2 { animation-delay: 0.2s; } .d-3 { animation-delay: 0.3s; }

    .ambient-bg { position: fixed; inset: 0; z-index: -5; background: var(--bg-main); overflow: hidden; transition: background 0.5s ease; }
    .ambient-orb { position: absolute; border-radius: 50%; filter: blur(100px); opacity: 0.35; animation: floatOrb 25s infinite alternate cubic-bezier(0.4, 0, 0.2, 1); pointer-events: none; }
    html:not(.dark) .ambient-orb { opacity: 0.6; mix-blend-mode: hard-light; }
    .orb-1 { width: 55vw; height: 55vw; max-width: 800px; max-height: 800px; background: var(--accent-blue); top: -10%; left: -10%; }
    .orb-2 { width: 45vw; height: 45vw; max-width: 700px; max-height: 700px; background: #3b82f6; bottom: -10%; right: -5%; animation-delay: -5s; }
    .orb-3 { width: 35vw; height: 35vw; max-width: 600px; max-height: 600px; background: var(--warning); top: 30%; left: 30%; animation-delay: -10s; opacity: 0.15; }
    html:not(.dark) .orb-1 { background: rgba(59, 130, 246, 0.4); } html:not(.dark) .orb-2 { background: rgba(59, 130, 246, 0.3); } html:not(.dark) .orb-3 { background: rgba(245, 158, 11, 0.3); opacity: 0.2; }
    
    #ug-canvas { position: fixed; inset: 0; z-index: 0; pointer-events: none; opacity: 0.45; }

    /* LAYOUT UTAMA */
    .dashboard-layout { position: relative; z-index: 2; width: 100%; min-height: 100vh; display: flex; font-family: var(--font-sans); color: var(--text-primary); }
    .cms-sidebar { width: 280px; flex-shrink: 0; background: var(--bg-surface); backdrop-filter: blur(35px); border-right: 1px solid var(--border-soft); display: flex; flex-direction: column; position: sticky; top: 0; height: 100vh; padding: 28px 24px; z-index: 50; overflow-y: auto; scrollbar-width: none; }
    .cms-sidebar::-webkit-scrollbar { display: none; }
    .sidebar-brand { display: flex; align-items: center; gap: 12px; margin-bottom: 30px; padding-bottom: 24px; border-bottom: 1px dashed var(--border-soft); font-size: 24px; font-weight: 800; } .sidebar-brand i { color: #0ea5e9; font-size: 28px; animation: glow-pulse 2s infinite; }
    .sidebar-profile { display: flex; align-items: center; gap: 16px; margin-bottom: 30px; } .sidebar-avatar { width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, var(--danger), var(--accent-indigo)); display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 800; color: #fff; position: relative; box-shadow: 0 0 15px rgba(239, 68, 68, 0.4); flex-shrink: 0; overflow: hidden; } .sidebar-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; } .sidebar-avatar::before { content: ''; position: absolute; inset: -4px; border-radius: 50%; border: 2px dashed var(--danger); animation: spin-slow 10s linear infinite; opacity: 0.2; } .sidebar-greeting { font-size: 12px; color: var(--text-secondary); font-weight: 600; text-transform: uppercase; margin-bottom: 2px;} .sidebar-name { font-size: 16px; font-weight: 800; color: var(--text-primary); }
    .sidebar-header { font-size: 12px; font-weight: 700; color: var(--text-tertiary); margin-bottom: 12px; letter-spacing: 1px; text-transform: uppercase; font-family: var(--font-mono);}
    .sidebar-menus { display: flex; flex-direction: column; gap: 6px; flex: 1;}
    .sidebar-item { display: flex; align-items: center; gap: 14px; padding: 12px 16px; border-radius: 12px; text-decoration: none; transition: all 0.3s; color: var(--text-secondary); border: 1px solid transparent;}
    .sidebar-item.active { background: var(--bg-input); border-color: var(--border-soft); color: var(--text-primary); border-left: 4px solid var(--accent-blue); }
    .sidebar-item:hover { border-color: #0ea5e9; background: rgba(59, 130, 246, 0.05); color: var(--text-primary); transform: translateX(6px); } html:not(.dark) .sidebar-item:hover { background: rgba(14, 165, 233, 0.05); }
    .sidebar-item i.icon { font-size: 16px; width: 24px; text-align: center; color: var(--text-tertiary);} .sidebar-item.active i.icon { color: #0ea5e9; } .sidebar-item:hover i.icon { color: #0ea5e9; transform: scale(1.1); }
    .sidebar-item .title { font-size: 14px; font-weight: 700; flex: 1; }
    .logout-box { margin-top: auto; padding-top: 24px; } .btn-logout { display: flex; align-items: center; gap: 12px; padding: 14px 16px; border-radius: 14px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: var(--danger); font-size: 14px; font-weight: 700; width: 100%; cursor: pointer; transition: all 0.3s; border: none;} .btn-logout:hover { background: var(--danger); color: #fff; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(239, 68, 68, 0.4); }

    .cms-main { flex: 1; display: flex; flex-direction: column; gap: 32px; padding: 32px 40px; max-width: calc(100vw - 280px); }
    .glass-card { background: var(--bg-surface); backdrop-filter: blur(25px); border: 1px solid var(--border-soft); border-radius: var(--radius-lg); position: relative; overflow: hidden;}
    .glass-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: radial-gradient(600px circle at var(--mouse-x, -500px) var(--mouse-y, -500px), rgba(59, 130, 246, 0.08), transparent 40%); z-index: 0; pointer-events: none; transition: opacity 0.3s ease; opacity: 0; } html:not(.dark) .glass-card::before { background: radial-gradient(800px circle at var(--mouse-x, -500px) var(--mouse-y, -500px), rgba(14, 165, 233, 0.12), transparent 45%); } .glass-card:hover::before { opacity: 1; }
    
    .topbar { display: flex; justify-content: space-between; align-items: center; padding: 20px 32px; border-radius: 16px; margin-bottom: 4px;} .topbar::before { content: ''; position: absolute; inset: 0; background: linear-gradient(90deg, var(--bg-main) 0%, transparent 100%); z-index: 1; opacity: 0.9; pointer-events: none; } html:not(.dark) .topbar::before { background: linear-gradient(90deg, rgba(239, 246, 255, 0.95) 0%, rgba(239, 246, 255, 0.3) 100%); } .topbar > * { position: relative; z-index: 2; }
    .topbar-left { display: flex; align-items: center; gap: 16px; } .topbar-left i.fa-bars { font-size: 20px; cursor: pointer; padding: 8px;} .topbar-title { font-size: 18px; font-weight: 800; display: flex; align-items: center; gap: 12px; }
    .topbar-right { display: flex; align-items: center; gap: 24px; } .clock-display { font-family: var(--font-mono); font-size: 13px; font-weight: 700; padding: 8px 16px; border-radius: 10px; border: 1px dashed var(--border-soft); background: var(--bg-input); } .top-profile { display: flex; align-items: center; gap: 12px; padding: 6px 12px; border-radius: 12px;} .top-profile span { font-size: 14px; font-weight: 700; }

    /* FORM STYLES */
    .form-panel { padding: 32px; z-index: 2;}
    .form-group { margin-bottom: 24px; }
    .form-label { display: block; font-size: 12px; font-weight: 700; color: var(--text-secondary); margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px; }
    .form-control { width: 100%; padding: 16px 20px; font-size: 15px; font-weight: 600; font-family: var(--font-sans); background: var(--bg-input); border: 1px solid var(--border-strong); border-radius: 14px; color: var(--text-primary); outline: none; transition: all 0.3s ease; }
    .form-control:focus { border-color: #0ea5e9; box-shadow: 0 0 20px rgba(59, 130, 246, 0.15); transform: translateY(-2px); } html:not(.dark) .form-control:focus { box-shadow: 0 0 20px rgba(14, 165, 233, 0.15); }
    select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='none' stroke='%233b82f6' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M3 5l4 4 4-4'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 16px center; cursor: pointer; }
    select.form-control option { background: #0B0E14; color: #fff; } html:not(.dark) select.form-control option { background: #fff; color: #000; }
    
    .btn-glow { background: linear-gradient(135deg, #3b82f6, #3b82f6); color: #ffffff !important; border: none; border-radius: 14px; padding: 16px; font-size: 15px; font-weight: 800; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 10px; width: 100%; transition: all 0.4s; text-transform: uppercase; font-family: var(--font-mono); box-shadow: 0 10px 20px rgba(59, 130, 246, 0.2);}
    .btn-glow:hover { transform: translateY(-3px); box-shadow: 0 15px 35px rgba(59, 130, 246, 0.5); }
    .btn-back { display: inline-flex; align-items: center; gap: 8px; color: var(--text-secondary); text-decoration: none; font-weight: 700; font-size: 14px; margin-bottom: 20px; transition: color 0.3s;} .btn-back:hover { color: #0ea5e9; }

    @media (max-width: 1024px) { .dashboard-layout { flex-direction: column; } .cms-sidebar { width: 100%; height: auto; position: relative; border-right: none;} .cms-main { max-width: 100%; padding: 24px 20px; margin-left: 0; } }
</style>

<div class="ambient-bg">
    <div class="ambient-orb orb-1"></div>
    <div class="ambient-orb orb-2"></div>
    <div class="ambient-orb orb-3"></div>
</div>
<canvas id="ug-canvas"></canvas>

<div class="dashboard-layout">
    {{-- SIDEBAR --}}
    @include('admin.partials.sidebar', ['activeMenu' => 'users'])

    {{-- MAIN CONTENT --}}
    <main class="cms-main">
        <header class="glass-card topbar animate-float d-1">
            <div class="topbar-left"><button class="sidebar-toggle-btn" aria-label="Buka Menu"><i class="fas fa-bars"></i></button><div class="topbar-title hidden md:flex">Sistem Kontrol Pengguna</div></div>
            <div class="topbar-right">
                <div class="clock-display hidden lg:block" id="ug-clock">--:--:-- WIB</div>
                <div class="top-profile"><span>{{ Auth::user()->name ?? 'Admin' }}</span></div>
            </div>
        </header>

        <a href="{{ route('admin.users') }}" class="btn-back animate-float d-2"><i class="fas fa-arrow-left"></i> Batal & Kembali ke Daftar User</a>

        <div class="glass-card form-panel animate-float d-2">
            <div style="font-size: 24px; font-weight: 800; margin-bottom: 30px; display: flex; align-items: center; gap: 12px;">
                <div style="width: 45px; height: 45px; background: rgba(59, 130, 246, 0.1); color: #0ea5e9; border-radius: 12px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-user-plus"></i></div>
                Tambah Akun Civitas Baru
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap user..." required>
                    </div>
                    <div>
                        <label class="form-label">Email Akses</label>
                        <input type="email" name="email" class="form-control" placeholder="contoh@student.gunadarma.ac.id" required>
                    </div>
                </div>

                <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label class="form-label">Password Sementara</label>
                        <input type="password" name="password" class="form-control" placeholder="Buat kata sandi awal..." required>
                    </div>
                    <div>
                        <label class="form-label">Hak Akses (Role)</label>
                        <select name="role" class="form-control" required>
                            <option value="" disabled selected>Pilih wewenang akun...</option>
                            <option value="student">Mahasiswa (Student)</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn-glow mt-4">
                    <i class="fas fa-save"></i> Simpan User ke Sistem
                </button>
            </form>
        </div>
    </main>
</div>

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

    /* ── INTERACTIVE PARTICLE CANVAS ── */
    const canvas = document.getElementById('ug-canvas');
    if(!canvas) return; const ctx = canvas.getContext('2d');
    let W, H, particles = []; let mouse = { x: -1000, y: -1000 };
    const isDark = () => document.documentElement.classList.contains('dark') || true;

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
        draw() { ctx.beginPath(); ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2); ctx.fillStyle = isDark() ? `rgba(255,255,255,${this.alpha})` : `rgba(59, 130, 246,${this.alpha})`; ctx.fill(); }
    }
    for(let i = 0; i < 80; i++) particles.push(new Particle());
    function drawConnections() {
        const dist = 140;
        for(let i = 0; i < particles.length; i++) {
            for(let j = i + 1; j < particles.length; j++) {
                const dx = particles[i].x - particles[j].x, dy = particles[i].y - particles[j].y; const d = Math.sqrt(dx * dx + dy * dy);
                if(d < dist) { const alpha = (1 - d / dist) * 0.05; ctx.beginPath(); ctx.moveTo(particles[i].x, particles[i].y); ctx.lineTo(particles[j].x, particles[j].y); ctx.strokeStyle = isDark() ? `rgba(255,255,255,${alpha})` : `rgba(59, 130, 246,${alpha})`; ctx.lineWidth = 0.6; ctx.stroke(); }
            }
        }
    }
    function frame() { ctx.clearRect(0, 0, W, H); particles.forEach(p => { p.update(); p.draw(); }); drawConnections(); requestAnimationFrame(frame); }
    frame();
});
</script>
@endsection