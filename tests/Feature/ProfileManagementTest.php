<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileManagementTest extends TestCase
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

    public function test_user_can_view_profile_edit_page(): void
    {
        $student = $this->createStudent();

        $response = $this->actingAs($student)->get('/profile');

        $response->assertStatus(200);
        $response->assertSee('Profile Management');
        $response->assertSee($student->name);
    }

    public function test_user_can_update_profile_text_fields(): void
    {
        $student = $this->createStudent();

        $response = $this->actingAs($student)->post('/profile', [
            'name' => 'Updated Name',
            'phone' => '03339876543',
            'address' => '456 Lane Avenue, Karachi',
            'date_of_birth' => '2000-01-15',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Profile updated successfully!');

        $this->assertDatabaseHas('users', [
            'id' => $student->id,
            'name' => 'Updated Name',
            'phone' => '03339876543',
            'address' => '456 Lane Avenue, Karachi',
            'date_of_birth' => '2000-01-15',
        ]);
    }

    public function test_user_can_upload_profile_picture(): void
    {
        Storage::fake('public');

        $student = $this->createStudent();
        $file = UploadedFile::fake()->create('avatar.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($student)->post('/profile', [
            'name' => $student->name,
            'profile_picture' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Profile updated successfully!');

        // Refresh user and assert profile picture path exists in DB and storage
        $student->refresh();
        $this->assertNotNull($student->profile_picture);
        Storage::disk('public')->assertExists($student->profile_picture);
    }

    public function test_old_profile_picture_is_deleted_when_new_picture_is_uploaded(): void
    {
        Storage::fake('public');

        $student = $this->createStudent();

        // 1. Upload first picture
        $file1 = UploadedFile::fake()->create('avatar1.jpg', 100, 'image/jpeg');
        $this->actingAs($student)->post('/profile', [
            'name' => $student->name,
            'profile_picture' => $file1,
        ]);

        $student->refresh();
        $firstPicturePath = $student->profile_picture;
        Storage::disk('public')->assertExists($firstPicturePath);

        // 2. Upload second picture
        $file2 = UploadedFile::fake()->create('avatar2.jpg', 100, 'image/jpeg');
        $this->actingAs($student)->post('/profile', [
            'name' => $student->name,
            'profile_picture' => $file2,
        ]);

        $student->refresh();
        $secondPicturePath = $student->profile_picture;

        // The second picture should exist, and the first picture should be deleted
        Storage::disk('public')->assertExists($secondPicturePath);
        Storage::disk('public')->assertMissing($firstPicturePath);
    }
}
