<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Criteria;
use App\Models\Evaluation;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    private function getScoreOptions(): array
    {
        return [
            'Pangkat/Golongan' => [
                ['value' => 1, 'label' => 'Juru Muda / I.a - I.d'],
                ['value' => 2, 'label' => 'Pengatur / II.a - II.d'],
                ['value' => 3, 'label' => 'Penata / III.a - III.d'],
                ['value' => 4, 'label' => 'Pembina / IV.a - IV.c'],
                ['value' => 5, 'label' => 'Pembina Utama / IV.d - IV.e'],
            ],
            'Tingkat Pendidikan' => [
                ['value' => 1, 'label' => 'SMP/sederajat'],
                ['value' => 2, 'label' => 'SMA/sederajat'],
                ['value' => 3, 'label' => 'D3'],
                ['value' => 4, 'label' => 'S1/D4'],
                ['value' => 5, 'label' => 'S2 ke atas'],
            ],
            'Umur' => [
                ['value' => 1, 'label' => '> 55 tahun'],
                ['value' => 2, 'label' => '46 - 55 tahun'],
                ['value' => 3, 'label' => '36 - 45 tahun'],
                ['value' => 4, 'label' => '26 - 35 tahun'],
                ['value' => 5, 'label' => '20 - 25 tahun'],
            ],
            'Masa Kerja' => [
                ['value' => 1, 'label' => '< 5 tahun'],
                ['value' => 2, 'label' => '5 - 10 tahun'],
                ['value' => 3, 'label' => '11 - 15 tahun'],
                ['value' => 4, 'label' => '16 - 20 tahun'],
                ['value' => 5, 'label' => '> 20 tahun'],
            ],
            'Penilaian Kinerja' => [
                ['value' => 1, 'label' => 'Kurang'],
                ['value' => 2, 'label' => 'Cukup'],
                ['value' => 3, 'label' => 'Baik'],
                ['value' => 4, 'label' => 'Sangat Baik'],
                ['value' => 5, 'label' => 'Istimewa'],
            ],
        ];
    }

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
        $scoreOptions = $this->getScoreOptions();

        return view('evaluations.create', compact('personil', 'criteria', 'scoreOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'period' => 'required|date_format:Y-m',
            'scores' => 'required|array',
            'scores.*' => 'required|integer|min:1|max:5',
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
        $scoreOptions = $this->getScoreOptions();

        return view('evaluations.edit', compact('personil', 'criteria', 'evaluations', 'period', 'scoreOptions'));
    }

    public function update(Request $request, $userId, $period)
    {
        $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'required|integer|min:1|max:5',
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
