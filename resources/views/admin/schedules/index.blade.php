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

    /* ── BASE WRAPPER ── */
    .cms-container {
        display: flex; min-height: calc(100vh - 160px); width: 100%;
        font-family: var(--font-sans); color: var(--text-primary);
        z-index: 10; position: relative; margin-top: -16px;
    }

    /* ── SIDEBAR LEFT ── */
    .cms-sidebar {
        width: 280px; flex-shrink: 0; background: var(--bg-surface); backdrop-filter: blur(35px);
        border-right: 1px solid var(--border-soft); display: flex; flex-direction: column;
        position: sticky; top: 0; height: 100vh; padding: 28px 24px; z-index: 50;
        overflow-y: auto; scrollbar-width: none;
    }
    .cms-sidebar::-webkit-scrollbar { display: none; }
    html:not(.dark) .cms-sidebar { background: var(--bg-surface); }

    @keyframes spin-slow { 100% { transform: rotate(360deg); } }
    .sidebar-brand { display: flex; align-items: center; gap: 12px; margin-bottom: 30px; padding-bottom: 24px; border-bottom: 1px dashed var(--border-soft); font-size: 24px; font-weight: 800; }
    .sidebar-profile { display: flex; align-items: center; gap: 16px; margin-bottom: 30px; }
    .sidebar-avatar { width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, var(--danger), var(--accent-blue)); display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 800; color: #fff; position: relative; box-shadow: 0 0 15px rgba(239,68,68,0.4); flex-shrink: 0; overflow: hidden; }
    .sidebar-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
    .sidebar-avatar::before { content: ''; position: absolute; inset: -4px; border-radius: 50%; border: 2px dashed var(--danger); animation: spin-slow 10s linear infinite; opacity: 0.2; }
    .sidebar-greeting { font-size: 12px; color: var(--text-secondary); font-weight: 600; text-transform: uppercase; margin-bottom: 2px; }
    .sidebar-name { font-size: 16px; font-weight: 800; color: var(--text-primary); }
    
    .sidebar-header { font-size: 12px; font-weight: 700; color: var(--text-tertiary); margin-bottom: 12px; letter-spacing: 1px; text-transform: uppercase; font-family: var(--font-mono);}
    .sidebar-menus { display: flex; flex-direction: column; gap: 6px; flex: 1; }
    
    .sidebar-item {
        display: flex; align-items: center; gap: 14px; padding: 12px 16px; border-radius: 12px;
        color: var(--text-secondary); text-decoration: none; font-weight: 700;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); border: 1px solid transparent;
    }
    .sidebar-item.active { background: var(--bg-input); border-color: var(--border-soft); color: var(--text-primary); border-left: 4px solid var(--accent-blue); }
    .sidebar-item:hover { border-color: #0ea5e9; background: rgba(59,130,246,0.05); color: var(--text-primary); transform: translateX(6px); }
    html:not(.dark) .sidebar-item:hover { background: rgba(14,165,233,0.05); }
    .sidebar-item i.icon { font-size: 16px; width: 24px; text-align: center; color: var(--text-tertiary); }
    .sidebar-item.active i.icon { color: #0ea5e9; }
    .sidebar-item:hover i.icon { color: #0ea5e9; transform: scale(1.1); }
    .sidebar-item .title { font-size: 14px; font-weight: 700; flex: 1; }
    .logout-box { margin-top: auto; padding-top: 24px; }
    .btn-logout { display: flex; align-items: center; gap: 12px; padding: 14px 16px; border-radius: 14px; background: rgba(239,68,68,0.1); color: var(--danger); font-size: 14px; font-weight: 700; width: 100%; cursor: pointer; transition: all 0.3s; border: none; }
    .btn-logout:hover { background: var(--danger); color: #fff; transform: translateY(-2px); }

    /* ── RIGHT MAIN CONTENT ── */
    .cms-main { 
        flex: 1; display: flex; flex-direction: column; gap: 32px; padding: 32px 40px; max-width: calc(100vw - 280px); 
    }

    .glass-card {
        background: var(--bg-surface); backdrop-filter: blur(25px); -webkit-backdrop-filter: blur(25px);
        border: 1px solid var(--border-soft); border-radius: var(--radius-lg);
        box-shadow: var(--shadow-card); transition: border-color 0.4s ease, transform 0.4s ease;
    }

    .btn-glow {
        background: linear-gradient(135deg, var(--accent-blue), #2563eb);
        color: #fff !important; font-weight: 700; border-radius: 12px; padding: 12px 20px;
        box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3); border: none;
        transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-glow:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(59, 130, 246, 0.5); }

    /* ── CUSTOM DATA TABLE STYLING ── */
    .table-responsive { padding: 10px 20px 20px; }
    .data-table { width: 100%; border-collapse: separate; border-spacing: 0 10px; text-align: left; }
    .data-table th { padding: 12px 20px; font-size: 11px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; font-family: var(--font-mono); }
    .data-table td { padding: 14px 20px; font-size: 14px; background: rgba(255,255,255,0.03); vertical-align: middle; border-top: 1px solid transparent; border-bottom: 1px solid transparent; }
    html:not(.dark) .data-table td { background: rgba(255,255,255,0.5); }
    .data-table tr td:first-child { border-radius: 12px 0 0 12px; border-left: 3px solid transparent; }
    .data-table tr td:last-child { border-radius: 0 12px 12px 0; border-right: 3px solid transparent; }
    .data-table tbody tr:hover td { background: rgba(59, 130, 246, 0.08); }
    .data-table tbody tr:hover td:first-child { border-left-color: #0ea5e9; }
</style>

<div class="dashboard-layout">
    <!-- SIDEBAR -->
    @include('admin.partials.sidebar', ['activeMenu' => 'schedules'])

    <!-- MAIN CONTENT -->
    <main class="cms-main">
        <!-- Top Toolbar -->
        <div class="flex justify-between items-center flex-wrap gap-4 mb-2">
            <div>
                <h1 class="text-4xl font-extrabold tracking-tight">Manajemen Jadwal Dosen</h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium">Tentukan dan kelola jadwal kuliah resmi yang diisi oleh dosen di berbagai ruangan kelas.</p>
            </div>
            <div class="flex items-center gap-3">
                <form method="POST" action="{{ route('admin.schedules.reset-all') }}" class="m-0" onsubmit="return confirm('Reset semua status kelas selesai ke Ready dan kosongkan ruangan kelas?');">
                    @csrf
                    <button type="submit" class="btn-glow" style="background: linear-gradient(135deg, #ef4444, #b91c1c); box-shadow: 0 5px 15px rgba(239, 68, 68, 0.3);">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Semua Kelas Selesai ({{ $schedules->whereIn('status', ['selesai', 'selesai_selesai'])->count() }} Kelas)
                    </button>
                </form>
                <a href="{{ route('admin.schedules.create') }}" class="btn-glow">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Jadwal Kuliah
                </a>
            </div>
        </div>

        <!-- Table Listing Schedules -->
        <div class="glass-card overflow-hidden">
            @if($schedules->isEmpty())
                <div class="text-center py-16">
                    <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-4 text-slate-400">
                        <i class="bi bi-calendar-x text-3xl"></i>
                    </div>
                    <h4 class="text-lg font-bold mb-1">Belum Ada Jadwal Kuliah Terdaftar</h4>
                    <p class="text-slate-500 dark:text-slate-400 text-sm max-w-md mx-auto mb-0">Klik tombol di atas untuk menambahkan jadwal mengajar dosen resmi ke dalam sistem ruangan.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Dosen Mengajar</th>
                                <th>Mata Kuliah / Kelas</th>
                                <th>Hari / Waktu</th>
                                <th class="text-center">Ruangan</th>
                                <th class="text-center">Status Mengajar</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedules as $schedule)
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-500">
                                                <i class="bi bi-person-badge-fill text-xl"></i>
                                            </div>
                                            <div>
                                                <span class="font-extrabold block text-slate-900 dark:text-white">{{ $schedule->lecturer->name }}</span>
                                                <span class="text-xs text-slate-500 dark:text-slate-400">{{ $schedule->lecturer->email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-bold text-slate-800 dark:text-white block">{{ $schedule->subject }}</span>
                                        <span class="badge bg-slate-100 dark:bg-white/10 text-slate-600 dark:text-slate-300 text-[10px] font-black uppercase">Kelas: {{ $schedule->class_name }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 font-extrabold rounded-lg px-2.5 py-1 mb-1 inline-block">{{ $schedule->day_of_week }}</span>
                                        <div class="text-xs font-mono text-slate-500 dark:text-slate-400">
                                            <i class="bi bi-clock me-1"></i> {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-amber-500/10 border border-amber-500/30 text-amber-600 dark:text-[#FFC107] font-black px-3 py-1.5 rounded-xl text-sm">
                                            {{ $schedule->room->name }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($schedule->status === 'selesai')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-green-500/10 border border-green-500/30 text-green-600 dark:text-green-400 text-xs font-black uppercase tracking-wider">
                                                <span class="w-2 h-2 rounded-full bg-green-500 animate-ping"></span> Sedang Mengajar
                                            </span>
                                        @elseif($schedule->status === 'selesai_selesai')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 dark:bg-white/5 border border-slate-300 dark:border-white/10 text-slate-500 dark:text-slate-400 text-xs font-black uppercase tracking-wider">
                                                <i class="bi bi-check2-all text-sm"></i> Kelas Selesai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-600 dark:text-amber-400 text-xs font-black uppercase tracking-wider">
                                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> Belum Masuk
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            @if($schedule->status === 'selesai' || $schedule->status === 'selesai_selesai')
                                                <form method="POST" action="{{ route('admin.schedules.reset', $schedule) }}" class="m-0" onsubmit="return confirm('Reset status jadwal kuliah ini menjadi Ready agar Dosen dapat konfirmasi kembali?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-warning border-0 p-2 rounded-xl text-amber-500 hover:bg-amber-500/10" title="Reset Status ke Ready">
                                                        <i class="bi bi-arrow-counterclockwise text-lg"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}" class="m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal kuliah ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-2 rounded-xl text-red-500 hover:bg-red-500/10" title="Hapus Jadwal">
                                                    <i class="bi bi-trash3-fill text-lg"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </main>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
@endsection
