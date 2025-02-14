<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('/products', ProductController::class);

Route::get('/products/{id}/newtask', [ProductController::class, 'newtask'])->name('products.newtask');
Route::put('/products/{id}/updateDescription', [ProductController::class, 'updateDescription'])->name('products.updateDescription');
Route::put('/products/{product}/update-task-list', [ProductController::class, 'updateTaskList'])->name('products.updateTaskList');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
