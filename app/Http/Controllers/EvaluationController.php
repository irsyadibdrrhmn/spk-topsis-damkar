<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Criteria;
use App\Models\Evaluation;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', date('Y-m'));
        $personil = User::where('role', 'personil')->with(['evaluations' => function($q) use ($period) {
            $q->where('period', $period);
        }])->get();
        $criteria = Criteria::all();
        
        // Get available periods
        $periods = Evaluation::select('period')
            ->distinct()
            ->orderBy('period', 'desc')
            ->pluck('period');

        return view('evaluations.index', compact('personil', 'criteria', 'period', 'periods'));
    }

    public function create()
    {
        $personil = User::where('role', 'personil')->get();
        $criteria = Criteria::all();
        return view('evaluations.create', compact('personil', 'criteria'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'period' => 'required|date_format:Y-m',
            'scores' => 'required|array',
        ]);

        foreach ($request->scores as $criteria_id => $score) {
            Evaluation::updateOrCreate(
                [
                    'user_id' => $request->user_id,
                    'criteria_id' => $criteria_id,
                    'period' => $request->period
                ],
                ['score' => $score]
            );
        }

        return redirect()->route('evaluations.index', ['period' => $request->period])
            ->with('success', 'Penilaian berhasil disimpan');
    }

    public function edit($userId, $period)
    {
        $personil = User::findOrFail($userId);
        $criteria = Criteria::all();
        $evaluations = Evaluation::where('user_id', $userId)
            ->where('period', $period)
            ->get()
            ->keyBy('criteria_id');

        return view('evaluations.edit', compact('personil', 'criteria', 'evaluations', 'period'));
    }

    public function update(Request $request, $userId, $period)
    {
        $request->validate([
            'scores' => 'required|array',
        ]);

        foreach ($request->scores as $criteria_id => $score) {
            Evaluation::updateOrCreate(
                [
                    'user_id' => $userId,
                    'criteria_id' => $criteria_id,
                    'period' => $period
                ],
                ['score' => $score]
            );
        }

        return redirect()->route('evaluations.index', ['period' => $period])
            ->with('success', 'Penilaian berhasil diperbarui');
    }
}