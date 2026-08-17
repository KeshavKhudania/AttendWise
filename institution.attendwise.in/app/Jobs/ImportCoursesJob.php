<?php

namespace App\Jobs;

use App\Models\Course;
use App\Models\ImportLog;
use App\Models\InstitutionNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Throwable;

class ImportCoursesJob implements ShouldQueue
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
            if (!file_exists($fullPath)) {
                throw new \Exception('File does not exist.');
            }
            
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
                if ($name === '') {
                    $skipped++;
                    $errors[] = ['row' => $data, 'reason' => 'Missing course name'];
                    continue;
                }
                
                $course = Course::where('institution_id', $this->institutionId)
                    ->where('name', $name)
                    ->first();

                $departmentName = trim($data['department'] ?? '');
                $departmentId = null;
                if ($departmentName !== '') {
                    $dept = \App\Models\Department::where('institution_id', $this->institutionId)
                        ->where('name', $departmentName)
                        ->first();
                    if ($dept) $departmentId = $dept->id;
                }

                $updateData = [
                    'code' => trim($data['code'] ?? '') ?: null,
                    'level' => trim($data['level'] ?? '') ?: null,
                    'batch' => trim($data['batch'] ?? '') ?: null,
                    'duration_years' => trim($data['duration_years'] ?? '') ?: null,
                    'description' => trim($data['description'] ?? '') ?: null,
                    'course_type' => trim($data['course_type'] ?? '') ?: null,
                    'total_credits' => trim($data['total_credits'] ?? '') ?: null,
                ];

                if ($departmentId) {
                    $updateData['department_id'] = $departmentId;
                }

                if ($course) {
                    $course->update(array_filter($updateData, function($value) { return $value !== null; }));
                    $updated++;
                } else {
                    $createData = array_merge([
                        'institution_id' => $this->institutionId,
                        'name'           => $name,
                        'status'         => 1,
                    ], array_filter($updateData, function($value) { return $value !== null; }));
                    
                    Course::create($createData);
                    $inserted++;
                }
            }

            $log = ImportLog::create([
                'institution_id' => $this->institutionId,
                'type'           => 'courses',
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
                'title'          => 'Course Import Completed',
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
            'title'          => 'Course Import Failed',
            'message'        => 'Course import failed.',
            'data'           => [
                'error' => $exception->getMessage(),
            ],
        ]);
    }
}
