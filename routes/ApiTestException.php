<?php


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;





Route::get('/unauthorized', static function () {
    abort(401);
});

Route::get('/forbidden', static function () {
    abort(403);
});

Route::get('/not-found', static function () {
    abort(404);
});

Route::get('/too-many-requests', static function () {
    abort(429);
});

Route::get('/internal-server-error', static function () {
    abort(500);
});

Route::get('/query-error', static function () {

    DB::table('non_existing_table')->get();
});
