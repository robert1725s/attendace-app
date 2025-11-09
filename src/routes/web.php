<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CorrectionController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//管理者ログイン
Route::get("/admin/login", function () {
    return view("auth.admin_login");
});

Route::middleware("auth")->group(function () {
    // メール認証
    Route::get("/verify_email", function () {
        return view("auth.verify_email");
    });

    // 一般ユーザー専用ルート
    Route::middleware("user")->group(function () {
        // 勤怠登録画面
        Route::get("/attendance", [StaffController::class, 'showAttendance']);

        //勤怠処理
        Route::post("/attendance/stamp", [StaffController::class, 'stamp']);
        Route::post("/attendance/rest", [StaffController::class, 'rest']);

        // 勤怠一覧画面
        Route::get("/attendance/list", [StaffController::class, 'showList']);

        // 勤怠詳細画面
        Route::get("/attendance/detail/{id}", [StaffController::class, 'showDetail']);

        //勤怠修正
        Route::post("/attendance/detail/request/{id}", [StaffController::class, 'requestModify']);
    });

    // 管理者専用ルート
    Route::middleware("admin")->group(function () {

        // 管理者用勤怠一覧画面
        Route::get("/admin/attendances/list", [AdminController::class, 'showAttendanceList']);

        // 管理者用勤怠詳細画面（新規・既存両方対応）
        Route::get("/admin/attendance/{id}", [AdminController::class, 'showDetail']);

        // 管理者用勤怠修正
        Route::post("/admin/attendance/modify/{id}", [AdminController::class, 'modify']);

        // 管理者用スタッフ一覧画面
        Route::get("/admin/staff/list", [AdminController::class, 'showStaffList']);

        // 管理者用スタッフ別勤怠一覧画面
        Route::get("/admin/attendance/staff/{id}", [AdminController::class, 'showStaffAttendance']);

        // 管理者用スタッフ勤怠CSV出力
        Route::post("/admin/attendance/staff/output/{id}", [AdminController::class, 'outputAttendance']);
    });

    // 申請一覧画面
    Route::get("/stamp_correction_request/list", [CorrectionController::class, 'showCorrection']);

    // 申請承認画面
    Route::get("/stamp_correction_request/approve/{attendance_correct_request_id}", [CorrectionController::class, 'showApprove']);

    // 申請承認処理
    Route::post("/stamp_correction_request/approve/{attendance_correct_request_id}", [CorrectionController::class, 'approve']);
});
