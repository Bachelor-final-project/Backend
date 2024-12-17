<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/users', [UserController::class, 'index'])->name('users');
Route::get('/dashboard/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/dashboard/users/create', [UserController::class, 'store'])->name('users.store');
Route::get('/tables/', [DashboardController::class, 'index'])->name('tables');
Route::get('/billing/', [DashboardController::class, 'index'])->name('billing');
Route::get('/virtual-reality/', [DashboardController::class, 'index'])->name('virtual-reality');
Route::get('/rtl/', [DashboardController::class, 'index'])->name('rtl');
Route::get('/notifications/', [DashboardController::class, 'index'])->name('notifications');
Route::get('/profile/', [DashboardController::class, 'index'])->name('profile');
Route::get('/static-sign-in/', [DashboardController::class, 'index'])->name('static-sign-in');
Route::get('/static-sign-up/', [DashboardController::class, 'index'])->name('static-sign-up');
Route::get('/logout/', [DashboardController::class, 'index'])->name('logout');
Route::get('/login/', [DashboardController::class, 'index'])->name('login');
Route::get('/register/', [DashboardController::class, 'index'])->name('register');
Route::get('/user-profile/', [ProfileController::class, 'index'])->name('user-profile');
Route::get('/user-management/', [ProfileController::class, 'index'])->name('user-management');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/profile', [AuthController::class, 'profile'])->name('profile')->middleware('auth:web');


$controllers = require base_path('vendor/composer/autoload_classmap.php');
$controllers = array_keys($controllers);
$controllers = array_filter($controllers, function ($controller) {
    return (strpos($controller, 'TenantControllers') !== false || strpos($controller, 'MasterControllers') !== false) && strlen($controller) > 0 && strpos($controller, 'Base') == false && strpos($controller, 'OAuth') == false;
});
array_map(function ($controller) {
    if (method_exists($controller, 'routeName'))
        Route::Resource($controller::routeName(), $controller);
}, $controllers);

Route::group([
    'prefix' => 'auth',
    'middleware' => 'web',
    'as' => 'auth.'
], function () {
    $auth_routes = ['login', 'logout'];
    foreach ($auth_routes as $auth_route) {
        Route::post("/" . $auth_route, [AuthController::class, $auth_route])->name($auth_route);
        // Route::post("/" . $auth_route, function () {
        //     dd("Welcome");
        // })->name($auth_route);
    }
    Route::get("profile", [AuthController::class, 'profile']);
});
