<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\InstitutionAcademicSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class InstitutionSettingController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $user = get_logged_in_user();
        if (!$user)
            return redirect()->route('login');

        $institutionId = $user->institution_id;
        $institution = Institution::findOrFail($institutionId);

        $settings = InstitutionAcademicSetting::firstOrCreate(
        ['institution_id' => $institutionId],
        [
            'slots_per_day' => 8,
            'faculty_lecture_limit' => 6,
            'theory_slot_limit' => 1,
            'lab_slot_limit' => 2,
            'slot_timings' => [],
            'working_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            'holidays' => []
        ]
        );

        $data = [
            'title' => 'Institution Settings',
            'institution' => $institution,
            'settings' => $settings,
            'working_days_list' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']
        ];

        return view('settings.index', $data);
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = get_logged_in_user();
            $institutionId = $user->institution_id;

            $institution = Institution::findOrFail($institutionId);
            $settings = InstitutionAcademicSetting::where('institution_id', $institutionId)->first();

            // 1. Update Institution Details
            $institution->update($request->only([
                'legal_name',
                'institution_type',
                'registered_address',
                'year_of_establishment'
            ]));

            // 2. Update Basic Academic Settings
            $settings->slots_per_day = $request->input('slots_per_day', 8);
            $settings->faculty_lecture_limit = $request->input('faculty_lecture_limit', 6);
            $settings->theory_slot_limit = $request->input('theory_slot_limit', 1);
            $settings->lab_slot_limit = $request->input('lab_slot_limit', 2);
            $settings->working_days = $request->input('working_days', []);

            // 3. Update Slot Timings
            $timings = [];
            for ($i = 1; $i <= $settings->slots_per_day; $i++) {
                $timings[$i] = [
                    'start' => $request->input("slot_{$i}_start"),
                    'end' => $request->input("slot_{$i}_end"),
                ];
            }
            $settings->slot_timings = $timings;

            // 4. Update Holiday Management
            $holidays = [];
            $holidayDates = $request->input('holiday_date', []);
            $holidayNames = $request->input('holiday_name', []);

            if (is_array($holidayDates)) {
                foreach ($holidayDates as $index => $date) {
                    if (!empty($date)) {
                        $holidays[] = [
                            'date' => $date,
                            'name' => $holidayNames[$index] ?? 'Holiday'
                        ];
                    }
                }
            }
            $settings->holidays = $holidays;

            $settings->save();

            DB::commit();

            return json_encode([
                "msg" => "Settings updated successfully.",
                "color" => "success",
                "icon" => "check-circle"
            ]);

        }
        catch (\Throwable $th) {
            DB::rollBack();
            return abort(403, json_encode([
                "msg" => "Failed to update settings: " . $th->getMessage(),
                "color" => "danger",
                "icon" => "exclamation-triangle"
            ]));
        }
    }
}