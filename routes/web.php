<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes — IT0049 TFA7: Forms, Validation, and File Upload
|--------------------------------------------------------------------------
*/

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');

Route::resource('customers', CustomerController::class)
    ->only(['index', 'create', 'store', 'edit', 'update']);

Route::resource('users', UserController::class)
    ->only(['index', 'create', 'store', 'edit', 'update']);

// Serve avatars from Storage::disk('public') safely when public/storage symlink is unavailable
Route::get('/avatars/{filename}', function (string $filename) {
    $safeName = basename($filename);
    $path = 'avatars/'.$safeName;

    abort_unless(Storage::disk('public')->exists($path), 404);

    return Storage::disk('public')->response($path);
})->where('filename', '[A-Za-z0-9._-]+')->name('avatars.show');
