<?php

namespace App\Http\Controllers;

use App\Models\Build;
use Illuminate\Http\Request;

class BuildController extends Controller
{
    /**
     * Display a listing of current builds.
     */
    public function currentBuilds()
    {
        $builds = Build::whereIn('status', ['queued', 'in_progress'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(12);
        
        return view('current-builds', compact('builds'));
    }

    /**
     * Display a listing of past builds.
     */
    public function pastBuilds()
    {
        $builds = Build::whereNotIn('status', ['queued', 'in_progress'])
                      ->orderBy('completed_at', 'desc')
                      ->paginate(12);
        
        return view('past-builds', compact('builds'));
    }

    /**
     * Store a newly created build notification.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'build_number' => 'required',
            'repository' => 'required',
            'branch' => 'required',
            'commit_hash' => 'required',
            'status' => 'required|in:success,failed,partial,in_progress,queued',
            'created_at' => 'nullable|date',
            'commit_message' => 'nullable|string',
        ]);

        // Calculate completed_at based on status
        $completedAt = in_array($request->status, ['success', 'failed', 'partial']) 
            ? now() 
            : null;

        // Create a new build
        $build = Build::updateOrCreate(
            [
                'build_number' => $request->build_number,
                'repository' => $request->repository
            ],
            [
                'branch' => $request->branch,
                'commit_hash' => $request->commit_hash,
                'commit_message' => $request->commit_message,
                'status' => $request->status,
                'created_at' => $request->created_at ?? now(),
                'completed_at' => $completedAt
            ]
        );

        return response()->json([
            'message' => 'Build notification received',
            'build' => $build
        ], 201);
    }

    /**
     * Update the specified build notification.
     */

    public function update(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'build_number' => 'required',
            'repository' => 'required',
            'branch' => 'required',
            'commit_hash' => 'required',
            'status' => 'required|in:success,failed,partial,in_progress,queued',
            'created_at' => 'nullable|date',
            'commit_message' => 'nullable|string',
        ]);

        // Calculate completed_at based on status
        $completedAt = in_array($request->status, ['success', 'failed', 'partial']) 
            ? now() 
            : null;

        // Update the build
        $build = Build::where('build_number', $request->build_number)
                      ->where('repository', $request->repository)
                      ->update([
                          'branch' => $request->branch,
                          'commit_hash' => $request->commit_hash,
                          'commit_message' => $request->commit_message,
                          'status' => $request->status,
                          'created_at' => $request->created_at ?? now(),
                          'completed_at' => $completedAt
                      ]);

        return response()->json([
            'message' => 'Build notification updated',
            'build' => $build
        ], 200);
    }
}