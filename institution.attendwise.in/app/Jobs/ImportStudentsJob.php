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
    // protected string $academicYear; // fallback only
    protected int $institutionId;
    protected bool $autoCreateSections;
    protected int $sectionCapacity;

    public function __construct(
        string $filePath,
        string $academicYear,
        int $institutionId,
        bool $autoCreateSections = true,
        int $sectionCapacity = 30
    ) {
        $this->filePath = $filePath;
        // $this->academicYear = $academicYear; // fallback
        $this->institutionId = $institutionId;
        $this->autoCreateSections = $autoCreateSections;
        $this->sectionCapacity = $sectionCapacity;
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
             | Open CSV
             |-----------------------------------------*/
            $file = fopen(storage_path('app/' . $this->filePath), 'r');
            if (!$file) {
                throw new \Exception('Unable to open CSV file.');
            }

            $header = fgetcsv($file);

            while (($row = fgetcsv($file)) !== false) {
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

                if ($sectionName !== '') {
                    $sectionKey = implode('|', [
                        $course->id,
                        strtolower($sectionName),
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
                                'name'           => $sectionName,
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
                $classGroup = null;
                $groupName = trim($data['class_group'] ?? '');

                if ($section && $groupName !== '') {
                    $classGroup = ClassGroup::firstOrCreate(
                        [
                            'institution_id' => $this->institutionId,
                            'section_id'     => $section->id,
                            'name'           => $groupName,
                        ],
                        ['status' => 1]
                    );
                }

                /* -----------------------------------------
                 | UPSERT Student (roll_number)
                 |-----------------------------------------*/
                $student = Student::where('institution_id', $this->institutionId)
                    ->where('roll_number', $data['roll_number'])
                    ->first();

                $payload = [];

                foreach (['name', 'email', 'mobile', 'gender'] as $field) {
                    if (!empty($data[$field])) {
                        $payload[$field] = $data[$field];
                    }
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

            fclose($file);

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
