@php
    $role = Auth::user()->role;
    $title = 'Verifikasi Wajah';
    $description = 'Arahkan wajah Anda ke kamera untuk masuk ke dashboard.';
    $badgeText = 'Verifikasi Wajah';

    if ($role === 'admin') {
        $title = 'Verifikasi Wajah Administrator';
        $description = 'Arahkan wajah Anda ke kamera untuk masuk ke panel admin.';
        $badgeText = 'Verifikasi Wajah Admin';
    } elseif ($role === 'lecturer') {
        $title = 'Verifikasi Wajah Dosen';
        $description = 'Arahkan wajah Anda ke kamera untuk masuk ke portal dosen.';
        $badgeText = 'Verifikasi Wajah Dosen';
    } elseif ($role === 'student') {
        $title = 'Verifikasi Wajah';
        $description = 'Arahkan wajah Anda ke kamera untuk membuka dashboard mahasiswa.';
        $badgeText = 'Verifikasi Wajah Mahasiswa';
    }
@endphp

<style>
    /* ── BIOMETRIC GATE SCREEN ── */
    #biometric-gate {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(240, 249, 255, 0.98);
        backdrop-filter: blur(15px);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        overflow-y: auto;
        padding: 20px 0;
        transition: transform 1s cubic-bezier(0.85, 0, 0.15, 1), opacity 0.8s ease;
    }

    .dark #biometric-gate {
        background: rgba(13, 15, 23, 0.98);
    }

    .scanner-box {
        width: 320px;
        height: 320px;
        border-radius: 50%;
        border: 2px solid rgba(14, 165, 233, 0.5);
        position: relative;
        overflow: hidden;
        box-shadow: 0 0 30px rgba(14, 165, 233, 0.3), inset 0 0 20px rgba(14, 165, 233, 0.2);
    }

    .dark .scanner-box {
        border-color: rgba(59, 130, 246, 0.5);
        box-shadow: 0 0 50px rgba(59, 130, 246, 0.5), inset 0 0 30px rgba(59, 130, 246, 0.3);
    }

    .scanner-laser {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(to bottom, rgba(59,130,246,0) 0%, rgba(59,130,246,1) 50%, rgba(59,130,246,0) 100%);
        box-shadow: 0 0 15px #3b82f6;
        animation: laserScan 2.5s linear infinite;
        z-index: 10;
    }

    @keyframes laserScan {
        0% { top: 0%; }
        50% { top: 100%; }
        100% { top: 0%; }
    }

    .scanner-grid {
        position: absolute;
        inset: 0;
        background: 
            linear-gradient(rgba(59,130,246,0.1) 1px, transparent 1px),
            linear-gradient(90deg, rgba(59,130,246,0.1) 1px, transparent 1px);
        background-size: 20px 20px;
        animation: gridPulse 4s infinite ease-in-out;
    }

    @keyframes gridPulse {
        0%, 100% { opacity: 0.3; }
        50% { opacity: 0.7; }
    }

    #biometric-gate {
        background:
            linear-gradient(135deg, rgba(248,250,252,0.98), rgba(226,232,240,0.94)),
            radial-gradient(circle at 50% 0%, rgba(14,165,233,0.14), transparent 38%);
        padding: clamp(16px, 3vh, 32px);
    }
    #biometric-gate::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image: linear-gradient(rgba(14,165,233,0.07) 1px, transparent 1px), linear-gradient(90deg, rgba(14,165,233,0.07) 1px, transparent 1px);
        background-size: 38px 38px;
        opacity: 0.65;
        pointer-events: none;
    }
    .dark #biometric-gate {
        background:
            linear-gradient(135deg, rgba(2,6,23,0.98), rgba(15,23,42,0.96)),
            radial-gradient(circle at 50% 0%, rgba(59,130,246,0.18), transparent 40%);
    }
    .gate-shell {
        position: relative;
        z-index: 1;
        width: min(calc(100% - 32px), 1040px);
        max-width: 1040px !important;
        display: grid !important;
        grid-template-columns: minmax(300px, 430px) minmax(290px, 1fr);
        grid-template-rows: auto auto auto auto auto auto auto auto;
        gap: 14px 30px;
        align-items: center;
        padding: clamp(22px, 3vw, 34px);
        border-radius: 30px;
        background: rgba(255,255,255,0.86);
        border: 1px solid rgba(14,165,233,0.22);
        box-shadow: 0 28px 80px rgba(15,23,42,0.18);
        overflow: hidden;
        text-align: left !important;
    }
    .gate-shell::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: inherit;
        background: linear-gradient(90deg, rgba(14,165,233,0.10), transparent 28%), linear-gradient(180deg, rgba(245,158,11,0.08), transparent 34%);
        pointer-events: none;
    }
    .dark .gate-shell {
        background: rgba(2,6,23,0.82);
        border-color: rgba(148,163,184,0.22);
        box-shadow: 0 30px 90px rgba(0,0,0,0.58);
    }
    .gate-shell > * { position: relative; z-index: 1; }
    .gate-avatar {
        grid-column: 2;
        grid-row: 1;
        width: 66px !important;
        height: 66px !important;
        margin: 0 !important;
        justify-self: start;
        align-self: center;
        filter: drop-shadow(0 12px 22px rgba(14,165,233,0.18));
    }
    .gate-kicker {
        grid-column: 2;
        grid-row: 1;
        margin: 0 0 0 84px !important;
        align-self: center;
    }
    .gate-kicker span,
    .gate-status-strip span {
        border-radius: 999px !important;
        background: rgba(15,23,42,0.04) !important;
        border-color: rgba(14,165,233,0.28) !important;
    }
    .dark .gate-kicker span,
    .dark .gate-status-strip span {
        background: rgba(15,23,42,0.72) !important;
        border-color: rgba(56,189,248,0.26) !important;
    }
    #gate-title {
        grid-column: 2;
        grid-row: 2;
        margin: 6px 0 0 !important;
        font-size: clamp(28px, 4vw, 42px);
        line-height: 1.02;
    }
    #gate-description {
        grid-column: 2;
        grid-row: 3;
        margin: 0 !important;
        max-width: 460px;
        line-height: 1.6;
    }
    .gate-telemetry {
        grid-column: 2;
        grid-row: 4;
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
        width: 100%;
        margin-top: 4px;
    }
    .gate-telemetry span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 38px;
        padding: 8px 10px;
        border-radius: 12px;
        background: rgba(248,250,252,0.86);
        border: 1px solid rgba(148,163,184,0.24);
        color: #334155;
        font: 800 10px 'JetBrains Mono', monospace;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .gate-telemetry i:first-child { color: #0ea5e9; }
    .gate-telemetry span:nth-child(2) i { color: #10b981; }
    .gate-telemetry span:nth-child(3) i { color: #f59e0b; }
    .dark .gate-telemetry span {
        background: rgba(15,23,42,0.72);
        border-color: rgba(148,163,184,0.20);
        color: #cbd5e1;
    }
    .gate-camera-stage {
        grid-column: 1;
        grid-row: 1 / span 8;
        justify-self: center;
        width: min(100%, 430px);
        aspect-ratio: 1;
        margin: 0 !important;
        padding: 24px;
        display: grid;
        place-items: center;
        border-radius: 30px;
        background: linear-gradient(145deg, rgba(15,23,42,0.98), rgba(30,41,59,0.94));
        border: 1px solid rgba(148,163,184,0.22);
        box-shadow: inset 0 0 0 1px rgba(255,255,255,0.04), 0 24px 70px rgba(15,23,42,0.34);
        overflow: hidden;
        isolation: isolate;
    }
    .gate-camera-stage::before {
        content: "";
        position: absolute;
        inset: 14px;
        z-index: 0;
        border-radius: 24px;
        border: 1px solid rgba(125,211,252,0.16);
        background: linear-gradient(135deg, rgba(14,165,233,0.08), transparent 40%, rgba(245,158,11,0.08));
        pointer-events: none;
    }
    .gate-camera-stage::after {
        content: "";
        position: absolute;
        left: 26px;
        right: 26px;
        bottom: 18px;
        z-index: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(125,211,252,0.38), transparent);
        pointer-events: none;
    }
    .gate-camera-header {
        position: absolute;
        top: 16px;
        left: 18px;
        right: 18px;
        z-index: 30;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: rgba(226,232,240,0.86);
        font: 800 10px 'JetBrains Mono', monospace;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        pointer-events: none;
    }
    .gate-camera-header span {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 9px;
        border-radius: 999px;
        background: rgba(2,6,23,0.62);
        border: 1px solid rgba(148,163,184,0.20);
    }
    .gate-camera-header i { color: #ef4444; }
    .gate-camera-stage .scanner-box {
        width: min(100%, 370px);
        height: auto;
        aspect-ratio: 1;
        border-radius: 26px;
        border: 1px solid rgba(125,211,252,0.48);
        background: #020617;
        box-shadow: 0 0 0 8px rgba(14,165,233,0.07), inset 0 0 34px rgba(14,165,233,0.22);
    }
    .gate-camera-stage .scanner-box::after {
        content: "";
        position: absolute;
        inset: 0;
        z-index: 11;
        background: radial-gradient(circle at 50% 48%, transparent 50%, rgba(2,6,23,0.22) 100%);
        pointer-events: none;
    }
    .gate-camera-stage .scanner-laser {
        height: 2px;
        background: linear-gradient(90deg, transparent, #38bdf8, #f59e0b, #38bdf8, transparent);
        box-shadow: 0 0 18px rgba(56,189,248,0.9);
        z-index: 16;
    }
    .gate-camera-stage .scanner-grid {
        display: none;
    }
    #gate-video {
        z-index: 1;
        transform: scaleX(-1) !important;
        transform-origin: center;
    }
    #gate-canvas {
        z-index: 18;
        transform: none !important;
        transform-origin: center;
    }
    #gate-placeholder { z-index: 25; }
    .gate-reticle {
        display: none;
    }
    .gate-reticle::before,
    .gate-reticle::after {
        content: "";
        position: absolute;
        background: rgba(226,232,240,0.22);
    }
    .gate-reticle::before { left: 50%; top: -10%; bottom: -10%; width: 1px; }
    .gate-reticle::after { top: 50%; left: -10%; right: -10%; height: 1px; }
    .gate-scan-corners {
        position: absolute;
        inset: 14px;
        z-index: 19;
        pointer-events: none;
    }
    .gate-scan-corners span {
        position: absolute;
        width: 34px;
        height: 34px;
        border-color: #f59e0b;
    }
    .gate-scan-corners span:nth-child(1) { top: 0; left: 0; border-top: 2px solid; border-left: 2px solid; }
    .gate-scan-corners span:nth-child(2) { top: 0; right: 0; border-top: 2px solid; border-right: 2px solid; }
    .gate-scan-corners span:nth-child(3) { bottom: 0; right: 0; border-bottom: 2px solid; border-right: 2px solid; }
    .gate-scan-corners span:nth-child(4) { bottom: 0; left: 0; border-bottom: 2px solid; border-left: 2px solid; }
    #scan-progress-container {
        grid-column: 2;
        grid-row: 5;
        width: 100%;
        height: 10px !important;
        margin: 2px 0 0 !important;
        background: rgba(148,163,184,0.20) !important;
        border: 1px solid rgba(148,163,184,0.18);
    }
    #scan-progress-bar {
        background: linear-gradient(90deg, #10b981, #22d3ee, #f59e0b) !important;
        box-shadow: 0 0 20px rgba(34,211,238,0.45);
    }
    #scan-progress-text {
        grid-column: 2;
        grid-row: 6;
        margin: -4px 0 0 !important;
        letter-spacing: 0.04em;
    }
    #terminal-logs {
        grid-column: 2;
        grid-row: 7;
        width: 100%;
        height: 158px !important;
        margin: 0 !important;
        border-radius: 16px !important;
        background: rgba(2,6,23,0.92) !important;
        border-color: rgba(56,189,248,0.18) !important;
        box-shadow: inset 0 0 0 1px rgba(15,23,42,0.6), 0 18px 40px rgba(15,23,42,0.18) !important;
    }
    .gate-status-strip {
        grid-column: 2;
        grid-row: 8;
        text-align: left !important;
    }
    .gate-status-strip span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: #059669 !important;
        border-color: rgba(16,185,129,0.30) !important;
        background: rgba(16,185,129,0.10) !important;
    }
    @media (max-width: 900px) {
        .gate-shell {
            grid-template-columns: 1fr;
            gap: 14px;
            text-align: center !important;
            padding: 22px;
        }
        .gate-avatar,
        .gate-kicker,
        #gate-title,
        #gate-description,
        .gate-telemetry,
        .gate-camera-stage,
        #scan-progress-container,
        #scan-progress-text,
        #terminal-logs,
        .gate-status-strip {
            grid-column: 1;
            grid-row: auto;
        }
        .gate-avatar { justify-self: center; }
        .gate-kicker {
            margin: 0 !important;
            justify-self: center;
        }
        #gate-description { justify-self: center; }
        .gate-telemetry { grid-template-columns: 1fr; }
        .gate-camera-stage {
            width: min(100%, 360px);
            padding: 18px;
        }
        .gate-camera-header {
            left: 14px;
            right: 14px;
        }
        .gate-status-strip { text-align: center !important; }
    }

    @media (max-width: 767px) {
        #biometric-gate {
            padding: 16px !important;
            justify-content: center !important;
            align-items: center !important;
        }
        .gate-shell {
            display: flex !important;
            flex-direction: column !important;
            width: 100% !important;
            height: 100% !important;
            max-height: 92vh !important;
            padding: 20px 16px !important;
            border-radius: 24px !important;
            gap: 10px !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            overflow-y: auto !important;
            scrollbar-width: none;
        }
        .gate-shell::-webkit-scrollbar {
            display: none;
        }
        .gate-avatar {
            display: none !important;
        }
        #terminal-logs {
            display: none !important;
        }
        .gate-telemetry {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: wrap !important;
            justify-content: center !important;
            gap: 6px !important;
            margin-top: 2px !important;
            width: 100% !important;
        }
        .gate-telemetry span {
            flex: none !important;
            min-height: 28px !important;
            padding: 4px 10px !important;
            border-radius: 8px !important;
            font-size: 9px !important;
        }
        #gate-title {
            font-size: 22px !important;
            margin-top: 0px !important;
            font-weight: 800 !important;
        }
        #gate-description {
            font-size: 11px !important;
            margin-bottom: 6px !important;
            max-width: 100% !important;
            line-height: 1.4 !important;
        }
        .gate-camera-stage {
            width: min(100%, 240px) !important;
            aspect-ratio: 1 !important;
            padding: 8px !important;
            margin: 4px 0 !important;
            border-radius: 20px !important;
        }
        .gate-camera-stage .scanner-box {
            width: 100% !important;
            border-radius: 16px !important;
        }
        .gate-camera-header {
            display: none !important;
        }
        .gate-scan-corners span {
            width: 20px !important;
            height: 20px !important;
        }
        #scan-progress-container {
            margin-top: 4px !important;
            height: 6px !important;
        }
        #scan-progress-text {
            font-size: 11px !important;
            margin-top: 0px !important;
        }
        .gate-status-strip {
            margin-top: 4px !important;
        }
    }

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
</style>

<!-- ── BIOMETRIC OPENCV FACE SCAN GATE ── -->
<div id="biometric-gate">
    <script>
        if (sessionStorage.getItem('biometric_verified') === 'true') {
            document.getElementById('biometric-gate').style.display = 'none';
        }
    </script>
    <div class="gate-shell">
        <!-- 3D Office Character Avatar Container or Profile Photo -->
        <div id="avatar-container" class="gate-avatar relative mx-auto mb-4 flex justify-center items-center w-28 h-28 waving">
            @if(Auth::user()->avatar)
                <img src="{{ Auth::user()->avatar }}" alt="Foto Dosen" class="w-full h-full object-cover rounded-full border-2 border-blue-500/30">
            @else
                <svg id="office-avatar" viewBox="0 0 160 160" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" style="overflow: visible;">
                    <!-- Background glow -->
                    <circle cx="80" cy="80" r="60" fill="rgba(59, 130, 246, 0.08)" class="avatar-bg-glow" />
                    
                    <!-- Shadow of body -->
                    <ellipse cx="80" cy="148" rx="45" ry="8" fill="rgba(0,0,0,0.1)" />

                    <!-- Main Body (Suit & Shirt) -->
                    <g id="avatar-body" class="avatar-body-bob">
                        <!-- Shoulders / Jacket -->
                        <path d="M35 150 C35 125, 45 110, 80 110 C115 110, 125 125, 125 150 Z" class="fill-slate-700 dark:fill-slate-800" />
                        
                        <!-- White Shirt V-Neck -->
                        <path d="M65 110 L95 110 L80 130 Z" fill="#ffffff" />
                        
                        <!-- Tie (UGFORCE Cyan/Blue or Gold) -->
                        <path d="M77 122 L83 122 L85 145 L80 152 L75 145 Z" class="fill-blue-500 dark:fill-sky-400" />
                        <!-- Tie Knot -->
                        <polygon points="76,120 84,120 82,126 78,126" class="fill-blue-600 dark:fill-sky-500" />

                        <!-- Suit Collar Left -->
                        <path d="M35 150 L58 118 L68 135 Z" class="fill-slate-800 dark:fill-slate-900" />
                        <!-- Suit Collar Right -->
                        <path d="M125 150 L102 118 L92 135 Z" class="fill-slate-800 dark:fill-slate-900" />
                    </g>

                    <!-- Neck -->
                    <rect x="72" y="98" width="16" height="15" rx="4" fill="#f1c27d" />
                    <!-- Neck Shadow -->
                    <path d="M72 108 L88 108 L80 113 Z" fill="rgba(0,0,0,0.15)" />

                    <!-- Head Group (can shake) -->
                    <g id="avatar-head" class="avatar-head-bob">
                        <!-- Ears -->
                        <circle cx="52" cy="72" r="8" fill="#f1c27d" />
                        <circle cx="108" cy="72" r="8" fill="#f1c27d" />
                        
                        <!-- Head Base -->
                        <rect x="56" y="44" width="48" height="52" rx="24" fill="#f1c27d" />

                        <!-- Face Details (Eyes, Eyebrows, Mouth) -->
                        <g id="avatar-face">
                            <!-- Eyebrows -->
                            <path id="left-brow" d="M62 61 Q70 59 74 62" stroke="#1e293b" stroke-width="2.5" stroke-linecap="round" fill="none" />
                            <path id="right-brow" d="M98 61 Q90 59 86 62" stroke="#1e293b" stroke-width="2.5" stroke-linecap="round" fill="none" />

                            <!-- Eyes -->
                            <g id="eyes">
                                <!-- Left Eye (Open) -->
                                <circle id="left-eye-open" cx="69" cy="69" r="3.5" fill="#1e293b" />
                                <!-- Right Eye (Open) -->
                                <circle id="right-eye-open" cx="91" cy="69" r="3.5" fill="#1e293b" />

                                <!-- Eyes closed / password focused -->
                                <path id="left-eye-closed" d="M65 70 Q69 74 73 70" stroke="#1e293b" stroke-width="3" stroke-linecap="round" fill="none" style="display: none;" />
                                <path id="right-eye-closed" d="M87 70 Q91 74 95 70" stroke="#1e293b" stroke-width="3" stroke-linecap="round" fill="none" style="display: none;" />
                                
                                <!-- Eyes worried / error -->
                                <path id="left-eye-worried" d="M65 71 Q69 67 73 71" stroke="#1e293b" stroke-width="3" stroke-linecap="round" fill="none" style="display: none;" />
                                <path id="right-eye-worried" d="M87 71 Q91 67 95 71" stroke="#1e293b" stroke-width="3" stroke-linecap="round" fill="none" style="display: none;" />
                            </g>

                            <!-- Blush cheeks -->
                            <circle cx="61" cy="76" r="4" fill="rgba(244,63,94,0.3)" />
                            <circle cx="99" cy="76" r="4" fill="rgba(244,63,94,0.3)" />

                            <!-- Mouths -->
                            <path id="avatar-mouth-happy" d="M74 81 Q80 87 86 81" stroke="#1e293b" stroke-width="3" stroke-linecap="round" fill="none" />
                            <path id="avatar-mouth-sad" d="M74 85 Q80 79 86 85" stroke="#ef4444" stroke-width="3" stroke-linecap="round" fill="none" style="display: none;" />
                        </g>

                        <!-- Hair (Front/Bangs) -->
                        <path d="M54 52 C54 30, 106 30, 106 52 C106 52, 100 44, 80 44 C60 44, 54 52, 54 52 Z" fill="#2d3748" />
                        <!-- Hair sideburns -->
                        <path d="M54 52 L56 68 L60 62 Z" fill="#2d3748" />
                        <path d="M106 52 L104 68 L100 62 Z" fill="#2d3748" />
                    </g>

                    <!-- Left Arm -->
                    <g id="avatar-left-arm" class="avatar-body-bob">
                        <path d="M35 130 Q20 145 25 155" stroke="#475569" stroke-width="12" stroke-linecap="round" fill="none" />
                    </g>

                    <!-- Right Arm (Waving animation group) -->
                    <g id="avatar-right-arm" class="avatar-arm-wave">
                        <!-- Sleeve -->
                        <path d="M125 130 C135 120, 142 105, 142 90" stroke="#475569" stroke-width="12" stroke-linecap="round" fill="none" />
                        <!-- Hand -->
                        <circle cx="142" cy="84" r="7" fill="#f1c27d" />
                        <path d="M139 84 Q142 76 145 84" stroke="#f1c27d" stroke-width="3" stroke-linecap="round" fill="none" />
                    </g>
                </svg>
            @endif
        </div>
        <!-- Shield Lock Icon / Biometrics Title -->
        <div class="gate-kicker mb-4">
            <span class="px-4 py-2 rounded-full bg-blue-500/10 border border-blue-500/30 text-blue-600 dark:text-blue-400 text-xs font-bold uppercase tracking-widest">
                <i class="bi bi-shield-fill-check me-2"></i>{{ $badgeText }}
            </span>
        </div>
        <h2 class="text-4xl font-extrabold text-slate-900 dark:text-white mb-2 tracking-tight" id="gate-title">{{ $title }}</h2>
        <p class="text-slate-600 dark:text-slate-400 mb-8 text-sm" id="gate-description">{{ $description }}</p>
        <div class="gate-telemetry">
            <span><i class="bi bi-hdd-network-fill"></i> Server Terhubung</span>
            <span><i class="bi bi-check2-circle"></i> Kamera Aktif</span>
            <span><i class="bi bi-lightning-charge-fill"></i> Pindai Wajah</span>
        </div>

        <!-- Dynamic Circular Scanner -->
        <div class="gate-camera-stage relative mb-8 mt-4">
            <div class="gate-camera-header">
                <span><i class="bi bi-record-circle-fill"></i> Kamera Aktif</span>
                <span>Deteksi Wajah</span>
            </div>
            <div class="scanner-box z-10">
                <div class="scanner-laser"></div>
                <!-- Video & Canvas for Real-Time Camera -->
                <video id="gate-video" autoplay playsinline muted class="absolute inset-0 w-full h-full object-cover z-0"></video>
                <canvas id="gate-canvas" class="absolute inset-0 w-full h-full object-cover z-10 pointer-events-none"></canvas>
                <!-- Pulsing Face Icon Placeholder (shown when camera starting) -->
                <div id="gate-placeholder" class="absolute inset-0 flex items-center justify-center z-20 bg-slate-950/80">
                    <i class="bi bi-person-bounding-box text-sky-600/25 dark:text-blue-500/25 animate-pulse" style="font-size: 80px;"></i>
                </div>
                <div class="gate-scan-corners"><span></span><span></span><span></span><span></span></div>
            </div>
        </div>

        <!-- Progress Scan Bar -->
        <div class="w-full bg-slate-200 dark:bg-slate-800 rounded-full h-2 mb-2 relative overflow-hidden" id="scan-progress-container" style="display: none;">
            <div id="scan-progress-bar" class="bg-green-500 h-full w-0 transition-all duration-100"></div>
        </div>
        <div id="scan-progress-text" class="text-xs font-mono text-green-600 dark:text-green-400 font-bold mb-4" style="display: none;">MEMINDAI WAJAH: 0%</div>

        <!-- Terminal Logs output simulating OpenCV processing -->
        <div class="w-full bg-black/80 rounded-xl p-4 mb-6 text-left font-mono text-xs text-green-400 border border-slate-800 shadow-2xl h-32 overflow-y-auto mb-4" id="terminal-logs">
            <div class="text-slate-500">[MEMULAI SISTEM...]</div>
        </div>

        <!-- Status indicator for Python OpenCV -->
        <div class="gate-status-strip w-full text-center">
            <span class="px-4 py-2 rounded-full bg-blue-500/10 border border-blue-500/30 text-blue-500 dark:text-blue-400 text-xs font-bold uppercase tracking-widest animate-pulse">
                <i class="bi bi-cpu-fill me-2"></i>SISTEM PINDAI WAJAH AKTIF
            </span>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const biometricGate = document.getElementById('biometric-gate');
        const userRole = @json($role);
        const userName = @json(Auth::user()->name);
        const registeredSignature = @json(Auth::user()->face_signature);
        const isRealSignature = registeredSignature && registeredSignature.startsWith('data:image/');
        const hasFaceSignature = !!isRealSignature;
        const alreadyAttended = @json(isset($alreadyAttended) && $alreadyAttended);

        if (sessionStorage.getItem('biometric_verified') === 'true') {
            if (biometricGate) {
                biometricGate.style.display = 'none';
            }
            const navbar = document.querySelector('nav');
            if (navbar) {
                navbar.style.display = 'flex';
            }
            document.body.style.overflow = '';
            return;
        }

        if (biometricGate) {
            document.body.appendChild(biometricGate);
        }
        initBiometricGate();

        function initBiometricGate() {
            // Hide navigation elements to prevent overlap with gate
            const header = document.querySelector('.top-app-bar');
            const tabBar = document.querySelector('.bottom-tab-bar');
            const desktopNav = document.querySelector('nav.desktop-nav');
            if (header) header.style.display = 'none';
            if (tabBar) tabBar.style.display = 'none';
            if (desktopNav) desktopNav.style.display = 'none';
            
            document.body.style.overflow = 'hidden'; // Prevent scrolling
            const logBox = document.getElementById('terminal-logs');
            
            // Progress Controls
            const progressContainer = document.getElementById('scan-progress-container');
            const progressBar = document.getElementById('scan-progress-bar');
            const progressText = document.getElementById('scan-progress-text');

            // Video & Canvas
            const video = document.getElementById('gate-video');
            const canvas = document.getElementById('gate-canvas');
            const placeholder = document.getElementById('gate-placeholder');

            let stream = null;
            let isDrawing = true;
            let animationFrameId = null;
            window.verificationState = 'not_found';
            
            // Bounding box tracking coordinates
            let currentX = 0, currentY = 0, currentW = 0, currentH = 0;
            let targetX = 0, targetY = 0, targetW = 0, targetH = 0;
            let faceDetected = false;
            let activeTracking = false;

            function getComfortFaceBox(rawFace, sourceW = 320, sourceH = 240) {
                const padX = rawFace.w * 0.05;
                const padY = rawFace.h * 0.05;
                const x = Math.max(0, rawFace.x - padX);
                const y = Math.max(0, rawFace.y - padY);
                const w = Math.min(sourceW - x, rawFace.w + (padX * 2));
                const h = Math.min(sourceH - y, rawFace.h + (padY * 2));
                return { x, y, w, h };
            }

            function roundedBoxPath(ctx, x, y, w, h, radius) {
                const r = Math.min(radius, w / 2, h / 2);
                ctx.beginPath();
                ctx.moveTo(x + r, y);
                ctx.lineTo(x + w - r, y);
                ctx.quadraticCurveTo(x + w, y, x + w, y + r);
                ctx.lineTo(x + w, y + h - r);
                ctx.quadraticCurveTo(x + w, y + h, x + w - r, y + h);
                ctx.lineTo(x + r, y + h);
                ctx.quadraticCurveTo(x, y + h, x, y + h - r);
                ctx.lineTo(x, y + r);
                ctx.quadraticCurveTo(x, y, x + r, y);
            }

            function drawFaceHud(ctx, x, y, w, h, label) {
                const radius = Math.min(18, Math.max(10, Math.min(w, h) * 0.08));
                const corner = Math.min(44, Math.max(24, Math.min(w, h) * 0.24));

                ctx.save();
                roundedBoxPath(ctx, x, y, w, h, radius);
                ctx.fillStyle = 'rgba(239, 68, 68, 0.07)';
                ctx.fill();
                ctx.lineWidth = 1;
                ctx.strokeStyle = 'rgba(248, 113, 113, 0.32)';
                ctx.stroke();

                ctx.beginPath();
                ctx.moveTo(x, y + corner);
                ctx.lineTo(x, y + radius);
                ctx.quadraticCurveTo(x, y, x + radius, y);
                ctx.lineTo(x + corner, y);
                ctx.moveTo(x + w - corner, y);
                ctx.lineTo(x + w - radius, y);
                ctx.quadraticCurveTo(x + w, y, x + w, y + radius);
                ctx.lineTo(x + w, y + corner);
                ctx.moveTo(x + w, y + h - corner);
                ctx.lineTo(x + w, y + h - radius);
                ctx.quadraticCurveTo(x + w, y + h, x + w - radius, y + h);
                ctx.lineTo(x + w - corner, y + h);
                ctx.moveTo(x + corner, y + h);
                ctx.lineTo(x + radius, y + h);
                ctx.quadraticCurveTo(x, y + h, x, y + h - radius);
                ctx.lineTo(x, y + h - corner);
                ctx.lineWidth = 2.5;
                ctx.strokeStyle = '#ef4444';
                ctx.stroke();

                ctx.shadowBlur = 0;
                ctx.font = 'bold 11px JetBrains Mono, monospace';
                const text = label.toUpperCase();
                const textWidth = ctx.measureText(text).width;
                const badgeW = Math.min(Math.max(textWidth + 26, 150), ctx.canvas.width - 12);
                const badgeH = 24;
                const badgeX = Math.max(6, Math.min(x, ctx.canvas.width - badgeW - 6));
                const badgeY = Math.max(6, y - badgeH - 7);
                roundedBoxPath(ctx, badgeX, badgeY, badgeW, badgeH, 12);
                ctx.fillStyle = 'rgba(127, 29, 29, 0.88)';
                ctx.fill();
                ctx.strokeStyle = 'rgba(248, 113, 113, 0.55)';
                ctx.lineWidth = 1;
                ctx.stroke();
                ctx.fillStyle = '#fecaca';
                ctx.fillText(text, badgeX + 18, badgeY + 16);
                ctx.beginPath();
                ctx.arc(badgeX + 9, badgeY + 12, 3, 0, Math.PI * 2);
                ctx.fillStyle = '#ef4444';
                ctx.fill();
                ctx.restore();
            }

            // Print logging simulator lines
            function addLog(text, type = 'info') {
                const el = document.createElement('div');
                const now = new Date().toLocaleTimeString();
                let color = 'text-green-500 dark:text-green-400';
                if (type === 'success') color = 'text-cyan-600 dark:text-cyan-400 font-extrabold';
                if (type === 'error') color = 'text-red-500 dark:text-red-400 font-bold';
                if (type === 'warn') color = 'text-yellow-600 dark:text-yellow-400';
                
                el.className = `${color} mb-1`;
                el.innerHTML = `<span class="text-slate-500">[${now}]</span> ${text}`;
                logBox.appendChild(el);
                logBox.scrollTop = logBox.scrollHeight;
            }

            // Initialize camera
            async function startCamera() {
                addLog('Kamera sedang dinyalakan...', 'info');
                try {
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: { facingMode: 'user', width: 640, height: 480, frameRate: { ideal: 60 } }
                    });
                    video.srcObject = stream;
                    
                    video.onloadedmetadata = () => {
                        placeholder.style.display = 'none';
                        addLog('Kamera aktif. Pemindaian wajah dimulai.', 'success');
                        startDetectionLoop();
                    };
                } catch (err) {
                    console.error(err);
                    addLog('ERROR: Gagal mengakses kamera! Periksa izin browser.', 'error');
                }
            }

            startCamera();

            // 60FPS Drawing Loop
            function drawLoop() {
                if (!isDrawing) return;
                
                const ctx = canvas.getContext('2d');
                const canvasW = video.videoWidth || 320;
                const canvasH = video.videoHeight || 240;
                if (canvas.width !== canvasW || canvas.height !== canvasH) {
                    canvas.width = canvasW;
                    canvas.height = canvasH;
                }
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                if (activeTracking && faceDetected) {
                    // Interpolate (lerp) coordinates for 60fps tracking
                    currentX += (targetX - currentX) * 0.35;
                    currentY += (targetY - currentY) * 0.35;
                    currentW += (targetW - currentW) * 0.35;
                    currentH += (targetH - currentH) * 0.35;

                    const x = currentX;
                    const y = currentY;
                    const w = currentW;
                    const h = currentH;

                    const labelName = userName.toUpperCase();
                    let labelText = 'FACIAL TRACKING: NOT FOUND';
                    if (!hasFaceSignature) {
                        labelText = 'RECORDING SCAN: ' + labelName;
                    } else if (window.verificationState === 'matched') {
                        labelText = 'FACIAL TRACKING: ' + labelName;
                    } else if (window.isVerifying) {
                        labelText = 'FACIAL TRACKING: VERIFYING...';
                    } else {
                        labelText = 'FACIAL TRACKING: NOT FOUND';
                    }
                    drawFaceHud(ctx, x, y, w, h, labelText);
                }

                animationFrameId = requestAnimationFrame(drawLoop);
            }

            // Start drawing frame animation
            requestAnimationFrame(drawLoop);

            // Periodic Face Detection & Verification Loop (every 400ms - faster scanning)
            let isProcessing = false;
            let detectionInterval = null;
            let continuousDetections = 0;
            window.isVerifying = false;
            let currentProgress = 0;

            progressContainer.style.display = 'block';
            progressText.style.display = 'block';

            function startDetectionLoop() {
                detectionInterval = setInterval(async () => {
                    if (isProcessing) return;
                    isProcessing = true;

                    // Capture current video frame to base64
                    const captureCanvas = document.createElement('canvas');
                    captureCanvas.width = 320;
                    captureCanvas.height = 240;
                    const captureCtx = captureCanvas.getContext('2d');
                    captureCtx.drawImage(video, 0, 0, 320, 240);
                    const currentFrameB64 = captureCanvas.toDataURL('image/jpeg', 0.85);

                    try {
                        // 1. Check face detection & coordinates
                        const detectRes = await fetch('{{ env("FACE_API_URL", "http://127.0.0.1:5001") }}/detect', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ image: currentFrameB64 })
                        });
                        const detectData = await detectRes.json();

                        if (detectData.detected && detectData.faces.length > 0) {
                            faceDetected = true;
                            activeTracking = true;
                            
                            // Map 320x240 coordinates to canvas coordinates
                            const rawFace = detectData.faces[0];
                            const scaleX = canvas.width / 320;
                            const scaleY = canvas.height / 240;

                            const displayFace = getComfortFaceBox(rawFace);
                            targetX = canvas.width - ((displayFace.x + displayFace.w) * scaleX);
                            targetY = displayFace.y * scaleY;
                            targetW = displayFace.w * scaleX;
                            targetH = displayFace.h * scaleY;

                            // Ensure they don't clip canvas bounds
                            targetX = Math.max(0, targetX);
                            targetY = Math.max(0, targetY);
                            targetW = Math.min(targetW, canvas.width - targetX);
                            targetH = Math.min(targetH, canvas.height - targetY);

                            if (!hasFaceSignature) {
                                // ── SETUP / REGISTER FLOW ──
                                continuousDetections++;
                                addLog(`Wajah terdeteksi (${continuousDetections}/3).`, 'info');
                                currentProgress = Math.min(100, Math.round((continuousDetections / 3) * 100));
                                progressBar.style.width = currentProgress + '%';
                                progressText.innerText = `MEREKAM WAJAH: ${currentProgress}%`;

                                if (continuousDetections >= 3) {
                                    clearInterval(detectionInterval);
                                    addLog('SUKSES: Perekaman wajah berhasil!', 'success');
                                    saveFaceSignature(currentFrameB64);
                                }
                            } else {
                                // ── VERIFICATION FLOW ──
                                if (window.isVerifying) return;
                                window.isVerifying = true;

                                addLog('Wajah terdeteksi. Mencocokkan wajah Anda...', 'warn');
                                currentProgress = 50;
                                progressBar.style.width = '50%';
                                progressText.innerText = 'MEMPROSES VERIFIKASI: 50%';

                                fetch('{{ env("FACE_API_URL", "http://127.0.0.1:5001") }}/verify', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json' },
                                    body: JSON.stringify({
                                        img1: currentFrameB64,
                                        img2: registeredSignature
                                    })
                                }).then(res => res.json()).then(verifyData => {
                                    if (verifyData.verified) {
                                        window.verificationState = 'matched';
                                        clearInterval(detectionInterval);
                                        addLog(`SUKSES: Identitas terverifikasi! Tingkat kecocokan: ${verifyData.similarity}%`, 'success');
                                        currentProgress = 100;
                                        progressBar.style.width = '100%';
                                        progressText.innerText = 'VERIFIKASI SUKSES: 100%';
                                        
                                        // Lecturer specific attendance log
                                        if (userRole === 'lecturer') {
                                            if (alreadyAttended) {
                                                addLog('INFO: Anda sudah melakukan presensi hari ini. Akses diberikan.', 'info');
                                            } else {
                                                const nowTime = new Date();
                                                const isAttendanceWindow = (nowTime.getHours() >= 6 && nowTime.getHours() < 20);

                                                if (isAttendanceWindow) {
                                                    sendAttendanceLog(currentFrameB64);
                                                } else {
                                                    addLog('INFO: Diluar jam presensi (Batas: 20:00 WIB). Presensi tidak dicatat.', 'warn');
                                                }
                                            }
                                        }

                                        setTimeout(() => {
                                            triggerUnlock();
                                        }, 500);
                                    } else {
                                        window.verificationState = 'not_found';
                                        const errMsg = verifyData.error ? verifyData.error : 'Wajah tidak cocok.';
                                        addLog(`GAGAL: Verifikasi wajah gagal! ${errMsg}`, 'error');
                                        currentProgress = 0;
                                        progressBar.style.width = '0%';
                                        progressText.innerText = 'VERIFIKASI GAGAL';
                                        setTimeout(() => { window.isVerifying = false; }, 1000);
                                    }
                                }).catch(err => {
                                    console.error(err);
                                    window.verificationState = 'not_found';
                                    addLog('GAGAL: Koneksi server verifikasi bermasalah.', 'error');
                                    currentProgress = 0;
                                    progressBar.style.width = '0%';
                                    progressText.innerText = 'KONEKSI BERMASALAH';
                                    window.isVerifying = false;
                                });
                            }
                        } else {
                            // Face not detected
                            faceDetected = false;
                            window.verificationState = 'not_found';
                            addLog('Arahkan wajah ke kamera.', 'warn');
                            if (!hasFaceSignature) {
                                continuousDetections = Math.max(0, continuousDetections - 1);
                                currentProgress = Math.min(100, Math.round((continuousDetections / 3) * 100));
                                progressBar.style.width = currentProgress + '%';
                                progressText.innerText = `MEREKAM WAJAH: ${currentProgress}%`;
                            } else {
                                currentProgress = 0;
                                progressBar.style.width = '0%';
                                progressText.innerText = 'WAJAH BELUM TERDETEKSI';
                            }
                        }

                    } catch (err) {
                        console.error(err);
                        addLog('ERROR: Terjadi kesalahan koneksi ke Python OpenCV Server!', 'error');
                    } finally {
                        isProcessing = false;
                    }
                }, 500);
            }

            async function saveFaceSignature(signature) {
                addLog('Menyimpan data wajah...', 'info');
                try {
                    const response = await fetch('/update-face-signature', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ face_signature: signature })
                    });
                    
                    const data = await response.json();
                    if (data.success) {
                        addLog('SUCCESS: Tanda tangan wajah berhasil disimpan!', 'success');
                        setTimeout(() => {
                            triggerUnlock();
                        }, 800);
                    } else {
                        addLog('ERROR: Gagal menyimpan data wajah ke server.', 'error');
                    }
                } catch (err) {
                    console.error(err);
                    addLog('ERROR: Terjadi kesalahan jaringan.', 'error');
                }
            }

            window.attendanceLogged = false;
            function sendAttendanceLog(frameB64) {
                if (window.attendanceLogged) return;
                window.attendanceLogged = true;

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(position => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        const locationText = `GPS: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                        postAttendance(frameB64, locationText);
                    }, err => {
                        let errMsg = 'Akses Lokasi Ditolak';
                        if (err.code === err.TIMEOUT) {
                            errMsg = 'Timeout Akses Lokasi';
                        } else if (err.code === err.POSITION_UNAVAILABLE) {
                            errMsg = 'Posisi Tidak Tersedia';
                        }
                        console.warn('Geolocation failed:', err);
                        postAttendance(frameB64, `GPS Gagal (${errMsg})`);
                    }, { timeout: 5000, enableHighAccuracy: true });
                } else {
                    postAttendance(frameB64, 'GPS Tidak Didukung Browser');
                }
            }

            function postAttendance(frameB64, location) {
                fetch('{{ route('lecturer.attendance.record') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        image: frameB64,
                        location: location
                    })
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        addLog('Presensi Kehadiran berhasil dicatat!', 'success');
                    }
                }).catch(err => console.error('Gagal mencatat presensi:', err));
            }

            function triggerUnlock() {
                sessionStorage.setItem('biometric_verified', 'true');
                addLog('Verifikasi berhasil. Membuka dashboard...', 'success');
                
                // Clean up intervals and animation loops
                isDrawing = false;
                clearInterval(detectionInterval);
                if (animationFrameId) cancelAnimationFrame(animationFrameId);
                
                // Stop webcam stream completely and clear element source
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
                if (video) {
                    video.srcObject = null;
                }

                // Restore header, bottom navigation tab bar, and desktop navigation
                const header = document.querySelector('.top-app-bar');
                const tabBar = document.querySelector('.bottom-tab-bar');
                const desktopNav = document.querySelector('nav.desktop-nav');
                if (header) header.style.display = 'flex';
                if (tabBar) tabBar.style.display = 'flex';
                if (desktopNav) desktopNav.style.display = 'flex';
                
                // Determine redirect URL based on role
                let redirectUrl = '/dashboard';
                if (userRole === 'admin') {
                    redirectUrl = '/admin/dashboard';
                } else if (userRole === 'lecturer') {
                    redirectUrl = '/lecturer/dashboard';
                }

                setTimeout(() => {
                    if (biometricGate) {
                        biometricGate.style.transform = 'translateY(-100%)';
                        biometricGate.style.opacity = '0';
                    }
                    
                    setTimeout(() => {
                        if (biometricGate) {
                            biometricGate.style.display = 'none';
                        }
                        document.body.style.overflow = ''; // Restore body scrollbar
                        
                        // Redirect to the correct role dashboard
                        window.location.href = redirectUrl;
                    }, 800);
                }, 500);
            }
        }
    });
</script>
