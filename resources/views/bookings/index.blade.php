@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap');

    /* ── CYBER-CASUAL TOKENS ── */
    :root {
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
        --bg-main:         #f0f9ff;
        --bg-surface:      rgba(255, 255, 255, 0.85);
        --bg-surface-hover:rgba(255, 255, 255, 0.95);
        --bg-input:        #ffffff;
        
        --border-soft:     rgba(14, 165, 233, 0.2);
        --border-strong:   rgba(14, 165, 233, 0.4);
        --border-focus:    #0ea5e9;
        
        --text-primary:    #0f172a;
        --text-secondary:  #0369a1;
        --text-tertiary:   #3b82f6;
        
        --accent-blue:     #0ea5e9;
        --accent-indigo:   #0ea5e9;
        
        --shadow-card:     0 10px 40px rgba(14, 165, 233, 0.08);
        --shadow-glow:     0 5px 20px rgba(14, 165, 233, 0.15);
    }

    /* ── ANIMATIONS ── */
    @keyframes float-up { 
        0% { opacity: 0; transform: translateY(30px) scale(0.98); } 
        100% { opacity: 1; transform: translateY(0) scale(1); } 
    }
    .animate-float { animation: float-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }

    /* ── BACKGROUND LAYER ── */
    .hero-photo-bg {
        position: absolute; top: 0; left: 0; right: 0; height: 400px;
        background-image: url('https://2.bp.blogspot.com/-ah30_c4lnYE/Tsia8ZNlT-I/AAAAAAAAAC4/avMnlc5l9x8/w1200-h630-p-k-no-nu/KAMPUS+E.jpg'); 
        background-size: cover; background-position: center 30%;
        z-index: -2; 
        mask-image: linear-gradient(to bottom, black 20%, transparent 100%);
        -webkit-mask-image: linear-gradient(to bottom, black 20%, transparent 100%);
        opacity: 0.7; transition: opacity 0.5s ease;
    }
    html.dark .hero-photo-bg { opacity: 0.2; }

    #ug-canvas { position: fixed; inset: 0; z-index: 0; pointer-events: none; opacity: 0.2; }

    /* ── LAYOUT WRAPPER ── */
    .page-container {
        position: relative; z-index: 2; max-width: 1100px; margin: 0 auto;
        padding: 40px 20px; font-family: var(--font-sans); color: var(--text-primary);
    }

    /* ── GLASS CARD ── */
    .card-glass {
        background: var(--bg-surface); backdrop-filter: blur(35px); -webkit-backdrop-filter: blur(35px);
        border: 1px solid var(--border-soft); border-radius: var(--radius-lg);
        box-shadow: var(--shadow-card); transition: border-color 0.4s ease;
        position: relative; overflow: hidden;
    }

    .card-glass::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        background: radial-gradient(600px circle at var(--mouse-x, -500px) var(--mouse-y, -500px), rgba(59, 130, 246, 0.08), transparent 40%);
        z-index: 0; pointer-events: none; transition: opacity 0.3s ease; opacity: 0;
    }
    html:not(.dark) .card-glass::before {
        background: radial-gradient(600px circle at var(--mouse-x, -500px) var(--mouse-y, -500px), rgba(14, 165, 233, 0.06), transparent 40%);
    }
    .card-glass:hover::before { opacity: 1; }
    .card-glass:hover { border-color: var(--border-strong); }

    /* ── TOP BAR & ACTION BUTTONS ── */
    .page-header {
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;
        flex-wrap: wrap; gap: 16px;
    }
    .btn-back {
        display: inline-flex; align-items: center; gap: 8px; color: #ffffff !important;
        font-weight: 700; text-decoration: none; font-size: 14px;
        transition: color 0.3s, transform 0.3s;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    .btn-back:hover { color: #0ea5e9 !important; transform: translateX(-4px); }

    .btn-glow-cyan {
        background: linear-gradient(135deg, #3b82f6, #3b82f6) !important;
        color: #ffffff !important; 
        border: 1px solid rgba(255,255,255,0.2) !important;
        border-radius: 12px; padding: 10px 20px; font-size: 14px; font-weight: 700;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        display: inline-flex; align-items: center; gap: 8px;
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3) !important;
        text-decoration: none;
    }
    .btn-glow-cyan:hover { transform: translateY(-3px); box-shadow: 0 15px 35px rgba(59, 130, 246, 0.6) !important; }

    /* ── TABLE PANEL ── */
    .table-panel { display: flex; flex-direction: column; overflow: hidden; padding: 0; }
    .table-toolbar { 
        padding: 24px 32px; 
        border-bottom: 1px dashed var(--border-soft); 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        background: rgba(0,0,0,0.15); 
        flex-wrap: wrap; gap: 20px;
    }
    html:not(.dark) .table-toolbar { background: rgba(0,0,0,0.03); }

    .panel-title { font-size: 18px; font-weight: 800; color: var(--text-primary); display: flex; align-items: center; gap: 12px; margin: 0; }
    .panel-title i { color: #0ea5e9; }

    .search-box {
        display: flex; align-items: center; gap: 12px; background: var(--bg-input);
        padding: 12px 18px; border-radius: var(--radius-md); border: 1px solid var(--border-strong); width: 100%; max-width: 350px;
        transition: all 0.3s;
    }
    html:not(.dark) .search-box { background: #ffffff; border-color: rgba(14, 165, 233, 0.2); }
    .search-box:focus-within { border-color: #0ea5e9; box-shadow: var(--shadow-glow); }
    .search-box i { color: #0ea5e9; }
    .search-box input { border: none; background: transparent; color: var(--text-primary); font-size: 14px; width: 100%; outline: none; }
    .search-box input::placeholder { color: var(--text-tertiary); }

    .table-wrapper { overflow-x: auto; padding: 10px 20px; }
    .data-table { width: 100%; border-collapse: separate; border-spacing: 0 10px; text-align: left; }
    .data-table th { padding: 12px 24px; font-size: 11px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; font-family: var(--font-mono); }
    .data-table td { padding: 18px 24px; font-size: 14px; color: var(--text-primary); background: rgba(255,255,255,0.03); vertical-align: middle; transition: all 0.3s; }
    html:not(.dark) .data-table td { background: #ffffff; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
    
    .data-table tr td:first-child { border-radius: 14px 0 0 14px; border-left: 3px solid transparent; }
    .data-table tr td:last-child { border-radius: 0 14px 14px 0; border-right: 3px solid transparent; }
    
    .data-table tbody tr:hover td { background: rgba(59, 130, 246, 0.08); }
    html:not(.dark) .data-table tbody tr:hover td { background: #f0f9ff; }
    .data-table tbody tr:hover td:first-child { border-left-color: #0ea5e9; }
    
    .td-facility { font-weight: 800; font-size: 15px; margin-bottom: 4px; color: var(--text-primary); display: flex; align-items: center; gap: 8px;}
    .td-facility i { color: #0ea5e9; opacity: 0.7;}
    .td-purpose { font-size: 12px; color: var(--text-secondary); max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    
    .td-date { font-weight: 700; margin-bottom: 6px; font-size: 13px; }
    .td-time { font-family: var(--font-mono); font-size: 12px; color: var(--text-secondary); font-weight: 600; background: var(--bg-input); padding: 4px 10px; border-radius: 6px; display: inline-flex; align-items: center; gap: 6px; border: 1px solid var(--border-soft);}
    html:not(.dark) .td-time { background: #f0f9ff; }

    /* Pills */
    .pill { display: inline-flex; align-items: center; gap: 8px; padding: 6px 14px; border-radius: 8px; font-size: 12px; font-weight: 700; border: 1px solid transparent; }
    .pill-pending { background: rgba(245, 158, 11, 0.1); color: var(--warning); border-color: rgba(245, 158, 11, 0.3); }
    .pill-approved { background: rgba(16, 185, 129, 0.1); color: var(--success); border-color: rgba(16, 185, 129, 0.3); }
    .pill-rejected { background: rgba(239, 68, 68, 0.1); color: var(--danger); border-color: rgba(239, 68, 68, 0.3); }
    .pill-completed { background: rgba(14, 165, 233, 0.1); color: var(--accent-blue); border-color: rgba(14, 165, 233, 0.3); }

    .btn-cancel { background: rgba(239, 68, 68, 0.1); color: var(--danger); border: 1px solid rgba(239, 68, 68, 0.3); padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px; font-family: var(--font-sans);}
    .btn-cancel:hover { background: var(--danger); color: #fff; transform: scale(1.05); box-shadow: 0 5px 15px rgba(239, 68, 68, 0.4); }
    .btn-complete { background: rgba(16, 185, 129, 0.1); color: var(--success); border: 1px solid rgba(16, 185, 129, 0.3); padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px; font-family: var(--font-sans);}
    .btn-complete:hover { background: var(--success); color: #fff; transform: scale(1.05); box-shadow: 0 5px 15px rgba(16, 185, 129, 0.4); }

    /* Empty State */
    .empty-state { text-align: center; padding: 80px 20px; color: var(--text-tertiary); }
    .empty-state i { font-size: 48px; margin-bottom: 16px; display: block; opacity: 0.2; color: #0ea5e9; }
    .empty-state p { font-size: 15px; font-weight: 600; }

    /* Pagination */
    .pagination-bar { padding: 20px 32px; border-top: 1px dashed var(--border-soft); display: flex; justify-content: space-between; align-items: center; background: rgba(0,0,0,0.15); }
    html:not(.dark) .pagination-bar { background: rgba(0,0,0,0.03); }
    .page-info { font-size: 13px; color: var(--text-secondary); font-weight: 600; font-family: var(--font-mono); }
    .page-btn { background: var(--bg-input); border: 1px solid var(--border-strong); color: var(--text-primary); padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; gap: 8px; }
    html:not(.dark) .page-btn { background: #ffffff; border-color: rgba(14, 165, 233, 0.2); }
    .page-btn:hover:not(:disabled) { border-color: #0ea5e9; color: #0ea5e9; background: rgba(59, 130, 246, 0.1); }
    .page-btn:disabled { opacity: 0.3; cursor: not-allowed; }

    /* ── MOBILE RESPONSIVE STYLES ── */
    @media (max-width: 768px) {
        .page-container {
            padding: 16px;
        }
        .page-header {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
            margin-bottom: 16px;
        }
        .btn-back {
            justify-content: center;
        }
        .btn-glow-cyan {
            justify-content: center;
            width: 100%;
        }
        .table-toolbar {
            padding: 16px 20px;
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }
        .search-box {
            max-width: 100%;
        }
        .table-wrapper {
            padding: 10px 12px;
        }
        
        /* Transform Table into beautiful individual cards */
        .data-table, .data-table thead, .data-table tbody, .data-table th, .data-table td, .data-table tr {
            display: block;
            width: 100%;
        }
        .data-table thead {
            display: none;
        }
        .data-table tr {
            margin-bottom: 16px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-soft);
            background: rgba(255, 255, 255, 0.02);
            padding: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        html:not(.dark) .data-table tr {
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.04);
        }
        .data-table tr:hover {
            border-color: var(--border-strong);
            transform: translateY(-2px);
        }
        .data-table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0 !important;
            background: transparent !important;
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            font-size: 13px;
        }
        .data-table td::before {
            content: attr(data-label);
            font-size: 11px;
            font-weight: 800;
            color: var(--text-secondary);
            text-transform: uppercase;
            font-family: var(--font-mono);
        }
        .data-table td:first-child {
            flex-direction: column;
            align-items: flex-start;
            border-bottom: 1px dashed var(--border-soft) !important;
            padding-bottom: 12px !important;
            margin-bottom: 8px;
        }
        .data-table td:first-child::before {
            display: none;
        }
        .td-facility {
            font-size: 16px;
            margin-bottom: 6px;
            padding-left: 0 !important;
        }
        .td-purpose {
            max-width: 100%;
            white-space: normal;
        }
        .data-table td:last-child {
            border-top: 1px dashed var(--border-soft) !important;
            padding-top: 12px !important;
            margin-top: 8px;
            justify-content: flex-end;
        }
        .data-table td:last-child::before {
            display: none;
        }
        .pagination-bar {
            padding: 16px 20px;
            flex-direction: column;
            gap: 12px;
            align-items: center;
        }
    }
</style>

<!-- BACKGROUND LAYER -->
<div class="hero-photo-bg"></div>
<canvas id="ug-canvas"></canvas>

<div class="page-container">
    <div class="page-header animate-float">
        <a href="{{ url('/dashboard') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
        <a href="{{ route('bookings.create') }}" class="btn-glow-cyan">
            <i class="bi bi-plus-circle-fill"></i> Ajukan Peminjaman Baru
        </a>
    </div>

    <!-- Table Panel Glass Card -->
    <div class="card-glass table-panel animate-float" style="animation-delay: 0.1s;">
        <div class="table-toolbar">
            <h2 class="panel-title"><i class="bi bi-clock-history"></i> Riwayat Peminjaman Ruang</h2>
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="ug-search" placeholder="Cari ruangan atau kegiatan...">
            </div>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Fasilitas</th>
                        <th>Tanggal & Waktu</th>
                        <th>Status Izin</th>
                        <th style="text-align:right">Opsi</th>
                    </tr>
                </thead>
                <tbody id="ug-tbody">
                    @forelse($bookings as $booking)
                    <tr data-search="{{ strtolower($booking->room->name . ' ' . $booking->purpose) }}">
                        <td data-label="Fasilitas">
                            <div class="td-facility"><i class="bi bi-grid-1x2-fill me-2 opacity-50"></i> {{ $booking->room->name }}</div>
                            <div class="td-purpose" title="{{ $booking->purpose }}">{{ $booking->purpose }}</div>
                        </td>
                        <td data-label="Waktu">
                            <div class="td-date">{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M Y') }}</div>
                            <div class="td-time">
                                <i class="bi bi-clock me-1"></i> 
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} — {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} WIB
                            </div>
                        </td>
                        <td data-label="Status">
                            @if($booking->status === 'pending')
                                <span class="pill pill-pending"><i class="bi bi-arrow-repeat spin-slow"></i> Menunggu Verifikasi</span>
                            @elseif($booking->status === 'approved')
                                <span class="pill pill-approved"><i class="bi bi-check-circle-fill"></i> Disetujui / Siap Pakai</span>
                            @elseif($booking->status === 'completed')
                                <span class="pill pill-completed"><i class="bi bi-check2-all"></i> Selesai Digunakan</span>
                            @else
                                <span class="pill pill-rejected"><i class="bi bi-x-circle-fill"></i> Ditolak</span>
                            @endif
                        </td>
                        <td data-label="Opsi" style="text-align:right">
                            @if($booking->status === 'pending')
                                <form action="{{ route('bookings.cancel', $booking) }}" method="POST" style="display:inline" onsubmit="return confirm('Yakin ingin membatalkan pengajuan peminjaman ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-cancel" title="Batalkan Reservasi">
                                        <i class="bi bi-trash3-fill"></i> Batalkan
                                    </button>
                                </form>
                            @elseif($booking->status === 'approved')
                                <form action="{{ route('bookings.complete', $booking) }}" method="POST" style="display:inline" onsubmit="return confirm('Yakin pemakaian ruangan ini sudah selesai?')">
                                    @csrf
                                    <button type="submit" class="btn-complete" title="Tandai Selesai Digunakan">
                                        <i class="bi bi-check-circle-fill"></i> Selesai Pakai
                                    </button>
                                </form>
                            @else
                                <i class="bi bi-lock-fill text-muted" style="font-size: 18px;" title="Peminjaman Terkunci"></i>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr id="ug-empty-row">
                        <td colspan="4">
                            <div class="empty-state">
                                <i class="bi bi-emoji-sunglasses"></i>
                                <p>Belum ada riwayat peminjaman. Yuk pesan ruangan pertamamu!</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div class="pagination-bar">
            <div class="page-info" id="ug-page-info">Halaman 1 dari 1</div>
            <div style="display: flex; gap: 8px;">
                <button class="page-btn" id="ug-prev" disabled><i class="bi bi-chevron-left"></i> Sebelumnya</button>
                <button class="page-btn" id="ug-next" disabled>Berikutnya <i class="bi bi-chevron-right"></i></button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    /* ── MAGNETIC GLOW TRACKER ── */
    const card = document.querySelector('.card-glass');
    if (card) {
        card.addEventListener('mousemove', e => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            card.style.setProperty('--mouse-x', `${x}px`);
            card.style.setProperty('--mouse-y', `${y}px`);
        });
    }

    /* ── LIVE SEARCH & CLIENT-SIDE PAGINATION ── */
    const searchEl = document.getElementById('ug-search');
    const tbody = document.getElementById('ug-tbody');
    const prevBtn = document.getElementById('ug-prev');
    const nextBtn = document.getElementById('ug-next');
    const infoEl = document.getElementById('ug-page-info');
    const emptyRow = document.getElementById('ug-empty-row');
    
    let allRows = [];
    let filteredRows = [];
    let currentPage = 1;
    const rowsPerPage = 7; // More spacing on full screen

    if(tbody) {
        allRows = Array.from(tbody.querySelectorAll('tr[data-search]'));
        filteredRows = allRows;
        updatePagination();
    }

    function updatePagination() {
        const totalRows = filteredRows.length;
        const totalPages = Math.ceil(totalRows / rowsPerPage) || 1;
        
        if(currentPage > totalPages) currentPage = totalPages;

        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        allRows.forEach(row => row.style.display = 'none');

        filteredRows.slice(start, end).forEach((row, index) => {
            row.style.display = '';
            row.style.opacity = '0';
            row.style.transform = 'translateY(15px)';
            setTimeout(() => {
                row.style.transition = 'all 0.4s cubic-bezier(0.16, 1, 0.3, 1)';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, index * 60); 
        });

        if(infoEl) infoEl.textContent = `Halaman ${currentPage} dari ${totalPages}`;
        if(prevBtn) prevBtn.disabled = currentPage === 1;
        if(nextBtn) nextBtn.disabled = currentPage === totalPages || totalPages === 0;
        if(emptyRow) emptyRow.style.display = totalRows === 0 ? '' : 'none';
    }

    if(searchEl) {
        searchEl.addEventListener('input', function() {
            const q = this.value.toLowerCase().trim();
            filteredRows = allRows.filter(row => !q || row.dataset.search.includes(q));
            currentPage = 1; 
            updatePagination();
        });
    }

    if(prevBtn) prevBtn.addEventListener('click', () => { if(currentPage > 1) { currentPage--; updatePagination(); } });
    if(nextBtn) nextBtn.addEventListener('click', () => { const max = Math.ceil(filteredRows.length / rowsPerPage); if(currentPage < max) { currentPage++; updatePagination(); } });

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

    for(let i = 0; i < 70; i++) particles.push(new Particle());

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