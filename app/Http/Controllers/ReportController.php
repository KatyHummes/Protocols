<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Models\Report;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function store(ReportRequest $request)
    {
        // dd($request->all());
        Report::create([
            'protocol_id' => $request->protocol_id,
            'status' => $request->status,
            'description' => $request->description,
        ]);
    }
}
