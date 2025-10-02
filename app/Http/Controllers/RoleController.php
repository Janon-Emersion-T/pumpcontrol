<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    // List all roles
    public function index()
    {
        $roles = Role::where('name', '!=', 'god')
                    ->with('permissions')
                    ->latest()
                    ->paginate(10);

        return view('dashboard.roles.index', compact('roles'));
    }


    // Show form to create a new role
    public function create()
    {
        $permissions = Permission::all();
        return view('dashboard.roles.create', compact('permissions'));
    }

    // Store new role with permissions
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => $validated['name']]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    // Show specific role with its permissions
    public function show(Role $role)
    {
        $role->load('permissions');
        return view('dashboard.roles.show', compact('role'));
    }

    // Show form to edit a role
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('dashboard.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    // Update the role and sync permissions
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update(['name' => $validated['name']]);

        if ($request->has('permissions')) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    // Delete a role
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted.');
    }
}
