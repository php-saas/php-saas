<?php

use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Project\AcceptProjectInviteController;
use App\Http\Controllers\Project\LeaveProjectController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Project\ProjectSwitchController;
use App\Http\Controllers\Project\ProjectUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/settings')->middleware(['auth'])->group(function () {
    Route::redirect('/', '/settings/profile')->name('settings');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('projects', ProjectController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::put('/projects/{project}/switch', ProjectSwitchController::class)->name('projects.switch');
    Route::post('/projects/{project}/users', [ProjectUserController::class, 'store'])->name('projects.users.store');
    Route::delete('/projects/{project}/users/{email}', [ProjectUserController::class, 'destroy'])
        ->name('projects.users.destroy');
    Route::delete('/projects/{project}/leave', LeaveProjectController::class)->name('projects.leave');
    Route::get('/projects/{project}/invitations/accept', AcceptProjectInviteController::class)
        ->name('projects.invitations.accept');

    Route::resource('api-keys', ApiKeyController::class)->only(['index', 'store', 'destroy']);
});
