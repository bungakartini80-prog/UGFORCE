@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap');

    /* ── CYBER-CASUAL TOKENS ── */
    :root {
        --bg-main:         #000000;
        --bg-surface:      rgba(13, 15, 23, 0.65);
        --bg-input:        rgba(5, 7, 12, 0.6);
        --border-soft:     rgba(59, 130, 246, 0.15);
        --text-primary:    #ffffff;
        --text-secondary:  #3b82f6;
        --accent-blue:     #3b82f6;
        --radius-lg:       20px;
        --radius-md:       14px;
        --font-sans:       'Plus Jakarta Sans', system-ui, sans-serif;
    }

    html:not(.dark) {
        --bg-main:         #f0f9ff;
        --bg-surface:      rgba(255, 255, 255, 0.85);
        --bg-input:        #ffffff;
        --border-soft:     rgba(14, 165, 233, 0.15);
        --text-primary:    #0f172a;
        --text-secondary:  #0369a1;
        --accent-blue:     #0ea5e9;
    }

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
    .sidebar-header { font-size: 12px; font-weight: 700; color: var(--text-tertiary); margin-bottom: 12px; letter-spacing: 1px; text-transform: uppercase; font-family: var(--font-mono); }
    .sidebar-menus { display: flex; flex-direction: column; gap: 6px; flex: 1; }
    .sidebar-item { display: flex; align-items: center; gap: 14px; padding: 12px 16px; border-radius: 12px; color: var(--text-secondary); text-decoration: none; font-weight: 700; transition: all 0.3s cubic-bezier(0.16,1,0.3,1); border: 1px solid transparent; }
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

    /* MAIN CONTENT */
    .cms-main { 
        flex: 1; display: flex; flex-direction: column; gap: 32px; padding: 32px 40px; max-width: calc(100vw - 280px); 
    }

    .glass-card {
        background: var(--bg-surface); backdrop-filter: blur(25px); -webkit-backdrop-filter: blur(25px);
        border: 1px solid var(--border-soft); border-radius: var(--radius-lg);
        padding: 35px;
    }

    .form-control, .form-select {
        background-color: var(--bg-input) !important;
        border: 1px solid var(--border-soft) !important;
        color: var(--text-primary) !important;
        border-radius: 12px !important;
        padding: 12px 16px !important;
        font-weight: 600;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--accent-blue) !important;
        box-shadow: 0 0 10px rgba(59, 130, 246, 0.2) !important;
    }

    .btn-glow {
        background: linear-gradient(135deg, var(--accent-blue), #2563eb);
        color: #fff !important; font-weight: 700; border-radius: 12px; padding: 14px 24px;
        box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3); border: none;
        transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-glow:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(59, 130, 246, 0.5); }

    /* ── FLOOR TABS SELECTOR ── */
    .floor-selector {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 12px;
        margin-bottom: 16px;
        scrollbar-width: none;
        border-bottom: 1px solid var(--border-soft);
    }
    .floor-selector::-webkit-scrollbar { display: none; }
    
    .floor-btn {
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        background: rgba(59, 130, 246, 0.05);
        border: 1px solid var(--border-soft);
        color: var(--text-secondary);
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    html:not(.dark) .floor-btn {
        background: #f0f9ff;
        border-color: #cbd5e1;
    }
    .floor-btn.active {
        background: var(--accent-blue) !important;
        border-color: var(--accent-blue) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.2);
    }

    /* ── ROOMS SELECTION GRID ── */
    .rooms-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 16px;
        max-height: 320px;
        overflow-y: auto;
        padding: 4px;
    }
    .rooms-grid::-webkit-scrollbar { width: 6px; }
    .rooms-grid::-webkit-scrollbar-thumb { background: rgba(59, 130, 246, 0.2); border-radius: 4px; }

    .room-select-card {
        background: rgba(5, 7, 12, 0.4);
        border: 2px solid var(--border-soft);
        border-radius: var(--radius-md);
        cursor: pointer;
        position: relative;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    html:not(.dark) .room-select-card {
        background: #ffffff;
        border-color: #cbd5e1;
    }
    .room-select-card:hover {
        border-color: var(--accent-blue);
        transform: translateY(-2px);
        background: rgba(59, 130, 246, 0.08);
    }
    html:not(.dark) .room-select-card:hover {
        background: #f0f9ff;
    }

    .room-select-card.selected {
        border-color: #10b981 !important;
        background: rgba(16, 185, 129, 0.08) !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
    }
    html:not(.dark) .room-select-card.selected {
        background: #f0fdf4 !important;
        border-color: #10b981 !important;
    }

    .room-select-card .card-check {
        position: absolute;
        top: 8px;
        right: 8px;
        color: #10b981;
        font-size: 16px;
        opacity: 0;
        transition: opacity 0.2s;
        z-index: 5;
        background: #000000;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
    }
    html:not(.dark) .room-select-card .card-check {
        background: #ffffff;
    }
    .room-select-card.selected .card-check {
        opacity: 1;
    }

    .room-select-image {
        width: 100%;
        height: 80px;
        overflow: hidden;
        position: relative;
        background: #cbd5e1;
    }
    .room-select-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .room-select-card:hover .room-select-image img {
        transform: scale(1.08);
    }

    .room-card-info {
        padding: 12px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .room-card-name {
        font-size: 14px;
        font-weight: 800;
        color: var(--text-primary);
    }
    .room-card-capacity {
        font-size: 11px;
        color: var(--text-secondary);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .selected-room-banner {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.2);
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    html:not(.dark) .selected-room-banner {
        background: #f0fdf4;
        border-color: #bbf7d0;
    }
    .selected-room-info {
        font-size: 13px;
        font-weight: 700;
        color: #10b981;
    }
</style>

<div class="dashboard-layout">
    <!-- SIDEBAR -->
    @include('admin.partials.sidebar', ['activeMenu' => 'schedules'])

    <!-- MAIN CONTENT -->
    <main class="cms-main">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tight">Tambah Jadwal Kuliah Dosen</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium">Buat jadwal pengajaran resmi yang akan terikat dengan data dosen & akan terbuka otomatis via presensi wajah OpenCV.</p>
        </div>

        <div class="glass-card">
            <form method="POST" action="{{ route('admin.schedules.store') }}">
                @csrf
                
                <div class="row g-4">
                    <!-- Select Dosen -->
                    <div class="col-md-6">
                        <label class="form-label font-extrabold text-sm mb-2"><i class="bi bi-person-badge-fill me-1"></i> Pilih Dosen Mengajar</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">-- Pilih Dosen --</option>
                            @foreach($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}">{{ $lecturer->name }} ({{ $lecturer->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Select Ruangan (Visual Grid grouped by Floor with Photos) -->
                    <div class="col-md-6">
                        <label class="form-label font-extrabold text-sm mb-2"><i class="bi bi-door-open-fill me-1"></i> Pilih Ruangan Kelas</label>
                        <input type="hidden" name="room_id" id="room_id" required>

                        <!-- Selected Room Banner -->
                        <div class="selected-room-banner" id="room-banner" style="display: none; padding: 10px; margin-bottom: 12px;">
                            <div class="selected-room-info" style="font-size: 12px;">
                                <i class="bi bi-check-circle-fill"></i> Terpilih: <span id="banner-room-name" class="font-extrabold">Belum ada</span>
                            </div>
                            <span style="font-size: 10px; opacity: 0.8; font-weight: 600;" id="banner-room-cap"></span>
                        </div>

                        <!-- Floor Selector Tabs -->
                        <div class="floor-selector" style="margin-bottom: 12px; gap: 4px; padding-bottom: 8px;">
                            @foreach([1, 2, 3, 4, 5, 6] as $floorNum)
                                <button type="button" class="floor-btn" data-floor="{{ $floorNum }}" onclick="switchFloorTab('{{ $floorNum }}')" style="padding: 6px 12px; font-size: 11px;">
                                    Lt {{ $floorNum }}
                                </button>
                            @endforeach
                        </div>

                        <!-- Rooms Grid List Container -->
                        <div class="rooms-grid-container">
                            @foreach([1, 2, 3, 4, 5, 6] as $floorNum)
                                @php
                                    $floorRooms = $rooms->where('lantai', $floorNum);
                                @endphp
                                <div class="floor-rooms-grid" id="floor-grid-{{ $floorNum }}" style="display: none;">
                                    @if($floorRooms->isEmpty())
                                        <div class="text-center py-4 bg-slate-100/50 dark:bg-white/5 rounded-2xl">
                                            <i class="bi bi-exclamation-circle text-muted text-lg"></i>
                                            <p class="text-muted mt-1 mb-0 text-[10px]">Tidak ada ruangan di lantai ini.</p>
                                        </div>
                                    @else
                                        <div class="rooms-grid" style="max-height: 200px;">
                                            @foreach($floorRooms as $room)
                                                @php
                                                    $isAvailable = $room->status === 'available';
                                                @endphp
                                                <div class="room-select-card" data-room-id="{{ $room->id }}" data-floor="{{ $floorNum }}" data-name="{{ $room->name }}" data-capacity="{{ $room->capacity }}" onclick="selectRoomCard(this)">
                                                    <i class="bi bi-check-circle-fill card-check"></i>
                                                    @if(!$isAvailable)
                                                        <span class="absolute top-1 right-1 bg-amber-500 text-white text-[8px] font-extrabold px-1.5 py-0.5 rounded shadow z-10">DIPAKAI</span>
                                                    @endif
                                                    <div class="room-select-image" style="height: 60px;">
                                                        <img src="{{ asset('classroom.png') }}" alt="{{ $room->name }}">
                                                    </div>
                                                    <div class="room-card-info" style="padding: 8px;">
                                                        <span class="room-card-name" style="font-size: 12px;">{{ $room->name }}</span>
                                                        <span class="room-card-capacity" style="font-size: 10px;">
                                                            <i class="bi bi-people-fill"></i> {{ $room->capacity }} Kursi
                                                            @if(!$isAvailable)
                                                                <span class="text-amber-500 ms-1 font-bold">(Sibuk)</span>
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Mata Kuliah -->
                    <div class="col-md-8">
                        <label class="form-label font-extrabold text-sm mb-2"><i class="bi bi-book-half me-1"></i> Nama Mata Kuliah / Subjek</label>
                        <input type="text" name="subject" class="form-control" placeholder="Contoh: Pemrograman Web & Jaringan" required>
                    </div>

                    <!-- Kelas -->
                    <div class="col-md-4">
                        <label class="form-label font-extrabold text-sm mb-2"><i class="bi bi-tag-fill me-1"></i> Kode / Nama Kelas</label>
                        <input type="text" name="class_name" class="form-control" placeholder="Contoh: 3IA15" required>
                    </div>

                    <!-- Hari -->
                    <div class="col-md-4">
                        <label class="form-label font-extrabold text-sm mb-2"><i class="bi bi-calendar-event me-1"></i> Hari Mengajar</label>
                        <select name="day_of_week" class="form-select" required>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                        </select>
                    </div>

                    <!-- Jam Mulai -->
                    <div class="col-md-4">
                        <label class="form-label font-extrabold text-sm mb-2"><i class="bi bi-clock-fill me-1"></i> Jam Mulai</label>
                        <input type="time" name="start_time" class="form-control" required>
                    </div>

                    <!-- Jam Selesai -->
                    <div class="col-md-4">
                        <label class="form-label font-extrabold text-sm mb-2"><i class="bi bi-clock-fill me-1"></i> Jam Selesai</label>
                        <input type="time" name="end_time" class="form-control" required>
                    </div>
                </div>

                <div class="flex gap-3 justify-end mt-8">
                    <a href="{{ route('admin.schedules') }}" class="btn btn-outline-secondary py-3 px-4 rounded-xl font-bold border-slate-300 dark:border-white/10 text-slate-800 dark:text-white">
                        Batal
                    </a>
                    <button type="submit" class="btn-glow">
                        <i class="bi bi-check-circle-fill"></i> Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
<script>
    // Handle Tab switching for floors
    function switchFloorTab(floorNum) {
        // Toggle tab button active state
        document.querySelectorAll('.floor-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        const activeBtn = document.querySelector(`.floor-btn[data-floor="${floorNum}"]`);
        if (activeBtn) activeBtn.classList.add('active');

        // Toggle room grids
        document.querySelectorAll('.floor-rooms-grid').forEach(grid => {
            grid.style.display = 'none';
        });
        const activeGrid = document.getElementById(`floor-grid-${floorNum}`);
        if (activeGrid) activeGrid.style.display = 'block';
    }

    // Handle selecting a room card
    function selectRoomCard(element) {
        const roomId = element.getAttribute('data-room-id');
        const roomName = element.getAttribute('data-name');
        const roomCapacity = element.getAttribute('data-capacity');

        // Update hidden input
        document.getElementById('room_id').value = roomId;

        // Remove selection styles from all cards
        document.querySelectorAll('.room-select-card').forEach(card => {
            card.classList.remove('selected');
        });

        // Add selection styles to clicked card
        element.classList.add('selected');

        // Update selected room banner info
        const banner = document.getElementById('room-banner');
        const bannerName = document.getElementById('banner-room-name');
        const bannerCap = document.getElementById('banner-room-cap');
        
        bannerName.textContent = roomName;
        bannerCap.textContent = `Kapasitas ${roomCapacity} Orang`;
        banner.style.display = 'flex';
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Show floor 1 by default
        switchFloorTab('1');
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
@endsection
