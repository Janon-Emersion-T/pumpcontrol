<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Staff::with('user')->paginate(20);
        return view('dashboard.staff.index', compact('staff'));
    }

    public function create()
    {
        $users = User::all(); // Optional: only if staff belongs to users
        return view('dashboard.staff.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => ['required', Rule::exists('users', 'id')],
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:staff,email',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        Staff::create($request->all());

        return redirect()->route('staff.index')->with('success', 'Staff created successfully.');
    }

    public function show(Staff $staff)
    {
        return view('dashboard.staff.show', compact('staff'));
    }

    public function edit(Staff $staff)
    {
        $users = User::all();
        return view('dashboard.staff.edit', compact('staff', 'users'));
    }

    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            'user_id' => ['required', Rule::exists('users', 'id')],
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => ['required', 'email', Rule::unique('staff', 'email')->ignore($staff->id)],
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $staff->update($request->all());

        return redirect()->route('staff.index')->with('success', 'Staff updated successfully.');
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();
        return redirect()->route('staff.index')->with('success', 'Staff deleted successfully.');
    }
}
