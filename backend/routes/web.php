<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $databaseConnected = false;

    try {
        DB::connection()->getPdo();
        $databaseConnected = true;
    } catch (\Throwable) {
        // Keep the public status page available without exposing connection details.
    }

    return view('welcome', [
        'apiOnline' => true,
        'databaseConnected' => $databaseConnected,
    ]);
});
