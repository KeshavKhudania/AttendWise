<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('attendance.session.{uuid}', function ($user, $uuid) {
    $session = \App\Models\AttendanceSession::where('uuid', $uuid)->first();
    if (!$session) return false;

    if (get_class($user) === \App\Models\Faculty::class) {
        if ($session->faculty_id === $user->id) {
            return ['id' => 'faculty_'.$user->id, 'name' => $user->name, 'type' => 'faculty'];
        }
    } elseif (get_class($user) === \App\Models\Student::class) {
        return ['id' => 'student_'.$user->id, 'name' => $user->name, 'roll_number' => $user->roll_number, 'type' => 'student'];
    }
    
    return false;
}, ['guards' => ['faculty', 'student']]);
