<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Table extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public string $modelClass, public $headers)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        // return view('components.table');
    }
}
