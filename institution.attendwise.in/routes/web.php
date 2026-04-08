<?php

use App\Http\Controllers\AnesthesiaTypeController;
use App\Http\Controllers\VenueController;
use App\Http\Middleware\CheckLogin;
use App\Http\Middleware\CheckRoute;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminGroupController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\InstitutionSettingController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AppErrorController;

Route::controller(LoginController::class)->group(function () {
    Route::get("login", "index")->name("login_view");
    Route::post("login-check", "loginCheck")->name("login_check");
    Route::get("login/forgot-password", "forgotPasswordView")->name("forgotPasswordView");
    Route::post("login/forgot-password", "SendResetPasswordLink")->name("forgotPasswordSubmit");
});
Route::middleware(CheckLogin::class)->group(function () {
    Route::controller(HomeController::class)->group(function () {
            Route::get("/", "index")->name("dashboard_view");
            Route::get("dashboard", "index")->name("dashboard_view");
            Route::post("get-barcode", "generateBarCode")->name("generateBarCodeR");
            Route::get("profile", "profile_view")->name("profile_view");
            Route::get("add-perm/{perm_name}/{icon}/{sort_order}/{perm_parent}/{perm_type}", "addPerms")->name("addPermFn");
            Route::get("add-front-perm/{perm_name}/{icon}/{sort_order}/{perm_parent}/{perm_type}", "addFrontPerms")->name("addFrontPermFn");
            Route::post("app-errors/resolve/{id}", "resolveError")->name("institution.app-errors.resolve");
        }
        );
        Route::middleware(CheckRoute::class)->group(function () {
            Route::controller(AdminUserController::class)->group(function () {
                    Route::get("users", function () {
                            return redirect()->to("users/manage");
                        }
                        );
                        Route::get("users/manage", "index")->name("institution.admin.user.manage");
                        Route::get("users/add", "formView")->name("institution.admin.user.add.view");
                        Route::get("users/edit/{id}", "formView")->name("institution.admin.user.edit.view");
                        Route::post("users/create", "form")->name("institution.admin.user.create");
                        Route::post("users/update/{id}", "form")->name("institution.admin.user.update");
                        Route::post("users/delete/{id}", "delete")->name("institution.admin.user.delete");
                    }
                    );
                    Route::controller(HomeController::class)->group(function () {
                    // Route::get("hospital/setting", function(){
                    //     return redirect()->to("hospital/setting");
                    // });
                    Route::get("hospital/setting", "HospitalSetting")->name("institution.setting.manage");
                    Route::post("hospital/setting/save", "HospitalSetting")->name("institution.setting.manage");
                // Route::get("users/add", "formView")->name("institution.admin.add.view");
                // Route::get("users/edit/{id}", "formView")->name("institution.admin.edit.view");
                // Route::post("users/create", "form")->name("institution.admin.create");
                // Route::post("users/update/{id}", "form")->name("institution.admin.update");
                // Route::post("users/delete/{id}", "delete")->name("institution.admin.delete");
                }
                );
                Route::controller(BlockController::class)->group(function () {
                    Route::get("institution/block", function () {
                            // Redirect to the prefixed route
                            return redirect()->to("institution/block/manage");
                        }
                        );
                        Route::get("block/manage", "index")->name("institution.blocks.manage");
                        Route::get("block/add", "formView")->name("institution.blocks.add.view");
                        Route::get("block/edit/{id}", "formView")->name("institution.blocks.edit.view");
                        Route::post("block/create", "form")->name("institution.blocks.create");
                        Route::post("block/update/{id}", "form")->name("institution.blocks.update");
                        Route::post("block/delete/{id}", "delete")->name("institution.blocks.delete");
                    }
                    );
                    Route::controller(VenueController::class)->group(function () {
                    Route::get("venue/manage", "index")->name("institution.venues.manage");
                    Route::get("venue/add", "formView")->name("institution.venues.add.view");
                    Route::get("venue/edit/{id}", "formView")->name("institution.venues.edit.view");
                    Route::post("venue/create", "form")->name("institution.venues.create");
                    Route::post("venue/update/{id}", "form")->name("institution.venues.update");
                    Route::post("venue/delete/{id}", "delete")->name("institution.venues.delete");
                }
                );
                Route::controller(AdminGroupController::class)->group(function () {
                    Route::get("users/group", function () {
                            return redirect()->to("users/group/manage");
                        }
                        );
                        Route::get("users/group/manage", "index")->name("institution.admin.group.manage");
                        Route::get("users/group/add", "formView")->name("institution.admin.group.add.view");
                        Route::get("users/group/edit/{id}", "formView")->name("institution.admin.group.edit.view");
                        Route::post("users/group/create", "form")->name("institution.admin.group.create");
                        Route::post("users/group/update/{id}", "form")->name("institution.admin.group.update");
                        Route::post("users/group/delete/{id}", "delete")->name("institution.admin.group.delete");
                    }
                    );
                    Route::controller(InstitutionController::class)->group(function () {
                    Route::get("institution", function () {
                            return redirect()->to("institution/manage");
                        }
                        );
                        Route::get("institution/manage", "index")->name("institution.institution.manage");
                        Route::get("institution/add", "formView")->name("institution.institution.add.view");
                        Route::get("institution/edit/{id}", "formView")->name("institution.institution.edit.view");
                        Route::post("institution/create", "form")->name("institution.institution.create");
                        Route::post("institution/update/{id}", "form")->name("institution.institution.update");
                        Route::post("institution/delete/{id}", "delete")->name("institution.institution.delete");
                    }
                    );
                    Route::controller(DepartmentController::class)->group(function () {
                    Route::get("department", function () {
                            return redirect()->to("department/manage");
                        }
                        );
                        Route::get("department/manage", "index")->name("institution.department.manage");
                        Route::get("department/add", "formView")->name("institution.department.add.view");
                        Route::get("department/edit/{id}", "formView")->name("institution.department.edit.view");
                        Route::post("department/create", "form")->name("institution.department.create");
                        Route::post("department/update/{id}", "form")->name("institution.department.update");
                        Route::post("department/delete/{id}", "delete")->name("institution.department.delete");
                    }
                    );
                    Route::controller(ClassRoomController::class)->group(function () {
                    Route::get("classroom", function () {
                            return redirect()->to("classroom/manage");
                        }
                        );
                        Route::get("classroom/manage", "index")->name("institution.class.room.manage");
                        Route::get("classroom/add", "formView")->name("institution.class.room.add.view");
                        Route::get("classroom/edit/{id}", "formView")->name("institution.class.room.edit.view");
                        Route::post("classroom/create", "form")->name("institution.class.room.create");
                        Route::post("classroom/update/{id}", "form")->name("institution.class.room.update");
                        Route::post("classroom/delete/{id}", "delete")->name("institution.class.room.delete");
                    }
                    );
                    Route::controller(CourseController::class)->group(function () {
                    Route::get("courses", function () {
                            return redirect()->to("courses/manage");
                        }
                        );
                        Route::get("courses/manage", "index")->name("institution.courses.manage");
                        Route::get("courses/add", "formView")->name("institution.courses.add.view");
                        Route::get("courses/edit/{id}", "formView")->name("institution.courses.edit.view");
                        Route::post("courses/create", "form")->name("institution.courses.create");
                        Route::post("courses/update/{id}", "form")->name("institution.courses.update");
                        Route::post("courses/delete/{id}", "delete")->name("institution.courses.delete");
                    }
                    );
                    Route::controller(SubjectController::class)->group(function () {
                    Route::get("subject", function () {
                            return redirect()->to("subject/manage");
                        }
                        );
                        Route::get("subject/manage", "index")->name("institution.subject.manage");
                        Route::get("subject/add", "formView")->name("institution.subject.add.view");
                        Route::get("subject/edit/{id}", "formView")->name("institution.subject.edit.view");
                        Route::post("subject/create", "form")->name("institution.subject.create");
                        Route::post("subject/update/{id}", "form")->name("institution.subject.update");
                        Route::post("subject/delete/{id}", "delete")->name("institution.subject.delete");

                        Route::get("subject-mapping", "semester_subject_mapping")->name("institution.subject.manage.mapping.index");
                        Route::get("semester-subject-mapping/add", "subjectMappingformView")->name("institution.subject.manage.mapping.add.view");
                        Route::get("semester-subject-mapping/edit/{id}", "subjectMappingformView")->name("institution.subject.manage.mapping.edit.view");
                        Route::post("semester-subject-mapping/create", "assignSubjectsToSemester")->name("institution.subject.manage.mapping.create");
                        Route::post("semester-subject-mapping/update/{id}", "assignSubjectsToSemester")->name("institution.subject.manage.mapping.update");
                        Route::delete("semester-subject-mapping/delete/{id}", "DeleteSemesterSubject")->name("institution.subject.manage.mapping.delete");
                    }
                    );
                    Route::controller(FacultyController::class)->group(function () {
                    Route::get("faculty", function () {
                            return redirect()->to("faculty/manage");
                        }
                        );
                        Route::get("faculty/manage", "index")->name("institution.faculty.manage");
                        Route::get("faculty/add", "formView")->name("institution.faculty.add.view");
                        Route::get("faculty/edit/{id}", "formView")->name("institution.faculty.edit.view");
                        Route::post("faculty/create", "form")->name("institution.faculty.create");
                        Route::post("faculty/update/{id}", "form")->name("institution.faculty.update");
                        Route::post("faculty/delete/{id}", "delete")->name("institution.faculty.delete");
                    }
                    );
                    Route::controller(ScheduleController::class)->group(function () {
                    Route::get("schedule", function () {
                            return redirect()->to("schedule/manage");
                        }
                        );
                        Route::get("schedule/temporary", "temporaryIndex")->name("institution.time.table.manage.temporary.index");
                        Route::post("schedule/temporary", "generateTemporary")->name("institution.time.table.manage.temporary.generate");
                        Route::get("schedule/manage", "index")->name("institution.time.table.manage");
                        Route::get("schedule/add", "formView")->name("institution.time.table.add.view");
                        Route::get("schedule/edit/{id}", "formView")->name("institution.time.table.edit.view");
                        Route::post("schedule/create", "form")->name("institution.time.table.create");
                        Route::post("schedule/update/{id}", "form")->name("institution.time.table.update");
                        Route::post("schedule/delete/{id}", "delete")->name("institution.time.table.delete");
                        Route::post("schedule/auto-generate", "autoGenerate")->name("institution.time.table.auto_generate");
                        Route::get("schedule/download-sample", "downloadSample")->name("institution.time.table.download_sample");
                        Route::post("schedule/upload", "upload")->name("institution.time.table.upload");
                        Route::get("schedule/export/{id}", "exportExcel")->name("institution.time.table.manage.export");
                        Route::post("schedule/export-bulk", "exportExcelBulk")->name("institution.time.table.manage.export_bulk");
                    }
                    );
                    Route::controller(ClubController::class)->group(function () {
                    Route::get("club", function () {
                            return redirect()->to("club/manage");
                        }
                        );
                        Route::get("club/manage", "index")->name("institution.club.manage");
                        Route::get("club/add", "formView")->name("institution.club.manage.add.view");
                        Route::get("club/edit/{id}", "formView")->name("institution.club.manage.edit.view");
                        Route::post("club/create", "form")->name("institution.club.manage.create");
                        Route::post("club/update/{id}", "form")->name("institution.club.manage.update");
                        Route::post("club/delete/{id}", "delete")->name("institution.club.manage.delete");

                        Route::get("club/members/{id}", "members")->name("institution.club.manage.members");
                        Route::post("club/members/add/{id}", "addMember")->name("institution.club.manage.members.add");
                        Route::post("club/members/update/{club_id}/{member_id}", "updateMember")->name("institution.club.manage.members.update");
                        Route::post("club/members/remove/{club_id}/{member_id}", "removeMember")->name("institution.club.manage.members.remove");
                    }
                    );

                    Route::controller(EventController::class)->group(function () {
                    Route::get("event", function () {
                            return redirect()->to("event/manage");
                        }
                        );
                        Route::get("event/manage", "index")->name("institution.events.manage");
                        Route::get("event/add", "formView")->name("institution.events.manage.add.view");
                        Route::get("event/edit/{id}", "formView")->name("institution.events.manage.edit.view");
                        Route::post("event/create", "form")->name("institution.events.manage.create");
                        Route::post("event/update/{id}", "form")->name("institution.events.manage.update");
                        Route::post("event/delete/{id}", "delete")->name("institution.events.manage.delete");

                        Route::get("event/participants/{id}", "participants")->name("institution.events.manage.participants");
                        Route::post("event/participants/add/{id}", "addParticipants")->name("institution.events.manage.participants.add");
                        Route::post("event/participants/update/{participant_id}", "updateParticipant")->name("institution.events.manage.participants.update");
                        Route::post("event/participants/toggle-attendance/{participant_id}", "toggleAttendancePrivilege")->name("institution.events.manage.participants.toggle_attendance");
                        Route::post("event/participants/remove/{participant_id}", "removeParticipant")->name("institution.events.manage.participants.remove");
                        Route::get("event/attendance/{id}", "attendance")->name("institution.events.manage.attendance");
                        Route::post("event/attendance/mark/{id}", "markAttendance")->name("institution.events.manage.attendance.mark");
                    }
                    );

                    Route::controller(StudentController::class)->group(function () {
                    Route::get("student", function () {
                            return redirect()->to("student/manage");
                        }
                        );
                        Route::get("student/manage", "index")->name("institution.student.manage");
                        Route::get("student/add", "formView")->name("institution.student.add.view");
                        Route::get("student/edit/{id}", "formView")->name("institution.student.edit.view");
                        Route::post("student/create", "form")->name("institution.student.create");
                        Route::post("student/import", "import")->name("institution.student.add.view");
                        Route::post("student/update/{id}", "form")->name("institution.student.update");
                        Route::post("student/delete/{id}", "delete")->name("institution.student.delete");
                    }
                    );
                    Route::controller(SectionController::class)->group(function () {
                    Route::get("section", function () {
                            return redirect()->to("section/manage");
                        }
                        );
                        Route::post("fetch/sections-by-course", "fetchSectionByCourse")->name("institution.section.manage");
                        Route::get("section/manage", "index")->name("institution.section.manage");
                        Route::get("section/add", "formView")->name("institution.section.add.view");
                        Route::get("section/edit/{id}", "formView")->name("institution.section.edit.view");
                        Route::post("section/create", "form")->name("institution.section.create");
                        Route::post("section/update/{id}", "form")->name("institution.section.update");
                        Route::post("section/delete/{id}", "delete")->name("institution.section.delete");
                    }
                    );
                    Route::controller(AnesthesiaTypeController::class)->group(function () {
                    Route::get("anesthesia-types", function () {
                            return redirect()->to("anesthesia-types/manage");
                        }
                        );
                        Route::get("anesthesia-types/manage", "index")->name("institution.anesthesia.types.manage");
                        Route::get("anesthesia-types/add", "formView")->name("institution.anesthesia.types.add.view");
                        Route::get("anesthesia-types/edit/{id}", "formView")->name("institution.anesthesia.types.edit.view");
                        Route::post("anesthesia-types/create", "form")->name("institution.anesthesia.types.create");
                        Route::post("anesthesia-types/update/{id}", "form")->name("institution.anesthesia.types.update");
                        Route::post("anesthesia-types/delete/{id}", "delete")->name("institution.anesthesia.types.delete");
                    }
                    );

                    Route::controller(InstitutionSettingController::class)->group(function () {
                    Route::get("settings", "index")->name("institution.settings.manage");
                    Route::post("settings/update", "update")->name("institution.settings.update");
                }
                );

                // Staff Permissions
        
                Route::controller(LoginController::class)->group(function () {
                    Route::get("logout", "logout")->name("logout");
                }
                );
            }
            );
        });