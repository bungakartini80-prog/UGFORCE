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

    /* ── HERO BANNER PORTAL PENDIDIKAN ── */
    .portal-hero {
        position: relative;
        width: 100%;
        background-image: linear-gradient(135deg, rgba(0, 0, 0, 0.35) 0%, rgba(0, 0, 0, 0.5) 100%), url('https://images.unsplash.com/photo-1562774053-701939374585?q=80&w=1200&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        background-blend-mode: normal;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        padding: 40px 60px;
        color: #ffffff;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        margin-bottom: -60px;
        z-index: 1;
    }
    .dark .portal-hero {
        background-image: linear-gradient(135deg, rgba(0, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0.75) 100%), url('https://images.unsplash.com/photo-1562774053-701939374585?q=80&w=1200&auto=format&fit=crop');
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        background-blend-mode: normal;
    }
    .portal-hero-title h2 {
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin: 0 0 8px 0;
    }
    .portal-hero-title p {
        font-size: 15px;
        font-weight: 500;
        opacity: 0.9;
        margin: 0;
    }

    /* ── MAIN LAYOUT SPLIT GRID ── */
    .main-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 32px;
        margin-top: 80px;
        position: relative;
        z-index: 2;
    }
    @media (max-width: 992px) {
        .main-grid {
            grid-template-columns: 1fr;
        }
    }

    /* ── CARD STYLING ── */
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
        -webkit-backdrop-filter: blur(25px);
        border-color: rgba(255, 255, 255, 0.08);
        box-shadow: 0 20px 40px rgba(0,0,0,0.4);
    }

    .card-title {
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid var(--border-soft);
        padding-bottom: 16px;
    }

    /* ── FLOOR TABS SELECTOR ── */
    .floor-selector {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 12px;
        margin-bottom: 24px;
        scrollbar-width: none;
    }
    .floor-selector::-webkit-scrollbar { display: none; }
    
    .floor-btn {
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
    }
    .dark .floor-btn {
        background: rgba(255, 255, 255, 0.04);
        border-color: rgba(255, 255, 255, 0.06);
    }
    .floor-btn.active {
        background: var(--accent-blue);
        border-color: #0ea5e9;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.2);
    }
    .dark .floor-btn.active {
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }

    /* ── ROOMS SELECTION GRID ── */
    .rooms-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 16px;
        max-height: 400px;
        overflow-y: auto;
        padding: 4px;
    }
    .rooms-grid::-webkit-scrollbar { width: 6px; }
    .rooms-grid::-webkit-scrollbar-thumb { background: var(--border-strong); border-radius: 4px; }

    .room-select-card {
        background: #f0f9ff;
        border: 2px solid #e2e8f0;
        border-radius: var(--radius-md);
        cursor: pointer;
        position: relative;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        flex-direction: column;
        gap: 0;
        overflow: hidden;
    }
    .dark .room-select-card {
        background: rgba(255, 255, 255, 0.02);
        border-color: rgba(255, 255, 255, 0.06);
    }
    .room-select-card:hover {
        border-color: #0ea5e9;
        transform: translateY(-2px);
        background: #f0f9ff;
    }
    .dark .room-select-card:hover {
        background: rgba(255, 255, 255, 0.04);
    }

    .room-select-card.selected {
        border-color: #10b981;
        background: #f0fdf4;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.1);
    }
    .dark .room-select-card.selected {
        border-color: #10b981;
        background: rgba(16, 185, 129, 0.08);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
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
        background: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
    }
    .dark .room-select-card .card-check {
        background: #000000;
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
        font-size: 15px;
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

    /* ── FORM CONTROL STYLING ── */
    .form-group {
        margin-bottom: 20px;
    }
    .form-label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: var(--text-secondary);
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .form-control {
        width: 100%;
        padding: 12px 16px;
        font-size: 14px;
        font-weight: 600;
        background: #f0f9ff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        color: var(--text-primary);
        outline: none;
        transition: all 0.3s;
    }
    .dark .form-control {
        background: rgba(255, 255, 255, 0.03);
        border-color: rgba(255, 255, 255, 0.1);
    }
    .form-control:focus {
        border-color: #0ea5e9;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }
    .dark .form-control:focus {
        background: rgba(13, 15, 23, 0.9);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    textarea.form-control {
        resize: none;
        min-height: 100px;
    }

    .btn-submit {
        width: 100%;
        padding: 14px;
        font-size: 14px;
        font-weight: 800;
        color: #ffffff;
        background: linear-gradient(135deg, #0ea5e9 0%, #0ea5e9 100%);
        border: none;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(14, 165, 233, 0.3);
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--text-secondary);
        font-weight: 700;
        text-decoration: none;
        margin-bottom: 24px;
        font-size: 13px;
        transition: color 0.2s, transform 0.2s;
    }
    .btn-back:hover {
        color: #0ea5e9;
        transform: translateX(-4px);
    }

    .selected-room-banner {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .dark .selected-room-banner {
        background: rgba(16, 185, 129, 0.1);
        border-color: rgba(16, 185, 129, 0.2);
    }
    .selected-room-info {
        font-size: 13px;
        font-weight: 700;
        color: #10b981;
    }
</style>

<div class="container py-5" style="max-width: 1100px;">
    <!-- Back to Dashboard -->
    <a href="{{ url('/dashboard') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
    </a>

    <!-- Portal Hero Banner -->
    <div class="portal-hero">
        <div class="portal-hero-title">
            <h2>Pusat Layanan Peminjaman Ruang</h2>
            <p>Silakan pilih ruangan berdasarkan lantai, pilih jadwal kosong, dan kirimkan pengajuan Anda.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('bookings.store') }}">
        @csrf
        <input type="hidden" name="room_id" id="room_id" value="{{ old('room_id', request('room_id')) }}" required>

        <div class="main-grid">
            <!-- LEFT PANEL: FLOOR & ROOM SELECTOR -->
            <div class="portal-card">
                <h3 class="card-title"><i class="bi bi-building"></i> Pilih Ruang Kelas</h3>

                <!-- Selected Room Banner -->
                <div class="selected-room-banner" id="room-banner" style="display: none;">
                    <div class="selected-room-info">
                        <i class="bi bi-check-circle-fill"></i> Ruangan Terpilih: <span id="banner-room-name">Belum ada</span>
                    </div>
                    <span style="font-size: 11px; opacity: 0.8; font-weight: 600;" id="banner-room-cap"></span>
                </div>

                <!-- Floor Select Tabs -->
                <div class="floor-selector">
                    @foreach([1, 2, 3, 4, 5, 6] as $floorNum)
                        <button type="button" class="floor-btn" data-floor="{{ $floorNum }}" onclick="switchFloorTab('{{ $floorNum }}')">
                            Lantai {{ $floorNum }}
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
                                <div class="text-center py-5">
                                    <i class="bi bi-exclamation-circle text-muted" style="font-size: 32px;"></i>
                                    <p class="text-muted mt-2 mb-0" style="font-size: 13px;">Tidak ada ruangan kosong di lantai ini saat ini.</p>
                                </div>
                            @else
                                <div class="rooms-grid">
                                    @foreach($floorRooms as $room)
                                        @php
                                            $isAvailable = $room->status === 'available';
                                        @endphp
                                        <div class="room-select-card {{ !$isAvailable ? 'opacity-60 pointer-events-none' : '' }}" data-room-id="{{ $room->id }}" data-floor="{{ $floorNum }}" data-name="{{ $room->name }}" data-capacity="{{ $room->capacity }}" onclick="{{ $isAvailable ? 'selectRoomCard(this)' : '' }}">
                                            @if($isAvailable)
                                                <i class="bi bi-check-circle-fill card-check"></i>
                                            @else
                                                <span class="absolute top-2 right-2 bg-red-500 text-white text-[9px] font-extrabold px-1.5 py-0.5 rounded shadow z-10">TERISI</span>
                                            @endif
                                            <div class="room-select-image">
                                                <img src="{{ asset('classroom.png') }}" alt="{{ $room->name }}">
                                            </div>
                                            <div class="room-card-info">
                                                <span class="room-card-name">{{ $room->name }}</span>
                                                <span class="room-card-capacity">
                                                    @if($isAvailable)
                                                        <i class="bi bi-people-fill text-blue-500"></i> {{ $room->capacity }} Kursi
                                                    @else
                                                        <i class="bi bi-x-circle-fill text-red-500"></i> Sedang Dipakai
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

            <!-- RIGHT PANEL: JADWAL & DETAIL ACARA -->
            <div class="portal-card">
                <h3 class="card-title"><i class="bi bi-clock-history"></i> Detail Jadwal</h3>

                <div class="form-group">
                    <label class="form-label">Tanggal Acara</label>
                    <input type="date" name="booking_date" class="form-control" value="{{ old('booking_date') }}" required>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="form-label">Jam Mulai</label>
                        <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="form-label">Jam Selesai</label>
                        <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tujuan Peminjaman</label>
                    <textarea name="purpose" class="form-control" placeholder="Contoh: Praktikum Web, Rapat UKM, Ujian Susulan..." required>{{ old('purpose') }}</textarea>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="bi bi-send-fill"></i> Ajukan Peminjaman
                </button>
            </div>
        </div>
    </form>
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
        // Parse pre-selected room
        const preSelectedRoomId = "{{ old('room_id', request('room_id')) }}";
        if (preSelectedRoomId) {
            const card = document.querySelector(`.room-select-card[data-room-id="${preSelectedRoomId}"]`);
            if (card) {
                selectRoomCard(card);
                const floor = card.getAttribute('data-floor');
                switchFloorTab(floor);
            } else {
                switchFloorTab('1');
            }
        } else {
            // Show floor 1 by default
            switchFloorTab('1');
        }
    });
</script>
@endsection