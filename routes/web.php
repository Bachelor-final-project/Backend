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
use App\Http\Controllers\ExportPDFController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\StripeController;
use Illuminate\Http\Request;

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


Route::get('/proposal/overview', [ProposalController::class, 'overview'])->name('proposal.overview');

$controllers = require base_path('vendor/composer/autoload_classmap.php');
$controllers = array_keys($controllers);
$controllers = array_filter($controllers, function ($controller) {
    return strpos($controller, 'App\Http\Controllers') === 0 && strpos($controller, '\Auth') === false && $controller != 'App\Http\Controllers\Controller' 
    && $controller != 'App\Http\Controllers\StripeController';
});

Route::group(['middleware' => 'auth'], function () use ($controllers) {
    // Route::get('/', fn () => Inertia::render('Dashboard'))->name('dashboard');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/warehouse_items', [WarehouseController::class, 'warehouse_items'])->name('warehouse.items');
    array_map(function ($controller) {
        Route::resource(Str::snake(substr(substr($controller, 21), 0, -10)), $controller);
    }, $controllers);
});

Route::get('/donating-form/{donating_form_path}', [EntityController::class, 'donatingForm'])->name('donating-form');
Route::post('/donating-form/', [EntityController::class, 'storeDonatingForm'])->name('store-donating-form');


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
Route::get('/import-donors', [GeneralController::class, 'importDonors'])->name('import-donors');
Route::get('/import-donations', [GeneralController::class, 'importDonations'])->name('import-donations');
Route::get('/import-documents', [GeneralController::class, 'importDocuments'])->name('import-documents');
Route::get('/import-proposals-overview', [GeneralController::class, 'importProposalsOverview'])->name('import-proposals-overview');

Route::get('/checkout', [StripeController::class, 'checkout'])->name('checkout');
Route::post('/test', [StripeController::class, 'test']);
Route::get('/success', [StripeController::class, 'success'])->name('success');

Route::get('ExportPDF/proposal/{id}', [ExportPDFController::class, 'proposal'])->name('export-pdf-proposal');
