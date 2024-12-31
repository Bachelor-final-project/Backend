<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class Controller
{
    public $user;
    protected $pagination = 15;
    public function __construct(Request $request)
    {
        if (auth('web')->user())
            $this->user = auth('web')->user();
    }

    public function getClassControllerName()
    {
        return Str::replaceLast('Controller', '', class_basename(static::class));
    }

    public function getModelInstance()
    {
        // Get the controller name (e.g., "UserController")
        $controllerName = $this->getClassControllerName();

        // Resolve the model class dynamically (e.g., "App\Models\User")
        $modelClass = "App\\Models\\" . $controllerName;

        // Return a new instance of the model
        if (class_exists($modelClass)) {
            return new $modelClass;
        }
        return null;
        // throw new \Exception("Model {$modelClass} not found.");
    }
}
