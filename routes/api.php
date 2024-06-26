<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;


Route::get('/getAllBlogs', [BlogController::class, 'index'])->name('blog.index');
