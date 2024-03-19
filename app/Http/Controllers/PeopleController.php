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
        // dd($request->all());
        People::create([
            'name' => $request->name,
            'birth' => $request->birth,
            'cpf' => $request->cpf,
            'sex' => $request->sex,
            'city' => $request->city,
            'neighborhood' => $request->neighborhood,
            'street' => $request->street,
            'number' => $request->number,
            'complement' => $request->complement,
        ]);
    }

    public function show($id)
    {
        $people = People::find($id);
        // dd($people);
        return Inertia::render('EditPeople', [
            'people' => $people
        ]);
    }

    public function update(PeopleRequest $request, $id)
    {
        $people = People::find($id);
        $people->update([
            'name' => $request->input('name'),
            'birth' => $request->input('birth'),
            'cpf' => $request->input('cpf'),
            'sex' => $request->input('sex'),
            'city' => $request->input('city'),
            'neighborhood' => $request->input('neighborhood'),
            'street' => $request->input('street'),
            'number' => $request->input('number'),
            'complement' => $request->input('complement'),
        ]);
    }
    public function destroy($id)
    {
        People::destroy($id);
    }
}
