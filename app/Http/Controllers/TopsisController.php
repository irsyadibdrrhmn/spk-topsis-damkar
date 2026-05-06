<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Criteria;
use App\Models\Evaluation;
use App\Models\TopsisResult;
use Illuminate\Http\Request;

class TopsisController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', date('Y-m'));
        
        // Get available periods
        $periods = Evaluation::select('period')
            ->distinct()
            ->orderBy('period', 'desc')
            ->pluck('period');

        $personil = User::where('role', 'personil')->get();
        $criteria = Criteria::all();

        // Check if evaluations exist for this period
        $hasEvaluations = Evaluation::where('period', $period)->exists();

        if (!$hasEvaluations) {
            return view('topsis.index', compact('period', 'periods', 'personil', 'criteria'))
                ->with('warning', 'Belum ada data penilaian untuk periode ini');
        }

        // Build decision matrix
        $matrix = [];
        foreach ($personil as $p) {
            foreach ($criteria as $criterion) {
                $eval = Evaluation::where('user_id', $p->id)
                    ->where('criteria_id', $criterion->id)
                    ->where('period', $period)
                    ->first();
                $matrix[$p->id][$criterion->id] = $eval ? $eval->score : 0;
            }
        }

        // TOPSIS Calculation
        $topsisData = $this->calculateTOPSIS($matrix, $criteria, $personil);

        return view('topsis.index', compact(
            'period',
            'periods',
            'personil',
            'criteria',
            'matrix',
            'topsisData'
        ));
    }

    public function ranking(Request $request)
    {
        $period = $request->get('period', date('Y-m'));
        
        $periods = Evaluation::select('period')
            ->distinct()
            ->orderBy('period', 'desc')
            ->pluck('period');

        $personil = User::where('role', 'personil')->get();
        $criteria = Criteria::all();

        $hasEvaluations = Evaluation::where('period', $period)->exists();

        if (!$hasEvaluations) {
            return view('topsis.ranking', compact('period', 'periods'))
                ->with('warning', 'Belum ada data penilaian untuk periode ini');
        }

        // Build decision matrix
        $matrix = [];
        foreach ($personil as $p) {
            foreach ($criteria as $criterion) {
                $eval = Evaluation::where('user_id', $p->id)
                    ->where('criteria_id', $criterion->id)
                    ->where('period', $period)
                    ->first();
                $matrix[$p->id][$criterion->id] = $eval ? $eval->score : 0;
            }
        }

        // Calculate TOPSIS
        $topsisData = $this->calculateTOPSIS($matrix, $criteria, $personil);
        
        // Save results
        $this->saveResults($topsisData['ranking'], $period);

        return view('topsis.ranking', compact('period', 'periods', 'topsisData', 'criteria'));
    }

    private function calculateTOPSIS($matrix, $criteria, $ppks)
    {
        // Step 1: Normalized Decision Matrix
        $normalized = [];
        foreach ($criteria as $criterion) {
            $sumSquares = 0;
            foreach ($ppks as $ppk) {
                $sumSquares += pow($matrix[$ppk->id][$criterion->id], 2);
            }
            $denominator = sqrt($sumSquares);
            
            foreach ($ppks as $ppk) {
                $normalized[$ppk->id][$criterion->id] = $denominator != 0 
                    ? $matrix[$ppk->id][$criterion->id] / $denominator 
                    : 0;
            }
        }

        // Step 2: Weighted Normalized Decision Matrix
        $weighted = [];
        foreach ($ppks as $ppk) {
            foreach ($criteria as $criterion) {
                $weighted[$ppk->id][$criterion->id] = 
                    $normalized[$ppk->id][$criterion->id] * $criterion->weight;
            }
        }

        // Step 3: Ideal Solutions (A+ and A-)
        $idealPositive = [];
        $idealNegative = [];
        
        foreach ($criteria as $criterion) {
             // Ambil semua nilai weighted untuk kriteria ini
    $values = array_map(function($w) use ($criterion) {
        return $w[$criterion->id] ?? null;
    }, $weighted);

    // Buang null agar tidak error
    $values = array_filter($values, fn($v) => $v !== null);

    // Jika tidak ada nilai → set default agar tidak error
    if (empty($values)) {
        $idealPositive[$criterion->id] = 0;
        $idealNegative[$criterion->id] = 0;
        continue;
    }

    // Benefit atau Cost
    if ($criterion->type === 'benefit') {
        $idealPositive[$criterion->id] = max($values);
        $idealNegative[$criterion->id] = min($values);
    } else {
        $idealPositive[$criterion->id] = min($values);
        $idealNegative[$criterion->id] = max($values);
    }
}

        // Step 4: Separation Measures
        $separationPositive = [];
        $separationNegative = [];
        
        foreach ($ppks as $ppk) {
            $sumPositive = 0;
            $sumNegative = 0;
            
            foreach ($criteria as $criterion) {
                $sumPositive += pow($weighted[$ppk->id][$criterion->id] - $idealPositive[$criterion->id], 2);
                $sumNegative += pow($weighted[$ppk->id][$criterion->id] - $idealNegative[$criterion->id], 2);
            }
            
            $separationPositive[$ppk->id] = sqrt($sumPositive);
            $separationNegative[$ppk->id] = sqrt($sumNegative);
        }

        // Step 5: Preference Value (Closeness)
        $preferenceValues = [];
        foreach ($ppks as $ppk) {
            $total = $separationPositive[$ppk->id] + $separationNegative[$ppk->id];
            $preferenceValues[$ppk->id] = $total != 0 
                ? $separationNegative[$ppk->id] / $total 
                : 0;
        }

        // Step 6: Ranking
        arsort($preferenceValues);
        $ranking = [];
        $rank = 1;
        foreach ($preferenceValues as $ppkId => $value) {
            $ranking[] = [
                'ppk' => $ppks->where('id', $ppkId)->first(),
                'positive_distance' => $separationPositive[$ppkId],
                'negative_distance' => $separationNegative[$ppkId],
                'preference_value' => $value,
                'rank' => $rank++
            ];
        }

        return [
            'matrix' => $matrix,
            'normalized' => $normalized,
            'weighted' => $weighted,
            'idealPositive' => $idealPositive,
            'idealNegative' => $idealNegative,
            'separationPositive' => $separationPositive,
            'separationNegative' => $separationNegative,
            'preferenceValues' => $preferenceValues,
            'ranking' => $ranking,
        ];
    }

    private function saveResults($ranking, $period)
    {
        foreach ($ranking as $result) {
            TopsisResult::updateOrCreate(
                [
                    'user_id' => $result['ppk']->id,
                    'period' => $period
                ],
                [
                    'positive_distance' => $result['positive_distance'],
                    'negative_distance' => $result['negative_distance'],
                    'preference_value' => $result['preference_value'],
                    'rank' => $result['rank']
                ]
            );
        }
    }
}