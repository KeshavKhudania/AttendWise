<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$perm = DB::table('institution_admin_permissions')->where('route_name', 'institution.attendance.dashboard')->first();
if (!$perm) {
    $permId = DB::table('institution_admin_permissions')->insertGetId([
        'name' => 'Attendance System',
        'route_name' => 'institution.attendance.dashboard',
        'action' => 'R',
        'icon' => 'fa-check-double',
        'sort_order' => 2,
        'method' => 'GET',
        'parent_id' => 0,
        'status' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]);
} else {
    $permId = $perm->id;
}

$group = \App\Models\AdminGroup::first(); // Assuming ID 1 or first is admin
if ($group) {
    $perms = unserialize($group->permissions) ?: [];
    if (!in_array('institution.attendance.dashboard', $perms)) {
        $perms[] = 'institution.attendance.dashboard';
        $group->permissions = serialize($perms);
        $group->save();
        echo 'Permission added to group';
    }
}
echo "Done.";
