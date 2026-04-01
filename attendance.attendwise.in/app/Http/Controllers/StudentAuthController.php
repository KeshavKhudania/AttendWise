<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

class StudentAuthController extends Controller
{
    /**
     * Handle student login and issue Sanctum token.
     */
    public function login(Request $request): Response
    {
        // Validate incoming request
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to retrieve the student by email
        $student = Student::where('email', search_hash($validated['email']))->first();

        // Verify password
        if (! $student || ! $validated['password'] == $student->password) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        // Create a Sanctum token for the student
        $token = $student->createToken('student-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'student' => $student,
        ], 200);
    }
}
