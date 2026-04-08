<?php

namespace App\Http\Controllers;

use App\Models\AppErrorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AppErrorController extends Controller
{
    /**
     * Log an error from the mobile app.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'error_message' => 'required|string',
                'stack_trace' => 'nullable|string',
                'device_id' => 'nullable|string',
                'app_version' => 'nullable|string',
                'device_info' => 'nullable|array',
                'api_endpoint' => 'nullable|string',
                'request_payload' => 'nullable|array',
                'response_data' => 'nullable|array',
            ]);

            $errorLog = AppErrorLog::create([
                'student_id' => $request->user()?->id,
                'device_id' => $validated['device_id'],
                'error_message' => $validated['error_message'],
                'stack_trace' => $validated['stack_trace'],
                'app_version' => $validated['app_version'],
                'device_info' => $validated['device_info'],
                'api_endpoint' => $validated['api_endpoint'],
                'request_payload' => $validated['request_payload'],
                'response_data' => $validated['response_data'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Error logged successfully',
                'log_id' => $errorLog->id
            ], 201);

        } catch (\Exception $e) {
            Log::error('Failed to log app error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to log error'
            ], 500);
        }
    }
}
