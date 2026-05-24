<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskResponse extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'response',
        'status',
        'feedback',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
