<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/tables/', [DashboardController::class, 'index'])->name('tables');
Route::get('/billing/', [DashboardController::class, 'index'])->name('billing');
Route::get('/virtual-reality/', [DashboardController::class, 'index'])->name('virtual-reality');
Route::get('/rtl/', [DashboardController::class, 'index'])->name('rtl');
Route::get('/notifications/', [DashboardController::class, 'index'])->name('notifications');
Route::get('/profile/', [DashboardController::class, 'index'])->name('profile');
Route::get('/static-sign-in/', [DashboardController::class, 'index'])->name('static-sign-in');
Route::get('/static-sign-up/', [DashboardController::class, 'index'])->name('static-sign-up');
// Route::get('/logout/', [DashboardController::class, 'index'])->name('logout');
// Route::get('/login/', [DashboardController::class, 'index'])->name('login');
// Route::get('/register/', [DashboardController::class, 'index'])->name('register');
Route::get('/user-profile/', [ProfileController::class, 'index'])->name('user-profile');
Route::get('/user-management/', [ProfileController::class, 'index'])->name('user-management');


$controllers = [];

foreach (glob(app_path('Http/Controllers/*.php')) as $file) {
    $basename = basename($file, '.php');
    $class = 'App\\Http\\Controllers\\' . $basename;
    if (in_array($basename, ['AuthController.php', 'Controller.php'])) {
        continue;
    }
    if (class_exists($class)) {
        $reflection = new ReflectionClass($class);
        if (!$reflection->isAbstract() && !$reflection->isInterface() && !$reflection->isTrait()) {
            $controllers[] = $class;
        }
    }
}
Route::middleware(['web'])->group(function () use ($controllers) {
    array_map(function ($controller) {
        if (method_exists($controller, 'routeName')) {
            // Artisan::call('make:resource ' . ucfirst(Str::camel($controller::routeName())) . 'Resource ');
            Route::resource($controller::routeName(), $controller);
        }
    }, $controllers);
});

Route::get('/login', [AuthController::class, 'create'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Route::group([
//     'prefix' => 'auth',
//     'middleware' => 'web',
//     'as' => 'auth.'
// ], function () {
//     $auth_routes = ['login', 'logout', 'register'];
//     foreach ($auth_routes as $auth_route) {
//         Route::post("/" . $auth_route, [AuthController::class, $auth_route])->name($auth_route);
//         // Route::post("/" . $auth_route, function () {
//         //     dd("Welcome");
//         // })->name($auth_route);
//     }
//     Route::get("profile", [AuthController::class, 'profile']);
// });


