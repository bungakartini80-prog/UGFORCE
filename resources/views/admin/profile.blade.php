@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap');
    :root {
        --bg-main:#000; --bg-surface:rgba(13,15,23,.65); --bg-input:rgba(5,7,12,.6);
        --border-soft:rgba(59,130,246,.15); --border-strong:rgba(59,130,246,.35);
        --text-primary:#fff; --text-secondary:#3b82f6; --text-tertiary:#0369a1;
        --accent-blue:#3b82f6; --success:#10b981; --warning:#f59e0b; --danger:#ef4444;
        --radius-lg:20px; --font-sans:'Plus Jakarta Sans',sans-serif; --font-mono:'JetBrains Mono',monospace;
        --shadow-card:0 10px 40px rgba(0,0,0,.5); --shadow-glow:0 0 25px rgba(59,130,246,.15);
    }
    html:not(.dark) {
        --bg-main:#f0f9ff; --bg-surface:rgba(255,255,255,.85); --bg-input:#fff;
        --border-soft:rgba(14,165,233,.15); --border-strong:rgba(14,165,233,.35);
        --text-primary:#0f172a; --text-secondary:#0369a1; --accent-blue:#0ea5e9;
        --shadow-card:0 10px 40px rgba(14,165,233,.08); --shadow-glow:0 5px 20px rgba(14,165,233,.15);
    }
    *,*::before,*::after{box-sizing:border-box;} body{margin:0;padding:0;}
    @keyframes spin-slow{100%{transform:rotate(360deg);}}
    @keyframes float-up{0%{opacity:0;transform:translateY(30px) scale(.98);}100%{opacity:1;transform:translateY(0) scale(1);}}
    @keyframes floatOrb{0%{transform:translate(0,0) scale(1);}50%{transform:translate(6vw,-5vh) scale(1.1);}100%{transform:translate(0,0) scale(1);}}
    .animate-float{animation:float-up .8s cubic-bezier(.16,1,.3,1) forwards;opacity:0;}
    .d-1{animation-delay:.1s;} .d-2{animation-delay:.2s;} .d-3{animation-delay:.3s;}
    .ambient-bg{position:fixed;inset:0;z-index:-5;background:var(--bg-main);overflow:hidden;transition:background .5s;}
    .ambient-orb{position:absolute;border-radius:50%;filter:blur(100px);opacity:.35;animation:floatOrb 25s infinite alternate;pointer-events:none;}
    html:not(.dark) .ambient-orb{opacity:.5;mix-blend-mode:hard-light;}
    .orb-1{width:55vw;height:55vw;max-width:800px;max-height:800px;background:var(--accent-blue);top:-10%;left:-10%;}
    .orb-2{width:45vw;height:45vw;max-width:700px;max-height:700px;background:#3b82f6;bottom:-10%;right:-5%;animation-delay:-5s;}
    .orb-3{width:35vw;height:35vw;max-width:600px;max-height:600px;background:var(--warning);top:30%;left:30%;animation-delay:-10s;opacity:.12;}
    html:not(.dark) .orb-1{background:rgba(59,130,246,.4);}
    html:not(.dark) .orb-2{background:rgba(59,130,246,.3);}
    html:not(.dark) .orb-3{background:rgba(245,158,11,.3);opacity:.18;}
    #ug-canvas{position:fixed;inset:0;z-index:0;pointer-events:none;opacity:.4;}
</style>
<style>
    .dashboard-layout{position:relative;z-index:2;width:100%;min-height:100vh;display:flex;font-family:var(--font-sans);color:var(--text-primary);}
    .cms-sidebar{width:280px;flex-shrink:0;background:var(--bg-surface);backdrop-filter:blur(35px);border-right:1px solid var(--border-soft);display:flex;flex-direction:column;position:sticky;top:0;height:100vh;padding:24px 20px;z-index:50;overflow-y:auto;scrollbar-width:none;}
    .cms-sidebar::-webkit-scrollbar{display:none;}
    .sidebar-brand{display:flex;align-items:center;gap:12px;margin-bottom:30px;padding-bottom:24px;border-bottom:1px dashed var(--border-soft);font-size:24px;font-weight:800;}
    .sidebar-profile{display:flex;align-items:center;gap:16px;margin-bottom:30px;}
    .sidebar-avatar{width:50px;height:50px;border-radius:50%;background:linear-gradient(135deg,var(--danger),var(--accent-blue));display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:800;color:#fff;position:relative;box-shadow:0 0 15px rgba(239,68,68,.4);flex-shrink:0;overflow:hidden;}
    .sidebar-avatar img{width:100%;height:100%;object-fit:cover;border-radius:50%;}
    .sidebar-avatar::before{content:'';position:absolute;inset:-4px;border-radius:50%;border:2px dashed var(--danger);animation:spin-slow 10s linear infinite;opacity:.2;}
    .sidebar-greeting{font-size:12px;color:var(--text-secondary);font-weight:600;text-transform:uppercase;margin-bottom:2px;}
    .sidebar-name{font-size:16px;font-weight:800;color:var(--text-primary);}
    .sidebar-header{font-size:12px;font-weight:700;color:var(--text-tertiary);margin-bottom:12px;letter-spacing:1px;text-transform:uppercase;font-family:var(--font-mono);}
    .sidebar-menus{display:flex;flex-direction:column;gap:6px;flex:1;}
    .sidebar-item{display:flex;align-items:center;gap:14px;padding:12px 16px;border-radius:12px;text-decoration:none;transition:all .3s;color:var(--text-secondary);border:1px solid transparent;}
    .sidebar-item.active{background:var(--bg-input);border-color:var(--border-soft);color:var(--text-primary);border-left:4px solid var(--accent-blue);}
    .sidebar-item:hover{border-color:#0ea5e9;background:rgba(59,130,246,.05);color:var(--text-primary);transform:translateX(6px);}
    html:not(.dark) .sidebar-item:hover{background:rgba(14,165,233,.05);}
    .sidebar-item i.icon{font-size:16px;width:24px;text-align:center;color:var(--text-tertiary);}
    .sidebar-item.active i.icon{color:#0ea5e9;}
    .sidebar-item:hover i.icon{color:#0ea5e9;transform:scale(1.1);}
    .sidebar-item .title{font-size:14px;font-weight:700;flex:1;}
    .logout-box{margin-top:auto;padding-top:24px;}
    .btn-logout{display:flex;align-items:center;gap:12px;padding:14px 16px;border-radius:14px;background:rgba(239,68,68,.1);color:var(--danger);font-size:14px;font-weight:700;width:100%;cursor:pointer;transition:all .3s;border:none;}
    .btn-logout:hover{background:var(--danger);color:#fff;transform:translateY(-2px);}
    .cms-main{flex:1;display:flex;flex-direction:column;gap:24px;padding:24px 32px;max-width:calc(100vw - 280px);}
    .glass-card{background:var(--bg-surface);backdrop-filter:blur(25px);border:1px solid var(--border-soft);border-radius:var(--radius-lg);box-shadow:var(--shadow-card);position:relative;overflow:hidden;transition:border-color .4s,box-shadow .4s;}
    .glass-card:hover{border-color:var(--border-strong);box-shadow:var(--shadow-glow);}
    .glass-card::before{content:'';position:absolute;inset:0;background:radial-gradient(600px circle at var(--mouse-x,-500px) var(--mouse-y,-500px),rgba(59,130,246,.08),transparent 40%);z-index:0;pointer-events:none;opacity:0;transition:opacity .3s;}
    html:not(.dark) .glass-card::before{background:radial-gradient(800px circle at var(--mouse-x,-500px) var(--mouse-y,-500px),rgba(14,165,233,.12),transparent 45%);}
    .glass-card:hover::before{opacity:1;}
    .topbar{display:flex;justify-content:space-between;align-items:center;padding:18px 28px;border-radius:16px;}
    .topbar::before{content:'';position:absolute;inset:0;background:linear-gradient(90deg,var(--bg-main) 0%,transparent 100%);z-index:1;opacity:.9;pointer-events:none;}
    html:not(.dark) .topbar::before{background:linear-gradient(90deg,rgba(239,246,255,.95) 0%,rgba(239,246,255,.3) 100%);}
    .topbar>*{position:relative;z-index:2;}
    .topbar-left{display:flex;align-items:center;gap:16px;}
    .topbar-title{font-size:18px;font-weight:800;display:flex;align-items:center;gap:12px;}
    .topbar-right{display:flex;align-items:center;gap:24px;}
    .clock-display{font-family:var(--font-mono);font-size:13px;font-weight:700;padding:8px 16px;border-radius:10px;border:1px dashed var(--border-soft);background:var(--bg-input);}
    .top-profile{display:flex;align-items:center;gap:12px;padding:6px 12px;border-radius:12px;}
    .top-profile span{font-size:14px;font-weight:700;}
    @media(max-width:1024px){.dashboard-layout{flex-direction:column;}.cms-sidebar{width:100%;height:auto;position:relative;border-right:none;border-bottom:1px dashed var(--border-soft);}.sidebar-menus{flex-direction:row;flex-wrap:wrap;}.sidebar-item{flex:1;min-width:150px;}.cms-main{max-width:100%;padding:20px 16px;}}
</style>
<style>
    .profile-grid{display:grid;grid-template-columns:1fr 1.4fr;gap:24px;}
    @media(max-width:900px){.profile-grid{grid-template-columns:1fr;}}
    .profile-card{padding:32px;z-index:2;display:flex;flex-direction:column;align-items:center;gap:20px;}
    .avatar-ring{position:relative;width:160px;height:160px;flex-shrink:0;}
    .avatar-ring::before{content:'';position:absolute;inset:-6px;border-radius:50%;border:2px dashed var(--accent-blue);animation:spin-slow 12s linear infinite;opacity:.4;}
    .avatar-ring::after{content:'';position:absolute;inset:-12px;border-radius:50%;border:1px dashed var(--accent-blue);animation:spin-slow 20s linear infinite reverse;opacity:.2;}
    .avatar-img{width:160px;height:160px;border-radius:50%;object-fit:cover;border:3px solid var(--accent-blue);box-shadow:0 0 30px rgba(59,130,246,.3);display:block;}
    .avatar-placeholder{width:160px;height:160px;border-radius:50%;background:linear-gradient(135deg,var(--danger),var(--accent-blue));display:flex;align-items:center;justify-content:center;font-size:52px;font-weight:800;color:#fff;border:3px solid var(--accent-blue);box-shadow:0 0 30px rgba(59,130,246,.3);}
    .avatar-badge{position:absolute;bottom:6px;right:6px;width:32px;height:32px;border-radius:50%;background:var(--success);border:3px solid var(--bg-surface);display:flex;align-items:center;justify-content:center;font-size:12px;color:#fff;z-index:3;}
    .avatar-badge.no-photo{background:var(--warning);}
    .profile-name{font-size:22px;font-weight:800;color:var(--text-primary);text-align:center;}
    .profile-role{font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:2px;color:var(--warning);background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);padding:4px 14px;border-radius:20px;}
    .profile-email{font-size:13px;color:var(--text-secondary);font-family:var(--font-mono);}
    .btn-clear{background:rgba(239,68,68,.1);color:var(--danger);border:1px solid rgba(239,68,68,.3);padding:10px 20px;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;transition:all .3s;display:inline-flex;align-items:center;gap:8px;}
    .btn-clear:hover{background:var(--danger);color:#fff;transform:translateY(-2px);}
    .camera-panel{padding:28px;z-index:2;}
    .panel-title{font-size:18px;font-weight:800;color:var(--text-primary);display:flex;align-items:center;gap:10px;margin-bottom:20px;}
    .camera-container{position:relative;width:100%;aspect-ratio:4/3;background:#000;border-radius:16px;overflow:hidden;border:2px solid var(--border-strong);}
    #profile-video{width:100%;height:100%;object-fit:cover;display:block;}
    #profile-canvas{position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:5;}
    .camera-overlay-text{position:absolute;bottom:12px;left:50%;transform:translateX(-50%);background:rgba(0,0,0,.7);color:#00ff00;font-family:var(--font-mono);font-size:11px;font-weight:700;padding:4px 12px;border-radius:6px;white-space:nowrap;z-index:6;}
    .camera-placeholder{width:100%;aspect-ratio:4/3;background:var(--bg-input);border-radius:16px;border:2px dashed var(--border-soft);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;color:var(--text-secondary);}
    .camera-placeholder i{font-size:48px;opacity:.3;}
    .camera-placeholder p{font-size:14px;font-weight:600;opacity:.6;margin:0;}
    .btn-row{display:flex;gap:12px;flex-wrap:wrap;margin-top:16px;}
    .btn-primary{background:linear-gradient(135deg,#3b82f6,#0ea5e9);color:#fff;border:none;padding:12px 20px;border-radius:12px;font-size:14px;font-weight:700;cursor:pointer;transition:all .3s;display:inline-flex;align-items:center;gap:8px;flex:1;justify-content:center;}
    .btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(59,130,246,.4);}
    .btn-primary:disabled{opacity:.5;cursor:not-allowed;transform:none;}
    .btn-success{background:linear-gradient(135deg,#10b981,#059669);color:#fff;border:none;padding:12px 20px;border-radius:12px;font-size:14px;font-weight:700;cursor:pointer;transition:all .3s;display:inline-flex;align-items:center;gap:8px;flex:1;justify-content:center;}
    .btn-success:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(16,185,129,.4);}
    .btn-success:disabled{opacity:.5;cursor:not-allowed;transform:none;}
    .btn-secondary{background:var(--bg-input);color:var(--text-secondary);border:1px solid var(--border-soft);padding:12px 20px;border-radius:12px;font-size:14px;font-weight:700;cursor:pointer;transition:all .3s;display:inline-flex;align-items:center;gap:8px;}
    .btn-secondary:hover{border-color:var(--accent-blue);color:var(--text-primary);}
    .terminal-log{background:#000;border:1px solid rgba(0,255,0,.2);border-radius:12px;padding:14px 16px;font-family:var(--font-mono);font-size:11px;color:#00ff00;height:120px;overflow-y:auto;margin-top:16px;scrollbar-width:thin;scrollbar-color:rgba(0,255,0,.3) transparent;}
    html:not(.dark) .terminal-log{background:#0a0a0a;}
    .terminal-log::-webkit-scrollbar{width:4px;}
    .terminal-log::-webkit-scrollbar-thumb{background:rgba(0,255,0,.3);border-radius:4px;}
    .progress-wrap{margin-top:12px;display:none;}
    .progress-label{font-size:11px;font-family:var(--font-mono);font-weight:700;color:var(--text-secondary);margin-bottom:6px;}
    .progress-track{width:100%;height:6px;background:var(--bg-input);border-radius:6px;overflow:hidden;}
    .progress-fill{height:100%;width:0%;background:linear-gradient(90deg,#3b82f6,#00ff00);border-radius:6px;transition:width .1s;}
    .captured-preview{display:none;margin-top:16px;}
    .captured-preview img{width:100%;border-radius:12px;border:2px solid var(--success);box-shadow:0 0 20px rgba(16,185,129,.3);}
    .captured-label{font-size:12px;font-weight:700;color:var(--success);margin-bottom:8px;display:flex;align-items:center;gap:6px;}
    .server-status{display:flex;align-items:center;gap:8px;font-size:12px;font-family:var(--font-mono);font-weight:700;padding:8px 14px;border-radius:8px;margin-bottom:12px;}
    .server-status.ok{background:rgba(16,185,129,.1);color:var(--success);border:1px solid rgba(16,185,129,.3);}
    .server-status.err{background:rgba(239,68,68,.1);color:var(--danger);border:1px solid rgba(239,68,68,.3);}
    .server-status.checking{background:rgba(245,158,11,.1);color:var(--warning);border:1px solid rgba(245,158,11,.3);}
</style>

<div class="ambient-bg"><div class="ambient-orb orb-1"></div><div class="ambient-orb orb-2"></div><div class="ambient-orb orb-3"></div></div>
<canvas id="ug-canvas"></canvas>

<div class="dashboard-layout">
    @include('admin.partials.sidebar', ['activeMenu' => 'profile'])

    <main class="cms-main">
        <header class="glass-card topbar animate-float d-1">
            <div class="topbar-left">
                <button class="sidebar-toggle-btn" aria-label="Buka Menu"><i class="fas fa-bars"></i></button>
                <div class="topbar-title hidden md:flex"><i class="fas fa-user-circle" style="color:#0ea5e9;"></i> Profil Admin</div>
            </div>
            <div class="topbar-right">
                <div class="clock-display hidden lg:block" id="ug-clock">--:--:-- WIB</div>
                <div class="top-profile"><span>{{ Auth::user()->name ?? 'Admin' }}</span></div>
            </div>
        </header>

        @if(session('success'))
        <div class="glass-card p-3 animate-float d-2" style="background:rgba(16,185,129,.1);border-color:rgba(16,185,129,.3);color:var(--success);font-weight:700;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
        @endif

        <div class="profile-grid animate-float d-2">
            {{-- KIRI: KARTU PROFIL --}}
            <div class="glass-card profile-card">
                <div class="avatar-ring">
                    @if($user->avatar)
                        <img id="current-avatar-img" class="avatar-img" src="{{ $user->avatar }}" alt="Foto Profil">
                        <div class="avatar-badge"><i class="fas fa-check"></i></div>
                    @else
                        <div class="avatar-placeholder">{{ strtoupper(substr($user->name ?? 'AD', 0, 2)) }}</div>
                        <div class="avatar-badge no-photo"><i class="fas fa-camera"></i></div>
                    @endif
                </div>
                <div class="text-center">
                    <div class="profile-name">{{ $user->name }}</div>
                    <div class="profile-role mt-2">Administrator</div>
                    <div class="profile-email mt-2">{{ $user->email }}</div>
                </div>
                <div style="width:100%;border-top:1px dashed var(--border-soft);padding-top:16px;display:flex;flex-direction:column;gap:10px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;">
                        <span style="color:var(--text-secondary);font-weight:600;">Foto Profil</span>
                        <span style="font-weight:700;color:{{ $user->avatar ? 'var(--success)' : 'var(--warning)' }};">{{ $user->avatar ? '✓ Terpasang' : '✗ Belum Ada' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;">
                        <span style="color:var(--text-secondary);font-weight:600;">Biometrik Wajah</span>
                        <span style="font-weight:700;color:{{ $user->face_signature ? 'var(--success)' : 'var(--warning)' }};">{{ $user->face_signature ? '✓ Terdaftar' : '✗ Belum Ada' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;">
                        <span style="color:var(--text-secondary);font-weight:600;">Role</span>
                        <span style="font-weight:700;color:var(--warning);">Admin</span>
                    </div>
                </div>
                @if($user->avatar || $user->face_signature)
                <form action="{{ route('admin.profile.clear-avatar') }}" method="POST" onsubmit="return confirm('Yakin hapus foto profil dan data biometrik?')">
                    @csrf
                    <button type="submit" class="btn-clear" style="width:100%;justify-content:center;">
                        <i class="fas fa-trash-alt"></i> Hapus Foto & Reset Biometrik
                    </button>
                </form>
                @endif
            </div>

            {{-- KANAN: KAMERA DEEPFACE --}}
            <div class="glass-card camera-panel">
                <div class="panel-title">
                    <div style="width:40px;height:40px;background:rgba(59,130,246,.1);color:#0ea5e9;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-camera"></i>
                    </div>
                    Ambil Foto Profil (DeepFace + RetinaFace)
                </div>

                {{-- Server Status --}}
                <div id="server-status" class="server-status checking">
                    <i class="fas fa-circle-notch fa-spin"></i> Mengecek DeepFace server...
                </div>

                {{-- Mode Toggle --}}
                <div style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap;">
                    <button onclick="setMode('dark')" class="btn-secondary" style="font-size:12px;padding:8px 14px;">
                        <i class="fas fa-moon"></i> Mode Gelap
                    </button>
                    <button onclick="setMode('light')" class="btn-secondary" style="font-size:12px;padding:8px 14px;">
                        <i class="fas fa-sun"></i> Mode Cerah
                    </button>
                    <span id="mode-badge" style="padding:6px 14px;border-radius:8px;font-size:11px;font-weight:700;font-family:var(--font-mono);background:rgba(59,130,246,.1);color:#0ea5e9;border:1px solid rgba(59,130,246,.3);display:flex;align-items:center;gap:6px;">
                        <i class="fas fa-circle" style="font-size:8px;"></i> AUTO
                    </span>
                </div>

                {{-- Camera View --}}
                <div id="camera-container" class="camera-container" style="display:none;">
                    <video id="profile-video" autoplay playsinline muted></video>
                    <canvas id="profile-canvas"></canvas>
                    <div class="camera-overlay-text" id="camera-status-text">DeepFace: READY</div>
                </div>
                <div id="camera-placeholder" class="camera-placeholder">
                    <i class="fas fa-camera-retro"></i>
                    <p>Klik "Aktifkan Kamera" untuk mulai</p>
                    <p style="font-size:11px;font-family:var(--font-mono);opacity:.4;">DeepFace RetinaFace Detection</p>
                </div>

                {{-- Captured Preview --}}
                <div class="captured-preview" id="captured-preview">
                    <div class="captured-label"><i class="fas fa-check-circle"></i> Foto Berhasil Diambil</div>
                    <img id="captured-img" src="" alt="Captured">
                </div>

                {{-- Progress --}}
                <div class="progress-wrap" id="progress-wrap">
                    <div class="progress-label" id="progress-label">PROCESSING: 0%</div>
                    <div class="progress-track"><div class="progress-fill" id="progress-fill"></div></div>
                </div>

                {{-- Terminal Log --}}
                <div class="terminal-log" id="terminal-log">
                    <div style="color:#666;">[SYSTEM] UGFORCE DeepFace Camera v3.0</div>
                    <div style="color:#666;">[SYSTEM] Engine: RetinaFace + Facenet512</div>
                </div>

                {{-- Buttons --}}
                <div class="btn-row">
                    <button id="btn-start-cam" class="btn-primary" onclick="startCamera()">
                        <i class="fas fa-video"></i> Aktifkan Kamera
                    </button>
                    <button id="btn-capture" class="btn-success" onclick="capturePhoto()" disabled>
                        <i class="fas fa-camera"></i> Ambil Foto
                    </button>
                    <button id="btn-stop-cam" class="btn-secondary" onclick="stopCamera()" style="display:none;">
                        <i class="fas fa-stop"></i> Stop
                    </button>
                </div>
                <div class="btn-row" id="save-row" style="display:none;">
                    <button id="btn-save" class="btn-success" onclick="savePhoto()">
                        <i class="fas fa-save"></i> Simpan Foto Profil
                    </button>
                    <button class="btn-secondary" onclick="retakePhoto()">
                        <i class="fas fa-redo"></i> Ambil Ulang
                    </button>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<script>
/* ================================================================
   UGFORCE Profile Camera — DeepFace Python API, NO mirror, 60fps
   ================================================================ */
const FACE_API     = 'http://127.0.0.1:5001';
const FRAME_MS     = 1000 / 60;
const API_INTERVAL = 120;   // ms between API calls (~8/sec)
const DET_W = 320, DET_H = 240;

let localStream     = null;
let capturedDataUrl = null;
let isDrawing       = false;
let animFrameId     = null;
let lastFrameTime   = 0;
let elapsed         = FRAME_MS;
let scanPhase       = 0;

let faceX=0, faceY=0, faceW=0, faceH=0;
let tgtX=0,  tgtY=0,  tgtW=0,  tgtH=0;
let faceDetected = false;
let noFaceFrames = 0;

let detCanvas, detCtx;
let lastApiCall = 0;
let apiPending  = false;

/* ── Logging ── */
function addLog(text, type) {
    const log = document.getElementById('terminal-log');
    if (!log) return;
    const el  = document.createElement('div');
    const now = new Date().toLocaleTimeString();
    const c   = {info:'#00ff00', success:'#00ffff', error:'#ff4444', warn:'#ffaa00'};
    el.style.color   = c[type] || '#00ff00';
    el.innerHTML     = '<span style="color:#444;">[' + now + ']</span> ' + text;
    log.appendChild(el);
    log.scrollTop = log.scrollHeight;
}

/* ── Theme toggle ── */
function setMode(mode) {
    const html  = document.getElementById('html-root');
    const badge = document.getElementById('mode-badge');
    if (mode === 'dark') {
        html.classList.add('dark'); localStorage.theme = 'dark';
        badge.innerHTML = '<i class="fas fa-moon" style="font-size:8px;"></i> MODE GELAP';
        badge.style.color = '#818cf8'; badge.style.borderColor = 'rgba(129,140,248,.3)'; badge.style.background = 'rgba(129,140,248,.1)';
        addLog('Mode Gelap aktif.', 'warn');
    } else {
        html.classList.remove('dark'); localStorage.theme = 'light';
        badge.innerHTML = '<i class="fas fa-sun" style="font-size:8px;"></i> MODE CERAH';
        badge.style.color = '#f59e0b'; badge.style.borderColor = 'rgba(245,158,11,.3)'; badge.style.background = 'rgba(245,158,11,.1)';
        addLog('Mode Cerah aktif.', 'warn');
    }
}

/* ── Check Python server ── */
async function checkServer() {
    const el = document.getElementById('server-status');
    try {
        const r = await fetch(FACE_API + '/health', { signal: AbortSignal.timeout(3000) });
        const d = await r.json();
        if (d.status === 'ok') {
            el.className = 'server-status ok';
            el.innerHTML = '<i class="fas fa-circle"></i> DeepFace server aktif — ' + d.engine;
            addLog('[OK] DeepFace server aktif: ' + d.engine, 'success');
            return true;
        }
    } catch (_) {}
    el.className = 'server-status err';
    el.innerHTML = '<i class="fas fa-exclamation-circle"></i> DeepFace server tidak aktif (port 5001)';
    addLog('ERROR: DeepFace server tidak aktif. Jalankan: py -3.11 python/face_server.py', 'error');
    return false;
}

/* ── Start camera ── */
async function startCamera() {
    detCanvas = document.createElement('canvas');
    detCanvas.width = DET_W; detCanvas.height = DET_H;
    detCtx = detCanvas.getContext('2d', { willReadFrequently: true });

    addLog('Menginisialisasi kamera WebRTC...', 'warn');
    const serverOk = await checkServer();

    try {
        localStream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'user', width: { ideal: 1280 }, height: { ideal: 720 }, frameRate: { ideal: 60 } }
        });
        const video = document.getElementById('profile-video');
        video.srcObject = localStream;
        await video.play();

        document.getElementById('camera-container').style.display  = 'block';
        document.getElementById('camera-placeholder').style.display = 'none';
        document.getElementById('btn-start-cam').style.display     = 'none';
        document.getElementById('btn-stop-cam').style.display      = 'flex';
        document.getElementById('btn-capture').disabled            = false;

        addLog('[OK] Kamera terhubung @ 60fps.', 'success');
        if (serverOk) addLog('RetinaFace detector siap.', 'success');
        else          addLog('WARN: Tanpa Python server — kotak wajah tidak aktif.', 'warn');

        isDrawing     = true;
        lastFrameTime = performance.now();
        animFrameId   = requestAnimationFrame(drawLoop);
    } catch (err) {
        addLog('ERROR: Kamera tidak dapat diakses — ' + err.message, 'error');
    }
}

/* ── Stop camera ── */
function stopCamera() {
    isDrawing = false;
    if (animFrameId) { cancelAnimationFrame(animFrameId); animFrameId = null; }
    if (localStream)  { localStream.getTracks().forEach(function(t){ t.stop(); }); localStream = null; }
    var cv = document.getElementById('profile-canvas');
    if (cv) cv.getContext('2d').clearRect(0, 0, cv.width, cv.height);
    document.getElementById('camera-container').style.display  = 'none';
    document.getElementById('camera-placeholder').style.display = 'flex';
    document.getElementById('btn-start-cam').style.display     = 'flex';
    document.getElementById('btn-stop-cam').style.display      = 'none';
    document.getElementById('btn-capture').disabled            = true;
    addLog('Kamera dihentikan.', 'warn');
}

/* ── Send frame to DeepFace API (non-blocking) ── */
function sendFrameToApi(video) {
    if (apiPending) return;
    var now = performance.now();
    if (now - lastApiCall < API_INTERVAL) return;
    if (!video || video.readyState < video.HAVE_ENOUGH_DATA) return;
    lastApiCall = now;
    apiPending  = true;

    /* Draw frame — NO mirror, natural direction */
    detCtx.drawImage(video, 0, 0, DET_W, DET_H);
    var b64 = detCanvas.toDataURL('image/jpeg', 0.7);

    fetch(FACE_API + '/detect', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ image: b64 }),
        signal:  AbortSignal.timeout(3000)
    })
    .then(function(r){ return r.json(); })
    .then(function(data) {
        apiPending = false;
        var vw = video.videoWidth  || 1280;
        var vh = video.videoHeight || 720;
        var sx = vw / DET_W, sy = vh / DET_H;

        if (data.detected && data.faces && data.faces.length > 0) {
            var best = data.faces.reduce(function(a,b){ return b.confidence > a.confidence ? b : a; });
            noFaceFrames = 0; faceDetected = true;

            /* Scale back to video coords */
            var rx = best.x * sx, ry = best.y * sy;
            var rw = best.w * sx, rh = best.h * sy;

            /* Crop ke area kepala saja:
               - Ambil hanya 65% tinggi dari atas (buang bahu/leher bawah)
               - Padding horizontal sangat kecil: 4%
               - Padding atas: 12% (sedikit ruang di atas kepala)
               - Tidak expand ke bawah sama sekali */
            rh = rh * 0.65;           /* potong 35% bawah (leher/bahu) */
            var padX = rw * 0.04;     /* 4% kiri-kanan */
            var padTop = rh * 0.12;   /* 12% ruang atas kepala */
            rx = Math.max(0, rx - padX);
            ry = Math.max(0, ry - padTop);
            rw = Math.min(vw - rx, rw + padX * 2);
            rh = Math.min(vh - ry, rh + padTop);

            /* Hard clamp: max 38% lebar, max 55% tinggi frame */
            var maxW = vw * 0.38;
            var maxH = vh * 0.55;
            if (rw > maxW) { var diff = (rw - maxW) / 2; rx += diff; rw = maxW; }
            if (rh > maxH) { rh = maxH; }

            tgtX = rx; tgtY = ry;
            tgtW = rw; tgtH = rh;
        } else {
            noFaceFrames++;
            if (noFaceFrames > 25) {
                faceDetected = false;
                tgtX = vw * 0.38; tgtY = vh * 0.18;
                tgtW = vw * 0.24; tgtH = vh * 0.40;
            }
        }
    })
    .catch(function(){ apiPending = false; });
}

function lerp(a, b, t) { return a + (b - a) * t; }

/* ── Main 60fps draw loop ── */
function drawLoop(timestamp) {
    if (!isDrawing) return;
    elapsed = timestamp - lastFrameTime;
    if (elapsed < FRAME_MS - 1) { animFrameId = requestAnimationFrame(drawLoop); return; }
    lastFrameTime = timestamp - (elapsed % FRAME_MS);

    var video  = document.getElementById('profile-video');
    var canvas = document.getElementById('profile-canvas');
    var statusText = document.getElementById('camera-status-text');
    if (!canvas) { animFrameId = requestAnimationFrame(drawLoop); return; }

    var ctx = canvas.getContext('2d');
    var vw  = video.videoWidth  || 1280;
    var vh  = video.videoHeight || 720;

    if (canvas.width !== vw || canvas.height !== vh) {
        canvas.width = vw; canvas.height = vh;
        if (!tgtW) { tgtX=vw*.38; tgtY=vh*.18; tgtW=vw*.24; tgtH=vh*.40; }
        if (!faceW) { faceX=tgtX; faceY=tgtY; faceW=tgtW; faceH=tgtH; }
    }
    ctx.clearRect(0, 0, vw, vh);

    sendFrameToApi(video);

    var ls = faceDetected ? 0.22 : 0.04;
    faceX = lerp(faceX, tgtX, ls); faceY = lerp(faceY, tgtY, ls);
    faceW = lerp(faceW, tgtW, ls); faceH = lerp(faceH, tgtH, ls);

    var bx=faceX, by=faceY, bw=faceW, bh=faceH;
    var col  = faceDetected ? '#00ff00' : '#ffaa00';
    var colD = faceDetected ? 'rgba(0,255,0,.25)'  : 'rgba(255,170,0,.25)';
    var colF = faceDetected ? 'rgba(0,255,0,.04)'  : 'rgba(255,170,0,.04)';

    /* 3D perspective box */
    var yaw   = ((bx+bw/2) - vw/2) / (vw/2);
    var pitch = ((by+bh/2) - vh/2) / (vh/2);
    var depth = Math.min(bw,bh) * 0.16;
    var ox = yaw*depth*0.8, oy = pitch*depth*0.6;
    var f = [{x:bx,y:by},{x:bx+bw,y:by},{x:bx+bw,y:by+bh},{x:bx,y:by+bh}];
    var k = [{x:bx-ox,y:by-oy},{x:bx+bw-ox,y:by-oy},{x:bx+bw-ox,y:by+bh-oy},{x:bx-ox,y:by+bh-oy}];

    ctx.strokeStyle=colD; ctx.lineWidth=1;
    ctx.beginPath(); ctx.moveTo(k[0].x,k[0].y); ctx.lineTo(k[1].x,k[1].y); ctx.lineTo(k[2].x,k[2].y); ctx.lineTo(k[3].x,k[3].y); ctx.closePath(); ctx.stroke();
    for (var i=0;i<4;i++){ctx.beginPath();ctx.moveTo(f[i].x,f[i].y);ctx.lineTo(k[i].x,k[i].y);ctx.stroke();}
    ctx.fillStyle=colF;
    ctx.beginPath();ctx.moveTo(f[0].x,f[0].y);ctx.lineTo(f[1].x,f[1].y);ctx.lineTo(k[1].x,k[1].y);ctx.lineTo(k[0].x,k[0].y);ctx.closePath();ctx.fill();
    ctx.beginPath();ctx.moveTo(f[1].x,f[1].y);ctx.lineTo(f[2].x,f[2].y);ctx.lineTo(k[2].x,k[2].y);ctx.lineTo(k[1].x,k[1].y);ctx.closePath();ctx.fill();
    ctx.fillStyle=colF; ctx.fillRect(bx,by,bw,bh);
    ctx.strokeStyle=col; ctx.lineWidth=2; ctx.strokeRect(bx,by,bw,bh);

    /* Corner brackets */
    var cl = Math.min(bw,bh)*0.11;
    ctx.fillStyle=col;
    ctx.fillRect(bx-1,by-1,cl,2.5);          ctx.fillRect(bx-1,by-1,2.5,cl);
    ctx.fillRect(bx+bw-cl+1,by-1,cl,2.5);    ctx.fillRect(bx+bw-1,by-1,2.5,cl);
    ctx.fillRect(bx-1,by+bh-1,cl,2.5);       ctx.fillRect(bx-1,by+bh-cl+1,2.5,cl);
    ctx.fillRect(bx+bw-cl+1,by+bh-1,cl,2.5); ctx.fillRect(bx+bw-1,by+bh-cl+1,2.5,cl);

    /* Scan line */
    scanPhase = (scanPhase + 0.016) % 1;
    var sl = by + scanPhase * bh;
    ctx.strokeStyle=col; ctx.lineWidth=1.5;
    ctx.beginPath(); ctx.moveTo(bx+2,sl); ctx.lineTo(bx+bw-2,sl); ctx.stroke();
    var sg = ctx.createLinearGradient(0,sl-10,0,sl+10);
    sg.addColorStop(0,'transparent');
    sg.addColorStop(0.5, faceDetected ? 'rgba(0,255,0,.16)' : 'rgba(255,170,0,.16)');
    sg.addColorStop(1,'transparent');
    ctx.fillStyle=sg; ctx.fillRect(bx+2,sl-10,bw-4,20);

    /* HUD */
    ctx.font='bold 11px monospace'; ctx.fillStyle=col;
    ctx.fillText('{{ Auth::user()->name }}'.toUpperCase(), bx, by-20);
    ctx.fillText(faceDetected ? 'FACE: LOCKED' : 'SEARCHING...', bx, by-8);
    ctx.font='9px monospace'; ctx.fillStyle='rgba(0,255,0,.75)';
    var isDark = document.documentElement.classList.contains('dark');
    ctx.fillText('DeepFace + RetinaFace', 8, 16);
    ctx.fillText('BRIGHTNESS: ' + (isDark ? 'ENHANCED' : 'NORMAL'), 8, 28);
    ctx.fillText('MODEL: Facenet512', 8, 40);
    ctx.fillText(faceDetected ? 'STATUS: FACE DETECTED' : 'STATUS: SCANNING...', 8, 52);
    ctx.textAlign='right';
    ctx.fillText('FPS: ' + Math.min(Math.round(1000/Math.max(elapsed,1)),60), vw-8, 16);
    ctx.textAlign='left';

    if (statusText) statusText.textContent = faceDetected ? 'DeepFace: FACE LOCKED' : 'DeepFace: SCANNING...';
    animFrameId = requestAnimationFrame(drawLoop);
}

/* ── Capture photo — NO mirror ── */
function capturePhoto() {
    if (!localStream) { addLog('ERROR: Kamera belum aktif.', 'error'); return; }
    var video       = document.getElementById('profile-video');
    var btnCapture  = document.getElementById('btn-capture');
    var progressWrap  = document.getElementById('progress-wrap');
    var progressFill  = document.getElementById('progress-fill');
    var progressLabel = document.getElementById('progress-label');

    if (!faceDetected) addLog('WARN: Wajah belum terdeteksi. Pastikan wajah terlihat jelas.', 'warn');

    btnCapture.disabled = true;
    progressWrap.style.display = 'block';
    addLog('DeepFace: Memproses capture...', 'warn');

    var p = 0;
    var interval = setInterval(function() {
        p += 4; if (p > 100) p = 100;
        progressFill.style.width  = p + '%';
        progressLabel.textContent = 'PROCESSING: ' + p + '%';
        if (p === 25) addLog('DeepFace: Landmark wajah (68 titik)...', 'info');
        if (p === 55) addLog('DeepFace: Facenet512 embedding...', 'info');
        if (p === 80) addLog('DeepFace: Optimasi kualitas...', 'info');
        if (p >= 100) {
            clearInterval(interval);
            /* Capture — NO mirror, natural direction */
            var snap = document.createElement('canvas');
            snap.width  = video.videoWidth  || 1280;
            snap.height = video.videoHeight || 720;
            snap.getContext('2d').drawImage(video, 0, 0, snap.width, snap.height);
            capturedDataUrl = snap.toDataURL('image/jpeg', 0.88);
            addLog('[SUCCESS] Foto berhasil diambil!', 'success');
            progressWrap.style.display = 'none';
            showCapturedPreview(capturedDataUrl);
        }
    }, 50);
}

function showCapturedPreview(dataUrl) {
    document.getElementById('captured-img').src = dataUrl;
    document.getElementById('captured-preview').style.display = 'block';
    document.getElementById('save-row').style.display         = 'flex';
    document.getElementById('btn-capture').disabled           = false;
    addLog('PREVIEW: Foto siap disimpan.', 'success');
}

function retakePhoto() {
    capturedDataUrl = null;
    document.getElementById('captured-preview').style.display = 'none';
    document.getElementById('save-row').style.display         = 'none';
    addLog('Siap untuk ambil foto ulang.', 'warn');
}

async function savePhoto() {
    if (!capturedDataUrl) { addLog('ERROR: Tidak ada foto.', 'error'); return; }
    var btn = document.getElementById('btn-save');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Menyimpan...';
    addLog('SYSTEM: Mengunggah foto ke server...', 'warn');
    try {
        var res  = await fetch('{{ route("admin.profile.update-avatar") }}', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body:    JSON.stringify({ avatar: capturedDataUrl })
        });
        var data = await res.json();
        if (data.success) {
            addLog('[SUCCESS] Foto profil berhasil disimpan!', 'success');
            var sa = document.querySelector('.sidebar-avatar');
            if (sa) sa.innerHTML = '<img src="' + capturedDataUrl + '" alt="Avatar" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">';
            var ring = document.querySelector('.avatar-ring');
            if (ring) ring.innerHTML = '<img class="avatar-img" src="' + capturedDataUrl + '" alt="Foto Profil"><div class="avatar-badge"><i class="fas fa-check"></i></div>';
            btn.innerHTML = '<i class="fas fa-check"></i> Tersimpan!';
            btn.style.background = 'linear-gradient(135deg,#10b981,#059669)';
            setTimeout(function(){ location.reload(); }, 1500);
        } else {
            addLog('ERROR: Gagal menyimpan foto.', 'error');
            btn.disabled = false; btn.innerHTML = '<i class="fas fa-save"></i> Simpan Foto Profil';
        }
    } catch (err) {
        addLog('ERROR: Kesalahan jaringan.', 'error');
        btn.disabled = false; btn.innerHTML = '<i class="fas fa-save"></i> Simpan Foto Profil';
    }
}

/* ── Particle canvas & clock ── */
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.glass-card').forEach(function(card) {
        card.addEventListener('mousemove', function(e) {
            var r = card.getBoundingClientRect();
            card.style.setProperty('--mouse-x', (e.clientX - r.left) + 'px');
            card.style.setProperty('--mouse-y', (e.clientY - r.top)  + 'px');
        });
    });
    setInterval(function() {
        var now = new Date(), pad = function(v){ return String(v).padStart(2,'0'); };
        var el = document.getElementById('ug-clock');
        if (el) el.textContent = pad(now.getHours())+':'+pad(now.getMinutes())+':'+pad(now.getSeconds())+' WIB';
    }, 1000);
    var canvas = document.getElementById('ug-canvas');
    if (!canvas) return;
    var ctx = canvas.getContext('2d');
    var W, H, particles = [], mouse = {x:-1000,y:-1000};
    var isDarkFn = function(){ return document.documentElement.classList.contains('dark'); };
    function resize(){ W = canvas.width = window.innerWidth; H = canvas.height = window.innerHeight; }
    resize(); window.addEventListener('resize', resize);
    window.addEventListener('mousemove', function(e){ mouse.x=e.clientX; mouse.y=e.clientY; });
    window.addEventListener('mouseout',  function(){ mouse.x=-1000; mouse.y=-1000; });
    function Particle(){ this.reset(); }
    Particle.prototype.reset = function(){ this.x=Math.random()*W; this.y=Math.random()*H; this.r=Math.random()*1.5+.5; this.vx=(Math.random()-.5)*.4; this.vy=(Math.random()-.5)*.4; this.alpha=Math.random()*.4+.1; };
    Particle.prototype.update = function(){
        var dx=mouse.x-this.x, dy=mouse.y-this.y, d=Math.sqrt(dx*dx+dy*dy);
        if(d<120){var f=(120-d)/120; this.vx-=(dx/d)*f*.2; this.vy-=(dy/d)*f*.2;}
        this.x+=this.vx; this.y+=this.vy; this.vx*=.99; this.vy*=.99;
        if(Math.abs(this.vx)<.2) this.vx+=(Math.random()-.5)*.1;
        if(Math.abs(this.vy)<.2) this.vy+=(Math.random()-.5)*.1;
        if(this.x<0||this.x>W||this.y<0||this.y>H) this.reset();
    };
    Particle.prototype.draw = function(){ ctx.beginPath(); ctx.arc(this.x,this.y,this.r,0,Math.PI*2); ctx.fillStyle=isDarkFn()?'rgba(255,255,255,'+this.alpha+')':'rgba(59,130,246,'+this.alpha+')'; ctx.fill(); };
    for(var i=0;i<80;i++) particles.push(new Particle());
    function drawConn(){ for(var i=0;i<particles.length;i++) for(var j=i+1;j<particles.length;j++){ var dx=particles[i].x-particles[j].x, dy=particles[i].y-particles[j].y, d=Math.sqrt(dx*dx+dy*dy); if(d<140){var a=(1-d/140)*.05; ctx.beginPath(); ctx.moveTo(particles[i].x,particles[i].y); ctx.lineTo(particles[j].x,particles[j].y); ctx.strokeStyle=isDarkFn()?'rgba(255,255,255,'+a+')':'rgba(59,130,246,'+a+')'; ctx.lineWidth=.6; ctx.stroke();}}}
    function frame(){ ctx.clearRect(0,0,W,H); particles.forEach(function(p){p.update();p.draw();}); drawConn(); requestAnimationFrame(frame); }
    frame();

    /* Auto-check server on page load */
    checkServer();
});
</script>
@endsection
