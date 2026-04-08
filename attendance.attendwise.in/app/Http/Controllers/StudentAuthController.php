<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;

class StudentAuthController extends Controller
{
    /**
     * Handle student login for mobile app.
     *
     * POST /api/student/auth/login
     *
     * Accepts email/mobile + password + device_id.
     * Enforces single-device login: logging in on a new device
     * automatically logs out every other device.
     */
    public function login(Request $request): JsonResponse
    {
        // --- 1. Validate ---
        $validated = $request->validate([
            'roll_number' => 'required|string',             // roll number
            'login'       => 'required|string',             // email or mobile
            'password'    => 'required|string',
            'device_id'   => 'required|string',             // unique device identifier
            'fcm_token'   => 'nullable|string',             // push notification token
            'platform'    => 'nullable|string|in:android,ios',
        ]);

        // --- 2. Find student by roll_number AND (email or mobile) ---
        $loginHash = search_hash($validated['login']);

        $student = Student::where('roll_number', $validated['roll_number'])
                          ->where(function ($query) use ($loginHash) {
                              $query->where('email_hash', $loginHash)
                                    ->orWhere('mobile_hash', $loginHash);
                          })
                          ->first();

        if (! $student) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
            ], 401);
        }

        // --- 4. Check account status ---
        if ($student->status != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been deactivated. Please contact your institution.',
            ], 403);
        }

        // --- 5. Verify password ---
        // Password is stored via the Encrypted cast (Laravel Crypt), not bcrypt
        $decryptedPassword = $student->password; // Accessor auto-decrypts via Encrypted cast

        if ($validated['password'] !== $decryptedPassword) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
            ], 401);
        }

        // --- 5. Enforce single-device login ---
        // This deletes all existing Sanctum tokens and upserts the session record
        $student->enforceSingleDeviceLogin(
            $validated['device_id'],
            $validated['fcm_token'] ?? null
        );

        // Update platform if provided
        if (isset($validated['platform'])) {
            $student->session()->update(['platform' => $validated['platform']]);
        }

        // --- 6. Create new Sanctum token ---
        $token = $student->createToken('student-mobile-token')->plainTextToken;

        // --- 7. Load relationships for response ---
        $student->load(['institution:id,name', 'session']);

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'data'    => [
                'token'   => $token,
                'student' => [
                    'id'                => $student->id,
                    'name'              => $student->name,
                    'email'             => $student->email,
                    'mobile'            => $student->mobile,
                    'roll_number'       => $student->roll_number,
                    'enrollment_number' => $student->enrollment_number,
                    'institution'       => $student->institution ? [
                        'id'   => $student->institution->id,
                        'name' => $student->institution->name,
                    ] : null,
                ],
            ],
        ], 200);
    }

    /**
     * Logout current device.
     *
     * POST /api/student/auth/logout
     * Requires: auth:sanctum
     */
    public function logout(Request $request): JsonResponse
    {
        /** @var Student $student */
        $student = $request->user();

        // Delete only the current token
        $student->currentAccessToken()->delete();

        // Clear the session record (device_id, fcm_token)
        $student->session()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);
    }

    /**
     * Logout from all devices.
     *
     * POST /api/student/auth/logout-all
     * Requires: auth:sanctum
     */
    public function logoutAll(Request $request): JsonResponse
    {
        /** @var Student $student */
        $student = $request->user();

        $student->logoutFromAllDevices();

        return response()->json([
            'success' => true,
            'message' => 'Logged out from all devices successfully.',
        ]);
    }
}
