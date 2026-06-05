<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// --- RUTE HALAMAN UTAMA (WELCOME SCREEN) ---
Route::get('/debug', function () {
    return "Debug OK";
});

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/login-face', [AuthController::class, 'loginFace'])->name('login.face');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::post('/update-face-signature', [AuthController::class, 'updateFaceSignature'])->name('update.face.signature')->middleware('auth');

// --- SMART ROUTE DASHBOARD (PENGARAH OTOMATIS) ---
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        // Cek jika user login sebagai admin, lempar otomatis ke /admin/dashboard
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        // Cek jika user login sebagai lecturer (dosen), lempar ke lecturer dashboard
        if (auth()->user()->role === 'lecturer') {
            return redirect()->route('lecturer.dashboard');
        }
        // Jika student, jalankan dashboard student
        return app(DashboardController::class)->studentDashboard();
    })->name('dashboard');

    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
});

// Lecturer (Dosen) routes
Route::middleware(['auth', 'role:lecturer'])->prefix('lecturer')->name('lecturer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'lecturerDashboard'])->name('dashboard');
    Route::post('/schedules/{schedule}/confirm', [DashboardController::class, 'confirmSchedule'])->name('schedules.confirm');
    Route::post('/schedules/{schedule}/finish', [DashboardController::class, 'finishSchedule'])->name('schedules.finish');
    Route::post('/attendance/record', [DashboardController::class, 'recordAttendance'])->name('attendance.record');
});

// Student & Lecturer routes
Route::middleware(['auth', 'role:student,lecturer'])->group(function () {
    Route::resource('rooms', RoomController::class)->only(['index', 'show']);
    Route::resource('bookings', BookingController::class)->except(['edit', 'update', 'destroy']);
    Route::delete('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

    // Room management
    Route::get('/rooms', [AdminController::class, 'indexRooms'])->name('rooms');
    Route::get('/rooms/create', [AdminController::class, 'createRoom'])->name('rooms.create');
    Route::post('/rooms', [AdminController::class, 'storeRoom'])->name('rooms.store');
    Route::get('/rooms/{room}/edit', [AdminController::class, 'editRoom'])->name('rooms.edit');
    Route::put('/rooms/{room}', [AdminController::class, 'updateRoom'])->name('rooms.update');
    Route::delete('/rooms/{room}', [AdminController::class, 'destroyRoom'])->name('rooms.destroy');

    // Booking verification
    Route::get('/bookings', [AdminController::class, 'indexBookings'])->name('bookings');
    Route::patch('/bookings/{booking}/approve', [AdminController::class, 'approveBooking'])->name('bookings.approve');
    Route::patch('/bookings/{booking}/reject', [AdminController::class, 'rejectBooking'])->name('bookings.reject');

    // Lecturer Attendance logs
    Route::get('/attendance', [AdminController::class, 'indexAttendance'])->name('attendance');
    Route::delete('/attendance/{attendance}', [AdminController::class, 'destroyAttendance'])->name('attendance.destroy');

    // ========== LAPORAN DATA ==========
    Route::get('/reports', [AdminController::class, 'indexReports'])->name('reports');
    Route::get('/reports/export/pdf', [AdminController::class, 'exportPdf'])->name('reports.pdf');
    Route::get('/reports/export/excel', [AdminController::class, 'exportExcel'])->name('reports.excel');

    // ========== MANAJEMEN USER ==========
    Route::get('/users', [AdminController::class, 'indexUsers'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

    // ========== PROFIL ADMIN ==========
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::post('/profile/update-avatar', [AdminController::class, 'updateAvatar'])->name('profile.update-avatar');
    Route::post('/profile/clear-avatar', [AdminController::class, 'clearAvatar'])->name('profile.clear-avatar');

    // ========== MANAJEMEN JADWAL DOSEN ==========
    Route::get('/schedules', [AdminController::class, 'indexSchedules'])->name('schedules');
    Route::get('/schedules/create', [AdminController::class, 'createSchedule'])->name('schedules.create');
    Route::post('/schedules', [AdminController::class, 'storeSchedule'])->name('schedules.store');
    Route::post('/schedules/reset-all', [AdminController::class, 'resetAllSchedules'])->name('schedules.reset-all');
    Route::post('/schedules/{schedule}/reset', [AdminController::class, 'resetSchedule'])->name('schedules.reset');
    Route::delete('/schedules/{schedule}', [AdminController::class, 'destroySchedule'])->name('schedules.destroy');
});

// --- RUTE MOCK FACE API (ULTRA-FAST & ANTI-LAG) ---
Route::post('/api/face/detect', function (\Illuminate\Http\Request $request) {
    return response()->json([
        "detected" => true,
        "faces" => [
            [
                "x" => 100,
                "y" => 80,
                "w" => 120,
                "h" => 120,
                "confidence" => 0.98,
                "loading_progress" => 98
            ]
        ]
    ]);
});

Route::post('/api/face/verify', function (\Illuminate\Http\Request $request) {
    return response()->json([
        "verified" => true,
        "distance" => 0.28,
        "similarity" => 93.6,
        "checks" => [
            [
                "model" => "ArcFace",
                "distance" => 0.28,
                "threshold" => 0.65,
                "passed" => true
            ]
        ],
        "confidence" => [
            "webcam" => 0.98,
            "stored" => 0.98
        ]
    ]);
});

Route::get('/api/face/health', function () {
    return response()->json([
        "status" => "ok",
        "engine" => "Mock Face API (Laravel Vercel)",
        "time" => time()
    ]);
});