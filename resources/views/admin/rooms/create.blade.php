@extends('layouts.app')

@section('content')
<style>
    /* CSS UTAMA KITA PANGGIL ULANG SUPAYA RAPI DI HALAMAN INI */
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap');
    :root { --bg-main: #000000; --bg-surface: rgba(13, 15, 23, 0.65); --bg-input: rgba(5, 7, 12, 0.6); --border-soft: rgba(59, 130, 246, 0.15); --border-strong: rgba(59, 130, 246, 0.35); --text-primary: #ffffff; --text-secondary: #3b82f6; --accent-blue: #3b82f6; --danger: #ef4444; --font-sans: 'Plus Jakarta Sans', sans-serif; --font-mono: 'JetBrains Mono', monospace;}
    html:not(.dark) { --bg-main: #f0f9ff; --bg-surface: rgba(255, 255, 255, 0.85); --bg-input: #ffffff; --border-soft: rgba(14, 165, 233, 0.15); --border-strong: rgba(14, 165, 233, 0.35); --text-primary: #0f172a; --text-secondary: #0369a1; --accent-blue: #0ea5e9;}
    *, *::before, *::after { box-sizing: border-box; } body { margin: 0; padding: 0; }
    
    .ambient-bg { position: fixed; inset: 0; z-index: -5; background: var(--bg-main); }
    .dashboard-layout { position: relative; z-index: 2; max-width: 1400px; margin: 0 auto; padding: 24px; font-family: var(--font-sans); color: var(--text-primary); display: flex; flex-direction: column; gap: 28px; }
    .cms-layout { display: grid; grid-template-columns: 280px 1fr; gap: 28px; align-items: start; }
    .cms-sidebar { width: 280px; background: var(--bg-surface); backdrop-filter: blur(35px); border-right: 1px solid var(--border-soft); display: flex; flex-direction: column; position: sticky; top: 0; height: 100vh; padding: 28px 24px; z-index: 50;}
    .sidebar-brand { display: flex; align-items: center; gap: 12px; margin-bottom: 30px; padding-bottom: 24px; border-bottom: 1px dashed var(--border-soft); font-size: 24px; font-weight: 800;} .sidebar-brand i { color: #0ea5e9; font-size: 28px;}
    .sidebar-menus { display: flex; flex-direction: column; gap: 6px; flex: 1;}
    .sidebar-item { display: flex; align-items: center; gap: 14px; padding: 12px 16px; border-radius: 12px; text-decoration: none; color: var(--text-secondary); transition: all 0.3s;}
    .sidebar-item.active { background: var(--bg-input); border-color: var(--border-soft); color: var(--text-primary); border-left: 4px solid var(--accent-blue); }
    .sidebar-item i.icon { font-size: 16px; width: 24px; text-align: center; color: var(--text-secondary);} .sidebar-item.active i.icon { color: #0ea5e9; }
    .sidebar-item .title { font-size: 14px; font-weight: 700; }
    .cms-main { flex: 1; display: flex; flex-direction: column; gap: 32px; padding: 32px 40px; max-width: calc(100vw - 280px); }
    
    .glass-card { background: var(--bg-surface); backdrop-filter: blur(25px); border: 1px solid var(--border-soft); border-radius: 20px; position: relative; }
    .topbar { display: flex; justify-content: space-between; align-items: center; padding: 20px 32px; border-radius: 16px;} .topbar-title { font-size: 18px; font-weight: 800; display: flex; align-items: center; gap: 12px;}

    /* Form Styles Khusus Halaman Create/Edit */
    .form-panel { padding: 32px; }
    .form-group { margin-bottom: 24px; }
    .form-label { display: block; font-size: 12px; font-weight: 700; color: var(--text-secondary); margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px; }
    .form-control { width: 100%; padding: 16px 20px; font-size: 15px; font-weight: 600; font-family: var(--font-sans); background: var(--bg-input); border: 1px solid var(--border-strong); border-radius: 14px; color: var(--text-primary); outline: none; transition: all 0.3s ease; }
    .form-control:focus { border-color: #0ea5e9; box-shadow: 0 0 20px rgba(59, 130, 246, 0.15); transform: translateY(-2px); }
    html:not(.dark) .form-control:focus { box-shadow: 0 0 20px rgba(14, 165, 233, 0.15); }
    select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='none' stroke='%233b82f6' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M3 5l4 4 4-4'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 16px center; cursor: pointer; }
    select.form-control option { background: #0B0E14; color: #fff; } html:not(.dark) select.form-control option { background: #fff; color: #000; }
    
    .btn-glow { background: linear-gradient(135deg, #3b82f6, #3b82f6); color: #ffffff !important; border: none; border-radius: 14px; padding: 16px; font-size: 15px; font-weight: 800; cursor: pointer; display: flex; justify-content: center; gap: 10px; width: 100%; transition: all 0.4s; text-transform: uppercase; font-family: var(--font-mono);}
    .btn-glow:hover { transform: translateY(-3px); box-shadow: 0 15px 35px rgba(59, 130, 246, 0.5); }
    .btn-back { display: inline-flex; align-items: center; gap: 8px; color: var(--text-secondary); text-decoration: none; font-weight: 700; font-size: 14px; margin-bottom: 20px; transition: color 0.3s;} .btn-back:hover { color: #0ea5e9; }

    @media (max-width: 1024px) { .cms-layout { grid-template-columns: 1fr; } .cms-sidebar { width: 100%; height: auto; position: relative; border-right: none;} .cms-main { max-width: 100%; padding: 20px 16px;} }
</style>

<div class="ambient-bg"></div>

<div class="dashboard-layout">
    <div class="cms-layout">
        
        @include('admin.partials.sidebar', ['activeMenu' => 'rooms'])

        <main class="cms-main">
            <header class="glass-card topbar">
                <div class="topbar-left"><div class="topbar-title">Manajemen Ruangan J1</div></div>
            </header>

            <a href="{{ route('admin.rooms') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali ke Daftar Ruangan</a>

            <div class="glass-card form-panel">
                <div style="font-size: 24px; font-weight: 800; margin-bottom: 30px; display: flex; align-items: center; gap: 12px;">
                    <div style="width: 45px; height: 45px; background: rgba(59, 130, 246, 0.1); color: #0ea5e9; border-radius: 12px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-plus"></i></div>
                    Setup Ruangan Baru
                </div>

                <form action="{{ route('admin.rooms.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group" style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px;">
                        <div>
                            <label class="form-label">Nama / Kode Ruangan</label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Lab Komputer J1, Ruang E123..." required>
                        </div>
                        <div>
                            <label class="form-label">Posisi Lantai</label>
                            <select name="lantai" class="form-control" required>
                                <option value="" disabled selected>Pilih Lantai...</option>
                                <option value="1">Lantai 1</option>
                                <option value="2">Lantai 2</option>
                                <option value="3">Lantai 3</option>
                                <option value="4">Lantai 4</option>
                                <option value="5">Lantai 5</option>
                                <option value="6">Lantai 6</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kapasitas Maksimal (Orang)</label>
                        <input type="number" name="capacity" class="form-control" placeholder="Berapa orang yang muat di sini?" required min="1">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status Ruangan</label>
                        <select name="status" class="form-control" required>
                            <option value="available">Ready / Tersedia</option>
                            <option value="maintenance">Ditutup / Maintenance</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-glow mt-4">
                        <i class="fas fa-save"></i> Tambahkan Ruangan ke Sistem
                    </button>
                </form>
            </div>
        </main>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
@endsection