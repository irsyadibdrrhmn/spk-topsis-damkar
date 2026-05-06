<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\PersonilController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\TopsisController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    // Dashboard - Different for each role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return view('dashboard.admin');
        } elseif ($user->isPimpinan()) {
            return view('dashboard.pimpinan');
        } else {
            return view('dashboard.personil');
        }
    })->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::middleware(['role:admin'])->group(function () {
        // Criteria Management
        Route::resource('criteria', CriteriaController::class);
        
        // Personil Management
        Route::resource('personil', PersonilController::class);
        
        // Evaluation Management
        Route::get('evaluations', [EvaluationController::class, 'index'])->name('evaluations.index');
        Route::get('evaluations/create', [EvaluationController::class, 'create'])->name('evaluations.create');
        Route::post('evaluations', [EvaluationController::class, 'store'])->name('evaluations.store');
        Route::get('evaluations/{user}/{period}/edit', [EvaluationController::class, 'edit'])->name('evaluations.edit');
        Route::put('evaluations/{user}/{period}', [EvaluationController::class, 'update'])->name('evaluations.update');
        
        // TOPSIS Calculation & Ranking
        Route::get('topsis', [TopsisController::class, 'index'])->name('topsis.index');
        Route::get('topsis/ranking', [TopsisController::class, 'ranking'])->name('topsis.ranking');
    });

    // Pimpinan Routes (View Only)
    Route::middleware(['role:pimpinan'])->group(function () {
        // View recommendations and rankings
        Route::get('topsis/ranking', [TopsisController::class, 'ranking'])->name('topsis.ranking');
        Route::get('topsis', [TopsisController::class, 'index'])->name('topsis.index');
    });

    // Personil Routes (View Only)
    Route::middleware(['role:personil'])->group(function () {
        Route::get('my-performance', function () {
            $user = auth()->user();
            $periods = \App\Models\Evaluation::where('user_id', $user->id)
                ->select('period')
                ->distinct()
                ->orderBy('period', 'desc')
                ->pluck('period');
            
            $results = \App\Models\TopsisResult::where('user_id', $user->id)
                ->orderBy('period', 'desc')
                ->get();
            
            return view('personil.performance', compact('user', 'periods', 'results'));
        })->name('personil.performance');
    });
});

require __DIR__.'/auth.php';
