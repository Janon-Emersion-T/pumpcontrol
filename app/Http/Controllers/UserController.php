<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // List all users except "Janon Emersion T"
    public function index()
    {
        $users = User::where('name', '!=', 'Janon Emersion T')
                     ->with('roles')
                     ->latest()
                     ->paginate(10);

        return view('dashboard.users.index', compact('users'));
    }

    // Show form to create a new user
    public function create()
    {
        $roles = Role::where('name', '!=', 'god')->get();
        return view('dashboard.users.create', compact('roles'));
    }

    // Store a new user with roles
    public function store(UserRequest $request)
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        return redirect()->route('users.index')->with('success', 'User created and roles assigned successfully.');
    }

    // Show specific user
    public function show(User $user)
    {
        return view('dashboard.users.show', compact('user'));
    }

    // Show form to edit user and their roles
    public function edit(User $user)
    {
        $roles = Role::where('name', '!=', 'god')->get();
        $userRoles = $user->roles->pluck('name')->toArray();

        return view('dashboard.users.edit', compact('user', 'roles', 'userRoles'));
    }

    // Update user and sync roles
    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        return redirect()->route('users.index')->with('success', 'User updated and roles synchronized.');
    }

    // Delete a user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted.');
    }
}
