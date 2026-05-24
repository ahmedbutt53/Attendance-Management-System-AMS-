<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            'mark_attendance',
            'view_attendance',
            'request_leave',
            'view_own_leaves',
            'approve_leaves',
            'reject_leaves',
            'add_attendance',
            'edit_attendance',
            'delete_attendance',
            'view_all_attendance',
            'assign_tasks',
            'view_tasks',
            'submit_task',
            'approve_task',
            'reject_task',
            'create_roles',
            'edit_roles',
            'delete_roles',
            'assign_permissions',
            'view_grades',
            'calculate_grades',
            'view_reports',
            'send_notifications',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'description' => 'System Administrator']);
        $studentRole = Role::firstOrCreate(['name' => 'Student', 'description' => 'Student User']);
        $teacherRole = Role::firstOrCreate(['name' => 'Teacher', 'description' => 'Teacher User']);
        $hrRole = Role::firstOrCreate(['name' => 'HR', 'description' => 'HR Manager']);

        // Assign Permissions to Admin
        $adminPermissions = Permission::whereIn('name', [
            'mark_attendance',
            'view_attendance',
            'approve_leaves',
            'reject_leaves',
            'add_attendance',
            'edit_attendance',
            'delete_attendance',
            'view_all_attendance',
            'assign_tasks',
            'view_tasks',
            'approve_task',
            'reject_task',
            'create_roles',
            'edit_roles',
            'delete_roles',
            'assign_permissions',
            'view_grades',
            'calculate_grades',
            'view_reports',
            'send_notifications',
        ])->get();
        $adminRole->permissions()->sync($adminPermissions);

        // Assign Permissions to Student
        $studentPermissions = Permission::whereIn('name', [
            'mark_attendance',
            'view_attendance',
            'request_leave',
            'view_own_leaves',
            'view_tasks',
            'submit_task',
        ])->get();
        $studentRole->permissions()->sync($studentPermissions);

        // Assign Permissions to Teacher
        $teacherPermissions = Permission::whereIn('name', [
            'mark_attendance',
            'view_attendance',
            'view_all_attendance',
            'assign_tasks',
            'view_tasks',
            'approve_task',
            'reject_task',
            'view_grades',
            'view_reports',
        ])->get();
        $teacherRole->permissions()->sync($teacherPermissions);

        // Assign Permissions to HR
        $hrPermissions = Permission::whereIn('name', [
            'view_all_attendance',
            'approve_leaves',
            'reject_leaves',
            'view_reports',
            'view_grades',
            'send_notifications',
        ])->get();
        $hrRole->permissions()->sync($hrPermissions);

        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@attendance.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'phone' => '03001234567',
                'is_active' => true,
            ]
        );
        $admin->roles()->sync([$adminRole->id]);

        // Create Test Student
        $student = User::firstOrCreate(
            ['email' => 'student@attendance.com'],
            [
                'name' => 'Test Student',
                'password' => Hash::make('password123'),
                'phone' => '03009876543',
                'department' => 'Computer Science',
                'is_active' => true,
            ]
        );
        $student->roles()->sync([$studentRole->id]);

        // Create Test Teacher
        $teacher = User::firstOrCreate(
            ['email' => 'teacher@attendance.com'],
            [
                'name' => 'Test Teacher',
                'password' => Hash::make('password123'),
                'phone' => '03005555555',
                'department' => 'Computer Science',
                'is_active' => true,
            ]
        );
        $teacher->roles()->sync([$teacherRole->id]);

        echo "✅ Roles, Permissions, and Users seeded successfully!\n";
    }
}
