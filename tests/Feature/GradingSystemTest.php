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

class GradingSystemTest extends TestCase
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

    public function test_grade_is_calculated_correctly_based_on_present_days(): void
    {
        $student = $this->createStudent();
        
        // Under 10 days = F
        $student->updateOrCreateGradeForMonth();
        $this->assertEquals('F', $student->grades()->first()->grade);

        // 10 presents = D
        for ($i = 1; $i <= 10; $i++) {
            Attendance::create([
                'user_id' => $student->id,
                'attendance_date' => Carbon::today()->startOfMonth()->addDays($i)->toDateString(),
                'status' => 'present',
            ]);
        }
        
        $student->updateOrCreateGradeForMonth();
        $this->assertEquals('D', $student->grades()->first()->fresh()->grade);

        // 15 presents = C
        for ($i = 11; $i <= 15; $i++) {
            Attendance::create([
                'user_id' => $student->id,
                'attendance_date' => Carbon::today()->startOfMonth()->addDays($i)->toDateString(),
                'status' => 'present',
            ]);
        }
        $student->updateOrCreateGradeForMonth();
        $this->assertEquals('C', $student->grades()->first()->fresh()->grade);

        // 20 presents = B
        for ($i = 16; $i <= 20; $i++) {
            Attendance::create([
                'user_id' => $student->id,
                'attendance_date' => Carbon::today()->startOfMonth()->addDays($i)->toDateString(),
                'status' => 'present',
            ]);
        }
        $student->updateOrCreateGradeForMonth();
        $this->assertEquals('B', $student->grades()->first()->fresh()->grade);

        // 26 presents = A
        for ($i = 21; $i <= 26; $i++) {
            Attendance::create([
                'user_id' => $student->id,
                'attendance_date' => Carbon::today()->startOfMonth()->addDays($i)->toDateString(),
                'status' => 'present',
            ]);
        }
        $student->updateOrCreateGradeForMonth();
        $this->assertEquals('A', $student->grades()->first()->fresh()->grade);
    }

    public function test_student_dashboard_displays_current_month_grade(): void
    {
        $student = $this->createStudent(['name' => 'Fakhar Zaman']);

        // Create 12 present days (should be Grade D)
        for ($i = 1; $i <= 12; $i++) {
            Attendance::create([
                'user_id' => $student->id,
                'attendance_date' => Carbon::today()->startOfMonth()->addDays($i)->toDateString(),
                'status' => 'present',
            ]);
        }

        $response = $this->actingAs($student)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Current Month Grade');
        $response->assertSee('Grade D');
        $response->assertSee('12 Presents');
    }

    public function test_admin_student_directory_displays_grades(): void
    {
        $admin = $this->createAdmin();
        $student = $this->createStudent(['name' => 'Imad Wasim']);

        // Create 21 present days (should be Grade B)
        for ($i = 1; $i <= 21; $i++) {
            Attendance::create([
                'user_id' => $student->id,
                'attendance_date' => Carbon::today()->startOfMonth()->addDays($i)->toDateString(),
                'status' => 'present',
            ]);
        }

        $response = $this->actingAs($admin)->get('/admin/students');

        $response->assertStatus(200);
        $response->assertSee('Current Grade');
        $response->assertSee('Grade B');
    }

    public function test_admin_student_detail_displays_grade_history(): void
    {
        $admin = $this->createAdmin();
        $student = $this->createStudent(['name' => 'Haris Rauf']);

        // Create 15 present days (should be Grade C)
        for ($i = 1; $i <= 15; $i++) {
            Attendance::create([
                'user_id' => $student->id,
                'attendance_date' => Carbon::today()->startOfMonth()->addDays($i)->toDateString(),
                'status' => 'present',
            ]);
        }

        $response = $this->actingAs($admin)->get('/admin/students/' . $student->id);

        $response->assertStatus(200);
        $response->assertSee('Monthly Grade History');
        $response->assertSee('Grade C');
        $response->assertSee('15P / 0A / 0L');
    }
}
