<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BuildController;
use App\Models\Build;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

// Dashboard route
Route::get('/dashboard', function () {

    $totalProjects = Build::distinct('repository')->count('repository');
    $successBuilds = Build::where('status', 'success')->count();
    $partialBuilds = Build::where('status', 'partial')->count();
    $failedBuilds = Build::where('status', 'failed')->count();
    $inProgressBuilds = Build::whereIn('status', ['in_progress', 'queued'])->count();
    
   
    $completedBuilds = $successBuilds + $partialBuilds;
    $totalBuilds = $completedBuilds + $failedBuilds + $inProgressBuilds;
    
    $totalFinishedBuilds = $completedBuilds + $failedBuilds;
    $successRate = $totalFinishedBuilds > 0 
        ? round(($successBuilds / $totalFinishedBuilds) * 100) 
        : 0;
    

    $latestBuilds = Build::orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();
    
    // Daily builds (last 7 days)
    $dailyLabels = [];
    $dailyBuilds = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = Carbon::now()->subDays($i)->format('Y-m-d');
        $count = Build::whereDate('created_at', $date)->count();
        $dailyLabels[] = Carbon::now()->subDays($i)->format('D');
        $dailyBuilds[] = $count;
    }
    
    // Branch distribution
    $branchData = Build::select('branch', DB::raw('count(*) as count'))
                        ->whereNotIn('status', ['queued', 'in_progress'])
                        ->groupBy('branch')
                        ->orderBy('count', 'desc')
                        ->limit(5)
                        ->get();

    $branches = $branchData->pluck('branch')->toArray();
    $branchBuilds = $branchData->pluck('count')->toArray();
    
    return view('dashboard', compact(
        'totalProjects',
        'totalBuilds',
        'completedBuilds', 
        'inProgressBuilds',
        'failedBuilds',
        'partialBuilds',
        'successBuilds',
        'successRate',
        'dailyLabels',
        'dailyBuilds',
        'branches',
        'branchBuilds',
        'latestBuilds'  
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

// Root route
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

// Builds (current + past)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/current-builds', [BuildController::class, 'currentBuilds'])->name('current-builds');
    Route::get('/past-builds', [BuildController::class, 'pastBuilds'])->name('past-builds');
});

// Profile management
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Build notification endpoint
Route::get('/builds/{build}', [BuildController::class, 'show'])->name('builds.show')->middleware(['auth', 'verified']);

// Auth routes (login, register, forgot password, etc.)
require __DIR__.'/auth.php';
