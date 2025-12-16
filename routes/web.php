<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpRequestController;
use App\Http\Controllers\Web\CampusCourseController;
use App\Http\Controllers\Web\CampusCourseSubjectController;
use App\Http\Controllers\Web\CampusGradeController;
use App\Http\Controllers\Web\CourseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\LocationBarangayController;
use App\Http\Controllers\Web\LocationCityController;
use App\Http\Controllers\Web\LocationProvinceController;
use App\Http\Controllers\Web\LocationRegionController;
use App\Http\Controllers\Web\ReferenceController;
use App\Http\Controllers\Web\RoleController;
use App\Http\Controllers\Web\RouteController;
use App\Http\Controllers\Web\SchoolController;
use App\Http\Controllers\Web\UserController;
use App\Models\SchoolCampusCourseSubjects;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->name('login.store');
    Route::post('otp/request', [OtpRequestController::class, 'create'])->name('otp.check');
    Route::post('otp/login', [OtpRequestController::class, 'store'])->name('otp.store');
});

Route::middleware('auth', 'web')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
    Route::put('roles/{id}/{type}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');

    Route::post('routes', [RouteController::class, 'store'])->name('routes.store');
    Route::put('routes/{id}/{type}', [RouteController::class, 'update'])->name('routes.update');
    Route::delete('routes/{id}', [RouteController::class, 'destroy'])->name('routes.destroy');

    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::put('users/{id}/{type}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{id}/{type}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::post('location/regions', [LocationRegionController::class, 'store'])->name('location.regions.store');
    Route::put('location/regions/{id}/{type}', [LocationRegionController::class, 'update'])->name('location.regions.update');
    Route::delete('location/regions/{id}/{type}', [LocationRegionController::class, 'destroy'])->name('location.regions.destroy');

    Route::post('location/provinces', [LocationProvinceController::class, 'store'])->name('location.provinces.store');
    Route::put('location/provinces/{id}/{type}', [LocationProvinceController::class, 'update'])->name('location.provinces.update');
    Route::delete('location/provinces/{id}/{type}', [LocationProvinceController::class, 'destroy'])->name('location.provinces.destroy');

    Route::post('location/cities', [LocationCityController::class, 'store'])->name('location.cities.store');
    Route::put('location/cities/{id}/{type}', [LocationCityController::class, 'update'])->name('location.cities.update');
    Route::delete('location/cities/{id}/{type}', [LocationCityController::class, 'destroy'])->name('location.cities.destroy');

    Route::post('location/barangays', [LocationBarangayController::class, 'store'])->name('location.barangays.store');
    Route::put('location/barangay/{id}/{type}', [LocationBarangayController::class, 'update'])->name('location.barangays.update');
    Route::delete('location/barangay/{id}/{type}', [LocationBarangayController::class, 'destroy'])->name('location.barangays.destroy');

    Route::post('academic/courses', [CourseController::class, 'store'])->name('academic.courses.store');
    Route::put('academic/courses/{id}/{type}', [CourseController::class, 'update'])->name('academic.courses.update');
    Route::delete('academic/courses/{id}/{type}', [CourseController::class, 'destroy'])->name('academic.courses.destroy');

    Route::post('academic/references', [ReferenceController::class, 'store'])->name('academic.references.store');
    Route::put('academic/references/{id}/{type}', [ReferenceController::class, 'update'])->name('academic.references.update');
    Route::delete('academic/references/{id}/{type}', [ReferenceController::class, 'destroy'])->name('academic.references.destroy');

    Route::post('academic/universities', [SchoolController::class, 'store'])->name('academic.universities.store');
    Route::put('academic/universities/{id}/{type}', [SchoolController::class, 'update'])->name('academic.universities.update');
    Route::delete('academic/universities/{id}/{type}', [SchoolController::class, 'destroy'])->name('academic.universities.destroy');

    Route::post('academic/universities/course', [CampusCourseController::class, 'store'])->name('academic.universities.course.store');
    Route::put('academic/universities/course/{id}/{type}', [CampusCourseController::class, 'update'])->name('academic.universities.course.update');
    Route::delete('academic/universities/course/{id}/{type}', [CampusCourseController::class, 'destroy'])->name('academic.universities.course.destroy');

    Route::post('academic/universities/grade', [CampusGradeController::class, 'store'])->name('academic.universities.grade.store');
    Route::put('academic/universities/grade/{id}/{type}', [CampusGradeController::class, 'update'])->name('academic.universities.grade.update');
    Route::delete('academic/universities/grade/{id}/{type}', [CampusGradeController::class, 'destroy'])->name('academic.universities.grade.destroy');

    Route::post('subjects', [CampusCourseSubjectController::class, 'store'])->name('subject.store');
    Route::put('subjects/{id}/{type}', [CampusCourseSubjectController::class, 'update'])->name('subject.update');
    Route::delete('subjects/{id}/{type}', [CampusCourseSubjectController::class, 'destroy'])->name('subject.destroy');
});
Route::middleware('auth', 'web', 'role')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('roles', [RoleController::class, 'index'])->name('roles');
    Route::get('routes', [RouteController::class, 'index'])->name('routes');
    Route::get('users', [UserController::class, 'index'])->name('users');
    Route::get('location/regions', [LocationRegionController::class, 'index'])->name('location.regions');
    Route::get('location/provinces', [LocationProvinceController::class, 'index'])->name('location.provinces');
    Route::get('location/cities', [LocationCityController::class, 'index'])->name('location.cities');
    Route::get('location/barangay', [LocationBarangayController::class, 'index'])->name('location.barangays');
    Route::get('academic/courses', [CourseController::class, 'index'])->name('academic.courses');
    Route::get('academic/references', [ReferenceController::class, 'index'])->name('academic.references');
    Route::get('academic/universities', [SchoolController::class, 'index'])->name('academic.universities');
});
