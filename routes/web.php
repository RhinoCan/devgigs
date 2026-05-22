<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GigController;
use App\Http\Controllers\UserController;

//---------------------------------------------------------------------

// Display all gigs
Route::get('/', [GigController::class, 'index']);

// Create new gig
Route::get('/create', [GigController::class, 'create'])->middleware('auth');

// Manage gigs
Route::get('/manage', [GigController::class, 'manage'])->middleware('auth');

// Store new gig
Route::post('/', [GigController::class, 'store'])->middleware('auth');

// Display one gig
Route::get('/{gig}', [GigController::class, 'show'])->where('gig', '[0-9]+');

// Edit one gig
Route::get('/{gig}/edit', [GigController::class, 'edit'])->middleware('auth')->where('gig', '[0-9]+');

// Update one gig
Route::put('/{gig}', [GigController::class, 'update'])->middleware('auth')->where('gig', '[0-9]+');

// Delete one gig
Route::delete('/{gig}', [GigController::class, 'destroy'])->middleware('auth')->where('gig', '[0-9]+');

// Delete confirmation for one gig
Route::get('/{gig}/delete-confirm', [GigController::class, 'confirmDelete'])->middleware('auth')->where('gig', '[0-9]+');

//------------------------------------------------------------------------

// Show Registration form
Route::get('/register', [UserController::class, 'create'])->middleware('guest');

// Create new user
Route::post('/users', [UserController::class, 'store']);

// Log user out
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');

// Show login form
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');

// Log user in
Route::post('/users/authenticate', [UserController::class, 'authenticate']);

//------------------------------------------------------------------------

