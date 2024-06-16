<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\CustomAuthController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/crud/{code}/list', [TasksController::class, 'list'])->name('crud.list');
    Route::delete('/crud/{code}/{id}/delete', [TasksController::class, 'delete'])->name('crud.delete');
    Route::get('crud/{code}/{id}/edit', [TasksController::class, 'edit'])->name('crud.edit');
    Route::get('/crud/{code}/create', [TasksController::class, 'create'])->name('crud.create');
    Route::patch('/tasks/save/{code}/{id}', [TasksController::class, 'save'])->name('tasks.save');
    Route::post('/tasks/{code}', [TasksController::class, 'save'])->name('tasks.save');
    Route::match(['post', 'patch'], '/tasks/{code}/{id?}', [TasksController::class, 'save'])->name('tasks.save');
    Route::patch('/tasks/{code}/{id?}', [TasksController::class, 'save'])->name('tasks.save');
});

Route::get('dashboard', [CustomAuthController::class, 'dashboard'])->name('dashboard');
Route::get('login', [CustomAuthController::class, 'index'])->name('login');
Route::post('custom-login', [CustomAuthController::class, 'customLogin'])->name('login.custom');
Route::get('register', [CustomAuthController::class, 'registration'])->name('register');
Route::post('custom-registration', [CustomAuthController::class, 'customRegistration'])->name('register.custom');
Route::get('signout', [CustomAuthController::class, 'signOut'])->name('signout');
