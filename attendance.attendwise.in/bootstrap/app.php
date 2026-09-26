<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectTo(
            guests: function (\Illuminate\Http\Request $request) {
                if ($request->is('faculty*')) {
                    return route('faculty.login');
                }
                if ($request->is('club*')) {
                    return route('club.login');
                }
                return route('student.login');
            },
            users: function (\Illuminate\Http\Request $request) {
                if ($request->is('faculty*')) {
                    return route('faculty.dashboard');
                }
                if ($request->is('club*')) {
                    return route('club.dashboard');
                }
                return route('student.dashboard');
            }
        );
        
        $middleware->validateCsrfTokens(except: [
            'api/v1/attendance/mark-qr'
        ]);

        $middleware->alias([
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
            'club.role' => \App\Http\Middleware\CheckClubRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson()) {
                $loginUrl = route('student.login');
                if ($request->is('faculty*')) {
                    $loginUrl = route('faculty.login');
                } elseif ($request->is('club*')) {
                    $loginUrl = route('club.login');
                }
                return response()->json(['message' => 'Session expired. Please login again.', 'redirect' => $loginUrl], 401);
            }
        });

        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            $loginUrl = route('student.login');
            if ($request->is('faculty*')) {
                $loginUrl = route('faculty.login');
            } elseif ($request->is('club*')) {
                $loginUrl = route('club.login');
            }
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Session expired. Please login again.', 'redirect' => $loginUrl], 419);
            }
            return redirect()->to($loginUrl)->with('error', 'Session expired. Please login again.');
        });
    })->create();
