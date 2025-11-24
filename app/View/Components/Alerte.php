<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alerte extends Component
{
    public ?string $message;

    public string $type;

    public ?string $icon;

    public function __construct(?string $message = null, string $type = 'success', ?string $icon = null)
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
