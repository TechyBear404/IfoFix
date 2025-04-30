<?php


use App\Http\Controllers\ClientController;
use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\InterventionsController;
use App\Http\Controllers\ModelsController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\TypesController;
use App\Http\Controllers\UsersController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    // return Inertia::render('Welcome', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    //     'laravelVersion' => Application::VERSION,
    //     'phpVersion' => PHP_VERSION,
    // ]);
    return redirect('/tickets');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Route::get('/dashboard', function () {
    //     return Inertia::render('Dashboard');
    // })->name('dashboard');

    Route::resource('tickets', TicketsController::class);
    Route::resource('interventions', InterventionsController::class);
    Route::patch('/interventions/{intervention}/cloturer', [InterventionsController::class, 'cloturer'])->name('interventions.cloturer');

    Route::resource('devices', ModelsController::class);
    // Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');

    Route::put('/types/{type}', [TypesController::class, 'update'])->name('types.update');
    Route::put('/brands/{brand}', [BrandsController::class, 'update'])->name('brands.update');
    Route::put('/devices/{device}', [ModelsController::class, 'update'])->name('devices.update');

    Route::post('/types', [TypesController::class, 'store'])->name('types.store');
    Route::post('/brands', [BrandsController::class, 'store'])->name('brands.store');

    Route::delete('/types/{type}', [TypesController::class, 'destroy'])->name('types.destroy');
    Route::delete('/devices/{device}', [ModelsController::class, 'destroy'])->name('devices.destroy');
    Route::delete('/brands/{brand}', [BrandsController::class, 'destroy'])->name('brands.destroy');

    Route::get('/administration/stats', [AdministrationController::class, 'stats'])->name('administration.stats');
    Route::get('/administration/users', [AdministrationController::class, 'users'])->name('administration.users');
    Route::get('/administration/clients', [AdministrationController::class, 'clients'])->name('administration.clients');
    Route::get('/administration/devices', [AdministrationController::class, 'devices'])->name('administration.devices');
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::post('/users', [UsersController::class, 'store'])->name('users.store');
    Route::put('/users', [UsersController::class, 'edit'])->name('users.edit');
    Route::put('/users/password', [UsersController::class, 'editPassword'])->name('users.edit.password');
    Route::get('/clients', [ClientsController::class, 'index'])->name('clients.index');
    Route::post('/clients', [ClientsController::class, 'store'])->name('clients.store');
    Route::put('/clients', [ClientsController::class, 'edit'])->name('clients.edit');
});
