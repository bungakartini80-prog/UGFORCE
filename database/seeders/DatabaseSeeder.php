<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Sekretariat',
            'email' => 'admin@ugforce.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Contoh mahasiswa
        User::create([
            'name' => 'Mahasiswa 1',
            'email' => 'student@ugforce.com',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        // Contoh Dosen
        $lecturer = User::create([
            'name' => 'Prof. Dr. Ir. Budi Santoso, M.T.',
            'email' => 'dosen@ugforce.com',
            'password' => Hash::make('password'),
            'role' => 'lecturer',
            'face_signature' => 'mock_signature_prof_budi', // Seed a default face signature
        ]);

        $this->call(RoomSeeder::class);

        // Get some seeded rooms
        $room1 = \App\Models\Room::where('name', 'J114')->first();
        $room2 = \App\Models\Room::where('name', 'J123')->first();

        if ($room1 && $room2) {
            \App\Models\LecturerSchedule::create([
                'user_id' => $lecturer->id,
                'room_id' => $room1->id,
                'day_of_week' => 'Senin',
                'start_time' => '08:30:00',
                'end_time' => '10:30:00',
                'subject' => 'Rekayasa Perangkat Lunak',
                'class_name' => '3IA15',
            ]);

            \App\Models\LecturerSchedule::create([
                'user_id' => $lecturer->id,
                'room_id' => $room2->id,
                'day_of_week' => 'Rabu',
                'start_time' => '13:30:00',
                'end_time' => '15:30:00',
                'subject' => 'Kecerdasan Buatan (Artificial Intelligence)',
                'class_name' => '3IA15',
            ]);
        }
    }
}