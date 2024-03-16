<?php

namespace App\Http\Controllers;

use App\Models\People;
use Illuminate\Http\Request;
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
 
    public function store(Request $request)
    {
        People::create([
            'name' => $request->name,
        ]);
    }

}
