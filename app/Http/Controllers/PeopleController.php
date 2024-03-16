<?php

namespace App\Http\Controllers;

use App\Http\Requests\PeopleRequest;
use App\Models\People;
use Inertia\Inertia;

class PeopleController extends Controller
{
    public function index()
    {
        $peoples = People::all();
        return Inertia::render('Peoples', [
            'peoples' => $peoples
        ]);
    }

    public function store(PeopleRequest $request)
    {
        $sex = $request->input('sex')['name'];
        People::create([
            'name' => $request->name,
            'birth' => $request->birth,
            'cpf' => $request->cpf,
            'sex' => $sex,
            'city' => $request->city,
            'neighborhood' => $request->neighborhood,
            'street' => $request->street,
            'number' => $request->number,
            'complement' => $request->complement,
        ]);
    }
}
