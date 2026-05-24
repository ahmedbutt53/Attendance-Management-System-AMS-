<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'user_id',
        'present_days',
        'absent_days',
        'leave_days',
        'grade',
        'calculated_date',
    ];

    protected $casts = [
        'calculated_date' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
