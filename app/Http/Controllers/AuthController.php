<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\UserEditRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function create()
    {
        $userActive = auth()->user()->active;
        $userType = auth()->user()->type;
        if ($userType === 'A') {
            return redirect()->back();
        } 
        if ($userActive === 'N') {
            return redirect()->back();
        }
        return Inertia::render('CreateUser');
    }

    public function register(AuthRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
            'cpf' => $request->cpf,
            'active' === 'S'
        ]);
    }

    public function index()
    {
        return Inertia::render('UsersIndex', [
            'users' => User::all(),
        ]);
    }

    public function show(User $user)
    {
        return Inertia::render('UsersShow', [
            'user' => $user,
        ]);
    }

    public function update(User $user, UserEditRequest $request)
    {
        $request->validated();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
            'cpf' => $request->cpf,
            'active' === 'S'
        ]);
    }
}