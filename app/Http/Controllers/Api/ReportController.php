<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|integer',
            'user_id' => 'required|integer',
            'module_name' => 'required|string',
            'report_type' => 'required|string',
            'other' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $existingReport = Report::where('user_id', $request->user_id)
            ->where('post_id', $request->post_id)
            ->where('module_name', $request->module_name)
            ->exists();

        if ($existingReport) {
            return response()->json([
                'status' => 'error',
                'message' => 'You have already reported this post.',
            ], 422);
        }

        $report = Report::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Report submitted successfully.',
            'data' => $report
        ], 201);
    }
}
