<?php

namespace App\Http\Controllers;

use App\Models\Build;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; 


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
     * Display a listing of past builds with optional status filtering.
     */
    public function pastBuilds(Request $request)
    {
        $query = Build::whereNotIn('status', ['queued', 'in_progress']);
        
        // Apply status filter if provided
        if ($request->has('status') && in_array($request->status, ['success', 'failed', 'partial'])) {
            $query->where('status', $request->status);
        }
        
        $builds = $query->orderBy('completed_at', 'desc')
                    ->paginate(12)
                    ->withQueryString();
        
        // Get counts for each status in a single query
        $statusCounts = Build::select('status', DB::raw('count(*) as count'))
                        ->whereNotIn('status', ['queued', 'in_progress'])
                        ->groupBy('status')
                        ->pluck('count', 'status')
                        ->toArray();
        
        $counts = [
            'all' => array_sum($statusCounts),
            'success' => $statusCounts['success'] ?? 0,
            'failed' => $statusCounts['failed'] ?? 0,
            'partial' => $statusCounts['partial'] ?? 0,
        ];
        
        return view('past-builds', compact('builds', 'counts'));
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
            'logs' => 'nullable|string',  
        ]);
    
        // Calculate completed_at based on status
        $completedAt = in_array($request->status, ['success', 'failed', 'partial']) 
            ? now() 
            : null;
    
        // Prepare build data
        $data = [
            'branch' => $request->branch,
            'commit_hash' => $request->commit_hash,
            'commit_message' => $request->commit_message,
            'status' => $request->status,
            'created_at' => $request->created_at ?? now(),
            'completed_at' => $completedAt
        ];
        
        // Add logs if the column exists
        if (Schema::hasColumn('builds', 'logs') && $request->has('logs')) {
            $data['logs'] = $request->logs;
        }
    
        // Create a new build
        $build = Build::updateOrCreate(
            [
                'build_number' => $request->build_number,
                'repository' => $request->repository
            ],
            $data
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
            'logs' => 'nullable|string',  // Added logs validation
        ]);

        // Calculate completed_at based on status
        $completedAt = in_array($request->status, ['success', 'failed', 'partial']) 
            ? now() 
            : null;

        // Prepare update data
        $data = [
            'branch' => $request->branch,
            'commit_hash' => $request->commit_hash,
            'commit_message' => $request->commit_message,
            'status' => $request->status,
            'created_at' => $request->created_at ?? now(),
            'completed_at' => $completedAt
        ];
        
        // Add logs if the column exists
        if (Schema::hasColumn('builds', 'logs') && $request->has('logs')) {
            $data['logs'] = $request->logs;
        }

        // Update the build
        $build = Build::where('build_number', $request->build_number)
                      ->where('repository', $request->repository)
                      ->first();
        
        if ($build) {
            $build->update($data);
            
            return response()->json([
                'message' => 'Build notification updated',
                'build' => $build
            ], 200);
        }
        
        return response()->json([
            'message' => 'Build not found',
        ], 404);
    }
    
    /**
     * Display details for a specific build.
     */
    public function show(Build $build)
    {
        // Check if the build exists
        return view('builds.show', compact('build'));
    }
}