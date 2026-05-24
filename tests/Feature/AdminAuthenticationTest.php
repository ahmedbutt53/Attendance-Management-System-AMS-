<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
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
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);
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
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $user->roles()->attach($adminRole->id);
        }
        return $user;
    }

    public function test_admin_login_page_can_be_rendered(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('Admin Portal');
        $response->assertSee('Secure Administrator Sign In Only');
    }

    public function test_admin_can_login_through_admin_login_page(): void
    {
        $admin = $this->createAdmin();

        $response = $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_student_cannot_login_through_admin_login_page(): void
    {
        $student = $this->createStudent();

        $response = $this->post('/admin/login', [
            'email' => $student->email,
            'password' => 'password123',
        ]);

        // Assert redirect back with validation errors
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest(); // Should be logged out
    }

    public function test_already_logged_in_admin_is_redirected_away_from_admin_login(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/login');

        $response->assertRedirect('/admin/dashboard');
    }

    public function test_already_logged_in_student_is_redirected_away_from_admin_login(): void
    {
        $student = $this->createStudent();

        $response = $this->actingAs($student)->get('/admin/login');

        $response->assertRedirect('/dashboard');
    }
}
