<?php

namespace App\Http\Controllers;

use App\Http\Requests\PeopleRequest;
use App\Models\People;
use Inertia\Inertia;
use Carbon\Carbon;

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
        $selectedDate = Carbon::parse($request->birth)->format('Y-m-d');
        $cpfUnmasked = preg_replace('/[^0-9]/', '', $request->cpf);
        People::create([
            'name' => $request->name,
            'birth' => $selectedDate,
            'cpf' => $cpfUnmasked,
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
        // dd($request->all());
        logger()->info('Received birth date:', ['birth' => $request->birth]);
        $selectedDate = Carbon::parse($request->birth)->format('Y-m-d');
        $people = People::find($id);
        $people->update([
            'name' => $request->name,
            'birth' => $selectedDate,
            'cpf' => $request->cpf,
            'sex' => $request->sex,
            'city' => $request->city,
            'neighborhood' => $request->neighborhood,
            'street' => $request->street,
            'number' => $request->number,
            'complement' => $request->complement,
        ]);
    }
    public function destroy($id)
    {
        People::destroy($id);
    }
}
