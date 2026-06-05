<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Cek konflik dengan booking lain yang sudah approved/pending
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
            return back()->withErrors(['room_id' => 'Ruangan sudah dipesan pada waktu tersebut.'])->withInput();
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'room_id' => $validated['room_id'],
            'booking_date' => $validated['booking_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'purpose' => $validated['purpose'],
            'status' => 'pending',
        ]);

        return redirect()->route('bookings.index')->with('success', 'Peminjaman diajukan, menunggu verifikasi admin.');
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
        $booking->update(['status' => 'completed']);
        return redirect()->route('bookings.index')->with('success', 'Ruangan selesai digunakan.');
    }
}