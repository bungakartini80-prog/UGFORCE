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
    body { margin: 0; padding: 0; }

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

    /* ── AMBIENT AURORA BACKGROUND ── */
    .ambient-bg {
        position: fixed; inset: 0; z-index: -5; overflow: hidden; background: var(--bg-main); transition: background 0.5s ease;
    }
    .ambient-orb {
        position: absolute; border-radius: 50%; filter: blur(100px); opacity: 0.35;
        animation: floatOrb 25s infinite alternate cubic-bezier(0.4, 0, 0.2, 1); pointer-events: none;
    }
    html:not(.dark) .ambient-orb { opacity: 0.6; filter: blur(100px); mix-blend-mode: hard-light; }
    
    .orb-1 { width: 55vw; height: 55vw; max-width: 800px; max-height: 800px; background: var(--accent-blue); top: -10%; left: -10%; }
    .orb-2 { width: 45vw; height: 45vw; max-width: 700px; max-height: 700px; background: #3b82f6; bottom: -10%; right: -5%; animation-delay: -5s; }
    .orb-3 { width: 35vw; height: 35vw; max-width: 600px; max-height: 600px; background: var(--warning); top: 30%; left: 30%; animation-delay: -10s; opacity: 0.15; }
    
    html:not(.dark) .orb-1 { background: rgba(59, 130, 246, 0.4); }
    html:not(.dark) .orb-2 { background: rgba(59, 130, 246, 0.3); }
    html:not(.dark) .orb-3 { background: rgba(245, 158, 11, 0.3); opacity: 0.2; }

    #ug-canvas { position: fixed; inset: 0; z-index: 0; pointer-events: none; opacity: 0.45; }

    /* ── NEW: FULL-BLEED CMS LAYOUT ── */
    .dashboard-layout {
        position: relative; z-index: 2; width: 100%; min-height: 100vh;
        display: flex; font-family: var(--font-sans); color: var(--text-primary);
    }

    /* ── LEFT SIDEBAR (FULL HEIGHT CMS STYLE) ── */
    .cms-sidebar {
        width: 280px; flex-shrink: 0;
        background: var(--bg-surface); backdrop-filter: blur(35px); -webkit-backdrop-filter: blur(35px);
        border-right: 1px solid var(--border-soft);
        display: flex; flex-direction: column;
        position: sticky; top: 0; height: 100vh;
        padding: 28px 24px; z-index: 50;
        overflow-y: auto; scrollbar-width: none;
    }
    .cms-sidebar::-webkit-scrollbar { display: none; }
    
    .sidebar-brand {
        display: flex; align-items: center; gap: 12px; margin-bottom: 30px; 
        padding-bottom: 24px; border-bottom: 1px dashed var(--border-soft);
        font-size: 24px; font-weight: 800; letter-spacing: 1px;
    }
    .sidebar-brand i { color: #0ea5e9; font-size: 28px; animation: glow-pulse 2s infinite; }
    
    .sidebar-profile { display: flex; align-items: center; gap: 16px; margin-bottom: 30px; }
    .sidebar-avatar {
        width: 50px; height: 50px; border-radius: 50%;
        background: linear-gradient(135deg, var(--danger), var(--accent-indigo));
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; font-weight: 800; color: #fff;
        position: relative; box-shadow: 0 0 15px rgba(239, 68, 68, 0.4); flex-shrink: 0;
    }
    .sidebar-avatar::before { content: ''; position: absolute; inset: -4px; border-radius: 50%; border: 2px dashed var(--danger); animation: spin-slow 10s linear infinite; opacity: 0.2; }
    .sidebar-greeting { font-size: 12px; color: var(--text-secondary); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px;}
    .sidebar-name { font-size: 16px; font-weight: 800; color: var(--text-primary); }

    .sidebar-header { font-size: 12px; font-weight: 700; color: var(--text-tertiary); margin-bottom: 12px; letter-spacing: 1px; text-transform: uppercase; font-family: var(--font-mono);}
    .sidebar-menus { display: flex; flex-direction: column; gap: 6px; flex: 1;}
    
    .sidebar-item {
        display: flex; align-items: center; gap: 14px; padding: 12px 16px; border-radius: 12px;
        background: transparent; border: 1px solid transparent;
        text-decoration: none; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative; overflow: hidden; color: var(--text-secondary);
    }
    .sidebar-item.active { 
        background: var(--bg-input); border-color: var(--border-soft); 
        color: var(--text-primary); border-left: 4px solid var(--accent-blue);
    }
    .sidebar-item:hover {
        border-color: #0ea5e9; background: rgba(59, 130, 246, 0.05); color: var(--text-primary);
        transform: translateX(6px); box-shadow: var(--shadow-glow);
    }
    html:not(.dark) .sidebar-item:hover { background: rgba(14, 165, 233, 0.05); }

    .sidebar-item i.icon { font-size: 16px; transition: all 0.3s ease; width: 24px; text-align: center; color: var(--text-tertiary);}
    .sidebar-item.active i.icon { color: #0ea5e9; }
    .sidebar-item:hover i.icon { color: #0ea5e9; transform: scale(1.1); }

    .sidebar-item .title { font-size: 14px; font-weight: 700; flex: 1; }

    /* ── TOMBOL LOGOUT ── */
    .logout-box { margin-top: auto; padding-top: 24px; }
    .btn-logout {
        display: flex; align-items: center; gap: 12px; padding: 14px 16px; border-radius: 14px;
        background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3);
        color: var(--danger); font-size: 14px; font-weight: 700; text-decoration: none;
        transition: all 0.3s; width: 100%; cursor: pointer;
    }
    .btn-logout:hover { background: var(--danger); color: #fff; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(239, 68, 68, 0.4); }

    /* ── RIGHT MAIN CONTENT ── */
    .cms-main { 
        flex: 1; display: flex; flex-direction: column; gap: 32px; padding: 32px 40px; 
        max-width: calc(100vw - 280px); 
    }

    /* ── CANGGIH GLASS CARD ── */
    .glass-card {
        background: var(--bg-surface); backdrop-filter: blur(25px); -webkit-backdrop-filter: blur(25px);
        border: 1px solid var(--border-soft); border-radius: var(--radius-lg);
        box-shadow: var(--shadow-card); transition: border-color 0.4s ease, transform 0.4s ease;
        position: relative; transform-style: preserve-3d; overflow: hidden;
    }
    .glass-card:hover { border-color: var(--border-strong); box-shadow: var(--shadow-glow); }
    
    .glass-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        background: radial-gradient(600px circle at var(--mouse-x, -500px) var(--mouse-y, -500px), rgba(59, 130, 246, 0.08), transparent 40%);
        z-index: 0; pointer-events: none; transition: opacity 0.3s ease; opacity: 0;
    }
    html:not(.dark) .glass-card::before {
        background: radial-gradient(800px circle at var(--mouse-x, -500px) var(--mouse-y, -500px), rgba(14, 165, 233, 0.12), transparent 45%);
    }
    .glass-card:hover::before { opacity: 1; }

    /* ── TOPBAR ── */
    .topbar { display: flex; justify-content: space-between; align-items: center; padding: 20px 32px; border-radius: 16px; margin-bottom: 4px;}
    .topbar::before { content: ''; position: absolute; inset: 0; background: linear-gradient(90deg, var(--bg-main) 0%, transparent 100%); z-index: 1; opacity: 0.9; pointer-events: none; }
    html:not(.dark) .topbar::before { background: linear-gradient(90deg, rgba(239, 246, 255, 0.95) 0%, rgba(239, 246, 255, 0.3) 100%); }
    .topbar > * { position: relative; z-index: 2; }
    
    .topbar-left { display: flex; align-items: center; gap: 16px; }
    .topbar-left i.fa-bars { font-size: 20px; color: var(--text-primary); cursor: pointer; padding: 8px; border-radius: 8px; transition: background 0.3s;}
    .topbar-left i.fa-bars:hover { background: var(--bg-input); color: #0ea5e9; }
    .topbar-title { font-size: 18px; font-weight: 800; color: var(--text-primary); display: flex; align-items: center; gap: 12px; letter-spacing: -0.5px; }
    
    .topbar-right { display: flex; align-items: center; gap: 24px; }
    .top-profile { display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 6px 12px; border-radius: 12px; transition: background 0.3s;}
    .top-profile:hover { background: var(--bg-input); }
    .top-profile span { font-size: 14px; font-weight: 700; color: var(--text-primary); }

    /* ── TOMBOL GLOW ── */
    .btn-glow {
        background: linear-gradient(135deg, #3b82f6, #3b82f6) !important;
        color: #ffffff !important; 
        border: 1px solid rgba(255,255,255,0.2) !important;
        border-radius: 12px; padding: 10px 20px; font-size: 14px; font-weight: 700;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative; z-index: 1; overflow: hidden; display: inline-flex; align-items: center; gap: 8px;
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3) !important; text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        text-decoration: none;
    }
    .btn-glow::after {
        content: ''; position: absolute; top: 0; left: -100%; width: 50%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transform: skewX(-25deg); transition: all 0.6s ease; z-index: -1;
    }
    .btn-glow:hover::after { left: 200%; }
    .btn-glow:hover { transform: translateY(-3px); box-shadow: 0 15px 35px rgba(59, 130, 246, 0.6) !important; color: #ffffff !important; }

    /* ── FILTER LANTAI (TABS) ── */
    .floor-tabs-container { padding: 0 24px 16px; border-bottom: 1px dashed var(--border-soft); margin-bottom: 10px; }
    .floor-tabs { display: flex; gap: 12px; overflow-x: auto; padding-bottom: 8px; scrollbar-width: thin;}
    .floor-tabs::-webkit-scrollbar { height: 4px; }
    .floor-tabs::-webkit-scrollbar-thumb { background: var(--border-strong); border-radius: 4px; }
    .floor-tab { 
        padding: 8px 18px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer;
        background: var(--bg-input); border: 1px solid var(--border-soft); color: var(--text-secondary);
        transition: all 0.3s; white-space: nowrap; display: flex; align-items: center; gap: 8px;
    }
    .floor-tab.active { background: rgba(59, 130, 246, 0.1); border-color: #0ea5e9; color: #0ea5e9; }
    html:not(.dark) .floor-tab.active { background: rgba(14, 165, 233, 0.1); }
    .floor-tab:hover:not(.active) { border-color: var(--border-strong); color: var(--text-primary); }

    /* ── TABLE PANEL ── */
    .table-panel { padding: 0; display: flex; flex-direction: column; border-radius: 20px;}
    .table-toolbar { padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; }
    .panel-header { font-size: 16px; font-weight: 800; color: var(--text-primary); display: flex; align-items: center; gap: 10px; margin: 0; letter-spacing: -0.5px; }
    
    .table-wrapper { overflow-x: auto; padding: 10px 20px 20px; }
    .data-table { width: 100%; border-collapse: separate; border-spacing: 0 10px; text-align: left; }
    .data-table th { padding: 12px 20px; font-size: 11px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; font-family: var(--font-mono); }
    .data-table td { padding: 16px 20px; font-size: 14px; color: var(--text-primary); background: rgba(255,255,255,0.03); vertical-align: middle; transition: all 0.3s; border-top: 1px solid transparent; border-bottom: 1px solid transparent;}
    html:not(.dark) .data-table td { background: rgba(255,255,255,0.5); }
    
    .data-table tr td:first-child { border-radius: 12px 0 0 12px; border-left: 3px solid transparent; }
    .data-table tr td:last-child { border-radius: 0 12px 12px 0; border-right: 3px solid transparent; }
    
    .data-table tbody tr:hover td { background: rgba(59, 130, 246, 0.08); }
    .data-table tbody tr:hover td:first-child { border-left-color: #0ea5e9; box-shadow: inset 5px 0 10px rgba(59, 130, 246, 0.1); }
    
    .td-primary { font-weight: 700; font-size: 15px; color: var(--text-primary); }
    
    /* Badges */
    .badge-cyber { padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid transparent; display: inline-flex; align-items: center; gap: 6px; }
    .badge-success { background: rgba(16, 185, 129, 0.1); color: var(--success); border-color: rgba(16, 185, 129, 0.3); }
    .badge-danger { background: rgba(239, 68, 68, 0.1); color: var(--danger); border-color: rgba(239, 68, 68, 0.3); }
    
    /* Badge Khusus Lantai */
    .badge-floor { background: rgba(59, 130, 246, 0.1); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.3); font-family: var(--font-mono); }

    /* Button Action Standard */
    .btn-action { background: rgba(59, 130, 246, 0.1); color: #0ea5e9; border: 1px solid rgba(59, 130, 246, 0.3); padding: 6px 14px; border-radius: 8px; font-size: 12px; font-weight: 700; text-decoration: none; transition: all 0.3s; display: inline-block;}
    .btn-action:hover { background: var(--accent-blue); color: #000; transform: scale(1.05); }
    .btn-danger-outline { background: rgba(239, 68, 68, 0.1); color: var(--danger); border: 1px solid rgba(239, 68, 68, 0.3); }
    .btn-danger-outline:hover { background: var(--danger); color: #fff; }

    /* Empty State */
    .empty-state { text-align: center; padding: 60px 20px; color: var(--text-tertiary); }
    .empty-state i { font-size: 40px; margin-bottom: 12px; display: block; opacity: 0.2; color: #0ea5e9; }

    /* ── RESPONSIVE ── */
    @media (max-width: 1024px) {
        .dashboard-layout { flex-direction: column; }
        .cms-sidebar { width: 100%; height: auto; position: relative; border-right: none; border-bottom: 1px dashed var(--border-soft); z-index: 10;}
        .sidebar-menus { flex-direction: row; flex-wrap: wrap; }
        .sidebar-item { flex: 1; min-width: 150px;}
        .cms-main { max-width: 100%; padding: 24px 20px; margin-left: 0; }
    }
</style>

<div class="ambient-bg">
    <div class="ambient-orb orb-1"></div>
    <div class="ambient-orb orb-2"></div>
    <div class="ambient-orb orb-3"></div>
</div>
<canvas id="ug-canvas"></canvas>

<div class="dashboard-layout">
    @include('admin.partials.sidebar', ['activeMenu' => 'rooms'])
    {{-- KANAN: MAIN CONTENT (ROOM MANAGEMENT) --}}
    <main class="cms-main">

        {{-- TOPBAR --}}
        <header class="glass-card topbar animate-float d-1">
            <div class="topbar-left">
                <button class="sidebar-toggle-btn" aria-label="Buka Menu"><i class="fas fa-bars"></i></button>
                <div class="topbar-title hidden md:flex">
                    Manajemen Ruangan Kampus J1
                </div>
            </div>
            <div class="topbar-right">
                <div class="top-profile">
                    <span>{{ Auth::user()->name }}</span>
                    <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--text-secondary);"></i>
                </div>
            </div>
        </header>

        {{-- TABLE PANEL RUANGAN --}}
        <div class="glass-card table-panel animate-float d-2">
            
            {{-- Toolbar Atas (Judul & Tombol Tambah) --}}
            <div class="table-toolbar">
                <div class="panel-header">
                    <i class="fas fa-door-open text-[#3b82f6]"></i> Daftar Fasilitas Ruangan
                </div>
                <a href="{{ route('admin.rooms.create') ?? '#' }}" class="btn-glow">
                    <i class="fas fa-plus"></i> Tambah Ruang Baru
                </a>
            </div>

            {{-- ── FILTER TABS LANTAI ── --}}
            <div class="floor-tabs-container">
                <div class="floor-tabs" id="floorFilterTabs">
                    <div class="floor-tab active" data-filter="all"><i class="fas fa-layer-group"></i> Semua Lantai</div>
                    <div class="floor-tab" data-filter="1">Lantai 1</div>
                    <div class="floor-tab" data-filter="2">Lantai 2</div>
                    <div class="floor-tab" data-filter="3">Lantai 3</div>
                    <div class="floor-tab" data-filter="4">Lantai 4</div>
                    <div class="floor-tab" data-filter="5">Lantai 5</div>
                    <div class="floor-tab" data-filter="6">Lantai 6</div>
                </div>
            </div>

            {{-- Isi Tabel --}}
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Kode/Nama Ruang</th>
                            <th>Posisi Lantai</th> <th>Kapasitas</th>
                            <th>Status Saat Ini</th>
                            <th style="text-align:right">Aksi Eksekusi</th>
                        </tr>
                    </thead>
                    <tbody id="roomsTableBody">
                        @forelse($rooms ?? [] as $room)
                        {{-- Data attribute 'data-lantai' untuk filter js. Fallback ke lantai 1 jika kosong --}}
                        <tr class="room-row" data-lantai="{{ $room->lantai ?? '1' }}">
                            <td>
                                <div class="td-primary"><i class="fas fa-desktop me-2 opacity-50 text-[#3b82f6]"></i> {{ $room->name }}</div>
                            </td>
                            <td>
                                <div class="badge-cyber badge-floor">
                                    <i class="fas fa-arrow-up"></i> Lt. {{ $room->lantai ?? '1' }}
                                </div>
                            </td>
                            <td>
                                <div class="td-primary">{{ $room->capacity }} <span style="font-size: 12px; color: var(--text-secondary); font-weight: normal;">Orang</span></div>
                            </td>
                            <td>
                                @if($room->status == 'available')
                                    <span class="badge-cyber badge-success"><i class="fas fa-check-circle"></i> Ready / Kosong</span>
                                @else
                                    <span class="badge-cyber badge-danger"><i class="fas fa-ban"></i> Sedang Dipakai</span>
                                @endif
                            </td>
                            <td style="text-align:right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <a href="{{ route('admin.rooms.edit', $room->id) ?? '#' }}" class="btn-action">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    
                                    <form action="{{ route('admin.rooms.destroy', $room->id) ?? '#' }}" method="POST" style="display:inline" onsubmit="return confirm('Data ruangan bakal hilang selamanya nih, yakin lanjut?')">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-danger-outline">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyRowIndicator">
                            <td colspan="5" style="text-align: center; padding: 60px;">
                                <i class="fas fa-ghost mb-3" style="font-size: 40px; color: #0ea5e9; opacity: 0.2;"></i>
                                <p style="color: var(--text-secondary);">Gawat min, belum ada ruangan sama sekali. Yuk klik Tambah Ruang Baru!</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    /* ── FILTER LANTAI JS ── */
    const floorTabs = document.querySelectorAll('#floorFilterTabs .floor-tab');
    const roomRows = document.querySelectorAll('.room-row');

    floorTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            floorTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            const filterValue = tab.getAttribute('data-filter');
            
            roomRows.forEach(row => {
                if (filterValue === 'all' || row.getAttribute('data-lantai') === filterValue) {
                    row.style.display = ''; 
                } else {
                    row.style.display = 'none'; 
                }
            });
            // Info: Blade directive logic dipindahkan untuk menghindari error compiler
        });
    });

    /* ── MAGNETIC GLOW TRACKER PADA KARTU ── */
    const cards = document.querySelectorAll('.glass-card');
    cards.forEach(card => {
        card.addEventListener('mousemove', e => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            card.style.setProperty('--mouse-x', `${x}px`);
            card.style.setProperty('--mouse-y', `${y}px`);
        });
    });

    /* ── INTERACTIVE PARTICLE CANVAS ── */
    const canvas = document.getElementById('ug-canvas');
    if(!canvas) return;
    const ctx = canvas.getContext('2d');
    let W, H, particles = [];
    let mouse = { x: -1000, y: -1000 };
    const isDark = () => document.documentElement.classList.contains('dark');

    function resize() { W = canvas.width = window.innerWidth; H = canvas.height = window.innerHeight; }
    resize(); window.addEventListener('resize', resize);
    window.addEventListener('mousemove', e => { mouse.x = e.clientX; mouse.y = e.clientY; });
    window.addEventListener('mouseout', () => { mouse.x = -1000; mouse.y = -1000; });

    class Particle {
        constructor() { this.reset(); }
        reset() {
            this.x = Math.random() * W; this.y = Math.random() * H;
            this.r = Math.random() * 1.5 + 0.5;
            this.vx = (Math.random() - .5) * 0.4; this.vy = (Math.random() - .5) * 0.4;
            this.baseX = this.x; this.baseY = this.y;
            this.alpha = Math.random() * 0.4 + 0.1;
        }
        update() {
            let dx = mouse.x - this.x;
            let dy = mouse.y - this.y;
            let distance = Math.sqrt(dx * dx + dy * dy);
            if (distance < 120) {
                let force = (120 - distance) / 120;
                this.vx -= (dx / distance) * force * 0.2;
                this.vy -= (dy / distance) * force * 0.2;
            }

            this.x += this.vx; this.y += this.vy;
            this.vx *= 0.99; this.vy *= 0.99;
            
            if(Math.abs(this.vx) < 0.2) this.vx += (Math.random() - 0.5) * 0.1;
            if(Math.abs(this.vy) < 0.2) this.vy += (Math.random() - 0.5) * 0.1;

            if(this.x < 0 || this.x > W || this.y < 0 || this.y > H) this.reset();
        }
        draw() {
            ctx.beginPath(); ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
            ctx.fillStyle = isDark() ? `rgba(255,255,255,${this.alpha})` : `rgba(59, 130, 246,${this.alpha})`;
            ctx.fill();
        }
    }

    for(let i = 0; i < 80; i++) particles.push(new Particle());

    function drawConnections() {
        const dist = 140;
        for(let i = 0; i < particles.length; i++) {
            for(let j = i + 1; j < particles.length; j++) {
                const dx = particles[i].x - particles[j].x, dy = particles[i].y - particles[j].y;
                const d = Math.sqrt(dx * dx + dy * dy);
                if(d < dist) {
                    const alpha = (1 - d / dist) * 0.05;
                    ctx.beginPath(); ctx.moveTo(particles[i].x, particles[i].y); ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.strokeStyle = isDark() ? `rgba(255,255,255,${alpha})` : `rgba(59, 130, 246,${alpha})`;
                    ctx.lineWidth = 0.6; ctx.stroke();
                }
            }
        }
    }

    function frame() {
        ctx.clearRect(0, 0, W, H);
        particles.forEach(p => { p.update(); p.draw(); });
        drawConnections();
        requestAnimationFrame(frame);
    }
    frame();
});
</script>
@endsection