<?php

use App\Http\Controllers\Auth\SocialiteCallbackController;
use App\Http\Controllers\Auth\SocialiteRedirectController;
use Illuminate\Support\Facades\Route;

Route::get('/auth/{provider}/redirect', SocialiteRedirectController::class)->name('auth.redirect');
Route::get('/auth/{provider}/callback', SocialiteCallbackController::class)->name('auth.callback');
