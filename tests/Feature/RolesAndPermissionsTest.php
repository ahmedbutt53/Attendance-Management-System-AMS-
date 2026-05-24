<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolesAndPermissionsTest extends TestCase
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

    public function test_non_admins_cannot_access_roles_endpoints(): void
    {
        $student = $this->createStudent();

        $this->actingAs($student)->get('/admin/roles')->assertRedirect('/dashboard');
        $this->actingAs($student)->post('/admin/roles', [])->assertRedirect('/dashboard');
    }

    public function test_admin_can_view_roles_and_permissions_list(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/roles');
        $response->assertStatus(200);
        $response->assertSee('Admin');
        $response->assertSee('Student');
        $response->assertSee('Teacher');
        $response->assertSee('HR');
    }

    public function test_admin_can_create_custom_role_with_permissions(): void
    {
        $admin = $this->createAdmin();
        $permission1 = Permission::where('name', 'mark_attendance')->first();
        $permission2 = Permission::where('name', 'request_leave')->first();

        $response = $this->actingAs($admin)->post('/admin/roles', [
            'name' => 'Supervisor',
            'description' => 'Department Supervisor Role',
            'permissions' => [$permission1->id, $permission2->id],
        ]);

        $response->assertRedirect('/admin/roles');
        $this->assertDatabaseHas('roles', [
            'name' => 'Supervisor',
            'description' => 'Department Supervisor Role',
        ]);

        $role = Role::where('name', 'Supervisor')->first();
        $this->assertTrue($role->permissions->contains($permission1->id));
        $this->assertTrue($role->permissions->contains($permission2->id));
    }

    public function test_admin_can_update_role_and_sync_permissions(): void
    {
        $admin = $this->createAdmin();
        $role = Role::create(['name' => 'Custom Role', 'description' => 'Initial Description']);
        
        $permission = Permission::where('name', 'view_reports')->first();

        $response = $this->actingAs($admin)->put('/admin/roles/' . $role->id, [
            'name' => 'Updated Custom Role',
            'description' => 'Updated Description',
            'permissions' => [$permission->id],
        ]);

        $response->assertRedirect('/admin/roles');
        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => 'Updated Custom Role',
            'description' => 'Updated Description',
        ]);

        $role = $role->fresh();
        $this->assertTrue($role->permissions->contains($permission->id));
    }

    public function test_admin_cannot_delete_core_system_roles(): void
    {
        $admin = $this->createAdmin();
        $studentRole = Role::where('name', 'Student')->first();

        $response = $this->actingAs($admin)->delete('/admin/roles/' . $studentRole->id);
        
        $response->assertRedirect('/admin/roles');
        $response->assertSessionHas('error', 'System core roles cannot be deleted.');
        $this->assertDatabaseHas('roles', ['name' => 'Student']);
    }

    public function test_admin_can_assign_roles_to_users(): void
    {
        $admin = $this->createAdmin();
        $student = $this->createStudent();
        $teacherRole = Role::where('name', 'Teacher')->first();

        $response = $this->actingAs($admin)->post('/admin/users/' . $student->id . '/roles', [
            'roles' => [$teacherRole->id],
        ]);

        $response->assertRedirect('/admin/roles');
        $this->assertTrue($student->fresh()->roles->contains($teacherRole->id));
    }

    public function test_admin_cannot_remove_admin_role_from_last_admin(): void
    {
        $admin = $this->createAdmin();
        
        // Delete all other users with the Admin role to make $admin the last remaining Admin
        User::where('id', '!=', $admin->id)->whereHas('roles', function ($q) {
            $q->where('name', 'Admin');
        })->delete();

        $studentRole = Role::where('name', 'Student')->first();

        // Attempting to remove Admin role and only assign Student role to the only Admin
        $response = $this->actingAs($admin)->post('/admin/users/' . $admin->id . '/roles', [
            'roles' => [$studentRole->id],
        ]);

        $response->assertRedirect('/admin/roles');
        $response->assertSessionHas('error', 'Cannot remove Admin role from the last remaining Administrator.');
        $this->assertTrue($admin->fresh()->roles->contains('name', 'Admin'));
    }
}
