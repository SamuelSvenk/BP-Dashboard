<?php

namespace App\Http\Controllers;

use App\Models\Build;
use Illuminate\Http\Request;

class BuildController extends Controller
{
    // API endpoint for GitLab CI/CD to send builds
    public function store(Request $request)
    {
        $request->validate([
            'build_number' => 'required|string|unique:builds',
            'repository' => 'required|string',
            'branch' => 'required|string',
            'commit_hash' => 'required|string',
            'commit_message' => 'nullable|string',
            'status' => 'required|in:success,failed,partial,in_progress,queued',
            'completed_at' => 'nullable|date',
        ]);

        $build = Build::create($request->all());

        return response()->json([
            'message' => 'Build recorded successfully',
            'data' => $build
        ], 201);
    }

    //  Page to show past (completed) builds
    public function pastBuilds()
    {
        $builds = Build::whereNotIn('status', ['queued', 'in_progress'])
                      ->orderBy('completed_at', 'desc')
                      ->paginate(6);
        
        return view('past-builds', compact('builds'));
    }

    // Page to show current (running) builds
    public function currentBuilds()
    {
        $builds = Build::whereIn('status', ['queued', 'in_progress'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(6);
        
        return view('current-builds', compact('builds'));
    }
}
