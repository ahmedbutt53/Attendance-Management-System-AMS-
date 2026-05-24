<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile_picture',
        'date_of_birth',
        'address',
        'department',
        'employee_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function permissions()
    {
        return $this->hasManyThrough(Permission::class, Role::class, 'id', 'id', 'id', 'id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function leavesApproved()
    {
        return $this->hasMany(Leave::class, 'approved_by');
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_user');
    }

    public function tasksAssigned()
    {
        return $this->hasMany(Task::class, 'assigned_by');
    }

    public function taskResponses()
    {
        return $this->hasMany(TaskResponse::class);
    }

    public function taskResponsesReviewed()
    {
        return $this->hasMany(TaskResponse::class, 'reviewed_by');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Calculate and store/update the attendance grade for a specific month.
     */
    public function updateOrCreateGradeForMonth($date = null)
    {
        $carbonDate = $date ? \Carbon\Carbon::parse($date) : \Carbon\Carbon::today();
        $startOfMonth = $carbonDate->copy()->startOfMonth();
        $endOfMonth = $carbonDate->copy()->endOfMonth();

        // Calculate attendance stats for this month
        $presentDays = $this->attendances()
            ->whereBetween('attendance_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->where('status', 'present')
            ->count();

        $absentDays = $this->attendances()
            ->whereBetween('attendance_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->where('status', 'absent')
            ->count();

        // Approved leaves overlapping this month
        $approvedLeaves = $this->leaves()
            ->where('status', 'approved')
            ->where('from_date', '<=', $endOfMonth->toDateString())
            ->where('to_date', '>=', $startOfMonth->toDateString())
            ->get();

        $leaveDaysCount = 0;
        foreach ($approvedLeaves as $leave) {
            $overlapStart = $leave->from_date->greaterThan($startOfMonth) ? $leave->from_date : $startOfMonth;
            $overlapEnd = $leave->to_date->lessThan($endOfMonth) ? $leave->to_date : $endOfMonth;
            $leaveDaysCount += $overlapStart->diffInDays($overlapEnd) + 1;
        }

        // Determine grade based on present days
        $gradeLetter = 'F';
        if ($presentDays >= 26) {
            $gradeLetter = 'A';
        } elseif ($presentDays >= 20) {
            $gradeLetter = 'B';
        } elseif ($presentDays >= 15) {
            $gradeLetter = 'C';
        } elseif ($presentDays >= 10) {
            $gradeLetter = 'D';
        }

        return $this->grades()->updateOrCreate(
            ['calculated_date' => $startOfMonth],
            [
                'present_days' => $presentDays,
                'absent_days' => $absentDays,
                'leave_days' => $leaveDaysCount,
                'grade' => $gradeLetter,
            ]
        );
    }

    /**
     * Check if the user has a specific permission through their roles.
     */
    public function hasPermission(string $permissionName): bool
    {
        // Admins automatically possess all permissions
        if ($this->roles()->where('name', 'Admin')->exists()) {
            return true;
        }

        return $this->roles()->whereHas('permissions', function ($query) use ($permissionName) {
            $query->where('name', $permissionName);
        })->exists();
    }
}
