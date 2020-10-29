<?php

use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Mail;
// use App\Mail\NewUserWelcomeMail;

Auth::routes();

// Route::get('/email', function() {
	
// 	// Mail::to($user->email)->send(new NewUserWelcomeMail());
// 	return new NewUserWelcomeMail();
// });

Route::post('follow/{user}', [App\Http\Controllers\FollowsController::class, 'store']);


Route::get('/', [App\Http\Controllers\PostsController::class, 'index']);
Route::get('/p/create', [App\Http\Controllers\PostsController::class, 'create'])->name('p.create');
Route::post('/p', [App\Http\Controllers\PostsController::class, 'store'])->name('p.store');
Route::get('/p/{post}', [App\Http\Controllers\PostsController::class, 'show'])->name('p.show');


Route::get('/profile/{user}', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.show');
Route::get('/profile/{user}/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile/{user}', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
