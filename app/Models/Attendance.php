<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';
    
    protected $fillable = [
        'user_id',
        'attendance_date',
        'status',
        'notes',
    ];

    // Custom accessor and mutator to force Y-m-d format in database storage (especially for SQLite testing compatibility)
    public function getAttendanceDateAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value) : null;
    }

    public function setAttendanceDateAttribute($value)
    {
        $this->attributes['attendance_date'] = $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : null;
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
