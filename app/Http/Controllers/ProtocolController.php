<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProtocolRequest;
use App\Models\DocAttach;
use App\Models\People;
use App\Models\Protocol;
use Carbon\Carbon;
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
        // dd($request->all());
        $selectedDate = Carbon::parse($request->date)->format('Y-m-d');
        $protocol = Protocol::create([
            'people_id' => $request->people_id,
            'description' => $request->description,
            'date' => $selectedDate,
            'term' => $request->term,
        ]);
        
        if ($protocol) {
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $file->storeAs('public', $fileName);
        
                    DocAttach::create([
                        'protocol_id' => $protocol->id,
                        'file' => $fileName,
                    ]);
                }
            }
        }
        
    }

    public function show($id)
    {
        $protocol = Protocol::findOrFail($id)->with('people')->first();
        $peoples = People::get(['id', 'name']);
        // dd($protocol);
        return Inertia::render('EditProtocol', [
            'protocol' => $protocol,
            'peoples' => $peoples,
        ]);
    }

    public function update(ProtocolRequest $request, $id)
    {
        $selectedDate = Carbon::parse($request->date)->format('Y-m-d');
        $protocol = Protocol::find($id);
        $protocol->update([
            'people_id' => $request->people_id,
            'description' => $request->description,
            'date' => $selectedDate,
            'term' => $request->term,
        ]);
    }

    public function destroy($id)
    {
        Protocol::destroy($id);
    }
}
