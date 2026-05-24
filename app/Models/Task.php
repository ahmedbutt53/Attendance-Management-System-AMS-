<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'assigned_by',
        'title',
        'description',
        'due_date',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    // Relationships
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'task_user');
    }

    public function responses()
    {
        return $this->hasMany(TaskResponse::class);
    }
}
