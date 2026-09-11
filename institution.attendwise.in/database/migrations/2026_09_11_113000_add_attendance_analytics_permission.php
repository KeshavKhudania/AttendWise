<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $parent = DB::table('institution_admin_permissions')->where('name', 'like', '%Analystics%')->orWhere('name', 'like', '%Analytics%')->where('parent_id', 0)->first();
        $parentId = $parent ? $parent->id : 289;

        $exists = DB::table('institution_admin_permissions')->where('route_name', 'institution.analytics.attendance')->first();
        if (!$exists) {
            DB::table('institution_admin_permissions')->insert([
                'name' => 'Attendance Analytics',
                'route_name' => 'institution.analytics.attendance',
                'action' => 'R',
                'icon' => 'fa-chart-bar',
                'sort_order' => 1,
                'method' => 'GET',
                'parent_id' => $parentId,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        // Add to Admin Groups
        $groups = DB::table('admin_groups')->get();
        foreach ($groups as $g) {
            $p = @unserialize($g->permissions);
            if (is_array($p) && !in_array('institution.analytics.attendance', $p)) {
                $p[] = 'institution.analytics.attendance';
                DB::table('admin_groups')->where('id', $g->id)->update(['permissions' => serialize($p)]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('institution_admin_permissions')->where('route_name', 'institution.analytics.attendance')->delete();
    }
};
