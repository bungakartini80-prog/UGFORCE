@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap');

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
        --accent-indigo:   #0ea5e9;
        --success:         #10b981;
        --warning:         #f59e0b;
        --danger:          #ef4444;
        --radius-lg:       24px;
        --radius-md:       16px;
        --radius-sm:       10px;
        --font-sans:       'Plus Jakarta Sans', system-ui, sans-serif;
    }

    .dark {
        --bg-main:         #000000;
        --bg-surface:      rgba(13, 15, 23, 0.7);
        --border-soft:     rgba(255, 255, 255, 0.08);
        --border-strong:   rgba(255, 255, 255, 0.15);
        --border-focus:    #3b82f6;
        --text-primary:    #ffffff;
        --text-secondary:  #3b82f6;
        --text-tertiary:   #0369a1;
        --accent-blue:     #3b82f6;
        --accent-indigo:   #3b82f6;
    }

    body {
        background-color: var(--bg-main);
        color: var(--text-primary);
        font-family: var(--font-sans);
        transition: background-color 0.5s ease, color 0.5s ease;
    }

    /* ── BACKGROUND LAYER ── */
    .hero-photo-bg {
        position: absolute; top: 0; left: 0; right: 0; height: 500px;
        background-image: url('https://2.bp.blogspot.com/-ah30_c4lnYE/Tsia8ZNlT-I/AAAAAAAAAC4/avMnlc5l9x8/w1200-h630-p-k-no-nu/KAMPUS+E.jpg'); 
        background-size: cover; background-position: center 30%;
        z-index: -2; 
        mask-image: linear-gradient(to bottom, black 20%, transparent 100%);
        -webkit-mask-image: linear-gradient(to bottom, black 20%, transparent 100%);
        opacity: 0.45; transition: opacity 0.5s ease;
    }
    html.dark .hero-photo-bg { opacity: 0.25; }

    #ug-canvas { position: fixed; inset: 0; z-index: 0; pointer-events: none; opacity: 0.2; }

    /* ── PORTAL GREETING HERO ── */
    .portal-hero {
        position: relative;
        width: 100%;
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.35) 0%, rgba(0, 0, 0, 0.5) 100%), url('https://images.unsplash.com/photo-1562774053-701939374585?q=80&w=1200&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        background-blend-mode: normal;
        border-radius: var(--radius-lg);
        padding: 40px 50px;
        color: #ffffff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        z-index: 1;
        margin-bottom: 40px;
    }
    .dark .portal-hero {
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0.75) 100%), url('https://images.unsplash.com/photo-1562774053-701939374585?q=80&w=1200&auto=format&fit=crop');
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        background-blend-mode: normal;
    }
    .hero-greeting {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .hero-avatar {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 800;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.3);
    }
    .hero-text h2 {
        font-size: 26px;
        font-weight: 800;
        margin: 0 0 6px 0;
    }
    .hero-text p {
        font-size: 14px;
        opacity: 0.9;
        margin: 0;
        font-family: 'JetBrains Mono', monospace;
    }

    /* ── METRICS GRID ── */
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 28px;
        margin-bottom: 40px;
    }
    .metric-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-soft);
        border-radius: var(--radius-md);
        padding: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.01);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .dark .metric-card {
        background: rgba(13, 15, 23, 0.6);
        border-color: rgba(255, 255, 255, 0.08);
    }
    .metric-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0; width: 4px;
        background: var(--accent-blue);
    }
    .metric-card.warning::before { background: var(--warning); }
    .metric-card.success::before { background: var(--success); }

    .metric-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .metric-val {
        font-size: 32px;
        font-weight: 800;
        font-family: 'JetBrains Mono', monospace;
        margin-top: 4px;
        line-height: 1;
    }
    .metric-icon {
        font-size: 36px;
        color: #0ea5e9;
        opacity: 0.2;
    }
    .metric-card.warning .metric-icon { color: var(--warning); opacity: 0.3; }
    .metric-card.success .metric-icon { color: var(--success); opacity: 0.3; }

    /* ── MAIN PORTAL GRID ── */
    .portal-main-grid {
        display: grid;
        grid-template-columns: 1.6fr 1fr;
        gap: 36px;
    }
    @media (max-width: 992px) {
        .portal-main-grid {
            grid-template-columns: 1fr;
        }
    }

    /* ── CARDS STYLING ── */
    .portal-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-soft);
        border-radius: var(--radius-lg);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        padding: 36px;
        transition: all 0.3s ease;
    }
    .dark .portal-card {
        background: rgba(13, 15, 23, 0.65);
        backdrop-filter: blur(25px);
        border-color: rgba(255, 255, 255, 0.08);
        box-shadow: 0 20px 40px rgba(0,0,0,0.4);
    }

    .panel-header {
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 1px solid var(--border-soft);
        padding-bottom: 16px;
    }
    .panel-header i {
        color: #0ea5e9;
    }

    /* ── FLOOR FILTER TABS ── */
    .floor-tabs {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 8px;
        margin-bottom: 24px;
        width: 100%;
        max-width: 100%;
    }
    .floor-tabs::-webkit-scrollbar {
        height: 4px;
    }
    .floor-tabs::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
        border-radius: 10px;
    }
    .dark .floor-tabs::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }
    .floor-tabs::-webkit-scrollbar-thumb {
        background: var(--accent-blue);
        border-radius: 10px;
    }

    .floor-tab-btn {
        padding: 10px 18px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        background: #f0f9ff;
        border: 1px solid #e2e8f0;
        color: var(--text-secondary);
        transition: all 0.2s ease;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .dark .floor-tab-btn {
        background: rgba(255, 255, 255, 0.04);
        border-color: rgba(255, 255, 255, 0.06);
    }
    .floor-tab-btn.active {
        background: var(--accent-blue);
        border-color: #0ea5e9;
        color: #ffffff;
    }

    /* ── ROOMS LISTING GRID ── */
    .rooms-display-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        max-height: 520px;
        overflow-y: auto;
        padding: 4px;
    }
    .rooms-display-grid::-webkit-scrollbar { width: 6px; }
    .rooms-display-grid::-webkit-scrollbar-thumb { background: var(--border-strong); border-radius: 4px; }

    .room-display-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-soft);
        border-radius: var(--radius-md);
        overflow: hidden;
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 0;
    }
    .dark .room-display-card {
        background: rgba(255, 255, 255, 0.015);
        border-color: rgba(255, 255, 255, 0.06);
    }
    .room-display-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
        border-color: var(--border-strong);
    }
    .dark .room-display-card:hover {
        background: rgba(255, 255, 255, 0.03);
    }

    .room-card-image {
        width: 100%;
        height: 120px;
        overflow: hidden;
        position: relative;
        background: #cbd5e1;
    }
    .room-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .room-display-card:hover .room-card-image img {
        transform: scale(1.08);
    }

    .room-name-title {
        font-size: 17px;
        font-weight: 800;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .room-details {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .room-detail-item {
        font-size: 12px;
        color: var(--text-secondary);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        border: 1px solid transparent;
        width: fit-content;
    }
    .status-available {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border-color: rgba(16, 185, 129, 0.2);
    }
    .status-unavailable {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border-color: rgba(239, 68, 68, 0.2);
    }

    .btn-book {
        width: 100%;
        padding: 10px;
        font-size: 12px;
        font-weight: 800;
        text-align: center;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .btn-book-active {
        background: var(--accent-blue);
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.15);
    }
    .btn-book-active:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(14, 165, 233, 0.25);
    }
    .btn-book-disabled {
        background: #f0f9ff;
        color: var(--text-tertiary) !important;
        border: 1px solid #e2e8f0;
        cursor: not-allowed;
        pointer-events: none;
    }
    .dark .btn-book-disabled {
        background: rgba(255, 255, 255, 0.02);
        border-color: rgba(255, 255, 255, 0.05);
    }

    /* ── RIGHT COLUMN: FEED & MANUAL ── */
    .activity-feed {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .feed-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 20px;
        background: #f0f9ff;
        border: 1px solid #e2e8f0;
        border-radius: var(--radius-md);
        transition: all 0.2s ease;
    }
    .dark .feed-item {
        background: rgba(255, 255, 255, 0.01);
        border-color: rgba(255, 255, 255, 0.06);
    }
    .feed-item:hover {
        transform: translateX(4px);
        border-color: var(--border-strong);
    }

    .feed-left h4 {
        font-size: 15px;
        font-weight: 800;
        margin: 0 0 4px 0;
    }
    .feed-left p {
        font-size: 12px;
        color: var(--text-secondary);
        margin: 0;
    }

    .feed-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 6px;
    }
    .feed-date {
        font-size: 12px;
        font-weight: 700;
    }
    .feed-time {
        font-size: 11px;
        color: var(--text-secondary);
        font-family: 'JetBrains Mono', monospace;
    }
    .pill-status {
        font-size: 10px;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 4px;
        text-transform: uppercase;
        border: 1px solid transparent;
    }
    .pill-pending { background: rgba(245, 158, 11, 0.1); color: var(--warning); border-color: rgba(245, 158, 11, 0.2); }
    .pill-approved { background: rgba(16, 185, 129, 0.1); color: var(--success); border-color: rgba(16, 185, 129, 0.2); }
    .pill-rejected { background: rgba(239, 68, 68, 0.1); color: var(--danger); border-color: rgba(239, 68, 68, 0.2); }

    .instructions-card {
        margin-top: 24px;
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.05) 0%, rgba(14, 165, 233, 0.05) 100%);
        border: 1px dashed var(--accent-blue);
    }
    .instructions-card h4 {
        font-size: 15px;
        font-weight: 800;
        color: #0ea5e9;
        margin-bottom: 12px;
    }
    .instructions-list {
        font-size: 13px;
        color: var(--text-secondary);
        padding-left: 20px;
        margin: 0;
        line-height: 1.6;
    }

    /* ── 3D OFFICE AVATAR ANIMATIONS & STYLES ── */
    #avatar-container {
        transition: transform 0.3s ease;
    }
    
    .avatar-body-bob {
        animation: bodyBob 4s ease-in-out infinite;
        transform-origin: bottom center;
    }
    .avatar-head-bob {
        animation: headBob 4s ease-in-out infinite;
        transform-origin: bottom center;
    }

    @keyframes bodyBob {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(2px); }
    }
    @keyframes headBob {
        0%, 100% { transform: translateY(0) rotate(0); }
        50% { transform: translateY(4px) rotate(1deg); }
    }

    /* Waving animation (Welcome) */
    .waving #avatar-right-arm {
        animation: armWave 0.8s ease-in-out infinite alternate;
        transform-origin: 122px 125px;
    }

    @keyframes armWave {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(-25deg); }
    }

    /* Head Shake (Geleng-geleng) */
    .shake-head #avatar-head {
        animation: headShake 0.15s ease-in-out infinite;
        transform-origin: 80px 105px;
    }

    @keyframes headShake {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(-10deg); }
        75% { transform: rotate(10deg); }
    }

    /* State: Worried / Error */
    .shake-head #left-eye-open,
    .shake-head #right-eye-open,
    .shake-head #avatar-mouth-happy {
        display: none !important;
    }
    .shake-head #left-eye-worried,
    .shake-head #right-eye-worried,
    .shake-head #avatar-mouth-sad {
        display: block !important;
    }
    .shake-head #left-brow {
        transform: rotate(12deg) translate(-2px, 1px);
        transform-origin: 69px 61px;
    }
    .shake-head #right-brow {
        transform: rotate(-12deg) translate(2px, 1px);
        transform-origin: 91px 61px;
    }

    /* ── FLOATING WHATSAPP BUTTON ── */
    .wa-floating-btn {
        position: fixed;
        bottom: 90px;
        right: 24px;
        z-index: 999;
        display: flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
        color: #ffffff !important;
        padding: 12px 20px;
        border-radius: 50px;
        box-shadow: 0 8px 30px rgba(37, 211, 102, 0.35);
        font-family: var(--font-sans);
        font-weight: 700;
        font-size: 14px;
        text-decoration: none !important;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
    }
    
    .wa-floating-btn i {
        font-size: 20px;
        animation: waPulse 2s infinite;
    }
    
    .wa-floating-btn .wa-text {
        max-width: 0;
        overflow: hidden;
        white-space: nowrap;
        transition: max-width 0.3s ease-in-out, opacity 0.3s ease-in-out;
        opacity: 0;
    }
    
    .wa-floating-btn:hover {
        transform: translateY(-5px) scale(1.03);
        box-shadow: 0 12px 35px rgba(37, 211, 102, 0.5);
    }
    
    .wa-floating-btn:hover .wa-text {
        max-width: 200px;
        opacity: 1;
    }
    
    @keyframes waPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.15); }
    }
    
    @media (max-width: 768px) {
        .wa-floating-btn {
            bottom: 100px;
            right: 16px;
            padding: 10px 14px;
            font-size: 12px;
        }
        .wa-floating-btn .wa-text {
            max-width: 150px;
            opacity: 1;
        }
    }

    /* ── MOBILE RESPONSIVE MEDIA QUERIES ── */
    @media (max-width: 768px) {
        .container {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }
        .portal-hero {
            padding: 24px 20px !important;
            border-radius: var(--radius-md) !important;
            margin-bottom: 24px !important;
        }
        .hero-greeting {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 12px !important;
        }
        .hero-avatar {
            width: 48px !important;
            height: 48px !important;
            font-size: 18px !important;
            border-radius: 12px !important;
        }
        .hero-text h2 {
            font-size: 20px !important;
        }
        .hero-text p {
            font-size: 11px !important;
        }
        .clock-display {
            font-size: 12px !important;
        }
        .metrics-grid {
            grid-template-columns: 1fr !important;
            gap: 16px !important;
            margin-bottom: 24px !important;
        }
        .metric-card {
            padding: 16px !important;
            border-radius: var(--radius-md) !important;
        }
        .portal-card {
            padding: 20px 16px !important;
            border-radius: var(--radius-md) !important;
            min-width: 0 !important;
        }
        .panel-header {
            font-size: 16px !important;
            margin-bottom: 16px !important;
            padding-bottom: 12px !important;
        }
        .floor-tabs {
            margin-bottom: 16px !important;
        }
        .floor-tab-btn {
            padding: 8px 14px !important;
            font-size: 12px !important;
            border-radius: 8px !important;
        }
        .rooms-display-grid {
            grid-template-columns: 1fr !important;
            gap: 16px !important;
            max-height: none !important;
            padding: 0 !important;
        }
        .room-display-card {
            border-radius: var(--radius-md) !important;
        }
        .room-card-image {
            height: 160px !important;
        }
        .activity-feed {
            gap: 12px !important;
        }
        .feed-item {
            padding: 14px 16px !important;
            border-radius: var(--radius-md) !important;
        }
        .feed-left h4 {
            font-size: 14px !important;
        }
        .feed-left p {
            font-size: 11px !important;
        }
        .feed-right {
            align-items: flex-end !important;
        }
        .feed-date {
            font-size: 11px !important;
        }
        .feed-time {
            font-size: 10px !important;
        }
        .instructions-card {
            margin-top: 16px !important;
            padding: 20px 16px !important;
        }
    }
</style>
@include('partials.biometric-gate')

<!-- BACKGROUND PHOTO -->
<div class="hero-photo-bg"></div>
<canvas id="ug-canvas"></canvas>

<div class="container py-8" style="max-width: 1200px;">

    <!-- Greeting Banner -->
    <div class="portal-hero">
        <div class="hero-greeting">
            <div class="hero-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div class="hero-text">
                <h2>Selamat Datang, {{ Auth::user()->name }}</h2>
                <p id="typewriter"></p>
            </div>
        </div>
        <div>
            <div class="clock-display" id="ug-clock">--:--:-- WIB</div>
        </div>
    </div>

    <!-- Metrics Row -->
    <section class="metrics-grid">
        <div class="metric-card">
            <div>
                <span class="metric-label">Ruang Kelas Tersedia</span>
                <div class="metric-val" data-target="{{ $rooms }}">{{ $rooms }}</div>
            </div>
            <i class="bi bi-door-open-fill metric-icon"></i>
        </div>
        <div class="metric-card warning">
            <div>
                <span class="metric-label">Menunggu Persetujuan</span>
                <div class="metric-val" data-target="{{ $bookings->where('status', 'pending')->count() }}">{{ $bookings->where('status', 'pending')->count() }}</div>
            </div>
            <i class="bi bi-hourglass-split metric-icon"></i>
        </div>
        <div class="metric-card success">
            <div>
                <span class="metric-label">Jadwal Aktif</span>
                <div class="metric-val" data-target="{{ $bookings->where('status', 'approved')->count() }}">{{ $bookings->where('status', 'approved')->count() }}</div>
            </div>
            <i class="bi bi-calendar-check-fill metric-icon"></i>
        </div>
    </section>

    <!-- Main Section Grid -->
    <div class="portal-main-grid">
        
        <!-- LEFT COLUMN: ROOM BROWSER SEPARATED BY FLOOR -->
        <div class="portal-card">
            <h3 class="panel-header"><i class="bi bi-building"></i> Browsing Ruangan Kampus J1</h3>

            <!-- Floor tabs -->
            <div class="floor-tabs" id="floorTabs">
                <button type="button" class="floor-tab-btn active" data-floor="all" onclick="filterRooms('all')">Semua Lantai</button>
                @foreach([1, 2, 3, 4, 5, 6] as $floorNum)
                    <button type="button" class="floor-tab-btn" data-floor="{{ $floorNum }}" onclick="filterRooms('{{ $floorNum }}')">Lantai {{ $floorNum }}</button>
                @endforeach
            </div>

            <!-- Rooms Grid -->
            <div class="rooms-display-grid" id="roomsDisplayGrid">
                @forelse($allRooms as $room)
                    <div class="room-display-card" data-floor="{{ $room->lantai }}">
                        <div class="room-card-image">
                            <img src="{{ asset('classroom.png') }}" alt="{{ $room->name }}">
                        </div>
                        <div style="padding: 16px; display: flex; flex-direction: column; gap: 12px; flex-grow: 1;">
                            <div class="room-name-title"><i class="bi bi-display"></i> {{ $room->name }}</div>
                            <div class="room-details">
                                <div class="room-detail-item"><i class="bi bi-layer-backward"></i> Posisi: Lantai {{ $room->lantai }}</div>
                                <div class="room-detail-item"><i class="bi bi-people"></i> Kapasitas: {{ $room->capacity }} Orang</div>
                            </div>
                        </div>

                        <div class="d-flex flex-column gap-2" style="padding: 0 16px 16px 16px;">
                            @if($room->status === 'available')
                                <span class="status-badge status-available"><i class="bi bi-check-circle-fill"></i> Tersedia</span>
                                <a href="{{ route('bookings.create', ['room_id' => $room->id]) }}" class="btn-book btn-book-active">Pesan Ruangan</a>
                            @else
                                <span class="status-badge status-unavailable"><i class="bi bi-x-circle-fill"></i> Sedang Dipakai</span>
                                <button type="button" class="btn-book btn-book-disabled" disabled>Tidak Tersedia</button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 w-100">
                        <i class="bi bi-emoji-frown text-muted" style="font-size: 40px;"></i>
                        <p class="text-muted mt-2">Belum ada data ruangan di sistem.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- RIGHT COLUMN: RECENT ACTIVITY FEED & MANUAL -->
        <div class="d-flex flex-column gap-4">
            
            <div class="portal-card">
                <h3 class="panel-header"><i class="bi bi-chat-square-text"></i> Aktivitas Terakhir Anda</h3>

                <div class="activity-feed">
                    @forelse($bookings->take(4) ?? [] as $booking)
                        <div class="feed-item">
                            <div class="feed-left">
                                <h4>{{ $booking->room->name }}</h4>
                                <p>{{ $booking->purpose }}</p>
                            </div>
                            <div class="feed-right">
                                <span class="feed-date">{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M Y') }}</span>
                                <span class="feed-time">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} WIB</span>
                                @if($booking->status === 'pending')
                                    <span class="pill-status pill-pending">Pending</span>
                                @elseif($booking->status === 'approved')
                                    <span class="pill-status pill-approved">Disetujui</span>
                                @else
                                    <span class="pill-status pill-rejected">Ditolak</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted" style="font-size: 13px;">
                            <i class="bi bi-folder2-open d-block mb-2" style="font-size: 24px;"></i>
                            Belum ada riwayat pengajuan peminjaman.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Manual Instructions Card -->
            <div class="portal-card instructions-card">
                <h4><i class="bi bi-info-circle-fill"></i> Panduan Penggunaan</h4>
                <ul class="instructions-list">
                    <li>Pilih lantai yang diinginkan melalui tab navigasi di sebelah kiri.</li>
                    <li>Cari ruangan dengan kapasitas yang sesuai dengan kebutuhan acara Anda.</li>
                    <li>Klik tombol <strong>Pesan Ruangan</strong> pada ruangan yang berstatus <em>Tersedia</em>.</li>
                    <li>Lengkapi formulir tanggal, jam mulai-selesai, dan tujuan kegiatan.</li>
                    <li>Kirimkan pengajuan dan pantau status persetujuan di kolom <em>Aktivitas Terakhir</em> secara berkala.</li>
                </ul>
            </div>

        </div>

    </div>
</div>

<!-- FLOATING WHATSAPP CONSULTATION BUTTON -->
<a href="https://wa.me/6281234567890?text=Halo%20Admin%20UGFORCE,%20saya%20mahasiswa%20ingin%20berkonsultasi%20mengenai%20layanan%20peminjaman%20ruangan." 
   target="_blank" 
   class="wa-floating-btn"
   title="Konsultasi Layanan via WhatsApp"
   id="waConsultationBtn">
    <i class="bi bi-whatsapp"></i>
    <span class="wa-text">Konsultasi Layanan</span>
</a>

<script>
    // Filtering rooms by floor tab
    function filterRooms(floorNum) {
        // Toggle tab button active state
        document.querySelectorAll('.floor-tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        const activeBtn = document.querySelector(`.floor-tab-btn[data-floor="${floorNum}"]`);
        if (activeBtn) activeBtn.classList.add('active');

        // Toggle rooms display
        const roomsGrid = document.getElementById('roomsDisplayGrid');
        const roomCards = roomsGrid.querySelectorAll('.room-display-card');

        roomCards.forEach(card => {
            const cardFloor = card.getAttribute('data-floor');
            if (floorNum === 'all' || cardFloor === floorNum) {
                card.style.display = 'flex';
                // Trigger soft entry animation
                card.style.opacity = '0';
                card.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    card.style.transition = 'all 0.25s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'scale(1)';
                }, 50);
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Typewriter effect
    const textToType = "PORTAL FASILITAS & AKADEMIK FIKTI KAMPUS J1";
    const typewriterElement = document.getElementById('typewriter');
    let typeIndex = 0;
    function typeWriter() {
        if (typeIndex < textToType.length) {
            typewriterElement.innerHTML += textToType.charAt(typeIndex);
            typeIndex++;
            setTimeout(typeWriter, 35);
        }
    }

    // Clock
    function updateClock() {
        const now = new Date();
        const pad = v => String(v).padStart(2, '0');
        document.getElementById('ug-clock').textContent = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())} WIB`;
    }

    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(typeWriter, 500);
        updateClock();
        setInterval(updateClock, 1000);

        // Run number animate count up
        document.querySelectorAll('.metric-val').forEach(el => {
            const target = parseInt(el.getAttribute('data-target')) || 0;
            if(target === 0) return;
            let count = 0;
            const increment = Math.ceil(target / 25);
            const timer = setInterval(() => {
                count += increment;
                if(count >= target) {
                    el.textContent = target;
                    clearInterval(timer);
                } else {
                    el.textContent = count;
                }
            }, 30);
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
