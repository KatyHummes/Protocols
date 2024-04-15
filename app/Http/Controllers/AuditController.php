<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
    public function index()
    {
        $userType = auth()->user()->type;
        if ($userType === 'A') {
            return redirect()->back();
        }

        $audits = Audit::with('user')->get();
        return Inertia::render('Audit/Index', [
            'audits' => $audits
        ]);
    }   
}
