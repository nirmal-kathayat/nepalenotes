<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/', [AuthController::class, 'loginProcess'])->name('loginProcess');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['prefix' => 'admin'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // class/grade
    Route::group(['prefix' => 'grade'], function () {
        Route::get('/', [GradeController::class, 'index'])->name('admin.grade');
        Route::post('/store', [GradeController::class, 'store'])->name('admin.grade.store');
        Route::get('edit/{id}', [GradeController::class, 'edit'])->name('admin.grade.edit');
        Route::post('edit/{id}', [GradeController::class, 'update'])->name('admin.grade.update');
        Route::get('/delete/{id}', [GradeController::class, 'delete'])->name('admin.grade.delete');
    });

    // faculty
    Route::group(['prefix' => 'faculty'], function () {
        Route::get('/', [FacultyController::class, 'index'])->name('admin.faculty');
        Route::post('/store', [FacultyController::class, 'store'])->name('admin.faculty.store');
        Route::get('/edit/{id}', [FacultyController::class, 'edit'])->name('admin.faculty.edit');
        Route::post('/edit/{id}', [FacultyController::class, 'update'])->name('admin.faculty.update');
        Route::get('/delete/{id}', [FacultyController::class, 'delete'])->name('admin.faculty.delete');
    });

    // subject
    Route::group(['prefix' => 'subject'], function () {
        Route::get('/', [SubjectController::class, 'index'])->name('admin.subject');
        Route::post('/store', [SubjectController::class, 'store'])->name('admin.subject.store');
        Route::get('/edit/{id}', [SubjectController::class, 'edit'])->name('admin.subject.edit');
        Route::post('/edit/{id}', [SubjectController::class, 'update'])->name('admin.subject.update');
        Route::get('/delete/{id}', [SubjectController::class, 'delete'])->name('admin.subject.delete');
        Route::get('/admin/facultiesByGrade/{gradeId}', [SubjectController::class, 'getFacultiesByGrade'])->name('admin.facultiesByGrade');
    });
    // Course
    Route::group(['prefix' => 'course'], function () {
        Route::get('/', [CourseController::class, 'index'])->name('admin.course');
        Route::post('/store', [CourseController::class, 'store'])->name('admin.course.store');
        Route::get('/edit/{id}', [CourseController::class, 'edit'])->name('admin.course.edit');
        Route::post('/edit/{id}', [CourseController::class, 'update'])->name('admin.course.update');
        Route::get('/view/{id}', [CourseController::class, 'view'])->name('admin.course.view');
        Route::get('/delete/{id}', [CourseController::class, 'delete'])->name('admin.course.delete');
        Route::get('/get-subjects', [CourseController::class, 'getSubjects'])->name('admin.get-subjects');
    });
    // notes
    Route::group(['prefix' => 'note'], function () {
        Route::post('/store', [NoteController::class, 'store'])->name('admin.note.store');
        Route::get('/getNotes', [NoteController::class, 'getNotes'])->name('admin.note.getNotes');
        Route::get('/edit/{id}', [NoteController::class, 'edit'])->name('admin.note.edit');
        Route::post('/update/{id}', [NoteController::class, 'update'])->name('admin.note.update');
    });

    // permission
    Route::group(['prefix' => 'permission'], function () {
        Route::get('/', [PermissionController::class, 'index'])->name('admin.permission');
        Route::get('/create', [PermissionController::class, 'create'])->name('admin.permission.create');
        Route::post('/create', [PermissionController::class, 'store'])->name('admin.permission.store');
        Route::get('edit/{id}', [PermissionController::class, 'edit'])->name('admin.permission.edit');
        Route::post('edit/{id}', [PermissionController::class, 'update'])->name('admin.permission.update');
        Route::get('delete/{id}', [PermissionController::class, 'delete'])->name('admin.permission.delete');
    });

    // role
    Route::group(['prefix' => 'role'], function () {
        Route::get('/', [RoleController::class, 'index'])->name('admin.role');
        Route::get('/create', [RoleController::class, 'create'])->name('admin.role.create');
        Route::post('/create', [RoleController::class, 'store'])->name('admin.role.store');
        Route::get('edit/{id}', [RoleController::class, 'edit'])->name('admin.role.edit');
        Route::post('edit/{id}', [RoleController::class, 'update'])->name('admin.role.update');
        Route::get('delete/{id}', [RoleController::class, 'delete'])->name('admin.role.delete');
    });
    // user
    Route::group(['prefix' => 'user'], function () {
        Route::get('/', [UserController::class, 'index'])->name('admin.user');
        Route::get('/create', [UserController::class, 'create'])->name('admin.user.create');
        Route::post('/create', [UserController::class, 'store'])->name('admin.user.store');
        Route::get('edit/{id}', [UserController::class, 'edit'])->name('admin.user.edit');
        Route::post('edit/{id}', [UserController::class, 'update'])->name('admin.user.update');
        Route::get('delete/{id}', [UserController::class, 'delete'])->name('admin.user.delete');
    });
});
