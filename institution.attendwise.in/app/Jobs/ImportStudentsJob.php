<?php

namespace App\Jobs;

use App\Models\Student;
use App\Models\Department;
use App\Models\Course;
use App\Models\Section;
use App\Models\ClassGroup;
use App\Models\ImportLog;
use App\Models\InstitutionNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Throwable;

class ImportStudentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $filePath;
    protected int $institutionId;
    protected bool $assignSections;
    protected bool $autoCreateSections;
    protected int $sectionCapacity;
    protected string $sectionPrefix;
    protected bool $autoCreateGroups;
    protected string $groupPrefix;

    public function __construct(
        string $filePath,
        int $institutionId,
        bool $assignSections,
        bool $autoCreateSections,
        int $sectionCapacity,
        string $sectionPrefix,
        bool $autoCreateGroups,
        string $groupPrefix
    ) {
        $this->filePath = $filePath;
        $this->institutionId = $institutionId;
        $this->assignSections = $assignSections;
        $this->autoCreateSections = $autoCreateSections;
        $this->sectionCapacity = $sectionCapacity;
        $this->sectionPrefix = $sectionPrefix;
        $this->autoCreateGroups = $autoCreateGroups;
        $this->groupPrefix = $groupPrefix;
    }

    public function handle(): void
    {
        $inserted = 0;
        $updated  = 0;
        $skipped  = 0;
        $errors   = [];

        DB::beginTransaction();

        try {
            /* -----------------------------------------
             | Section cache (course + section + year + semester)
             |-----------------------------------------*/
            $sectionsIndex = [];

            /* -----------------------------------------
             | Open Excel/CSV
             |-----------------------------------------*/
            $fullPath = storage_path('app/' . $this->filePath);
            if (!file_exists($fullPath)) {
                throw new \Exception('File does not exist.');
            }
            
            $dataRows = \Maatwebsite\Excel\Facades\Excel::toArray(new class {}, $fullPath)[0];
            
            if (count($dataRows) === 0) {
                throw new \Exception('File is empty.');
            }
            
            $header = array_shift($dataRows);
            if ($header) {
                foreach ($header as &$h) {
                    if (is_string($h)) {
                        $h = str_replace("\xEF\xBB\xBF", '', $h);
                        $h = trim(preg_replace('/\(required\)/i', '', $h));
                        $h = trim(str_replace('*', '', $h));
                    }
                }
            }

            foreach ($dataRows as $row) {
                if (count($header) !== count($row)) continue;
                $data = array_combine($header, $row);

                /* -----------------------------------------
                 | Resolve academic year & semester
                 |-----------------------------------------*/
                $academicYear = trim($data['academic_year'] ?? '');
                $semester     = trim($data['semester'] ?? null);

                if ($academicYear === '') {
                    $skipped++;
                    $errors[] = ['row' => $data, 'reason' => 'Missing academic year'];
                    continue;
                }

                /* -----------------------------------------
                 | Department
                 |-----------------------------------------*/
                $departmentName = trim($data['department'] ?? '');
                if ($departmentName === '') {
                    $skipped++;
                    $errors[] = ['row' => $data, 'reason' => 'Missing department'];
                    continue;
                }

                $department = Department::firstOrCreate(
                    [
                        'institution_id' => $this->institutionId,
                        'name' => $departmentName,
                    ],
                    ['status' => 1]
                );

                /* -----------------------------------------
                 | Course
                 |-----------------------------------------*/
                $courseName = trim($data['course'] ?? '');
                if ($courseName === '') {
                    $skipped++;
                    $errors[] = ['row' => $data, 'reason' => 'Missing course'];
                    continue;
                }

                $course = Course::firstOrCreate(
                    [
                        'institution_id' => $this->institutionId,
                        // 'department_id'  => $department->id,
                        'name'           => $courseName,
                    ],
                    ['status' => 1]
                );

                /* -----------------------------------------
                 | Section (WITH semester + academic year)
                 |-----------------------------------------*/
                $section = null;
                $sectionName = trim($data['section'] ?? '');
                $groupName = trim($data['class_group'] ?? '');
                $classGroup = null;

                if ($this->assignSections && $sectionName !== '') {
                    $fullName = trim($this->sectionPrefix . ' ' . $sectionName);
                    $sectionKey = implode('|', [
                        $course->id,
                        strtolower($fullName),
                        $academicYear,
                        $semester
                    ]);

                    if (isset($sectionsIndex[$sectionKey])) {
                        $section = $sectionsIndex[$sectionKey];
                    } elseif ($this->autoCreateSections) {
                        $section = Section::firstOrCreate(
                            [
                                'institution_id' => $this->institutionId,
                                'department_id'  => $department->id,
                                'course_id'      => $course->id,
                                'name'           => $fullName,
                                'academic_year'  => $academicYear,
                                'semester'       => $semester,
                            ],
                            [
                                'capacity' => $this->sectionCapacity,
                                'status'   => 1,
                            ]
                        );

                        $sectionsIndex[$sectionKey] = $section;
                    }
                }

                /* -----------------------------------------
                 | Class Group
                 |-----------------------------------------*/
                if ($section && $groupName !== '') {
                    $fullGroupName = trim($this->groupPrefix . ' ' . $groupName);
                    if ($this->autoCreateGroups) {
                        $classGroup = ClassGroup::firstOrCreate(
                            [
                                'institution_id' => $this->institutionId,
                                'section_id'     => $section->id,
                                'name'           => $fullGroupName,
                            ],
                            ['status' => 1]
                        );
                    } else {
                        $classGroup = ClassGroup::where('institution_id', $this->institutionId)
                            ->where('section_id', $section->id)
                            ->where('name', $fullGroupName)
                            ->first();
                    }
                }

                /* -----------------------------------------
                 | UPSERT Student (roll_number)
                 |-----------------------------------------*/
                $student = Student::where('institution_id', $this->institutionId)
                    ->where('roll_number', $data['roll_number'])
                    ->first();

                $payload = [];
                $extraFields = [
                    'name', 'email', 'mobile', 'gender', 'first_name', 'last_name', 'batch', 'specialization', 'address', 'guardian_details', 'admission_date', 'enrollment_number',
                    'date_of_birth', 'blood_group', 'religion', 'caste_category', 'nationality', 'mother_tongue', 'national_id', 'permanent_address', 'emergency_contact_name', 'emergency_contact_number',
                    'admission_type', 'previous_qualification', 'previous_school', 'previous_marks_percentage', 'is_hosteller', 'hostel_room_details', 'uses_transport', 'transport_route_details', 'bank_account_no', 'bank_name', 'bank_ifsc', 'medical_history'
                ];
                
                foreach ($extraFields as $field) {
                    if (isset($data[$field]) && trim($data[$field]) !== '') {
                        $payload[$field] = trim($data[$field]);
                    }
                }
                
                if (!empty($data['password'])) {
                    $payload['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
                }

                $payload['department_id']  = $department->id;
                $payload['course_id']      = $course->id;
                $payload['section_id']     = $section?->id;
                $payload['class_group_id'] = $classGroup?->id;
                $payload['academic_year'] = $academicYear;

                if ($student) {
                    $student->update($payload);
                    $updated++;
                } else {
                    Student::create(array_merge([
                        'institution_id' => $this->institutionId,
                        'roll_number'    => $data['roll_number'],
                        'status'         => 1,
                        'password'       => 'Qwerty@321',
                    ], $payload));
                    $inserted++;
                }
            }

            // File is loaded in memory, no need to fclose.

            /* -----------------------------------------
             | Import Log
             |-----------------------------------------*/
            $log = ImportLog::create([
                'institution_id' => $this->institutionId,
                'type'           => 'students',
                'total_rows'     => $inserted + $updated + $skipped,
                'inserted'       => $inserted,
                'updated'        => $updated,
                'skipped'        => $skipped,
                'errors'         => $errors ?: null,
            ]);

            DB::commit();

            InstitutionNotification::create([
                'institution_id' => $this->institutionId,
                'type'           => 'success',
                'title'          => 'Student Import Completed',
                'message'        => "Inserted {$inserted}, Updated {$updated}, Skipped {$skipped}.",
                'data'           => ['log_id' => $log->id],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function failed(Throwable $exception): void
    {
        InstitutionNotification::create([
            'institution_id' => $this->institutionId,
            'type'           => 'error',
            'title'          => 'Student Import Failed',
            'message'        => 'Student import failed.',
            'data'           => [
                'error' => $exception->getMessage(),
                'file'  => $exception->getFile(),
                'line'  => $exception->getLine(),
            ],
        ]);
    }
}
