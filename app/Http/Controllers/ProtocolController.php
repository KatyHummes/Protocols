<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProtocolRequest;
use App\Models\People;
use App\Models\Protocol;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProtocolController extends Controller
{
    public function index()
    {
        $peoples = People::get(['id', 'name']);
        $protocols = Protocol::with('people')->get();
        return Inertia::render('Protocols', [
            'protocols' => $protocols,
            'peoples' => $peoples,
        ]);
    }

    public function store(ProtocolRequest $request)
    {
        Protocol::create([
            'people_id' => $request->people_id,
            'description' => $request->description,
            'date' => $request->date,
            'term' => $request->term,
        ]);
    }
    
    public function destroy($id)
    {
        Protocol::destroy($id);
    }
}
