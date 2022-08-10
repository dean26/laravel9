<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\PasswordChangeController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\FlagController;
use App\Http\Controllers\Api\GuideController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UnavailabilityTimeController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth/login', [LoginController::class, 'login'])->name('login_custom_url');
Route::post('/login', [LoginController::class, 'login_spa'])->name('login_spa');
Route::post('/logout', [LoginController::class, 'logout_spa'])->middleware(['auth:sanctum'])->name('logout_spa');

Route::post('/auth/forgot-password', [ResetPasswordController::class, 'forgot_password']);
Route::post('/auth/reset-password', [ResetPasswordController::class, 'reset_password'])->name('password.reset');

Route::middleware(['auth:sanctum'])->group(function () {

    //auth
    Route::put('auth/password', [PasswordChangeController::class, 'update']);
    Route::get('auth/profile', [LoginController::class, 'profile']);
    Route::post('auth/logout', [LoginController::class, 'logout_current']);
    Route::post('auth/logout-current', [LoginController::class, 'logout']);

    //users
    Route::put('users/{id}/password', [UserController::class, 'update_password'])->where('id', '[0-9]+');
    Route::delete('users/destroy_many', [UserController::class, 'destroy_many']);
    Route::get('users/{id}/notes', [UserController::class, 'notes_index'])->where('id', '[0-9]+');
    Route::post('users/{id}/notes', [UserController::class, 'notes_store'])->where('id', '[0-9]+');
    Route::resource('users', UserController::class, ['except' => 'create', 'edit']);

    //customers
    Route::delete('customers/destroy_many', [CustomerController::class, 'destroy_many']);
    Route::get('customers/{id}/notes', [CustomerController::class, 'notes_index'])->where('id', '[0-9]+');
    Route::post('customers/{id}/notes', [CustomerController::class, 'notes_store'])->where('id', '[0-9]+');
    Route::get('customers/{id}/jobs', [CustomerController::class, 'jobs_index'])->where('id', '[0-9]+');
    Route::resource('customers', CustomerController::class, ['except' => 'create', 'edit']);

    //notes
    Route::delete('notes/destroy_many', [NoteController::class, 'destroy_many']);
    Route::delete('notes/{id}', [NoteController::class, 'destroy'])->where('id', '[0-9]+');
    Route::put('notes/{id}', [NoteController::class, 'update'])->where('id', '[0-9]+');

    //items
    Route::delete('items/destroy_many', [ItemController::class, 'destroy_many']);
    Route::resource('items', ItemController::class, ['except' => 'create', 'edit']);

    //jobs
    Route::delete('jobs/destroy_many', [JobController::class, 'destroy_many']);
    Route::get('jobs/{id}/notes', [JobController::class, 'notes_index'])->where('id', '[0-9]+');
    Route::post('jobs/{id}/notes', [JobController::class, 'notes_store'])->where('id', '[0-9]+');
    Route::post('jobs/{id}/status', [JobController::class, 'status'])->where('id', '[0-9]+');
    Route::get('jobs/{id}/files', [JobController::class, 'files_index'])->where('id', '[0-9]+');
    Route::post('jobs/{id}/files', [JobController::class, 'files_store'])->where('id', '[0-9]+');
    Route::get('jobs/{id}/tasks', [JobController::class, 'tasks_index'])->where('id', '[0-9]+');
    Route::post('jobs/{id}/tasks', [JobController::class, 'tasks_store'])->where('id', '[0-9]+');
    Route::get('jobs/{id}/guides', [JobController::class, 'guide_index'])->where('id', '[0-9]+');
    Route::post('jobs/{id}/guides', [JobController::class, 'guide_store'])->where('id', '[0-9]+');
    Route::post('jobs/{id}/flags', [JobController::class, 'flags_store'])->where('id', '[0-9]+');
    Route::resource('jobs', JobController::class, ['except' => 'create', 'edit']);

    //files
    //Route::get('file/{id}', [FileController::class, 'retrieve'])->where('id', '[0-9]+')->name('file_get');
    Route::delete('files/destroy_many', [FileController::class, 'destroy_many']);
    Route::delete('files/{id}', [FileController::class, 'destroy'])->where('id', '[0-9]+');

    //tasks
    Route::get('tasks/{id}', [TaskController::class, 'show'])->where('id', '[0-9]+');
    Route::post('tasks/{id}/flags', [TaskController::class, 'flags_store'])->where('id', '[0-9]+');
    Route::post('tasks/{id}/status', [TaskController::class, 'status'])->where('id', '[0-9]+');
    Route::delete('tasks/destroy_many', [TaskController::class, 'destroy_many']);
    Route::delete('tasks/{id}', [TaskController::class, 'destroy'])->where('id', '[0-9]+');

    //flags
    Route::delete('flags/destroy_many', [FlagController::class, 'destroy_many']);
    Route::delete('flags/{id}', [FlagController::class, 'destroy'])->where('id', '[0-9]+');

    //search
    Route::get('search', SearchController::class);

    //guides
    Route::delete('guides/destroy_many', [GuideController::class, 'destroy_many']);
    Route::delete('guides/{id}', [GuideController::class, 'destroy'])->where('id', '[0-9]+');
    Route::put('guides/{id}', [GuideController::class, 'update'])->where('id', '[0-9]+');

    //unavailability
    Route::resource('unavailability_times', UnavailabilityTimeController::class, ['except' => 'create', 'edit']);
});
