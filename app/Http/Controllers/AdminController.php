<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // ========== PROFIL ADMIN ==========
    public function profile()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        return view('admin.profile', compact('user'));
    }

    public function updateAvatar(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'avatar' => 'required|string',
        ]);

        $user = \Illuminate\Support\Facades\Auth::user();
        \App\Models\User::where('id', $user->id)->update([
            'avatar' => $request->avatar,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil diperbarui.',
        ]);
    }

    public function clearAvatar(\Illuminate\Http\Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        \App\Models\User::where('id', $user->id)->update([
            'avatar' => null,
            'face_signature' => null,
        ]);

        return redirect()->route('admin.profile')->with('success', 'Foto profil dan data biometrik berhasil dihapus.');
    }

    // ========== MANAJEMEN RUANG ==========
    public function indexRooms()
    {
        $rooms = Room::all();
        return view('admin.rooms.index', compact('rooms'));
    }

    public function createRoom()
    {
        return view('admin.rooms.create');
    }

    public function storeRoom(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lantai' => 'required|integer|min:1|max:6',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|in:available,maintenance',
        ]);

        Room::create($validated);
        return redirect()->route('admin.rooms')->with('success', 'Ruangan baru berhasil ditambahkan ke sistem.');
    }

    public function editRoom(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function updateRoom(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lantai' => 'required|integer|min:1|max:6',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|in:available,maintenance',
        ]);

        $room->update($validated);
        return redirect()->route('admin.rooms')->with('success', 'Perubahan data ruangan berhasil disimpan.');
    }

    // ========== LAPORAN DATA (REAL-TIME) ==========
    public function indexReports()
    {
        $totalBookings = Booking::count();

        // 1. Data Line Chart (Tren Bulanan Tahun Ini)
        $monthlyBookings = Booking::selectRaw('MONTH(booking_date) as month, COUNT(*) as count')
            ->whereYear('booking_date', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')->toArray();
        
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $lineLabels = [];
        $lineData = [];
        
        // Looping bulan 1 sampai bulan saat ini
        for ($i = 1; $i <= date('n'); $i++) {
            $lineLabels[] = $months[$i - 1];
            $lineData[] = $monthlyBookings[$i] ?? 0; // Jika kosong, set 0 biar grafik tetep jalan
        }

        // 2. Data Bar Chart (Ruangan Paling Sering Dipakai)
        $roomUsage = Booking::selectRaw('room_id, COUNT(*) as count')
            ->with('room')
            ->groupBy('room_id')
            ->orderByDesc('count')
            ->take(5) // Ambil 5 ruangan teratas
            ->get();
        
        $barLabels = [];
        $barData = [];
        foreach($roomUsage as $usage) {
            if($usage->room) {
                $barLabels[] = $usage->room->name;
                $barData[] = $usage->count;
            }
        }

        return view('admin.reports.index', compact('totalBookings', 'lineLabels', 'lineData', 'barLabels', 'barData'));
    }

    public function exportPdf() { return back()->with('success', 'Fitur Cetak PDF sedang dipersiapkan!'); }
    public function exportExcel() { return back()->with('success', 'Fitur Ekspor Excel sedang dipersiapkan!'); }

    // ========== MANAJEMEN USER ==========
    public function indexUsers()
    {
        $users = \App\Models\User::latest()->get(); 
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $val = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,student,lecturer'
        ]);
        $val['password'] = bcrypt($val['password']); // Hash password

        \App\Models\User::create($val);
        return redirect()->route('admin.users')->with('success', 'Akun user baru berhasil ditambahkan!');
    }

    public function editUser(\App\Models\User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, \App\Models\User $user)
    {
        $val = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => 'required|in:admin,student,lecturer'
        ]);

        if($request->filled('password')) {
            $val['password'] = bcrypt($request->password);
        }

        $user->update($val);
        return redirect()->route('admin.users')->with('success', 'Data user berhasil diupdate!');
    }

    public function destroyUser(\App\Models\User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Akun user dihapus secara permanen.');
    }
    public function destroyRoom(Room $room)
    {
        $room->delete();
        return redirect()->route('admin.rooms')->with('success', 'Ruangan berhasil dihapus secara permanen.');
    }

    // ========== MANAJEMEN BOOKING ==========
    public function indexBookings()
    {
        $bookings = Booking::with(['user', 'room'])->latest()->get();
        return view('admin.bookings.index', compact('bookings'));
    }

    public function approveBooking(Booking $booking)
    {
        $booking->update(['status' => 'approved']);
        return back()->with('success', 'Peminjaman disetujui.');
    }

    public function rejectBooking(Booking $booking)
    {
        $booking->update(['status' => 'rejected']);
        return back()->with('success', 'Peminjaman ditolak.');
    }

    public function indexAttendance()
    {
        $attendances = \App\Models\Attendance::with('user')->latest()->get();
        return view('admin.attendance.index', compact('attendances'));
    }

    public function destroyAttendance(\App\Models\Attendance $attendance)
    {
        $attendance->delete();
        return back()->with('success', 'Log kehadiran dosen berhasil dihapus.');
    }

    // ========== MANAJEMEN JADWAL DOSEN ==========
    public function indexSchedules()
    {
        $schedules = \App\Models\LecturerSchedule::with(['lecturer', 'room'])->latest()->get();
        return view('admin.schedules.index', compact('schedules'));
    }

    public function createSchedule()
    {
        $lecturers = \App\Models\User::where('role', 'lecturer')->get();
        $rooms = Room::all();
        return view('admin.schedules.create', compact('lecturers', 'rooms'));
    }

    public function storeSchedule(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'room_id' => 'required|exists:rooms,id',
            'day_of_week' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
            'subject' => 'required|string|max:255',
            'class_name' => 'required|string|max:255',
        ]);

        \App\Models\LecturerSchedule::create($validated);
        return redirect()->route('admin.schedules')->with('success', 'Jadwal kuliah dosen berhasil ditambahkan.');
    }

    public function resetSchedule(\App\Models\LecturerSchedule $schedule)
    {
        $schedule->update(['status' => 'ready']);
        if ($schedule->room) {
            $schedule->room->update(['status' => 'available']);
        }
        return redirect()->route('admin.schedules')->with('success', 'Jadwal kuliah berhasil direset menjadi Ready. Dosen dapat melakukan konfirmasi kembali.');
    }

    public function resetAllSchedules()
    {
        $schedules = \App\Models\LecturerSchedule::whereIn('status', ['selesai', 'selesai_selesai'])->get();
        
        if ($schedules->isEmpty()) {
            return redirect()->route('admin.schedules')->with('info', 'Tidak ada jadwal kelas selesai yang perlu direset.');
        }

        foreach ($schedules as $schedule) {
            $schedule->update(['status' => 'ready']);
            if ($schedule->room) {
                $schedule->room->update(['status' => 'available']);
            }
        }

        return redirect()->route('admin.schedules')->with('success', count($schedules) . ' jadwal kuliah berhasil direset menjadi Ready.');
    }

    public function destroySchedule(\App\Models\LecturerSchedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.schedules')->with('success', 'Jadwal kuliah dosen berhasil dihapus.');
    }
}