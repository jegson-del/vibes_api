<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\NewEventController;
use App\Http\Livewire\EventView;

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

Route::view('/privacy-policy', 'privacy-policy');

Route::group(['middleware' => ['auth', 'is_admin']], function() {
    Route::get('/admin', NewEventController::class)->name('admin');

    Route::get('/admin/events', EventView::class)->name('admin.events');
});



//Route::get('/', function () {
//    return view('welcome');
//});
//
//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
