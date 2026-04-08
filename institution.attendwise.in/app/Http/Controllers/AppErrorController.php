<?php

namespace App\Http\Controllers;

use App\Models\AppErrorLog;
use Illuminate\Http\Request;

class AppErrorController extends Controller
{
    /**
     * Mark an app error as resolved.
     */
    public function resolve($id)
    {
        $error = AppErrorLog::findOrFail($id);
        $error->update(['is_resolved' => true]);

        return redirect()->back()->with('success', 'Error marked as resolved.');
    }
}
