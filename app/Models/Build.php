<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Build;

class BuildController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'build_number' => 'required|integer|unique:builds',
            'repository' => 'required|string',
            'branch' => 'required|string',
            'commit_hash' => 'required|string',
            'commit_message' => 'required|string',
            'status' => 'required|string',
            'completed_at' => 'nullable|date',
        ]);

        $build = Build::create($request->all());

        return response()->json([
            'message' => 'Build recorded successfully',
            'data' => $build
        ], 201);
    }
}
