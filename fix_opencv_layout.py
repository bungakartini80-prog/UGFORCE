import os
import re

files = [
    r'c:\xampp3\htdocs\ugforce\ugforce\resources\views\dashboard\admin.blade.php',
    r'c:\xampp3\htdocs\ugforce\ugforce\resources\views\dashboard\student.blade.php',
    r'c:\xampp3\htdocs\ugforce\ugforce\resources\views\dashboard\lecturer.blade.php'
]

# 1. Update biometric gate CSS & Scanner Box CSS
css_old = r"""    #biometric-gate {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(240, 249, 255, 0.98);
        backdrop-filter: blur(15px);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        overflow-y: auto;
        padding: 30px 0;
        transition: transform 1s cubic-bezier(0.85, 0, 0.15, 1), opacity 0.8s ease;
    }"""

css_new = r"""    #biometric-gate {
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
    }"""

css_gate_old2 = r"""        justify-content: flex-start;
        overflow-y: auto;
        padding: 30px 0;"""
css_gate_new2 = r"""        justify-content: center;
        overflow-y: auto;
        padding: 20px 0;"""

scanner_css_old = r"""    .scanner-box {
        width: 240px;
        height: 240px;
        border-radius: 50%;
        border: 4px solid #0ea5e9;
        position: relative;
        overflow: hidden;
        box-shadow: 0 0 20px rgba(14, 165, 233, 0.15);
    }

    .dark .scanner-box {
        border-color: #3b82f6;
        box-shadow: 0 0 40px rgba(59, 130, 246, 0.4);
    }"""

scanner_css_new = r"""    .scanner-box {
        width: 240px;
        height: 240px;
        border-radius: 50%;
        border: 2px solid rgba(14, 165, 233, 0.5);
        position: relative;
        overflow: hidden;
        box-shadow: 0 0 30px rgba(14, 165, 233, 0.3), inset 0 0 20px rgba(14, 165, 233, 0.2);
    }

    .dark .scanner-box {
        border-color: rgba(59, 130, 246, 0.5);
        box-shadow: 0 0 50px rgba(59, 130, 246, 0.5), inset 0 0 30px rgba(59, 130, 246, 0.3);
    }"""

# HTML Rings
html_rings_old = r"""        <!-- Dynamic Circular Scanner -->
        <div class="relative mb-8">
            <div class="scanner-box">
                <div class="scanner-laser"></div>
                <div class="scanner-grid"></div>
                <!-- Video & Canvas for Real-Time Camera -->
                <video id="gate-video" autoplay playsinline muted class="absolute inset-0 w-full h-full object-cover z-0" style="transform: scaleX(1);"></video>
                <canvas id="gate-canvas" class="absolute inset-0 w-full h-full object-cover z-10 pointer-events-none"></canvas>
                <!-- Pulsing Face Icon Placeholder (shown when camera starting) -->
                <div id="gate-placeholder" class="absolute inset-0 flex items-center justify-center z-20 bg-slate-950/80">
                    <i class="bi bi-person-bounding-box text-sky-600/25 dark:text-blue-500/25 animate-pulse" style="font-size: 80px;"></i>
                </div>
            </div>
            
            <!-- Target Face Ring indicator -->
            <div class="absolute -inset-4 border-2 border-dashed border-sky-500/15 dark:border-blue-500/30 rounded-full animate-[spin_30s_linear_infinite] pointer-events-none"></div>
        </div>"""

html_rings_new = r"""        <!-- Dynamic Circular Scanner -->
        <div class="relative mb-8 mt-4">
            <div class="scanner-box z-10">
                <div class="scanner-laser"></div>
                <div class="scanner-grid"></div>
                <!-- Video & Canvas for Real-Time Camera -->
                <video id="gate-video" autoplay playsinline muted class="absolute inset-0 w-full h-full object-cover z-0" style="transform: scaleX(1);"></video>
                <canvas id="gate-canvas" class="absolute inset-0 w-full h-full object-cover z-10 pointer-events-none"></canvas>
                <!-- Pulsing Face Icon Placeholder (shown when camera starting) -->
                <div id="gate-placeholder" class="absolute inset-0 flex items-center justify-center z-20 bg-slate-950/80">
                    <i class="bi bi-person-bounding-box text-sky-600/25 dark:text-blue-500/25 animate-pulse" style="font-size: 80px;"></i>
                </div>
            </div>
            
            <!-- HUD Target Face Rings (Professional look) -->
            <div class="absolute -inset-4 border-2 border-dashed border-sky-500/60 dark:border-blue-500/60 rounded-full animate-[spin_20s_linear_infinite] pointer-events-none z-0"></div>
            <div class="absolute -inset-8 border-2 border-transparent border-t-sky-500 border-b-sky-500 opacity-70 rounded-full animate-[spin_12s_linear_infinite_reverse] pointer-events-none z-0"></div>
            <div class="absolute -inset-10 border border-sky-500/30 rounded-full animate-pulse pointer-events-none z-0"></div>
        </div>"""

# JS Scrollbar fixes
js_init_old = r"""    function initBiometricGate() {
        // Hide navbar to prevent overlap with gate
        const navbar = document.querySelector('nav');
        if (navbar) {
            navbar.style.display = 'none';
        }"""
js_init_new = r"""    function initBiometricGate() {
        // Hide navbar to prevent overlap with gate
        const navbar = document.querySelector('nav');
        if (navbar) {
            navbar.style.display = 'none';
        }
        document.body.style.overflow = 'hidden'; // Fix double scrollbar"""

js_unlock_old = r"""                setTimeout(() => {
                    biometricGate.style.display = 'none';
                }, 1000);"""
js_unlock_new = r"""                setTimeout(() => {
                    biometricGate.style.display = 'none';
                    document.body.style.overflow = ''; // Restore body scrollbar
                }, 1000);"""

for file in files:
    with open(file, 'r', encoding='utf-8') as f:
        content = f.read()

    # CSS Gate
    if css_old in content:
        content = content.replace(css_old, css_new)
    else:
        content = content.replace(css_gate_old2, css_gate_new2)

    # Scanner CSS
    content = content.replace(scanner_css_old, scanner_css_new)
    
    # HTML Rings
    content = content.replace(html_rings_old, html_rings_new)

    # JS fixes
    content = content.replace(js_init_old, js_init_new)
    content = content.replace(js_unlock_old, js_unlock_new)

    with open(file, 'w', encoding='utf-8') as f:
        f.write(content)
    print(f"Updated {file}")


# 2. Update register.blade.php
reg_file = r'c:\xampp3\htdocs\ugforce\ugforce\resources\views\auth\register.blade.php'
with open(reg_file, 'r', encoding='utf-8') as f:
    reg_content = f.read()

reg_html_old = r"""                                <div class="d-flex justify-content-center mb-3">
                                    <!-- Circular preview overlay -->
                                    <div class="relative w-36 h-36 rounded-full border-2 border-sky-500/30 dark:border-blue-500/30 overflow-hidden shadow-lg bg-slate-100 dark:bg-black/50 flex items-center justify-center">
                                        <!-- Video & Canvas for Real-Time Camera -->
                                        <video id="register-video" autoplay playsinline muted class="absolute inset-0 w-full h-full object-cover z-0" style="transform: scaleX(1);"></video>
                                        <canvas id="register-canvas" class="absolute inset-0 w-full h-full z-10 pointer-events-none"></canvas>
                                        <!-- Pulsing Face Icon Placeholder (shown when camera starting) -->
                                        <div id="register-placeholder" class="absolute inset-0 flex items-center justify-center z-20 bg-slate-950/85">
                                            <i class="bi bi-person-bounding-box text-sky-600/25 dark:text-blue-500/25 animate-pulse" style="font-size: 55px;"></i>
                                        </div>
                                    </div>
                                </div>"""

reg_html_new = r"""                                <div class="d-flex justify-content-center mb-5 mt-4">
                                    <!-- Circular preview overlay with HUD -->
                                    <div class="relative">
                                        <div class="w-36 h-36 rounded-full border-2 border-sky-500/50 overflow-hidden shadow-[0_0_20px_rgba(14,165,233,0.3)] bg-slate-100 dark:bg-black/50 flex items-center justify-center relative z-10">
                                            <!-- Video & Canvas for Real-Time Camera -->
                                            <video id="register-video" autoplay playsinline muted class="absolute inset-0 w-full h-full object-cover z-0" style="transform: scaleX(1);"></video>
                                            <canvas id="register-canvas" class="absolute inset-0 w-full h-full z-10 pointer-events-none"></canvas>
                                            <!-- Pulsing Face Icon Placeholder (shown when camera starting) -->
                                            <div id="register-placeholder" class="absolute inset-0 flex items-center justify-center z-20 bg-slate-950/85">
                                                <i class="bi bi-person-bounding-box text-sky-600/25 dark:text-blue-500/25 animate-pulse" style="font-size: 55px;"></i>
                                            </div>
                                        </div>
                                        <!-- HUD Rings -->
                                        <div class="absolute -inset-3 border-2 border-dashed border-sky-500/60 rounded-full animate-[spin_15s_linear_infinite] pointer-events-none z-0"></div>
                                        <div class="absolute -inset-6 border-2 border-transparent border-t-sky-500 border-b-sky-500 opacity-60 rounded-full animate-[spin_8s_linear_infinite_reverse] pointer-events-none z-0"></div>
                                        <div class="absolute -inset-8 border border-sky-500/30 rounded-full animate-pulse pointer-events-none z-0"></div>
                                    </div>
                                </div>"""

reg_content = reg_content.replace(reg_html_old, reg_html_new)
with open(reg_file, 'w', encoding='utf-8') as f:
    f.write(reg_content)
print(f"Updated {reg_file}")
