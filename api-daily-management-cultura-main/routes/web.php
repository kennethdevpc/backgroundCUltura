<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\PDFController as PDFController_V1;

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('rollback', function () {
    Artisan::call('migrate:refresh --seed');
    echo '<a href=' . url('/') . '>Se ha reestablecido la configuración, volver al sistema.</a>';
});
Route::get('config-clear', function () {

    Artisan::call('config:clear');
    echo '<a href=' . url('/') . '>Se ha limpiado la configuración, volver al sistema.</a>';
});

// Route::post('format-image', [GeneralController::class, 'store'])->name('format-image');
Route::prefix('pdf')->group(function () {
    Route::post('contract/', [PDFController_V1::class, 'formateManagerMonitorings']);
});

Route::get('download/pdf/{raiz}/{type}/{file}', function ($raiz, $type, $file) {
    $path = public_path($raiz . '/' . $type . '/' . $file);
    return response()->download($path);
});

Route::get('download/pdf/{raiz}/{type}/{name}/{file}', function ($raiz, $type, $name, $file) {
    $path = public_path($raiz . '/' . $type . '/' . $name . '/' . $file);
    return response()->download($path);
});

route::get('/consultas',[PDFController_V1::class, 'consultas']);
