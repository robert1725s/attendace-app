<?php

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

Route::middleware("auth")->group(function () {
    // メール認証
    Route::get("/verify_email", function () {
        return view("auth.verify_email");
    });

    // 勤怠登録画面
    Route::get("/attendance", [StaffController::class, 'showAttendance']);

    //勤怠処理
    Route::post("/attendance/stamp", [StaffController::class, 'stamp']);
    Route::post("/attendance/rest", [StaffController::class, 'rest']);

    // 勤怠一覧画面
    Route::get("/attendance/list", [StaffController::class, 'showList']);

    // 勤怠詳細画面
    Route::get("/attendance/detail/{id}", [StaffController::class, 'showDetail']);

    //勤怠修正申請
    Route::post("/admin/attendance/modify/{id}", [StaffController::class, 'modify']);

    Route::get("/admin/attendances/list", function () {
        return view("admin.attendance.index");
    });

    Route::get("/admin/attendance/", function () {
        return view("admin.attendance.detail");
    });

    Route::get("/admin/staff/list", function () {
        return view("admin.staff.index");
    });

    Route::get("/admin/attendance/staff", function () {
        return view("admin.staff.attendance");
    });

    // 申請一覧画面
    Route::get("/stamp_correction_request/list", [CorrectionController::class, 'showCorrection']);

    Route::get("/stamp_correction_request/approve", function () {
        return view("correction.approve");
    });
});
