<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Test routes - add these ABOVE the catch-all
Route::get('/test', function() {
    return [
        'app_key' => config('app.key') ? 'SET' : 'MISSING',
        'app_env' => config('app.env'),
        'db_host' => config('database.connections.mysql.host'),
        'db_name' => config('database.connections.mysql.database'),
    ];
});

Route::get('/dbtest', function() {
    try {
        DB::connection()->getPdo();
        return 'DB Connected Successfully';
    } catch (\Exception $e) {
        return 'DB Error: ' . $e->getMessage();
    }
});

// Your catch-all route - must be LAST
Route::get('{any}', function () {
    return view('welcome');
})->where('any', '.*');