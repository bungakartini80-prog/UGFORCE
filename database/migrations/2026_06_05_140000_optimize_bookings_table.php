<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Convert status column type from enum to string (VARCHAR) natively on PostgreSQL/SQLite
        // This allows storing any string status, including 'completed'
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE bookings ALTER COLUMN status TYPE VARCHAR(255) USING status::varchar;');
            DB::statement("ALTER TABLE bookings ALTER COLUMN status SET DEFAULT 'pending';");
        } else {
            Schema::table('bookings', function (Blueprint $table) {
                $table->string('status')->default('pending')->change();
            });
        }

        // 2. Add database indexes to speed up lookup times (Supabase optimizer)
        Schema::table('bookings', function (Blueprint $table) {
            $table->index('status');
            $table->index('booking_date');
            $table->index(['room_id', 'booking_date', 'status'], 'bookings_conflict_index');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('bookings_status_index');
            $table->dropIndex('bookings_booking_date_index');
            $table->dropIndex('bookings_conflict_index');
            
            // Revert back to enum
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->change();
        });
    }
};
