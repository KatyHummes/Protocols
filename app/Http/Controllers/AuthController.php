<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function register(AuthRequest $request)
    {
        $request->validated();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
            'cpf' => $request->cpf,
            'ativo' === 'S'
        ]);
        Auth::login($user);
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
}
