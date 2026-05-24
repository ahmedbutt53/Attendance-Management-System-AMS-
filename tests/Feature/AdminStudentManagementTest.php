<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Attendance;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Tests\TestCase;

class AdminStudentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    /**
     * Helper to create a student.
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
     * Helper to create an admin.
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

    public function test_non_admins_cannot_access_student_management(): void
    {
        $student = $this->createStudent();

        $response = $this->actingAs($student)->get('/admin/students');
        $response->assertRedirect('/dashboard');
    }

    public function test_admins_can_view_student_list(): void
    {
        $admin = $this->createAdmin();
        $student = $this->createStudent(['name' => 'Adnan Student']);

        $response = $this->actingAs($admin)->get('/admin/students');

        $response->assertStatus(200);
        $response->assertSee('Student Directory');
        $response->assertSee('Adnan Student');
    }

    public function test_admins_can_search_students(): void
    {
        $admin = $this->createAdmin();
        $student1 = $this->createStudent(['name' => 'Sara Khan']);
        $student2 = $this->createStudent(['name' => 'Waqar Ahmed']);

        $response = $this->actingAs($admin)->get('/admin/students?search=Sara');

        $response->assertStatus(200);
        $response->assertSee('Sara Khan');
        $response->assertDontSee('Waqar Ahmed');
    }

    public function test_admins_can_view_student_details_and_summary(): void
    {
        $admin = $this->createAdmin();
        $student = $this->createStudent(['name' => 'Ali Raza']);
        
        Attendance::create([
            'user_id' => $student->id,
            'attendance_date' => Carbon::yesterday()->toDateString(),
            'status' => 'present',
            'notes' => 'On time',
        ]);

        \App\Models\Leave::create([
            'user_id' => $student->id,
            'from_date' => Carbon::today()->addDay()->toDateString(),
            'to_date' => Carbon::today()->addDays(2)->toDateString(),
            'reason' => 'Sick leave request from student',
            'status' => 'approved',
            'approved_by' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->get("/admin/students/{$student->id}");

        $response->assertStatus(200);
        $response->assertSee('Ali Raza');
        $response->assertSee('On time');
        $response->assertSee('100.0%'); // Attendance rate
        $response->assertSee('1'); // Approved leaves
        $response->assertSee('/ 1'); // Total leaves
    }

    public function test_admin_can_add_attendance_record(): void
    {
        $admin = $this->createAdmin();
        $student = $this->createStudent();
        $date = Carbon::yesterday()->toDateString();

        $response = $this->actingAs($admin)->post("/admin/students/{$student->id}/attendance", [
            'attendance_date' => $date,
            'status' => 'present',
            'notes' => 'Manually added by admin',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('attendance', [
            'user_id' => $student->id,
            'attendance_date' => $date,
            'status' => 'present',
            'notes' => 'Manually added by admin',
        ]);
    }

    public function test_admin_cannot_duplicate_attendance_record(): void
    {
        $admin = $this->createAdmin();
        $student = $this->createStudent();
        $date = Carbon::yesterday()->toDateString();

        Attendance::create([
            'user_id' => $student->id,
            'attendance_date' => $date,
            'status' => 'absent',
        ]);

        $response = $this->actingAs($admin)->post("/admin/students/{$student->id}/attendance", [
            'attendance_date' => $date,
            'status' => 'present',
        ]);

        $response->assertSessionHasErrors(['attendance_date']);
    }

    public function test_admin_can_edit_attendance_record(): void
    {
        $admin = $this->createAdmin();
        $student = $this->createStudent();
        $date = Carbon::yesterday()->toDateString();

        $record = Attendance::create([
            'user_id' => $student->id,
            'attendance_date' => $date,
            'status' => 'absent',
            'notes' => 'Sick',
        ]);

        $response = $this->actingAs($admin)->put("/admin/attendance/{$record->id}", [
            'status' => 'present',
            'notes' => 'Recovered and attended half day',
        ]);

        $response->assertRedirect();
        $record->refresh();
        $this->assertEquals('present', $record->status);
        $this->assertEquals('Recovered and attended half day', $record->notes);
    }

    public function test_admin_can_delete_attendance_record(): void
    {
        $admin = $this->createAdmin();
        $student = $this->createStudent();
        $date = Carbon::yesterday()->toDateString();

        $record = Attendance::create([
            'user_id' => $student->id,
            'attendance_date' => $date,
            'status' => 'present',
        ]);

        $response = $this->actingAs($admin)->delete("/admin/attendance/{$record->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('attendance', [
            'id' => $record->id,
        ]);
    }
}
