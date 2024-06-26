<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/newsblogs', function () {
    return view('bloglist');
});

Route::get('/details/{id}', function ($id) {
    return view('blogdetails', ['id' => $id]);
});

Route::get('/getAllBlogs', [BlogController::class, 'index'])->name('blog.index');

Route::get('/getBlogDetails/{id}', [BlogController::class, 'show'])->name('blog.show');

Route::post('/createNewBlog', [BlogController::class, 'store'])->name('blog.store');

Route::get('/blogmanager', function () {
    return view('blogmanager');
});

Route::get('/usermanager', function () {
    return view('usermanager');
});

Route::get('/getAllUsers', [UserController::class, 'index'])->name('users.index');

Route::get('/getUser/{id}', [UserController::class, 'show'])->name('users.show');

Route::post('/updateUser/{id}', [UserController::class, 'update'])->name('users.update');

Route::post('/postComment', [CommentController::class, 'store'])->name('comments.store');

require __DIR__.'/auth.php';
