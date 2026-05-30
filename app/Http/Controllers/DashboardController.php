<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function studentDashboard()
    {
        $bookings = Auth::user()->bookings()->latest()->limit(5)->get();
        $rooms = Room::where('status', 'available')->count(); // keep this for the counter card
        $allRooms = Room::orderBy('name')->get(); // fetch all rooms to display on the dashboard
        return view('dashboard.student', compact('bookings', 'rooms', 'allRooms'));
    }

    public function adminDashboard()
    {
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $approvedBookings = Booking::where('status', 'approved')->count();
        $rejectedBookings = Booking::where('status', 'rejected')->count();
        $totalRooms = Room::count();
        $recentBookings = Booking::with(['user', 'room'])->latest()->limit(5)->get();

        return view('dashboard.admin', compact(
            'totalBookings',
            'pendingBookings',
            'approvedBookings',
            'rejectedBookings',
            'totalRooms',
            'recentBookings'
        ));
    }

    public function lecturerDashboard()
    {
        $schedules = \App\Models\LecturerSchedule::where('user_id', Auth::id())
            ->with('room')
            ->orderBy('start_time')
            ->get();

        // Check if lecturer already attended in the current 6 AM cycle
        $now = now();
        $sixAmToday = now()->today()->setTime(6, 0, 0);
        $cycleStart = $now->lt($sixAmToday) 
            ? now()->yesterday()->setTime(6, 0, 0) 
            : $sixAmToday;

        $alreadyAttended = \App\Models\Attendance::where('user_id', Auth::id())
            ->where('created_at', '>=', $cycleStart)
            ->exists();

        return view('dashboard.lecturer', compact('schedules', 'alreadyAttended'));
    }

    public function confirmSchedule(\App\Models\LecturerSchedule $schedule)
    {
        if ($schedule->user_id !== Auth::id()) {
            abort(403);
        }

        $schedule->update(['status' => 'selesai']);
        $schedule->room->update(['status' => 'maintenance']);

        return back()->with('success', 'Kelas berhasil dikonfirmasi! Ruangan terisi (Dosen mengajar).');
    }

    public function finishSchedule(\App\Models\LecturerSchedule $schedule)
    {
        if ($schedule->user_id !== Auth::id()) {
            abort(403);
        }

        $schedule->update(['status' => 'selesai_selesai']);
        $schedule->room->update(['status' => 'available']);

        return back()->with('success', 'Kelas telah diakhiri. Ruangan kembali tersedia.');
    }

    public function recordAttendance(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'image' => 'required|string',
            'location' => 'nullable|string',
        ]);

        $now = now();
        // Hanya izinkan mencatat kehadiran pada pukul 06:00 - 20:00 WIB
        if ($now->hour < 6 || $now->hour >= 20) {
            return response()->json([
                'success' => false,
                'message' => 'Presensi ditolak. Batas waktu presensi kehadiran (06:00 - 20:00 WIB) telah berakhir.'
            ], 403);
        }

        // Check if lecturer already attended in the current 6 AM cycle
        $sixAmToday = now()->today()->setTime(6, 0, 0);
        $cycleStart = $now->lt($sixAmToday) 
            ? now()->yesterday()->setTime(6, 0, 0) 
            : $sixAmToday;

        $exists = \App\Models\Attendance::where('user_id', Auth::id())
            ->where('created_at', '>=', $cycleStart)
            ->exists();

        if ($exists) {
            return response()->json(['success' => true, 'message' => 'Attendance already recorded for today.']);
        }

        \App\Models\Attendance::create([
            'user_id' => Auth::id(),
            'scan_photo' => $request->image,
            'location' => $request->location ?? 'GPS Tidak Terdeteksi',
        ]);

        return response()->json(['success' => true]);
    }
}