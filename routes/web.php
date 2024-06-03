<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\PackagesController;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
    Route::resource('contacts', ContactsController::class)->names('contacts');
    Route::post('contacts-export', [ContactsController::class, 'export'])->name('contacts.export');
    Route::post('contacts-import', [ContactsController::class, 'import'])->name('contacts.import');
    Route::post('contacts-data', [ContactsController::class, 'data'])->name('contacts.data');
    // Route::get('packages', PackagesController::class)->name('packages');
});

Route::get('/filters/{column}', function ($column) {
    $filePath = "filters/{$column}.json";

    if (Storage::exists($filePath)) {
        return response()->json(json_decode(Storage::get($filePath)), 200);
    }

    return response()->json(['error' => 'File not found'], 404);
});
