<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AnesthesiaController;
use App\Http\Controllers\AnesthesiaTypeController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\RateList;
use App\Http\Middleware\CheckLogin;
use App\Http\Middleware\CheckRoute;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BedsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RoomsController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PythonController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\AdminGroupController;
use App\Http\Controllers\BedsCategoryController;
use App\Http\Controllers\RoomCategoryController;
use App\Http\Controllers\StaffCategoryController;
use App\Http\Controllers\DoctorScheduleController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InstitutionAdminController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\InstitutionGroupController;
use App\Http\Controllers\RoomAssignmentController;
use App\Http\Controllers\InsuranceProviderController;
use App\Http\Controllers\LabRoomController;
use App\Http\Controllers\OperationCategoryController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\StaffPortalApiController;
use App\Http\Controllers\StaffPortalHomeController;
use App\Http\Controllers\StaffPortalIpdController;
use App\Http\Controllers\StaffPortalLoginController;
use App\Http\Controllers\StaffPortalOpdController;
use App\Http\Controllers\StaffPortalRoomController;
use App\Http\Controllers\StaffPortalLabOperatorController;
use App\Http\Controllers\StaffPortalTestController;

Route::controller(LoginController::class)->group(function(){
    Route::get("login", "index")->name("login_view");
    Route::post("login-check", "loginCheck")->name("login_check");
    Route::get("login/forgot-password", "forgotPasswordView")->name("forgotPasswordView");
    Route::post("login/forgot-password", "SendResetPasswordLink")->name("forgotPasswordSubmit");
});
Route::middleware(CheckLogin::class)->group(function(){
    Route::controller(HomeController::class)->group(function(){
        Route::get("/", "index")->name("dashboard_view");
        Route::get("dashboard", "index")->name("dashboard_view");
        Route::post("get-barcode", "generateBarCode")->name("generateBarCodeR");
        Route::get("profile", "profile_view")->name("profile_view");
        Route::get("add-perm/{perm_name}/{icon}/{sort_order}/{perm_parent}/{perm_type}", "addPerms")->name("addPermFn");
        Route::get("add-front-perm/{perm_name}/{icon}/{sort_order}/{perm_parent}/{perm_type}", "addFrontPerms")->name("addFrontPermFn");
    });
    Route::controller(InstitutionGroupController::class)->group(function(){
        Route::post("institution-users/group/list", "GroupList")->name("api.fetch.institution.groups");
    });
    Route::middleware(CheckRoute::class)->group(function(){
        Route::controller(InstitutionAdminController::class)->group(function(){
            Route::get("institution-users", function(){
                return redirect()->to("institution-users/manage");
            });
            Route::get("institution-users/manage", "index")->name("admin.institution.users.manage");
            Route::get("institution-users/add", "formView")->name("admin.institution.users.add.view");
            Route::get("institution-users/edit/{id}", "formView")->name("admin.institution.users.edit.view");
            Route::post("institution-users/create", "form")->name("admin.institution.users.create");
            Route::post("institution-users/update/{id}", "form")->name("admin.institution.users.update");
            Route::post("institution-users/delete/{id}", "delete")->name("admin.institution.users.delete");
        });
        Route::controller(AdminUserController::class)->group(function(){
            Route::get("users", function(){
                return redirect()->to("users/manage");
            });
            Route::get("users/manage", "index")->name("admin.users.manage");
            Route::get("users/add", "formView")->name("admin.users.add.view");
            Route::get("users/edit/{id}", "formView")->name("admin.users.edit.view");
            Route::post("users/create", "form")->name("admin.users.create");
            Route::post("users/update/{id}", "form")->name("admin.users.update");
            Route::post("users/delete/{id}", "delete")->name("admin.users.delete");
        });
        Route::controller(HomeController::class)->group(function(){
            // Route::get("hospital/setting", function(){
            //     return redirect()->to("hospital/setting");
            // });
            Route::get("hospital/setting", "HospitalSetting")->name("admin.setting.manage");
            Route::post("hospital/setting/save", "HospitalSetting")->name("admin.setting.manage");
            // Route::get("users/add", "formView")->name("admin.users.add.view");
            // Route::get("users/edit/{id}", "formView")->name("admin.users.edit.view");
            // Route::post("users/create", "form")->name("admin.users.create");
            // Route::post("users/update/{id}", "form")->name("admin.users.update");
            // Route::post("users/delete/{id}", "delete")->name("admin.users.delete");
        });
        Route::controller(StaffPortalIpdController::class)->group(function(){
            Route::get("patient/ipd", function(){
                // Redirect to the prefixed route
                return redirect()->to("patient/ipd/manage");
            });
            Route::get("patient/ipd/view/{id}", "singleView")->name("admin.ipd.patients.manage");
            Route::get("patient/ipd/manage", "ipd_view")->name("admin.ipd.patients.manage");
            Route::get("patient/ipd/add", "formView")->name("admin.ipd.patients.add.view");
            Route::get("patient/ipd/edit/{id}", "formView")->name("admin.ipd.patients.edit.view");
            Route::post("patient/ipd/create", "form")->name("admin.ipd.patients.create");
            Route::post("patient/ipd/update/{id}", "form")->name("admin.ipd.patients.update");
            Route::post("patient/ipd/delete/{id}", "delete")->name("admin.ipd.patients.delete");
        });
        Route::controller(StaffPortalOpdController::class)->group(function(){
            Route::get("patient/opd", function(){
                // Redirect to the prefixed route
                return redirect()->to("staff-portal/opd/manage");
            });
            Route::get("patient/opd/view/{id}", "OpdView")->name("admin.opd.patients.manage");
            Route::get("patient/opd/manage", "index")->name("admin.opd.patients.manage");
            Route::get("patient/opd/add", "formView")->name("admin.opd.patients.add.view");
            Route::get("patient/opd/edit/{id}", "formView")->name("admin.opd.patients.edit.view");
            Route::post("patient/opd/regenrate-queue-number/{id}", "RegenerateQueueNumber")->name("admin.opd.patients.update");
            Route::post("patient/opd/refund/{id}", "Refund")->name("admin.opd.patients.update");
            Route::post("patient/opd/create", "form")->name("admin.opd.patients.create");
            Route::post("patient/opd/update/{id}", "form")->name("admin.opd.patients.update");
            Route::post("patient/opd/delete/{id}", "delete")->name("admin.opd.patients.delete");
        });
        Route::controller(AdminGroupController::class)->group(function(){
            Route::get("users/group", function(){
                return redirect()->to("users/group/manage");
            });
            Route::get("users/group/manage", "index")->name("admin.users.group.manage");
            Route::get("users/group/add", "formView")->name("admin.users.group.add.view");
            Route::get("users/group/edit/{id}", "formView")->name("admin.users.group.edit.view");
            Route::post("users/group/create", "form")->name("admin.users.group.create");
            Route::post("users/group/update/{id}", "form")->name("admin.users.group.update");
            Route::post("users/group/delete/{id}", "delete")->name("admin.users.group.delete");
        });
        Route::controller(InstitutionGroupController::class)->group(function(){
            Route::get("institution-users/group", function(){
                return redirect()->to("institution-users/group/manage");
            });
            Route::get("institution-users/group/manage", "index")->name("admin.institution.admin.group.manage");
            Route::get("institution-users/group/add", "formView")->name("admin.institution.admin.group.add.view");
            Route::get("institution-users/group/edit/{id}", "formView")->name("admin.institution.admin.group.edit.view");
            Route::post("institution-users/group/create", "form")->name("admin.institution.admin.group.create");
            Route::post("institution-users/group/update/{id}", "form")->name("admin.institution.admin.group.update");
            Route::post("institution-users/group/delete/{id}", "delete")->name("admin.institution.admin.group.delete");
        });
        Route::controller(InstitutionController::class)->group(function(){
            Route::get("institution", function(){
                return redirect()->to("institution/manage");
            });
            Route::get("institution/manage", "index")->name("admin.institution.manage");
            Route::get("institution/add", "formView")->name("admin.institution.add.view");
            Route::get("institution/edit/{id}", "formView")->name("admin.institution.edit.view");
            Route::post("institution/create", "form")->name("admin.institution.create");
            Route::post("institution/update/{id}", "form")->name("admin.institution.update");
            Route::post("institution/delete/{id}", "delete")->name("admin.institution.delete");
        });
        Route::controller(DoctorController::class)->group(function(){
            Route::get("doctor", function(){
                return redirect()->to("doctor/manage");
            });
            Route::get("doctor/manage", "index")->name("admin.doctor.manage");
            Route::get("doctor/add", "formView")->name("admin.doctor.add.view");
            Route::get("doctor/edit/{id}", "formView")->name("admin.doctor.edit.view");
            Route::post("doctor/create", "form")->name("admin.doctor.create");
            Route::post("doctor/update/{id}", "form")->name("admin.doctor.update");
            Route::post("doctor/delete/{id}", "delete")->name("admin.doctor.delete");
        });
        Route::controller(DepartmentController::class)->group(function(){
            Route::get("department", function(){
                return redirect()->to("department/manage");
            });
            Route::get("department/manage", "index")->name("admin.department.manage");
            Route::get("department/add", "formView")->name("admin.department.add.view");
            Route::get("department/edit/{id}", "formView")->name("admin.department.edit.view");
            Route::post("department/create", "form")->name("admin.department.create");
            Route::post("department/update/{id}", "form")->name("admin.department.update");
            Route::post("department/delete/{id}", "delete")->name("admin.department.delete");
        });
        Route::controller(InsuranceProviderController::class)->group(function(){
            Route::get("insurance-provider", function(){
                return redirect()->to("insurance-provider/manage");
            });
            Route::get("insurance-provider/manage", "index")->name("admin.insurance.provider.manage");
            Route::get("insurance-provider/add", "formView")->name("admin.insurance.provider.add.view");
            Route::get("insurance-provider/edit/{id}", "formView")->name("admin.insurance.provider.edit.view");
            Route::post("insurance-provider/create", "form")->name("admin.insurance.provider.create");
            Route::post("insurance-provider/update/{id}", "form")->name("admin.insurance.provider.update");
            Route::post("insurance-provider/delete/{id}", "delete")->name("admin.insurance.provider.delete");
        });
        Route::controller(TestController::class)->group(function(){
            Route::get("test", function(){
                return redirect()->to("test/manage");
            });
            Route::get("test/manage", "index")->name("admin.test.manage");
            Route::get("test/add", "formView")->name("admin.test.add.view");
            Route::get("test/edit/{id}", "formView")->name("admin.test.edit.view");
            Route::post("test/create", "form")->name("admin.test.create");
            Route::post("test/update/{id}", "form")->name("admin.test.update");
            Route::post("test/delete/{id}", "delete")->name("admin.test.delete");
        });
                Route::controller(LabController::class)->group(function(){
            Route::get("labs", function(){
                return redirect()->to("labs/manage");
            });
            Route::get("labs/manage", "index")->name("admin.labs.manage");
            Route::get("labs/add", "formView")->name("admin.labs.add.view");
            Route::get("labs/edit/{id}", "formView")->name("admin.labs.edit.view");
            Route::post("labs/create", "form")->name("admin.labs.create");
            Route::post("labs/update/{id}", "form")->name("admin.labs.update");
            Route::post("labs/delete/{id}", "delete")->name("admin.labs.delete");
        });
        Route::controller(StaffController::class)->group(function(){
            Route::get("staff", function(){
                return redirect()->to("staff/manage");
            });
            Route::get("staff/manage", "index")->name("admin.staff.manage");
            Route::get("staff/add", "formView")->name("admin.staff.add.view");
            Route::get("staff/edit/{id}", "formView")->name("admin.staff.edit.view");
            Route::post("staff/create", "form")->name("admin.staff.create");
            Route::post("staff/update/{id}", "form")->name("admin.staff.update");
            Route::post("staff/delete/{id}", "delete")->name("admin.staff.delete");
        });
        Route::controller(DoctorScheduleController::class)->group(function(){
            Route::get("doctor-schedule", function(){
                return redirect()->to("doctor-schedule/manage");
            });
            Route::get("doctor-schedule/manage", "index")->name("admin.doctor.schedule.manage");
            Route::get("doctor-schedule/add", "formView")->name("admin.doctor.schedule.add.view");
            Route::get("doctor-schedule/edit/{id}", "formView")->name("admin.doctor.schedule.edit.view");
            Route::post("doctor-schedule/create", "form")->name("admin.doctor.schedule.create");
            Route::post("doctor-schedule/update/{id}", "form")->name("admin.doctor.schedule.update");
            Route::post("doctor-schedule/delete/{id}", "delete")->name("admin.doctor.schedule.delete");
        });
        Route::controller(RateList::class)->group(function(){
            Route::get("setting/rate-list", function(){
                return redirect()->to("setting/rate-list/manage");
            });
            Route::get("setting/rate-list/manage", "index")->name("admin.rate.list.manage");
            Route::get("setting/rate-list/add", "formView")->name("admin.rate.list.add.view");
            Route::get("setting/rate-list/edit/{id}", "formView")->name("admin.rate.list.edit.view");
            Route::post("setting/rate-list/create", "form")->name("admin.rate.list.create");
            Route::post("setting/rate-list/update/{id}", "form")->name("admin.rate.list.update");
            Route::post("setting/rate-list/delete/{id}", "delete")->name("admin.rate.list.delete");
        });
        Route::controller(ExpenseController::class)->group(function(){
            Route::get("account/expense", function(){
                return redirect()->to("account/expense/manage");
            });
            Route::post("account/expense/records/fetch", "fetchExpenseRows")->name("admin.expense.manage");
            Route::get("account/expense/manage", "index")->name("admin.expense.manage");
            Route::get("account/expense/add", "formView")->name("admin.expense.add.view");
            Route::get("account/expense/edit/{id}", "formView")->name("admin.expense.edit.view");
            Route::post("account/expense/create", "form")->name("admin.expense.create");
            Route::post("account/expense/update/{id}", "form")->name("admin.expense.update");
            Route::post("account/expense/delete/{id}", "delete")->name("admin.expense.delete");
        });
        Route::controller(RevenueController::class)->group(function(){
            Route::get("account/revenue", function(){
                return redirect()->to("account/revenue/manage");
            });
            Route::post("account/revenue/records/fetch", "fetchRevenueRows")->name("admin.revenue.manage");
            Route::get("account/revenue/manage", "index")->name("admin.revenue.manage");
            Route::get("account/revenue/add", "formView")->name("admin.revenue.add.view");
            Route::get("account/revenue/edit/{id}", "formView")->name("admin.revenue.edit.view");
            Route::post("account/revenue/create", "form")->name("admin.revenue.create");
            Route::post("account/revenue/update/{id}", "form")->name("admin.revenue.update");
            Route::post("account/revenue/delete/{id}", "delete")->name("admin.revenue.delete");
        });
        Route::controller(LabRoomController::class)->group(function(){
            Route::get("laboratories/room", function(){
                return redirect()->to("laboratories/room/manage");
            });
            Route::get("laboratories/room/manage", "index")->name("admin.laboratories.manage");
            Route::get("laboratories/room/add", "formView")->name("admin.laboratories.add.view");
            Route::get("laboratories/room/edit/{id}", "formView")->name("admin.laboratories.edit.view");
            Route::post("laboratories/room/create", "form")->name("admin.laboratories.create");
            Route::post("laboratories/room/update/{id}", "form")->name("admin.laboratories.update");
            Route::post("laboratories/room/delete/{id}", "delete")->name("admin.laboratories.delete");
        });
        Route::controller(OperationController::class)->group(function(){
            Route::get("operation", function(){
                return redirect()->to("operation/manage");
            });
            Route::get("operation/manage", "index")->name("admin.operation.manage");
            Route::get("operation/add", "formView")->name("admin.operation.add.view");
            Route::get("operation/edit/{id}", "formView")->name("admin.operation.edit.view");
            Route::post("operation/create", "form")->name("admin.operation.create");
            Route::post("operation/update/{id}", "form")->name("admin.operation.update");
            Route::post("operation/delete/{id}", "delete")->name("admin.operation.delete");
        });
        Route::controller(OperationCategoryController::class)->group(function(){
            Route::get("operation-category", function(){
                return redirect()->to("operation-category/manage");
            });
            Route::get("operation-category/manage", "index")->name("admin.operation.category.manage");
            Route::get("operation-category/add", "formView")->name("admin.operation.category.add.view");
            Route::get("operation-category/edit/{id}", "formView")->name("admin.operation.category.edit.view");
            Route::post("operation-category/create", "form")->name("admin.operation.category.create");
            Route::post("operation-category/update/{id}", "form")->name("admin.operation.category.update");
            Route::post("operation-category/delete/{id}", "delete")->name("admin.operation.category.delete");
        });
        Route::controller(AnesthesiaTypeController::class)->group(function(){
            Route::get("anesthesia-types", function(){
                return redirect()->to("anesthesia-types/manage");
            });
            Route::get("anesthesia-types/manage", "index")->name("admin.anesthesia.types.manage");
            Route::get("anesthesia-types/add", "formView")->name("admin.anesthesia.types.add.view");
            Route::get("anesthesia-types/edit/{id}", "formView")->name("admin.anesthesia.types.edit.view");
            Route::post("anesthesia-types/create", "form")->name("admin.anesthesia.types.create");
            Route::post("anesthesia-types/update/{id}", "form")->name("admin.anesthesia.types.update");
            Route::post("anesthesia-types/delete/{id}", "delete")->name("admin.anesthesia.types.delete");
        });
        Route::controller(AnesthesiaController::class)->group(function(){
            Route::get("anesthesia", function(){
                return redirect()->to("anesthesia/manage");
            });
            Route::get("anesthesia/manage", "index")->name("admin.anesthesia.manage");
            Route::get("anesthesia/add", "formView")->name("admin.anesthesia.add.view");
            Route::get("anesthesia/edit/{id}", "formView")->name("admin.anesthesia.edit.view");
            Route::post("anesthesia/create", "form")->name("admin.anesthesia.create");
            Route::post("anesthesia/update/{id}", "form")->name("admin.anesthesia.update");
            Route::post("anesthesia/delete/{id}", "delete")->name("admin.anesthesia.delete");
        });
        Route::controller(CertificateController::class)->group(function(){
            Route::get("setting/certificate-template", function(){
                return redirect()->to("setting/certificate-template/manage");
            });
            Route::get("setting/certificate/manage", "index")->name("admin.certificates.manage");
            Route::get("setting/certificate/add", "formView")->name("admin.certificates.add.view");
            Route::get("setting/certificate/edit/{id}", "formView")->name("admin.certificates.edit.view");
            Route::post("setting/certificate/create", "form")->name("admin.certificates.create");
            Route::post("setting/certificate/update/{id}", "form")->name("admin.certificates.update");
            Route::post("setting/certificate/delete/{id}", "delete")->name("admin.certificates.delete");
        });
        Route::controller(RoomsController::class)->group(function(){
            Route::get("room/manage", "index")->name("admin.room.manage");
            Route::get("room/add", "formView")->name("admin.room.add.view");
            Route::get("room/edit/{id}", "formView")->name("admin.room.edit.view");
            Route::post("room/create", "form")->name("admin.room.create");
            Route::post("room/update/{id}", "form")->name("admin.room.update");
            Route::post("room/delete/{id}", "delete")->name("admin.room.delete");
        });
        Route::controller(RoomCategoryController::class)->group(function(){
            Route::get("room/category/manage", "index")->name("admin.room.category.manage");
            Route::get("room/category/add", "formView")->name("admin.room.category.add.view");
            Route::get("room/category/edit/{id}", "formView")->name("admin.room.category.edit.view");
            Route::post("room/category/create", "form")->name("admin.room.category.create");
            Route::post("room/category/update/{id}", "form")->name("admin.room.category.update");
            Route::post("room/category/delete/{id}", "delete")->name("admin.room.category.delete");
        });

        Route::controller(RoomAssignmentController::class)->group(function(){
            Route::get("room/assignment/manage", "index")->name("admin.room.assignment.manage");
            Route::get("room/assignment/add", "formView")->name("admin.room.assignment.add.view");
            Route::get("room/assignment/edit/{id}", "formView")->name("admin.room.assignment.edit.view");
            Route::post("room/assignment/create", "form")->name("admin.room.assignment.create");
            Route::post("room/assignment/update/{id}", "form")->name("admin.room.assignment.update");
            Route::post("room/assignment/delete/{id}", "delete")->name("admin.room.assignment.delete");
        });
        Route::controller(BedsCategoryController::class)->group(function(){
            Route::get("beds/category/manage", "index")->name("admin.beds.category.manage");
            Route::get("beds/category/add", "formView")->name("admin.beds.category.add.view");
            Route::get("beds/category/edit/{id}", "formView")->name("admin.beds.category.edit.view");
            Route::post("beds/category/create", "form")->name("admin.beds.category.create");
            Route::post("beds/category/update/{id}", "form")->name("admin.beds.category.update");
            Route::post("beds/category/delete/{id}", "delete")->name("admin.beds.category.delete");
        });
        Route::controller(BedsController::class)->group(function(){
            Route::get("beds/manage", "index")->name("admin.beds.manage");
            Route::get("beds/add", "formView")->name("admin.beds.add.view");
            Route::get("beds/edit/{id}", "formView")->name("admin.beds.edit.view");
            Route::post("beds/create", "form")->name("admin.beds.create");
            Route::post("beds/update/{id}", "form")->name("admin.beds.update");
            Route::post("beds/delete/{id}", "delete")->name("admin.beds.delete");
        });
        Route::controller(StaffController::class)->group(function(){
            Route::get("staffs/manage", "index")->name("admin.staffs.manage");
            Route::get("staffs/add", "formView")->name("admin.staffs.add.view");
            Route::get("staffs/edit/{id}", "formView")->name("admin.staffs.edit.view");
            Route::post("staffs/create", "form")->name("admin.staffs.create");
            Route::post("staffs/update/{id}", "form")->name("admin.staffs.update");
            Route::post("staffs/delete/{id}", "delete")->name("admin.staffs.delete");
        });
        Route::controller(StaffCategoryController::class)->group(function(){
            Route::get("staffs/category/manage", "index")->name("admin.staffs.category.manage");
            Route::get("staffs/category/add", "formView")->name("admin.staffs.category.add.view");
            Route::get("staffs/category/edit/{id}", "formView")->name("admin.staffs.category.edit.view");
            Route::post("staffs/category/create", "form")->name("admin.staffs.category.create");
            Route::post("staffs/category/update/{id}", "form")->name("admin.staffs.category.update");
            Route::post("staffs/category/delete/{id}", "delete")->name("admin.staffs.category.delete");
        });

        // Staff Permissions

        Route::prefix('staff-portal')->group(function () {

    Route::controller(StaffPortalHomeController::class)->group(function(){
        Route::get("/", "index");
        Route::get("dashboard", "index")->name("dashboard.view");
    });


    Route::controller(StaffPortalOpdController::class)->group(function(){
            Route::get("opd", function(){
                // Redirect to the prefixed route
                return redirect()->to("staff-portal/opd/manage");
            });
            Route::get("opd/show/{id}", "show")->name("admin.opd.manage");

            Route::get("opd/view/{id}", "OpdView")->name("admin.opd.manage");
            Route::get("opd/manage", "index")->name("admin.opd.manage");
            Route::get("opd/add", "formView")->name("admin.opd.add.view");
            Route::get("opd/edit/{id}", "formView")->name("admin.opd.edit.view");
            Route::post("opd/regenrate-queue-number/{id}", "RegenerateQueueNumber")->name("admin.opd.update");
            Route::post("opd/refund/{id}", "Refund")->name("admin.opd.update");
            Route::post("opd/create", "form")->name("admin.opd.create");
            Route::post("opd/update/{id}", "form")->name("admin.opd.update");
            Route::post("opd/delete/{id}", "delete")->name("admin.opd.delete");
        });

        Route::controller(StaffPortalIpdController::class)->group(function(){
            Route::get("ipd", function(){
                // Redirect to the prefixed route
                return redirect()->to("staff-portal/ipd/manage");
            });
            Route::get("ipd/view/{id}", "singleView")->name("admin.ipd.manage");
            Route::get("ipd/manage", "index")->name("admin.ipd.manage");
            Route::get("ipd/add", "formView")->name("admin.ipd.add.view");
            Route::get("opd/convert-ipd", "formView")->name("admin.ipd.add.view");
            Route::get("ipd/edit/{id}", "formView")->name("admin.ipd.edit.view");
            Route::post("ipd/create", "form")->name("admin.ipd.create");
            Route::post("ipd/update/{id}", "form")->name("admin.ipd.update");
            Route::post("ipd/delete/{id}", "delete")->name("admin.ipd.delete");
        });

        Route::controller(StaffPortalLabOperatorController::class)->group(function(){
            Route::get("lab_operator/test/manage", function(){
                // Redirect to the prefixed route
                return redirect()->to("staff-portal/lab/test/manage");
            });
            Route::get("lab/test/manage/", "index")->name("admin.manage.test.manage");
            Route::get("lab/test/manage/add", "formView")->name("admin.manage.test.add.view");
            Route::get("lab/test/manage/edit/{id}", "formView")->name("admin.manage.test.edit.view");
            Route::get("lab/test/manage/create/{id}", "form")->name("admin.manage.test.create");
            Route::post("lab/test/manage/update/{id}", "form")->name("admin.manage.test.update");
            Route::post("lab/test/manage/delete/{id}", "delete")->name("admin.manage.test.delete");
        });

        Route::controller(StaffPortalTestController::class)->group(function(){
            Route::get("test", function(){
                // Redirect to the prefixed route
                return redirect()->to("staff-portal/test/manage");
            });
            Route::get("test/manage", "index")->name("admin.test.entries.manage");
            Route::get("test/add", "formView")->name("admin.test.entries.add.view");
            Route::get("test/edit/{id}", "formView")->name("admin.test.entries.edit.view");
            Route::post("test/create", "form")->name("admin.test.entries.create");
            Route::post("test/update/{id}", "form")->name("admin.test.entries.update");
            Route::post("test/delete/{id}", "delete")->name("admin.test.entries.delete");
            Route::get("test/assign/{id}", "Assign")->name("admin.test.entries.assign");
        });

        Route::controller(StaffPortalRoomController::class)->group(function(){
            Route::get("rooms-beds", function(){
                // Redirect to the prefixed route
                return redirect()->to("staff-portal/rooms-beds/manage");
            });
            Route::get("rooms-beds/manage", "index")->name("admin.rooms.beds.manage");
            Route::get("rooms-beds/add", "formView")->name("admin.rooms.beds.add.view");
            Route::get("rooms-beds/edit/{id}", "formView")->name("admin.rooms.beds.edit.view");
            Route::post("rooms-beds/create", "form")->name("admin.rooms.beds.create");
            Route::post("rooms-beds/update/{id}", "form")->name("admin.rooms.beds.update");
            Route::post("rooms-beds/delete/{id}", "delete")->name("admin.rooms.beds.delete");
        });

    // These routes are outside the middleware group but still need the prefix

    Route::controller(StaffPortalIpdController::class)->group(function(){
        Route::get("ipd/manage","ipd_view")->name("admin.ipd.manage");
        
        // Route::get("ipd/vitals/update","VitalRecordSave")->name("admin.ipd.manage.vitals.update");
        Route::post("fetch/departmentdoctor", "fetch_doctor")->name("ipd.fetch.doctor");
        Route::post("fetch/doctorSchedule", "fetchSchedule")->name("ipd.fetch.schedule");
        Route::post("fetch/fetch_rooms", "fetch_rooms")->name("ipd.fetch.rooms");
        Route::post("fetch/beds", 'fetch_beds')->name("ipd.fetch.beds");
        Route::post("fetch/ipd/patients", "fetch_patients")->name("ipd.fetch.patients");
    });
    
    Route::controller(StaffPortalLoginController::class)->group(function(){
        Route::get("logout", "Logout")->name("logout");
    });
});
Route::controller(StaffPortalIpdController::class)->group(function(){
    Route::post("ipd/lab-investigation/fetch","LabInvestigationFetch")->name("admin.ipd.manage.lab_investigation");
    Route::post("ipd/lab-investigation/save","LabInvestigationSave")->name("admin.ipd.manage.lab_investigation");
    Route::post("ipd/room-allotment/save","BedAllotmentRecordSave")->name("admin.ipd.manage.room_allotment");
    Route::post("ipd/room-allotment/fetch","BedAllotmentRecordFetch")->name("admin.ipd.manage.room_allotment");
    Route::post("ipd/room-allotment/delete","BedAllotmentRecordDelete")->name("admin.ipd.manage.room_allotment");
    Route::post("ipd/vitals/save","VitalRecordSave")->name("admin.ipd.manage.vitals");
    Route::post("ipd/vitals/latest","VitalRecordFetch")->name("admin.ipd.manage.vitals");
    Route::post("ipd/vitals/delete","VitalRecordDelete")->name("admin.ipd.manage.vitals");
    Route::get("ipd/vitals/print","VitalRecordPrint")->name("admin.ipd.manage.vitals");
    Route::post("ipd/operation/fetch","OpertationFetch")->name("admin.ipd.manage.operations");
    Route::post("ipd/operation/save","OpertationRecordSave")->name("admin.ipd.manage.operations");
    Route::post("ipd/operation/records","OpertationRecordFetch")->name("admin.ipd.manage.operations");
    Route::post("ipd/balance/fetch","BalanceFetch")->name("admin.ipd.manage.balance");
    Route::post("ipd/balance/save","BalanceSave")->name("admin.ipd.manage.balance");
    Route::get("ipd/balance/print","BalancePrint")->name("admin.ipd.manage.balance");
});

    });
    Route::prefix('staff-portal')->group(function () {
        Route::controller(StaffPortalApiController::class)->group(function(){
            Route::post("fetch/opdipd/details", "opdipdDetails")->name("api.fetch.opd.ipd.details");
            Route::post("fetch/opd/patient", "FetchOpdPatient")->name("api.fetch.patient");
            Route::post("fetch/opd/doctor", "FetchDoctorByDepartment")->name("api.fetch.doctor");
            Route::post("fetch/operation/doctor", "FetchDoctorByOperation")->name("api.fetch.operation.doctor");
            Route::post("fetch/rooms", "FetchRooms")->name("api.fetch.rooms");
            Route::post("fetch/rooms/category", "FetchRoomsCategory")->name("api.fetch.rooms.category");
            Route::post("fetch/rooms/lab", "FetchLabRooms")->name("api.fetch.labs.rooms");
            Route::post("fetch/beds", "FetchBeds")->name("api.fetch.beds");
            Route::post("check/opd/date", "CheckDoctorByDate")->name("api.check.doctor.date");
            Route::post("check/doctor/date", "CheckDoctorStatusByDate")->name("api.check.doctor.date");
        });
    });
    

// Route::get('/run-python', [PythonController::class, 'runPythonScript']);
// Route::post('resize-image',[PythonController::class,'resizeImage']);
// Route::get('python',[PythonController::class,'index']);

    Route::controller(LoginController::class)->group(function(){
        Route::get("logout", "logout")->name("logout");
    });
});