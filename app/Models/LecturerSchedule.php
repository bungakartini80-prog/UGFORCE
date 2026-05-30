<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LecturerSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'day_of_week',
        'start_time',
        'end_time',
        'subject',
        'class_name',
        'status',
    ];

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
