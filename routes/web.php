<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CrawlerController;

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


Route::any('/{any}', [CrawlerController::class, 'index'])->where('any', '^(?!api).*$');

Route::get('/api/csrf-token', function() {
    return response()->json(['csrfToken' => csrf_token()]);
});

Route::post('/api/crawl', [CrawlerController::class, 'crawl']);