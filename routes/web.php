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
    // Get latest builds
    $latestBuilds = Build::orderBy('completed_at', 'desc')
                         ->take(10)
                         ->get();
    
    // Calculate build statistics
    $totalProjects = Build::distinct('repository')->count('repository');
    
    // Count all statuses separately
    $successBuilds = Build::where('status', 'success')->count();
    $partialBuilds = Build::where('status', 'partial')->count();
    $failedBuilds = Build::where('status', 'failed')->count();
    $inProgressBuilds = Build::whereIn('status', ['in_progress', 'queued'])->count();
    
    // Completed builds include success and partial
    $completedBuilds = $successBuilds + $partialBuilds;
    
    // Total builds includes all builds: completed, failed and in-progress
    $totalBuilds = $completedBuilds + $failedBuilds + $inProgressBuilds;
    
    // Calculate success rate if there are completed builds
    $totalFinishedBuilds = $completedBuilds + $failedBuilds;
    $successRate = $totalFinishedBuilds > 0 
        ? round(($successBuilds / $totalFinishedBuilds) * 100) 
        : 0;
    
    // Chart Data: Daily build activity for last 7 days
    $lastWeek = [];
    $dailyBuilds = [];
    $dailyLabels = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = Carbon::now()->subDays($i)->format('Y-m-d');
        $count = Build::whereDate('created_at', $date)->count();
        $dailyBuilds[] = $count;
        $dailyLabels[] = Carbon::now()->subDays($i)->format('D');
    }
    
    // Chart Data: Repository build distribution
    $branchData = Build::select('branch', DB::raw('count(*) as count'))
    ->whereNotIn('status', ['queued', 'in_progress'])
    ->groupBy('branch')
    ->orderBy('count', 'desc')
    ->get();

    $branches = $branchData->pluck('branch')->toArray();
    $branchBuilds = $branchData->pluck('count')->toArray();
    
    return view('dashboard', compact(
        'latestBuilds', 
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
        'branchBuilds'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

// Root route - show welcome page or redirect to dashboard based on authentication
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    } else {
        return view('welcome');
    }
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/current-builds', [BuildController::class, 'currentBuilds'])->name('current-builds');
    Route::get('/past-builds', [BuildController::class, 'pastBuilds'])->name('past-builds');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';