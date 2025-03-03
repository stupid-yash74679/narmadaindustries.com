<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

/**
 * Route to clear cache only
 */
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');

    return response()->json(['message' => 'Cache cleared successfully!']);
});

/**
 * Route to unlink storage
 */
Route::get('/unlink-storage', function () {
    $storagePath = public_path('storage');

    if (File::exists($storagePath)) {
        File::delete($storagePath);
        return response()->json(['message' => 'Old storage link removed successfully!']);
    }

    return response()->json(['message' => 'No existing storage link found.']);
});

/**
 * Route to link storage
 */
Route::get('/link-storage', function () {
    Artisan::call('storage:link');
    return response()->json(['message' => 'Storage linked successfully!']);
});

/**
 * Route to clear cache and reset storage in one go
 */
Route::get('/reset-storage', function () {
    // Clear all caches
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('route:clear');

    // Unlink old storage link if it exists
    $storagePath = public_path('storage');
    if (File::exists($storagePath)) {
        File::delete($storagePath);
    }

    // Create a fresh storage link
    Artisan::call('storage:link');

    return response()->json(['message' => 'Cache cleared & storage linked successfully!']);
});
