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

class AdminReportTest extends TestCase
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

    public function test_non_admins_cannot_access_reports_endpoints(): void
    {
        $student = $this->createStudent();

        $response = $this->actingAs($student)->get('/admin/reports');
        $response->assertRedirect('/dashboard');

        $response2 = $this->actingAs($student)->get('/admin/reports/student');
        $response2->assertRedirect('/dashboard');

        $response3 = $this->actingAs($student)->get('/admin/reports/system');
        $response3->assertRedirect('/dashboard');
    }

    public function test_admins_can_view_reports_selection_page(): void
    {
        $admin = $this->createAdmin();
        $student = $this->createStudent(['name' => 'Waseem Akram']);

        $response = $this->actingAs($admin)->get('/admin/reports');

        $response->assertStatus(200);
        $response->assertSee('Attendance & Leave Reports', false);
        $response->assertSee('Waseem Akram');
    }

    public function test_admin_can_generate_student_attendance_report(): void
    {
        $admin = $this->createAdmin();
        $student = $this->createStudent(['name' => 'Khurram Shehzad']);

        // Create 2 present records, 1 absent record
        Attendance::create([
            'user_id' => $student->id,
            'attendance_date' => '2026-05-10',
            'status' => 'present',
        ]);
        Attendance::create([
            'user_id' => $student->id,
            'attendance_date' => '2026-05-11',
            'status' => 'present',
        ]);
        Attendance::create([
            'user_id' => $student->id,
            'attendance_date' => '2026-05-12',
            'status' => 'absent',
        ]);

        // Create 1 approved leave spanning 2 days inside range (12th and 13th)
        Leave::create([
            'user_id' => $student->id,
            'from_date' => '2026-05-12',
            'to_date' => '2026-05-13',
            'reason' => 'Family event reason description',
            'status' => 'approved',
            'approved_by' => $admin->id,
        ]);

        // Make request for range 2026-05-10 to 2026-05-15
        $response = $this->actingAs($admin)->get('/admin/reports/student?' . http_build_query([
            'user_id' => $student->id,
            'from_date' => '2026-05-10',
            'to_date' => '2026-05-15',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Khurram Shehzad');
        $response->assertSee('66.7%'); // 2 presents out of 3 active days (present + absent)
        $response->assertSee('2'); // Present Days count
        $response->assertSee('1'); // Absent Days count
        $response->assertSee('2'); // Leave Days count (since 12th & 13th fall in range)
    }

    public function test_admin_can_generate_system_wide_report(): void
    {
        $admin = $this->createAdmin();
        $student1 = $this->createStudent(['name' => 'Babar Azam']);
        $student2 = $this->createStudent(['name' => 'Shaheen Afridi']);

        // Student 1: 1 present, 1 absent
        Attendance::create([
            'user_id' => $student1->id,
            'attendance_date' => '2026-05-20',
            'status' => 'present',
        ]);
        Attendance::create([
            'user_id' => $student1->id,
            'attendance_date' => '2026-05-21',
            'status' => 'absent',
        ]);

        // Student 2: 1 present, 1 approved leave (overlap 1 day)
        Attendance::create([
            'user_id' => $student2->id,
            'attendance_date' => '2026-05-20',
            'status' => 'present',
        ]);
        Leave::create([
            'user_id' => $student2->id,
            'from_date' => '2026-05-21',
            'to_date' => '2026-05-22',
            'reason' => 'Medical reason representation',
            'status' => 'approved',
            'approved_by' => $admin->id,
        ]);

        // Query system-wide range 2026-05-20 to 2026-05-21
        $response = $this->actingAs($admin)->get('/admin/reports/system?' . http_build_query([
            'from_date' => '2026-05-20',
            'to_date' => '2026-05-21',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Babar Azam');
        $response->assertSee('Shaheen Afridi');
        
        // System totals check
        $response->assertSee('2'); // Total Present Logs (1 from Babar, 1 from Shaheen)
        $response->assertSee('1'); // Total Absent Logs (1 from Babar)
        $response->assertSee('1'); // Total Approved Leaves (only 21st falls in date range)
        $response->assertSee('66.7%'); // Overall system attendance rate (2 presents out of 3 logs)
    }

    public function test_reports_validation_fails_with_invalid_parameters(): void
    {
        $admin = $this->createAdmin();

        // to_date before from_date
        $response = $this->actingAs($admin)->get('/admin/reports/system?' . http_build_query([
            'from_date' => '2026-05-20',
            'to_date' => '2026-05-19',
        ]));

        $response->assertSessionHasErrors(['to_date']);

        // Missing student for student report
        $response2 = $this->actingAs($admin)->get('/admin/reports/student?' . http_build_query([
            'from_date' => '2026-05-20',
            'to_date' => '2026-05-22',
        ]));

        $response2->assertSessionHasErrors(['user_id']);
    }
}
