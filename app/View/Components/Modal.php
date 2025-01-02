<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Modal extends Component
{
    public bool $saveBtn;
    /**
     * Create a new component instance.
     */
    public function __construct($saveBtn = true)
    {
        $this->saveBtn = $saveBtn;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        // return view('components.modal');
    }
}
