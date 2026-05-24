<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Task;
use App\Models\TaskResponse;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskManagementTest extends TestCase
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

    public function test_non_admins_cannot_access_admin_task_endpoints(): void
    {
        $student = $this->createStudent();

        $this->actingAs($student)->get('/admin/tasks')->assertRedirect('/dashboard');
        $this->actingAs($student)->get('/admin/tasks/create')->assertRedirect('/dashboard');
        $this->actingAs($student)->post('/admin/tasks', [])->assertRedirect('/dashboard');
    }

    public function test_admin_can_assign_task_to_students(): void
    {
        $admin = $this->createAdmin();
        $student1 = $this->createStudent();
        $student2 = $this->createStudent();

        $response = $this->actingAs($admin)->post('/admin/tasks', [
            'title' => 'Learn Laravel Feature Tests',
            'description' => '<p>Please write a test suite.</p>',
            'due_date' => now()->addDays(7)->toDateString(),
            'students' => [$student1->id, $student2->id],
        ]);

        $response->assertRedirect('/admin/tasks');
        $this->assertDatabaseHas('tasks', [
            'title' => 'Learn Laravel Feature Tests',
        ]);

        $task = Task::first();
        $this->assertTrue($task->users->contains($student1->id));
        $this->assertTrue($task->users->contains($student2->id));
    }

    public function test_student_can_view_assigned_tasks_and_submit_response(): void
    {
        $admin = $this->createAdmin();
        $student = $this->createStudent();

        // Create Task
        $task = Task::create([
            'assigned_by' => $admin->id,
            'title' => 'Sample Homework',
            'description' => '<p>Instructions</p>',
            'due_date' => now()->addDays(5)->toDateString(),
        ]);
        $task->users()->attach($student->id);

        // Student views list
        $response = $this->actingAs($student)->get('/tasks');
        $response->assertStatus(200);
        $response->assertSee('Sample Homework');

        // Student views task detail
        $response = $this->actingAs($student)->get('/tasks/' . $task->id);
        $response->assertStatus(200);
        $response->assertSee('Sample Homework');

        // Student submits response
        $response = $this->actingAs($student)->post('/tasks/' . $task->id . '/submit', [
            'response' => '<p>Here is my completed work for this task.</p>',
        ]);

        $response->assertRedirect('/tasks');
        $this->assertDatabaseHas('task_responses', [
            'task_id' => $task->id,
            'user_id' => $student->id,
            'response' => '<p>Here is my completed work for this task.</p>',
            'status' => 'submitted',
        ]);
    }

    public function test_admin_can_approve_or_reject_student_response(): void
    {
        $admin = $this->createAdmin();
        $student = $this->createStudent();

        $task = Task::create([
            'assigned_by' => $admin->id,
            'title' => 'Review Homework',
            'description' => '<p>Instructions</p>',
        ]);
        $task->users()->attach($student->id);

        $taskResponse = TaskResponse::create([
            'task_id' => $task->id,
            'user_id' => $student->id,
            'response' => '<p>Student response content</p>',
            'status' => 'submitted',
        ]);

        // Admin reviews and approves response
        $response = $this->actingAs($admin)->post('/admin/task-responses/' . $taskResponse->id . '/review', [
            'status' => 'approved',
            'feedback' => 'Excellent job!',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('task_responses', [
            'id' => $taskResponse->id,
            'status' => 'approved',
            'feedback' => 'Excellent job!',
            'reviewed_by' => $admin->id,
        ]);
    }
}
