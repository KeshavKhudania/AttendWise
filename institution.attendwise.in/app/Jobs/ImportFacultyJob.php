<?php

namespace App\Jobs;

use App\Models\Faculty;
use App\Models\Department;
use App\Models\ImportLog;
use App\Models\InstitutionNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Throwable;

class ImportFacultyJob implements ShouldQueue
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

                $email = trim($data['email'] ?? '');
                if ($email === '') {
                    $skipped++;
                    $errors[] = ['row' => $data, 'reason' => 'Missing email'];
                    continue;
                }

                $faculty = Faculty::where('institution_id', $this->institutionId)
                    ->where('email', $email)
                    ->first();

                $payload = [];

                foreach (['name', 'mobile', 'gender', 'designation', 'employee_code', 'date_of_birth', 'blood_group', 'nationality', 'national_id', 'pan_number', 'permanent_address', 'current_address', 'emergency_contact_name', 'emergency_contact_number', 'joining_date', 'leaving_date', 'employment_type', 'working_days', 'highest_qualification', 'years_of_experience', 'bank_account_no', 'bank_name', 'bank_ifsc', 'basic_salary'] as $field) {
                    if (isset($data[$field]) && trim($data[$field]) !== '') {
                        $payload[$field] = trim($data[$field]);
                    }
                }
                if (!empty($data['password'])) {
                    $payload['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
                }
                $payload['department_id'] = $department->id;

                if ($faculty) {
                    $faculty->update($payload);
                    $updated++;
                } else {
                    Faculty::create(array_merge([
                        'institution_id' => $this->institutionId,
                        'email'          => $email,
                        'status'         => 1,
                        'password'       => 'Qwerty@321',
                    ], $payload));
                    $inserted++;
                }
            }

            $log = ImportLog::create([
                'institution_id' => $this->institutionId,
                'type'           => 'faculty',
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
                'title'          => 'Faculty Import Completed',
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
            'title'          => 'Faculty Import Failed',
            'message'        => 'Faculty import failed.',
            'data'           => [
                'error' => $exception->getMessage()
            ],
        ]);
    }
}
