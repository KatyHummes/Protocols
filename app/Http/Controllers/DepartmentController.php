<?php

namespace App\Http\Controllers;

use App\Models\Department;
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
        $department = Department::find($id);
        return Inertia::render('EditDepartment', [
            'department' => $department
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
}
