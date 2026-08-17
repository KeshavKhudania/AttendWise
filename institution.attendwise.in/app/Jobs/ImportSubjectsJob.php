<?php

namespace App\Jobs;

use App\Models\Subject;
use App\Models\ImportLog;
use App\Models\InstitutionNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Throwable;

class ImportSubjectsJob implements ShouldQueue
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

                $name = trim($data['name'] ?? '');
                $code = trim($data['code'] ?? '');
                
                if ($name === '' || $code === '') {
                    $skipped++;
                    $errors[] = ['row' => $data, 'reason' => 'Missing subject name or code'];
                    continue;
                }
                
                $subject = Subject::where('institution_id', $this->institutionId)
                    ->where('code', $code)
                    ->first();

                $departmentName = trim($data['department'] ?? '');
                $departmentId = null;
                if ($departmentName !== '') {
                    $dept = \App\Models\Department::where('institution_id', $this->institutionId)
                        ->where('name', $departmentName)
                        ->first();
                    if ($dept) $departmentId = $dept->id;
                }

                $courseName = trim($data['course'] ?? '');
                $courseId = null;
                if ($courseName !== '') {
                    $courseModel = \App\Models\Course::where('institution_id', $this->institutionId)
                        ->where('name', $courseName)
                        ->first();
                    if ($courseModel) $courseId = $courseModel->id;
                }

                $additionalDepartmentName = trim($data['additional_department'] ?? '');
                $additionalDepartmentId = null;
                if ($additionalDepartmentName !== '') {
                    $addDept = \App\Models\Department::where('institution_id', $this->institutionId)
                        ->where('name', $additionalDepartmentName)
                        ->first();
                    if ($addDept) $additionalDepartmentId = $addDept->id;
                }
                
                $classroomTypeName = trim($data['classroom_type'] ?? '');
                $classroomId = null;
                if ($classroomTypeName !== '') {
                    $classroom = \App\Models\Classroom::where('institution_id', $this->institutionId)
                        ->where('name', $classroomTypeName)
                        ->first();
                    if ($classroom) $classroomId = $classroom->id;
                }

                $updateData = [
                    'name' => $name,
                    'semester' => trim($data['semester'] ?? '') ?: null,
                    'type' => trim($data['type'] ?? '') ?: null,
                    'credits' => trim($data['credits'] ?? '') ?: null,
                    'weekly_lectures' => trim($data['weekly_lectures'] ?? '') ?: null,
                    'max_lectures_per_day' => trim($data['max_lectures_per_day'] ?? '') ?: null,
                    'min_lectures_per_day' => trim($data['min_lectures_per_day'] ?? '') ?: null,
                    'continuous_lectures' => trim($data['continuous_lectures'] ?? '') ?: null,
                    'is_elective' => trim($data['is_elective'] ?? '') !== '' ? trim($data['is_elective']) : null,
                    'syllabus_url' => trim($data['syllabus_url'] ?? '') ?: null,
                    'passing_marks' => trim($data['passing_marks'] ?? '') ?: null,
                    'total_marks' => trim($data['total_marks'] ?? '') ?: null,
                ];

                if ($departmentId) $updateData['department_id'] = $departmentId;
                if ($courseId) $updateData['course_id'] = $courseId;
                if ($additionalDepartmentId) $updateData['additional_department_id'] = $additionalDepartmentId;
                if ($classroomId) $updateData['classroom_type'] = $classroomId;

                if ($subject) {
                    $subject->update(array_filter($updateData, function($value) { return $value !== null; }));
                    $updated++;
                } else {
                    $createData = array_merge([
                        'institution_id' => $this->institutionId,
                        'code'           => $code,
                        'status'         => 1,
                    ], array_filter($updateData, function($value) { return $value !== null; }));
                    
                    Subject::create($createData);
                    $inserted++;
                }
            }

            $log = ImportLog::create([
                'institution_id' => $this->institutionId,
                'type'           => 'subjects',
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
                'title'          => 'Subject Import Completed',
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
            'title'          => 'Subject Import Failed',
            'message'        => 'Subject import failed.',
            'data'           => [
                'error' => $exception->getMessage(),
            ],
        ]);
    }
}
