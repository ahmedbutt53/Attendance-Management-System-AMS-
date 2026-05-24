<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Leave;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Tests\TestCase;

class AdminLeaveManagementTest extends TestCase
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
    private function createStudent(): User
    {
        $user = User::factory()->create(['is_active' => true]);
        $studentRole = Role::where('name', 'Student')->first();
        if ($studentRole) {
            $user->roles()->attach($studentRole->id);
        }
        return $user;
    }

    /**
     * Helper to create an admin user.
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

    public function test_non_admins_cannot_access_admin_dashboard(): void
    {
        $student = $this->createStudent();

        $response = $this->actingAs($student)->get('/admin/dashboard');

        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('error', 'You do not have administrator access.');
    }

    public function test_admins_can_access_admin_dashboard(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('AMS Admin Portal');
        $response->assertSee('Pending Leaves');
    }

    public function test_admin_can_approve_leave_request(): void
    {
        $student = $this->createStudent();
        $admin = $this->createAdmin();

        $leave = Leave::create([
            'user_id' => $student->id,
            'from_date' => Carbon::today()->addDay()->toDateString(),
            'to_date' => Carbon::today()->addDays(3)->toDateString(),
            'reason' => 'Need some time off for medical reasons.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post("/admin/leaves/{$leave->id}/approve", [
            'admin_comments' => 'Approved. Take care.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Leave request approved successfully!');

        $leave->refresh();
        $this->assertEquals('approved', $leave->status);
        $this->assertEquals('Approved. Take care.', $leave->admin_comments);
        $this->assertEquals($admin->id, $leave->approved_by);
    }

    public function test_admin_can_reject_leave_request(): void
    {
        $student = $this->createStudent();
        $admin = $this->createAdmin();

        $leave = Leave::create([
            'user_id' => $student->id,
            'from_date' => Carbon::today()->addDay()->toDateString(),
            'to_date' => Carbon::today()->addDays(3)->toDateString(),
            'reason' => 'Need some time off for personal reasons.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post("/admin/leaves/{$leave->id}/reject", [
            'admin_comments' => 'Rejected due to exam dates.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Leave request rejected successfully!');

        $leave->refresh();
        $this->assertEquals('rejected', $leave->status);
        $this->assertEquals('Rejected due to exam dates.', $leave->admin_comments);
        $this->assertEquals($admin->id, $leave->approved_by);
    }
}
