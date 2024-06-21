<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\PackagesController;
use App\Http\Controllers\FiltersController;
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
    // Route::post('contacts-export', [ContactsController::class, 'export'])->name('contacts.export');
    Route::post('contacts-export', [ContactsController::class, 'export'])->name('contacts.export');
    Route::post('contacts-import', [ContactsController::class, 'import'])->name('contacts.import');
    Route::post('contacts-data', [ContactsController::class, 'data'])->name('contacts.data');
    // Route::get('packages', PackagesController::class)->name('packages');

    Route::get('/jobs-titles', [FiltersController::class, 'getJobTitles'])->name('jobs-titles');
    Route::get('/seniorities', [FiltersController::class, 'getSeniorities'])->name('seniorities');
    Route::get('/departments', [FiltersController::class, 'getDepartments'])->name('departments');
    Route::get('/companies', [FiltersController::class, 'getCompanies'])->name('companies');
    Route::get('/cities', [FiltersController::class, 'getCities'])->name('cities');
    Route::get('/states', [FiltersController::class, 'getStates'])->name('states');
    Route::get('/countries', [FiltersController::class, 'getCountries'])->name('countries');
    Route::get('/industries', [FiltersController::class, 'getIndustries'])->name('industries');
    Route::get('/technologies', [FiltersController::class, 'getTechnologies'])->name('technologies');

});

Route::get('/filters/{column}', function ($column) {
    $filePath = "filters/{$column}.json";

    if (Storage::exists($filePath)) {
        return response()->json(json_decode(Storage::get($filePath)), 200);
    }

    return response()->json(['error' => 'File not found'], 404);
});
