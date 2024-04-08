<?php

namespace App\Http\Controllers;

use App\Models\Access;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DepartmentController extends Controller
{
    public function index()
    {
        $userType = auth()->user()->type;
        if ($userType === 'A') {
            return redirect()->back();
        } 
        return Inertia::render('Departments', [
            'departments' => Department::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Department::create([
            'name' => $request->name,
        ]);
    }

    public function show($id)
    {
        $userType = auth()->user()->type;
        if ($userType === 'A') {
            return redirect()->back();
        } 
        $accesses = Access::with(['user' => function ($query) {
            $query->select('id', 'name');
        }])->where('department_id', $id)->get();
        $department = Department::find($id);
        return Inertia::render('EditDepartment', [
            'users' => User::get(['id', 'name']),
            'department' => $department,
            'accesses' => $accesses
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $department = Department::find($id);
        $department->update([
            'name' => $request->name,
        ]);
    }

    public function access(Request $request, $id)
    {
        // dd($request->all(), $id);

        Access::create([
            'user_id' => $request->user_id,
            'department_id' => $id,
        ]);
    }

    public function destroy($id)
    {
        $access = Access::find($id);
        $access->delete();
    }
}