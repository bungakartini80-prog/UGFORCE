@extends('layouts.app')

@section('content')
<style>
    /* =========================================
       AUTH FORM ISOLATED CSS
    ========================================= */
    .auth-wrapper {
        min-height: calc(100vh - 160px); 
        display: flex;
        align-items: center;
        justify-content: center;
        padding-top: 40px; 
        padding-bottom: 60px;
        perspective: 1200px; 
        z-index: 10;
        position: relative;
    }

    .floating-wrapper {
        animation: floatAuth 6s ease-in-out infinite;
        transform-style: preserve-3d;
    }

    @keyframes floatAuth {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    /* KARTU AUTH - DINAMIS TERANG & GELAP */
    .auth-card {
        background: rgba(255, 255, 255, 0.6); /* Terang transparan (Light) */
        backdrop-filter: blur(30px);
        -webkit-backdrop-filter: blur(30px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        border-radius: 28px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        padding: 3.5rem 3rem;
        opacity: 0;
        transform: translateY(20px);
        animation: authFadeIn 0.6s ease forwards;
        transition: all 0.4s ease;
    }

    /* Penyesuaian Kartu saat Dark Mode aktif */
    .dark .auth-card {
        background: rgba(15, 10, 30, 0.4); /* Gelap transparan (Dark) */
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5);
    }

    @keyframes authFadeIn {
        to { opacity: 1; transform: translateY(0); }
    }

    /* Form Input & Label */
    .form-floating label {
        color: #64748b !important; 
        padding-left: 1.25rem !important;
        padding-right: 3.5rem !important; 
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        width: 100% !important;
        z-index: 5 !important;
        font-weight: 600;
        background: transparent !important;
    }
    .dark .form-floating label { color: #3b82f6 !important; }

    .form-floating label::before,
    .form-floating label::after { display: none !important; }
    
    .form-floating > .form-control {
        background-color: rgba(0, 0, 0, 0.04) !important;
        border: 1px solid rgba(0, 0, 0, 0.1) !important;
        border-radius: 14px !important;
        color: #0f172a !important; /* Teks Hitam (Light) */
        height: 65px !important; 
        padding-left: 1.25rem !important;
        box-shadow: none !important;
        font-family: inherit;
        font-weight: 600;
        transition: all 0.3s ease !important;
    }

    .dark .form-floating > .form-control {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: #ffffff !important; /* Teks Putih (Dark) */
    }

    /* Autofill & Fokus */
    .form-floating > .form-control:focus,
    .form-floating > .form-control:not(:placeholder-shown) {
        background-color: #ffffff !important;
        border-color: #3b82f6 !important;
        color: #000000 !important;
    }
    .dark .form-floating > .form-control:focus,
    .dark .form-floating > .form-control:not(:placeholder-shown) {
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: #ffffff !important;
    }

    /* Fix Autofill Chrome */
    input:-webkit-autofill {
        -webkit-box-shadow: 0 0 0px 1000px #ffffff inset !important; 
        -webkit-text-fill-color: #000000 !important; 
    }
    .dark input:-webkit-autofill {
        -webkit-box-shadow: 0 0 0px 1000px #1a103c inset !important; 
        -webkit-text-fill-color: #ffffff !important; 
    }

    /* Tombol Login Tetap Menonjol */
    .btn-auth {
        background: linear-gradient(135deg, #3b82f6, #3b82f6) !important;
        color: #ffffff !important;
        border-radius: 14px;
        padding: 16px;
        font-weight: 700;
        border: none;
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
        transition: all 0.3s ease;
    }
    .btn-auth:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(59, 130, 246, 0.5);
    }

    /* =========================================
       3D OFFICE AVATAR ANIMATIONS & STYLES
    ========================================= */
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

    /* State: Password focused (Closed/Shy eyes) */
    .hide-eyes #left-eye-open,
    .hide-eyes #right-eye-open {
        display: none !important;
    }
    .hide-eyes #left-eye-closed,
    .hide-eyes #right-eye-closed {
        display: block !important;
    }
    
    /* Laser Sweep Animation */
    .laser-sweep {
        animation: laserScan 1.8s linear infinite;
    }
    @keyframes laserScan {
        0% { top: 0%; }
        50% { top: 100%; }
        100% { top: 0%; }
    }
</style>

<div class="auth-wrapper">
    <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4 floating-wrapper">
        <div class="auth-card">
            <!-- 3D Office Character Avatar Container -->
            <div id="avatar-container" class="relative mx-auto mb-6 flex justify-center items-center w-36 h-36 waving">
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
            </div>

            <div class="text-center mb-10">
                <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white">
                    PORTAL <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#3b82f6] to-[#3b82f6]">LOGIN</span>
                </h3>
                <p class="text-slate-500 dark:text-gray-400">Silakan masuk ke akun Anda</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-floating mb-4">
                    <input type="email" name="email" class="form-control" id="fEmail" placeholder="Email" required autofocus>
                    <label for="fEmail"><i class="bi bi-envelope me-2"></i>Email Gunadarma</label>
                </div>

                <div class="form-floating mb-4 position-relative">
                    <input type="password" name="password" class="form-control" id="fPass" placeholder="Password" required>
                    <label for="fPass"><i class="bi bi-key me-2"></i>Password</label>
                </div>

                <button type="submit" class="btn-auth w-full">MASUK SEKARANG</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Clear biometric verify state on loading login page to force re-verification on next login session
        sessionStorage.removeItem('biometric_verified');

        const avatarContainer = document.getElementById('avatar-container');
        const emailInput = document.getElementById('fEmail');
        const passInput = document.getElementById('fPass');
        const loginForm = document.querySelector('form');
        
        // Welcome wave duration: waves for 4 seconds on load, then goes to idle
        setTimeout(() => {
            if (avatarContainer && !avatarContainer.classList.contains('shake-head')) {
                avatarContainer.classList.remove('waving');
            }
        }, 4000);

        // Password focus interaction (close/cover eyes)
        if (passInput && avatarContainer) {
            passInput.addEventListener('focus', () => {
                avatarContainer.classList.add('hide-eyes');
                avatarContainer.classList.remove('waving');
            });
            passInput.addEventListener('blur', () => {
                avatarContainer.classList.remove('hide-eyes');
                avatarContainer.classList.add('waving');
                setTimeout(() => {
                    if (!passInput.matches(':focus')) {
                        avatarContainer.classList.remove('waving');
                    }
                }, 2000);
            });
        }

        // Form submit client-side validation check
        if (loginForm && avatarContainer) {
            loginForm.addEventListener('submit', (e) => {
                sessionStorage.removeItem('biometric_verified');
                if (!emailInput.value || !passInput.value) {
                    e.preventDefault();
                    triggerHeadShake();
                }
            });
        }

        function triggerHeadShake() {
            avatarContainer.classList.add('shake-head');
            avatarContainer.classList.remove('waving');
            avatarContainer.classList.remove('hide-eyes');
            
            setTimeout(() => {
                avatarContainer.classList.remove('shake-head');
                avatarContainer.classList.add('waving');
                setTimeout(() => {
                    avatarContainer.classList.remove('waving');
                }, 2000);
            }, 2500);
        }

        // Handle server-side errors passed to JS
        @if($errors->any() || session('error'))
            triggerHeadShake();
        @endif

    });
</script>
@endsection