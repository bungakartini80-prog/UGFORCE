<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Auth::user()->bookings()->with('room')->latest()->get();
        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        $rooms = Room::all();
        return view('bookings.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'purpose' => 'required|string|min:5',
        ], [
            'room_id.required' => 'Anda harus memilih salah satu ruangan kelas terlebih dahulu pada panel kiri.',
            'room_id.exists' => 'Ruangan kelas yang dipilih tidak terdaftar.',
            'booking_date.required' => 'Tanggal peminjaman wajib diisi.',
            'booking_date.date' => 'Format tanggal peminjaman tidak valid.',
            'booking_date.after_or_equal' => 'Tanggal peminjaman tidak boleh di masa lalu (minimal hari ini).',
            'start_time.required' => 'Waktu mulai peminjaman wajib diisi.',
            'end_time.required' => 'Waktu selesai peminjaman wajib diisi.',
            'end_time.after' => 'Waktu selesai harus setelah waktu mulai.',
            'purpose.required' => 'Tujuan peminjaman wajib diisi.',
            'purpose.min' => 'Tujuan peminjaman harus berupa penjelasan minimal :min karakter.',
        ]);

        // Cek konflik dengan booking lain yang sudah approved/pending (BUKAN completed/rejected)
        $conflict = Booking::where('room_id', $validated['room_id'])
            ->where('booking_date', $validated['booking_date'])
            ->where(function ($q) use ($validated) {
                $q->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                  ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                  ->orWhere(function ($q) use ($validated) {
                      $q->where('start_time', '<=', $validated['start_time'])
                        ->where('end_time', '>=', $validated['end_time']);
                  });
            })
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($conflict) {
            return back()->withErrors(['room_id' => 'Ruangan sudah dipesan pada waktu tersebut. Silakan pilih waktu lain.'])->withInput();
        }

        try {
            $booking = Booking::create([
                'user_id' => (int) Auth::id(),
                'room_id' => (int) $validated['room_id'],
                'booking_date' => $validated['booking_date'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'purpose' => $validated['purpose'],
                'status' => 'pending',
            ]);

            if (!$booking || !$booking->exists) {
                Log::error('Booking create returned falsy', ['user_id' => Auth::id(), 'data' => $validated]);
                return back()->withErrors(['room_id' => 'Gagal menyimpan data peminjaman. Silakan coba lagi.'])->withInput();
            }

            return redirect()->route('bookings.index')->with('success', 'Peminjaman berhasil diajukan! Menunggu verifikasi admin.');
        } catch (\Exception $e) {
            Log::error('Booking create exception: ' . $e->getMessage(), ['user_id' => Auth::id(), 'data' => $validated]);
            return back()->withErrors(['room_id' => 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage()])->withInput();
        }
    }

    public function cancel(Booking $booking)
    {
        if ((int)$booking->user_id !== (int)Auth::id() || $booking->status !== 'pending') {
            abort(403);
        }
        $booking->delete();
        return redirect()->route('bookings.index')->with('success', 'Peminjaman dibatalkan.');
    }

    public function complete(Booking $booking)
    {
        if ((int)$booking->user_id !== (int)Auth::id() || $booking->status !== 'approved') {
            abort(403);
        }

        try {
            $booking->update(['status' => 'completed']);
            return redirect()->route('bookings.index')->with('success', 'Ruangan selesai digunakan. Anda bisa mengajukan peminjaman baru.');
        } catch (\Exception $e) {
            Log::error('Booking complete exception: ' . $e->getMessage(), ['booking_id' => $booking->id]);
            return back()->withErrors(['error' => 'Gagal menyelesaikan peminjaman: ' . $e->getMessage()]);
        }
    }
}