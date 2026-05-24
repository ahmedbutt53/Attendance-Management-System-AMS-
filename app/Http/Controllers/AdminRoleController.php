<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminRoleController extends Controller
{
    /**
     * Display a listing of roles, permissions, and users for assignment.
     */
    public function index(Request $request)
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        
        $search = $request->get('search');
        $query = User::with('roles');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10)->withQueryString();

        return view('admin.roles.index', compact('roles', 'permissions', 'users', 'search'));
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:roles,name',
            'description' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? '',
        ]);

        if (!empty($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        return redirect()->route('admin.roles.index')->with('success', "Role '{$role->name}' created successfully!");
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        // Prevent editing core system Admin role name to avoid locking out admin
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, Role $role)
    {
        $isCoreRole = in_array($role->name, ['Admin', 'Student']);

        $rules = [
            'description' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ];

        // Only allow changing name if not a core system role
        if (!$isCoreRole) {
            $rules['name'] = [
                'required',
                'string',
                'max:50',
                Rule::unique('roles', 'name')->ignore($role->id),
            ];
        }

        $validated = $request->validate($rules);

        $role->update([
            'name' => $isCoreRole ? $role->name : $validated['name'],
            'description' => $validated['description'] ?? '',
        ]);

        // Sync permissions
        $permissions = $validated['permissions'] ?? [];
        
        // Ensure Admin role keeps all critical admin permissions to avoid lockout
        if ($role->name === 'Admin') {
            $adminPermissionIds = Permission::pluck('id')->toArray();
            $role->permissions()->sync($adminPermissionIds);
        } else {
            $role->permissions()->sync($permissions);
        }

        return redirect()->route('admin.roles.index')->with('success', "Role '{$role->name}' updated successfully!");
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role)
    {
        if (in_array($role->name, ['Admin', 'Student', 'Teacher', 'HR'])) {
            return redirect()->route('admin.roles.index')->with('error', "System core roles cannot be deleted.");
        }

        // Detach from users and permissions first
        $role->users()->detach();
        $role->permissions()->detach();
        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', "Role deleted successfully!");
    }

    /**
     * Assign roles to a specific user.
     */
    public function assignUserRoles(Request $request, User $user)
    {
        $validated = $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        // Prevent taking Admin role away from the last active admin to avoid lockout
        $isAdminBeingRemoved = !in_array(Role::where('name', 'Admin')->first()->id, $validated['roles']);
        
        if ($user->roles->contains('name', 'Admin') && $isAdminBeingRemoved) {
            $otherAdminCount = User::whereHas('roles', function ($q) {
                $q->where('name', 'Admin');
            })->where('id', '!=', $user->id)->count();

            if ($otherAdminCount === 0) {
                return redirect()->route('admin.roles.index')->with('error', "Cannot remove Admin role from the last remaining Administrator.");
            }
        }

        $user->roles()->sync($validated['roles']);

        return redirect()->route('admin.roles.index')->with('success', "Roles updated for user '{$user->name}' successfully!");
    }
}
