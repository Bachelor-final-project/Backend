<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\ProposalController;

// Route::get('/', function () {
//    return 'hi';
// });

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


// $controllers = [];


// foreach (glob(app_path('Http/Controllers/*.php')) as $file) {
//     $basename = basename($file, '.php');
//     $class = 'App\\Http\\Controllers\\' . $basename;
//     if (in_array($basename, ['AuthController.php', 'Controller.php'])) {
//         continue;
//     }
//     if (class_exists($class)) {
//         $reflection = new ReflectionClass($class);
//         if (!$reflection->isAbstract() && !$reflection->isInterface() && !$reflection->isTrait()) {
//             $controllers[] = $class;
//         }
//     }
// }
// Route::middleware(['web'])->middleware('auth')->group(function () use ($controllers) {
//     array_map(function ($controller) {
//         if (method_exists($controller, 'routeName')) {
//             // Artisan::call('make:resource ' . ucfirst(Str::camel($controller::routeName())) . 'Resource ');
//             Route::resource($controller::routeName(), $controller);
//         }
//         if (method_exists($controller, 'indexApi')) {
//             // Artisan::call('make:resource ' . ucfirst(Str::camel($controller::routeName())) . 'Resource ');
//             Route::get($controller::routeName() . 'Api', [$controller, 'indexApi'])->name(strtolower($controller::routeName() . ".index.api"));
//         }
//     }, $controllers);
// });
$controllers = require base_path('vendor/composer/autoload_classmap.php');
$controllers = array_keys($controllers);
$controllers = array_filter($controllers, function ($controller) {
    return strpos($controller, 'App\Http\Controllers') === 0 && strpos($controller, '\Auth') === false && $controller != 'App\Http\Controllers\Controller';
});

Route::group(['middleware' => 'auth'], function () use ($controllers) {
    // Route::get('/', fn () => Inertia::render('Dashboard'))->name('dashboard');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/warehouse_items', [WarehouseController::class, 'warehouse_items'])->name('warehouse.items');
    array_map(function ($controller) {
        Route::resource(Str::snake(substr(substr($controller, 21), 0, -10)), $controller);
    }, $controllers);
});

Route::get('/guest_index', [ProposalController::class, 'guestIndex'])->name('guest-index');
Route::get('/donating-form/{donating_form_path}', [EntityController::class, 'donatingForm'])->name('donating-form');


// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });
Route::get('/change-language/{locale}', [GeneralController::class, 'changeLanguage'])->name('change-language');

require __DIR__.'/auth.php';

Route::get('/import-users', [GeneralController::class, 'importUsers'])->name('import-users');
Route::get('/import-warehouses', [GeneralController::class, 'importWarehouses'])->name('import-warehouses');
Route::get('/import-items', [GeneralController::class, 'importItems'])->name('import-items');
Route::get('/import-units', [GeneralController::class, 'importUnits'])->name('import-units');
Route::get('/import-currencies', [GeneralController::class, 'importCurrencies'])->name('import-currencies');
Route::get('/import-proposals', [GeneralController::class, 'importProposals'])->name('import-proposals');
Route::get('/import-beneficiaries', [GeneralController::class, 'importBeneficiaries'])->name('import-beneficiaries');

