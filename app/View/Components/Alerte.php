<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alerte extends Component
{
    public $message;
    public $type;
    public $icon;

    /**
     * Create a new component instance.
     */
    public function __construct($message = null, $type = 'success', $icon = null)
    {
        $this->message = $message;
        $this->type = $type;
        $this->icon = $icon;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.alerte');
    }
}
