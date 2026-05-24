<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Attendance;
use App\Models\Leave;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Tests\TestCase;

class DashboardButtonsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    /**
     * Helper to create a student user.
     */
    private function createStudent(array $attributes = []): User
    {
        $user = User::factory()->create(array_merge(['is_active' => true], $attributes));
        $studentRole = Role::where('name', 'Student')->first();
        if ($studentRole) {
            $user->roles()->attach($studentRole->id);
        }
        return $user;
    }

    /**
     * Helper to create a non-student user (e.g., admin).
     */
    private function createAdmin(): User
    {
        $user = User::factory()->create(['is_active' => true]);
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $user->roles()->attach($adminRole->id);
        }
        return $user;
    }

    public function test_student_can_mark_attendance_once_per_day(): void
    {
        $student = $this->createStudent();
        $today = Carbon::today()->toDateString();

        // 1. Initial State: No attendance records in DB
        $this->assertDatabaseMissing('attendance', [
            'user_id' => $student->id,
            'attendance_date' => $today,
        ]);

        // 2. Mark Attendance
        $response = $this->actingAs($student)->post('/attendance/mark');

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Attendance marked successfully for today!');
        $this->assertTrue(Attendance::where('user_id', $student->id)
            ->whereDate('attendance_date', $today)
            ->where('status', 'present')
            ->exists());

        // 3. Try to mark attendance again (should fail)
        $response2 = $this->actingAs($student)->post('/attendance/mark');

        $response2->assertRedirect();
        $response2->assertSessionHas('error', 'You have already marked your attendance for today.');
        
        // Assert exactly 1 record exists
        $this->assertEquals(1, Attendance::where('user_id', $student->id)->whereDate('attendance_date', $today)->count());
    }

    public function test_non_students_cannot_mark_attendance(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post('/attendance/mark');

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Only students are allowed to self-mark attendance.');
        $this->assertEquals(0, Attendance::count());
    }

    public function test_student_can_request_leave(): void
    {
        $student = $this->createStudent();
        $fromDate = Carbon::today()->addDay()->toDateString();
        $toDate = Carbon::today()->addDays(3)->toDateString();

        $response = $this->actingAs($student)->post('/leaves/apply', [
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'reason' => 'This is a valid leave reason with more than ten characters.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Leave request submitted successfully to the administrator.');
        
        $this->assertTrue(Leave::where('user_id', $student->id)
            ->whereDate('from_date', $fromDate)
            ->whereDate('to_date', $toDate)
            ->where('reason', 'This is a valid leave reason with more than ten characters.')
            ->where('status', 'pending')
            ->exists());
    }

    public function test_student_cannot_request_leave_with_invalid_dates(): void
    {
        $student = $this->createStudent();
        
        // Scenario A: from_date is in the past
        $response = $this->actingAs($student)->post('/leaves/apply', [
            'from_date' => Carbon::yesterday()->toDateString(),
            'to_date' => Carbon::today()->toDateString(),
            'reason' => 'Reason must be long enough to pass validation.',
        ]);
        $response->assertSessionHasErrors(['from_date']);
        $this->assertEquals(0, Leave::count());

        // Scenario B: to_date is before from_date
        $response2 = $this->actingAs($student)->post('/leaves/apply', [
            'from_date' => Carbon::today()->addDays(2)->toDateString(),
            'to_date' => Carbon::today()->addDay()->toDateString(),
            'reason' => 'Reason must be long enough to pass validation.',
        ]);
        $response2->assertSessionHasErrors(['to_date']);
        $this->assertEquals(0, Leave::count());
    }

    public function test_student_can_view_attendance_history(): void
    {
        $student = $this->createStudent();
        
        // Add fake attendance log
        Attendance::create([
            'user_id' => $student->id,
            'attendance_date' => Carbon::yesterday()->toDateString(),
            'status' => 'present',
        ]);

        $response = $this->actingAs($student)->get('/attendance');

        $response->assertStatus(200);
        $response->assertSee(Carbon::yesterday()->format('l, M d, Y'));
        $response->assertSee('present');
    }

    public function test_student_can_view_leave_history(): void
    {
        $student = $this->createStudent();
        $fromDate = Carbon::today()->addDay();
        $toDate = Carbon::today()->addDays(3);

        Leave::create([
            'user_id' => $student->id,
            'from_date' => $fromDate->toDateString(),
            'to_date' => $toDate->toDateString(),
            'reason' => 'Sickness leave request with detailed reason.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($student)->get('/leaves');

        $response->assertStatus(200);
        $response->assertSee('Leave Requests History');
        $response->assertSee('Sickness leave request with detailed reason.');
        $response->assertSee('pending');
    }
}
