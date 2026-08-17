<?php

namespace App\Jobs;

use App\Models\Course;
use App\Models\Department;
use App\Models\Subject;
use App\Models\SemesterSubject;
use App\Models\ImportLog;
use App\Models\InstitutionNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Throwable;

class ImportSubjectMappingsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $filePath;
    protected int $institutionId;

    public function __construct(string $filePath, int $institutionId)
    {
        $this->filePath = $filePath;
        $this->institutionId = $institutionId;
    }

    public function handle(): void
    {
        $inserted = 0;
        $updated  = 0;
        $skipped  = 0;
        $errors   = [];

        DB::beginTransaction();

        try {
            $fullPath = storage_path('app/' . $this->filePath);
            if (!file_exists($fullPath)) throw new \Exception('File does not exist.');
            
            $dataRows = \Maatwebsite\Excel\Facades\Excel::toArray(new class {}, $fullPath)[0];
            if (count($dataRows) === 0) throw new \Exception('File is empty.');
            
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

                $courseName = trim($data['course'] ?? '');
                $departmentName = trim($data['department'] ?? '');
                $semester = trim($data['semester'] ?? '');
                $subjectCodes = trim($data['subject_codes'] ?? '');
                
                if ($courseName === '' || $semester === '' || $subjectCodes === '') {
                    $skipped++;
                    $errors[] = ['row' => $data, 'reason' => 'Missing course, semester or subject_codes'];
                    continue;
                }

                $course = Course::where('institution_id', $this->institutionId)
                    ->where('name', $courseName)
                    ->first();

                if (!$course) {
                    $skipped++;
                    $errors[] = ['row' => $data, 'reason' => 'Course not found'];
                    continue;
                }

                $department = null;
                if ($departmentName !== '') {
                    $department = Department::where('institution_id', $this->institutionId)
                        ->where('name', $departmentName)
                        ->first();
                }

                $subjectCodesArray = array_map('trim', explode(',', $subjectCodes));
                $subjects = Subject::where('institution_id', $this->institutionId)
                    ->whereIn('code', $subjectCodesArray)
                    ->pluck('id')
                    ->toArray();

                if (empty($subjects)) {
                    $skipped++;
                    $errors[] = ['row' => $data, 'reason' => 'No valid subject codes found'];
                    continue;
                }

                $mapping = SemesterSubject::where('institution_id', $this->institutionId)
                    ->where('course_id', $course->id)
                    ->where('semester', $semester)
                    ->first();

                if ($mapping) {
                    // Update existing mapping by adding/overwriting subjects
                    $existingSubjects = is_array($mapping->subjects) ? $mapping->subjects : json_decode($mapping->subjects, true);
                    $newSubjects = array_unique(array_merge($existingSubjects ?? [], $subjects));
                    $mapping->update([
                        'subjects' => $newSubjects,
                        'department_id' => $department ? $department->id : $mapping->department_id
                    ]);
                    $updated++;
                } else {
                    SemesterSubject::create([
                        'institution_id' => $this->institutionId,
                        'course_id'      => $course->id,
                        'semester'       => $semester,
                        'department_id'  => $department ? $department->id : null,
                        'subjects'       => $subjects
                    ]);
                    $inserted++;
                }
            }

            $log = ImportLog::create([
                'institution_id' => $this->institutionId,
                'type'           => 'subject_mappings',
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
                'title'          => 'Subject Mappings Import Completed',
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
            'title'          => 'Subject Mappings Import Failed',
            'message'        => 'Subject mappings import failed.',
            'data'           => [
                'error' => $exception->getMessage(),
            ],
        ]);
    }
}
