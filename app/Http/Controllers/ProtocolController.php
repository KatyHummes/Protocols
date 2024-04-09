<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProtocolRequest;
use App\Models\Department;
use App\Models\DocAttach;
use App\Models\People;
use App\Models\Protocol;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

// Criar protocolos
class ProtocolController extends Controller
{
    public function index()
    {
        $departments = Department::get(['id', 'name']);
        $peoples = People::get(['id', 'name']);
        $protocols = Protocol::with('people')->get();
        return Inertia::render('Protocols', [
            'protocols' => $protocols,
            'peoples' => $peoples,
            'departments' => $departments,
        ]);
    }

    public function store(ProtocolRequest $request)
    {
        // dd($request->all());
        $selectedDate = Carbon::parse($request->date)->format('Y-m-d');
        $protocol = Protocol::create([
            'department_id' => $request->department_id,
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

    // Editar protocolos
    public function show($id)
    {
        $userType = auth()->user()->type;
        if ($userType === 'A') {
            
        } 
        $protocol = Protocol::with('people', 'docattachs')->findOrFail($id);
        $departments = Department::get(['id', 'name']);
        $peoples = People::get(['id', 'name']);
        return Inertia::render('EditProtocol', [
            'protocol' => $protocol,
            'departments' => $departments,
            'peoples' => $peoples,
        ]);
    }

    public function update(ProtocolRequest $request, $id)
    {
        $selectedDate = Carbon::parse($request->date)->format('Y-m-d');
        $protocol = Protocol::find($id);
        $protocol->update([
            'department_id' => $request->department_id,
            'people_id' => $request->people_id,
            'description' => $request->description,
            'date' => $selectedDate,
            'term' => $request->term,
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public', $fileName);

                $protocol->docattach()->updateOrCreate(
                    ['protocol_id' => $protocol->id],
                    ['file' => $fileName]
                );
            }
        }
    }

    // Excluir protocolos
    public function destroy($id)
    {
        Protocol::destroy($id);
    }
}
