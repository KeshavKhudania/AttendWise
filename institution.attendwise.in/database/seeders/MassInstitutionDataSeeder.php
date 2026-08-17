<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use App\Models\Department;
use App\Models\Course;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Faculty;
use App\Models\Student;
use App\Models\SemesterSubject;
use App\Models\InstitutionAcademicSetting;

class MassInstitutionDataSeeder extends Seeder
{
    public function run()
    {
        $institutionId = 1;

        $academicYear = '2026-2027';

        DB::transaction(function () use ($institutionId, $academicYear) {
            $faker = \Faker\Factory::create();

            // 1. Departments
            $departmentsData = [
                'CSE' => ['name' => 'Computer Science & Engineering'],
                'IT' => ['name' => 'Information Technology'],
                'ECE' => ['name' => 'Electronics & Communication'],
                'ME' => ['name' => 'Mechanical Engineering'],
                'CE' => ['name' => 'Civil Engineering'],
                'BBA' => ['name' => 'Business Administration'],
                'BCA' => ['name' => 'Computer Applications'],
            ];

            $departments = [];
            foreach ($departmentsData as $code => $d) {
                $deptModel = Department::firstOrCreate(
                    ['institution_id' => $institutionId, 'name' => $d['name']],
                    ['is_additional' => 0, 'status' => 1]
                );
                // Dynamically attach our temporary 'code' to the model object for easier reference later
                $deptModel->code = $code;
                $departments[] = $deptModel;
            }

            // 2. Courses
            $coursesData = [
                ['name' => 'B.Tech in Computer Science', 'code' => 'BTECH-CSE', 'total_semesters' => 8, 'dept' => 'CSE'],
                ['name' => 'B.Tech in Information Technology', 'code' => 'BTECH-IT', 'total_semesters' => 8, 'dept' => 'IT'],
                ['name' => 'B.Tech in Electronics', 'code' => 'BTECH-ECE', 'total_semesters' => 8, 'dept' => 'ECE'],
                ['name' => 'B.Tech in Mechanical', 'code' => 'BTECH-ME', 'total_semesters' => 8, 'dept' => 'ME'],
                ['name' => 'Master of Computer Applications', 'code' => 'MCA', 'total_semesters' => 4, 'dept' => 'BCA'],
                ['name' => 'Master of Business Administration', 'code' => 'MBA', 'total_semesters' => 4, 'dept' => 'BBA'],
            ];

            $courses = [];
            foreach ($coursesData as $c) {
                $deptId = collect($departments)->firstWhere('code', $c['dept'])->id;
                $courses[] = Course::firstOrCreate(
                    ['institution_id' => $institutionId, 'code' => $c['code']],
                    [
                        'name' => $c['name'],
                        'department_id' => $deptId,
                        'total_semesters' => $c['total_semesters'],
                        'duration_years' => $c['total_semesters'] / 2,
                        'status' => 1,
                    ]
                );
            }

            // 3. Faculties (Create 150 faculties)
            $faculties = [];
            $designations = ['Professor', 'Assistant Professor', 'Associate Professor', 'Lecturer'];
            echo "Seeding Faculties...\n";
            $cachedPassword = bcrypt('password'); // Precompute for performance

            for ($i = 0; $i < 150; $i++) {
                $dept = $departments[array_rand($departments)];
                $email = "faculty{$i}@" . strtolower($dept->code) . ".edu.in";
                $mobile = $faker->numerify('9#########');

                $faculty = Faculty::create([
                    'institution_id' => $institutionId,
                    'department_id' => $dept->id,
                    'name' => $faker->name,
                    'email' => $email, // Cast handles encryption
                    'mobile' => $mobile, // Cast handles encryption
                    'password' => $cachedPassword,
                    'designation' => $designations[array_rand($designations)],
                    'status' => 1,
                    'working_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                ]);
                $faculties[] = $faculty;
            }

            // 4. Subjects and Semester Mapping
            $subjectsList = [
                'CSE' => ['Data Structures', 'Algorithms', 'Operating Systems', 'Database Systems', 'Computer Networks', 'Software Engineering', 'Artificial Intelligence', 'Machine Learning', 'Compiler Design', 'Web Technologies'],
                'IT' => ['Information Security', 'Cloud Computing', 'Data Mining', 'Big Data Analytics', 'IoT', 'Mobile Computing', 'E-Commerce', 'Human Computer Interaction', 'Software Testing', 'Cyber Security'],
                'ECE' => ['Digital Logic', 'Signals & Systems', 'Microprocessors', 'VLSI Design', 'Control Systems', 'Communication Systems', 'Antenna Theory', 'Embedded Systems', 'Digital Signal Processing', 'Wireless Comm'],
                'ME' => ['Thermodynamics', 'Fluid Mechanics', 'Heat Transfer', 'Manufacturing Processes', 'Machine Design', 'Kinematics', 'Dynamics of Machinery', 'Automobile Engineering', 'Robotics', 'CAD/CAM'],
                'CE' => ['Structural Analysis', 'Geotechnical Engineering', 'Transportation Engineering', 'Environmental Engineering', 'Surveying', 'Fluid Mechanics II', 'Concrete Technology', 'Steel Structures', 'Construction Management', 'Hydraulics'],
                'BCA' => ['Programming in C', 'Object Oriented Programming', 'Data Structures', 'Web Development', 'DBMS', 'Software Engineering', 'Java Programming', 'Computer Architecture', 'Operating Systems', 'Python'],
                'BBA' => ['Principles of Management', 'Financial Accounting', 'Business Economics', 'Marketing Management', 'Human Resource Management', 'Business Law', 'Organizational Behavior', 'Financial Management', 'Business Statistics', 'Strategic Management'],
            ];

            $allSubjects = [];
            echo "Seeding Subjects...\n";
            foreach ($courses as $course) {
                $deptCode = collect($departments)->firstWhere('id', $course->department_id)->code;
                $pool = $subjectsList[$deptCode] ?? $subjectsList['CSE'];

                for ($sem = 1; $sem <= $course->total_semesters; $sem++) {
                    $semSubjects = [];
                    // Assign 5 subjects per semester
                    for ($s = 0; $s < 5; $s++) {
                        $subjName = $pool[array_rand($pool)] . " - Sem " . $sem . " (V" . $s . ")";
                        $subj = Subject::create([
                            'institution_id' => $institutionId,
                            'department_id' => $course->department_id,
                            'course_id' => $course->id,
                            'name' => $subjName,
                            'code' => strtoupper(substr(str_replace(' ', '', $subjName), 0, 6)) . $sem . $s . '_' . $course->id,
                            'type' => $s == 4 ? 'Lab' : 'Theory',
                            'weekly_lectures' => $s == 4 ? 2 : 3,
                            'max_lectures_per_day' => $s == 4 ? 2 : 1,
                            'min_lectures_per_day' => 1,
                            'continuous_lectures' => $s == 4 ? true : false,
                            'status' => 1,
                        ]);
                        $semSubjects[] = $subj->id;
                        $allSubjects[] = $subj;

                        // Assign 1-2 random faculty to this subject
                        $deptFaculties = collect($faculties)->where('department_id', $course->department_id)->random(min(2, collect($faculties)->where('department_id', $course->department_id)->count()));
                        foreach ($deptFaculties as $f) {
                            DB::table('institution_faculty_subject')->insert([
                                'institution_id' => $institutionId,
                                'faculty_id' => $f->id,
                                'subject_id' => $subj->id,
                                'status' => 1,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }

                    SemesterSubject::updateOrCreate(
                        [
                            'institution_id' => $institutionId,
                            'course_id' => $course->id,
                            'semester' => $sem,
                        ],
                        [
                            'department_id' => $course->department_id,
                            'subjects' => $semSubjects,
                        ]
                    );
                }
            }

            // 5. Sections
            echo "Seeding Sections...\n";
            $sections = [];
            foreach ($courses as $course) {
                for ($sem = 1; $sem <= $course->total_semesters; $sem++) {
                    // 3 sections per semester
                    foreach (['A', 'B', 'C'] as $secName) {
                        $sections[] = Section::create([
                            'institution_id' => $institutionId,
                            'department_id' => $course->department_id,
                            'course_id' => $course->id,
                            'semester' => $sem,
                            'name' => "Sec {$secName} - Sem {$sem}",
                            'capacity' => 60,
                            'academic_year' => $academicYear,
                            'status' => 1,
                            'working_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                        ]);
                    }
                }
            }

            // 6. Students (6000 students)
            echo "Seeding 6000 Students (Chunked)...\n";
            $studentsToInsert = [];
            $batchSize = 1000;
            $count = 0;

            $now = now()->toDateTimeString();

            for ($i = 1; $i <= 6000; $i++) {
                $section = $sections[array_rand($sections)];
                $gender = $faker->randomElement(['Male', 'Female']);

                $email = "student{$i}@attendwise.edu.in";
                $mobile = $faker->numerify('9#########');

                $studentsToInsert[] = [
                    'institution_id' => $institutionId,
                    'section_id' => $section->id,
                    'course_id' => $section->course_id,
                    'first_name' => $faker->firstName($gender),
                    'last_name' => $faker->lastName,
                    'name' => $faker->name($gender), // some logic might use full name
                    'email' => Crypt::encryptString($email),
                    'email_hash' => hash('sha256', $email),
                    'mobile' => Crypt::encryptString($mobile),
                    'mobile_hash' => hash('sha256', $mobile),
                    'enrollment_number' => 'ENR' . str_pad($i, 6, '0', STR_PAD_LEFT),
                    'roll_number' => 'R' . str_pad($i, 6, '0', STR_PAD_LEFT),
                    'password' => $cachedPassword,
                    'gender' => $gender,
                    'academic_year' => $academicYear,
                    'status' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                $count++;

                if ($count % $batchSize == 0) {
                    DB::table('institution_students')->insert($studentsToInsert);
                    $studentsToInsert = [];
                    echo "Inserted {$count} students...\n";
                }
            }

            if (!empty($studentsToInsert)) {
                DB::table('institution_students')->insert($studentsToInsert);
                echo "Inserted {$count} students...\n";
            }

            echo "Database Seeding Completed Successfully!\n";
        });
    }
}
