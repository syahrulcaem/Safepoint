<?php

namespace App\Http\Controllers;

use App\Models\Cases;
use App\Models\Unit;
use Illuminate\Http\Request;

class CaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Cases::with(['reporterUser', 'assignedUnit'])
            ->byStatus($request->status)
            ->byCategory($request->category)
            ->byUnit($request->unit)
            ->search($request->q)
            ->orderBy('created_at', 'desc');

        $cases = $query->paginate(15)->withQueryString();

        $units = Unit::active()->orderBy('name')->get();

        $categories = ['UMUM', 'MEDIS', 'KEBAKARAN', 'KEAMANAN', 'BENCANA'];
        $statuses = ['NEW', 'VERIFIED', 'DISPATCHED', 'ON_THE_WAY', 'ON_SCENE', 'CLOSED', 'CANCELLED'];

        return view('cases.index', compact('cases', 'units', 'categories', 'statuses'));
    }

    public function show(Cases $case)
    {
        $case->load([
            'reporterUser',
            'assignedUnit',
            'caseEvents' => function ($query) {
                $query->with('actor')->orderBy('created_at', 'desc');
            },
            'dispatches' => function ($query) {
                $query->with(['unit', 'assignedBy'])->orderBy('created_at', 'desc');
            }
        ]);

        $units = Unit::active()->orderBy('name')->get();

        return view('cases.show', compact('case', 'units'));
    }
}
