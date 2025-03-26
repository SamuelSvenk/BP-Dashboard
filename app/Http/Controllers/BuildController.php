<?php

namespace App\Http\Controllers;

use App\Models\Build;
use Illuminate\Http\Request;

class BuildController extends Controller
{
    public function pastBuilds()
    {
        $builds = Build::whereNotIn('status', ['queued', 'in_progress'])
                      ->orderBy('completed_at', 'desc')
                      ->paginate(6);
        
        return view('past-builds', compact('builds'));
    }

    public function currentBuilds()
    {
        $builds = Build::whereIn('status', ['queued', 'in_progress'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(6);
        
        return view('current-builds', compact('builds'));
    }
}