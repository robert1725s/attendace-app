<?php

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
    // メール認証関連のルート
    Route::get("/verify_email", function () {
        return view("auth.verify_email");
    });

    Route::get("/attendance", function () {
        return view("user.attendance.stamp");
    });

    Route::get("/attendance/list", function () {
        return view("user.attendance.index");
    });

    Route::get("/attendance/detail", function () {
        return view("user.attendance.detail");
    });

    Route::get("/admin/attendances/list", function () {
        return view("admin.attendance.index");
    });

    Route::get("/admin/attendances/", function () {
        return view("admin.attendance.detail");
    });

    Route::get("/admin/staff/list", function () {
        return view("admin.staff.index");
    });

    Route::get("/admin/attendance/staff", function () {
        return view("admin.staff.attendance");
    });

    Route::get("/stamp_correction_request/list", function () {
        return view("correction.index");
    });

    Route::get("/stamp_correction_request/approve", function () {
        return view("correction.approve");
    });
});
