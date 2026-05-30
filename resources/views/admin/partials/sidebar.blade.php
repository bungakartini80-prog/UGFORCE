{{--
    Admin Sidebar Partial
    Usage: @include('admin.partials.sidebar', ['activeMenu' => 'dashboard'])
    activeMenu values: dashboard, rooms, bookings, reports, users, schedules, profile, attendance
--}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap');

    :root { 
        --bg-surface: rgba(13, 15, 23, 0.65); 
        --bg-input: rgba(5, 7, 12, 0.6); 
        --border-soft: rgba(59, 130, 246, 0.15); 
        --text-primary: #ffffff; 
        --text-secondary: #3b82f6; 
        --text-tertiary: #0369a1; 
        --accent-blue: #3b82f6; 
        --accent-indigo: #3b82f6; 
        --danger: #ef4444; 
    }
    html:not(.dark) { 
        --bg-surface: rgba(255, 255, 255, 0.85); 
        --bg-input: #ffffff; 
        --border-soft: rgba(14, 165, 233, 0.15); 
        --text-primary: #0f172a; 
        --text-secondary: #0369a1; 
        --accent-blue: #0ea5e9; 
    }

    /* ── DESKTOP SIDEBAR ── */
    .cms-sidebar { width: 280px; flex-shrink: 0; background: var(--bg-surface); backdrop-filter: blur(35px); border-right: 1px solid var(--border-soft); display: flex; flex-direction: column; position: sticky; top: 0; height: 100vh; padding: 28px 24px; z-index: 50; overflow: hidden; }
    .sidebar-brand { display: flex; align-items: center; gap: 12px; margin-bottom: 30px; padding-bottom: 24px; border-bottom: 1px dashed var(--border-soft); font-size: 24px; font-weight: 800; color: var(--text-primary); } .sidebar-brand i { color: #0ea5e9; font-size: 28px; }
    .sidebar-profile { display: flex; align-items: center; gap: 16px; margin-bottom: 30px; } 
    .sidebar-avatar { width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, var(--danger), var(--accent-indigo)); display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 800; color: #fff; position: relative; box-shadow: 0 0 15px rgba(239, 68, 68, 0.4); flex-shrink: 0; overflow: hidden; } 
    .sidebar-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; } 
    .sidebar-avatar::before { content: ''; position: absolute; inset: -4px; border-radius: 50%; border: 2px dashed var(--danger); animation: spin-slow 10s linear infinite; opacity: 0.2; } 
    .sidebar-greeting { font-size: 12px; color: var(--text-secondary); font-weight: 600; text-transform: uppercase; margin-bottom: 2px;} 
    .sidebar-name { font-size: 16px; font-weight: 800; color: var(--text-primary); }
    .sidebar-header { font-size: 12px; font-weight: 700; color: var(--text-tertiary); margin-bottom: 12px; letter-spacing: 1px; text-transform: uppercase; font-family: var(--font-mono);}
    .sidebar-menus { display: flex; flex-direction: column; gap: 6px; flex: 1; overflow-y: auto; scrollbar-width: none; }
    .sidebar-menus::-webkit-scrollbar { display: none; }
    .sidebar-item { display: flex; align-items: center; gap: 14px; padding: 12px 16px; border-radius: 12px; text-decoration: none; transition: all 0.3s; color: var(--text-secondary); border: 1px solid transparent;}
    .sidebar-item.active { background: var(--bg-input); border-color: var(--border-soft); color: var(--text-primary); border-left: 4px solid var(--accent-blue); }
    .sidebar-item:hover { border-color: #0ea5e9; background: rgba(59, 130, 246, 0.05); color: var(--text-primary); transform: translateX(6px); } html:not(.dark) .sidebar-item:hover { background: rgba(14, 165, 233, 0.05); }
    .sidebar-item i.icon { font-size: 16px; width: 24px; text-align: center; color: var(--text-tertiary);} .sidebar-item.active i.icon { color: #0ea5e9; } .sidebar-item:hover i.icon { color: #0ea5e9; transform: scale(1.1); }
    .sidebar-item .title { font-size: 14px; font-weight: 700; flex: 1; }
    .logout-box { margin-top: auto; padding-top: 24px; } 
    .btn-logout { display: flex; align-items: center; justify-content: center; gap: 12px; padding: 14px 16px; border-radius: 14px; background: rgba(239, 68, 68, 0.08) !important; border: 1px solid rgba(239, 68, 68, 0.25) !important; color: #ef4444 !important; font-size: 14px; font-weight: 700; width: 100%; cursor: pointer; transition: all 0.3s; } 
    .btn-logout:hover { background: #ef4444 !important; color: #fff !important; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(239, 68, 68, 0.3); }

    /* ── MOBILE SIDEBAR DRAWER ── */
    .sidebar-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9998;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .sidebar-backdrop.active {
        display: block;
        opacity: 1;
    }

    /* ── HAMBURGER TOGGLE BUTTON ── */
    .sidebar-toggle-btn {
        display: none; /* hidden on desktop */
        cursor: pointer;
        background: none;
        border: none;
        padding: 8px;
        border-radius: 8px;
        color: var(--text-primary);
        font-size: 20px;
        transition: background 0.3s, color 0.3s;
    }
    .sidebar-toggle-btn:hover {
        background: var(--bg-input);
        color: #0ea5e9;
    }

    @media (max-width: 767px) {
        /* Hide sidebar from normal flow */
        .cms-sidebar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            bottom: 0 !important;
            width: 280px !important;
            height: 100vh !important;
            z-index: 9999 !important;
            transform: translateX(-100%);
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            border-right: 1px solid var(--border-soft);
            animation: none !important;
            opacity: 1 !important;
            overflow: hidden !important;
            padding: 24px 20px 20px !important;
        }
        .cms-sidebar.mobile-open {
            transform: translateX(0);
        }
        .sidebar-brand {
            margin-bottom: 16px !important;
            padding-bottom: 16px !important;
        }
        .sidebar-profile {
            margin-bottom: 16px !important;
            gap: 12px !important;
        }
        .sidebar-avatar {
            width: 42px !important;
            height: 42px !important;
        }
        .sidebar-menus {
            flex-direction: column !important;
            flex-wrap: nowrap !important;
            overflow-y: auto !important;
            scrollbar-width: none !important;
        }
        .sidebar-menus::-webkit-scrollbar {
            display: none !important;
        }
        .sidebar-item {
            flex: none !important;
            width: 100% !important;
            min-width: 0 !important;
        }
        .logout-box {
            margin-top: auto !important;
            padding-top: 16px !important;
            padding-bottom: calc(16px + env(safe-area-inset-bottom)) !important;
        }

        /* Close button inside sidebar on mobile */
        .sidebar-close-btn {
            display: flex !important;
            position: absolute;
            top: 20px;
            right: 16px;
            width: 32px;
            height: 32px;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: var(--danger);
            cursor: pointer;
            font-size: 16px;
            z-index: 10;
            transition: all 0.2s;
        }
        .sidebar-close-btn:hover {
            background: var(--danger);
            color: #fff;
        }

        /* Show hamburger icon */
        .sidebar-toggle-btn {
            display: flex !important;
            align-items: center;
            justify-content: center;
        }

        /* Force layouts to collapse and take full width */
        .dashboard-layout,
        .cms-container,
        .cms-layout {
            display: flex !important;
            flex-direction: column !important;
            width: 100% !important;
            max-width: 100% !important;
            min-width: 0 !important;
            padding: 0 !important;
            margin: 0 !important;
            gap: 16px !important;
        }
        .cms-main {
            width: 100% !important;
            max-width: 100% !important;
            min-width: 0 !important;
            margin-left: 0 !important;
            padding: 16px !important;
            gap: 20px !important;
            flex: 1 !important;
        }

        /* Prevent parent page topbars from stacking weirdly with the global mobile top-app-bar */
        .topbar {
            padding: 14px 16px !important;
            flex-wrap: wrap !important;
            gap: 8px !important;
            margin-bottom: 0 !important;
            border-radius: 12px !important;
        }
        .topbar-title {
            font-size: 14px !important;
        }

        /* Make data tables scrollable and responsive */
        .table-wrapper,
        .table-responsive {
            padding: 8px 10px 16px !important;
            overflow-x: auto !important;
            width: 100% !important;
            max-width: 100% !important;
            -webkit-overflow-scrolling: touch;
        }
        .data-table {
            width: 100% !important;
            min-width: 650px !important; /* Ensure table headers don't collapse */
        }
        .data-table th,
        .data-table td {
            padding: 10px 12px !important;
            font-size: 12px !important;
        }

        /* Adjust other cards/metrics on mobile */
        .metrics-grid,
        .quick-links,
        .chart-grid {
            grid-template-columns: 1fr !important;
            flex-direction: column !important;
            gap: 16px !important;
            width: 100% !important;
        }
        .metric-card,
        .quick-card,
        .chart-container {
            width: 100% !important;
            min-width: 0 !important;
        }
        .stat-summary {
            flex-direction: column !important;
            align-items: stretch !important;
            padding: 16px !important;
            gap: 16px !important;
        }
        .stat-item {
            text-align: center !important;
        }
        .export-buttons {
            flex-direction: column !important;
            width: 100% !important;
            gap: 8px !important;
        }
    }

    /* Hide close btn on desktop */
    .sidebar-close-btn {
        display: none;
    }
</style>

<!-- Mobile Sidebar Backdrop -->
<div class="sidebar-backdrop" id="sidebar-backdrop"></div>

<aside class="cms-sidebar animate-float d-1" id="admin-sidebar">
    <!-- Close button (mobile only) -->
    <button class="sidebar-close-btn" id="sidebar-close-btn" aria-label="Tutup Menu">
        <i class="fas fa-times"></i>
    </button>

    <div class="sidebar-brand">
        <img src="{{ asset('logo.png') }}" alt="Logo" style="width:36px;height:36px;object-fit:contain;margin-right:8px;">
        <span>UG<span style="color:#0ea5e9;">FORCE</span></span>
    </div>

    <div class="sidebar-profile">
        <div class="sidebar-avatar">
            @if(Auth::user()->avatar)
                <img src="{{ Auth::user()->avatar }}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
            @else
                {{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}
            @endif
        </div>
        <div>
            <div class="sidebar-greeting">Selamat Bekerja,</div>
            <div class="sidebar-name">{{ Auth::user()->name ?? 'Admin' }}</div>
        </div>
    </div>

    <div class="sidebar-header">General</div>
    <div class="sidebar-menus">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ ($activeMenu ?? '') === 'dashboard' ? 'active' : '' }}">
            <i class="fas fa-home icon"></i><div class="title">Dashboard Induk</div>
        </a>
        <a href="{{ route('admin.rooms') }}" class="sidebar-item {{ ($activeMenu ?? '') === 'rooms' ? 'active' : '' }}">
            <i class="fas fa-building icon"></i><div class="title">Atur Fasilitas</div>
        </a>
        <a href="{{ route('admin.bookings') }}" class="sidebar-item {{ ($activeMenu ?? '') === 'bookings' ? 'active' : '' }}">
            <i class="fas fa-clipboard-check icon"></i><div class="title">Proses Antrean</div>
        </a>
        <a href="{{ route('admin.reports') }}" class="sidebar-item {{ ($activeMenu ?? '') === 'reports' ? 'active' : '' }}">
            <i class="fas fa-chart-pie icon"></i><div class="title">Laporan Data</div>
        </a>
        <a href="{{ route('admin.users') }}" class="sidebar-item {{ ($activeMenu ?? '') === 'users' ? 'active' : '' }}">
            <i class="fas fa-users-cog icon"></i><div class="title">Manajemen User</div>
        </a>
        <a href="{{ route('admin.schedules') }}" class="sidebar-item {{ ($activeMenu ?? '') === 'schedules' ? 'active' : '' }}">
            <i class="fas fa-calendar-alt icon"></i><div class="title">Jadwal Dosen</div>
        </a>
        <a href="{{ route('admin.attendance') }}" class="sidebar-item {{ ($activeMenu ?? '') === 'attendance' ? 'active' : '' }}">
            <i class="fas fa-id-card-clip icon"></i><div class="title">Kehadiran Dosen</div>
        </a>
        <a href="{{ route('admin.profile') }}" class="sidebar-item {{ ($activeMenu ?? '') === 'profile' ? 'active' : '' }}">
            <i class="fas fa-user-circle icon"></i><div class="title">Profil Saya</div>
        </a>
    </div>

    <div class="logout-box">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Keluar Sistem
            </button>
        </form>
    </div>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('admin-sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');
    const closeBtn = document.getElementById('sidebar-close-btn');

    // Use Event Delegation to support dynamically replaced hamburger buttons (e.g. by FontAwesome JS SVG replacement)
    document.addEventListener('click', function(e) {
        const toggleBtn = e.target.closest('.sidebar-toggle-btn, .topbar .fa-bars, .topbar svg.fa-bars, .topbar svg.fa-bars *, [class*="fa-bars"]');
        if (toggleBtn) {
            e.preventDefault();
            e.stopPropagation();
            if (sidebar) sidebar.classList.add('mobile-open');
            if (backdrop) backdrop.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    });

    function closeSidebar() {
        if (sidebar) sidebar.classList.remove('mobile-open');
        if (backdrop) backdrop.classList.remove('active');
        document.body.style.overflow = '';
    }

    if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
    if (backdrop) backdrop.addEventListener('click', closeSidebar);
});
</script>
