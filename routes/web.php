<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
Route::middleware(['web'])->middleware('auth')->group(function () use ($controllers) {
    array_map(function ($controller) {
        if (method_exists($controller, 'routeName')) {
            // Artisan::call('make:resource ' . ucfirst(Str::camel($controller::routeName())) . 'Resource ');
            Route::resource($controller::routeName(), $controller);
        }
        if (method_exists($controller, 'indexApi')) {
            // Artisan::call('make:resource ' . ucfirst(Str::camel($controller::routeName())) . 'Resource ');
            Route::get($controller::routeName() . 'Api', [$controller, 'indexApi'])->name(strtolower($controller::routeName() . ".index.api"));
        }
    }, $controllers);
});


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/change-language/{locale}', function (string $locale) {
    if (! in_array($locale, ['en', 'ar'])) {
        abort(400);
    }
 
    App::setLocale($locale);
 
    // ...
})->name('change-language');

require __DIR__.'/auth.php';
