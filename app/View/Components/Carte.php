<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Carte extends Component
{
    public $id, $name, $gradientHeader, $imageUrl, $cardImageHeight, $gradientBackground, $description,
        $primaryColor, $secondaryColor, $colorIndicatorSize, $colorTooltipPrimary, $colorTooltipSecondary,
        $logoUrl, $logoSize;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $id, $name, $gradientHeader, $imageUrl, $cardImageHeight, $gradientBackground, $description,
        $primaryColor, $secondaryColor, $colorIndicatorSize, $colorTooltipPrimary, $colorTooltipSecondary,
        $logoUrl, $logoSize
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->gradientHeader = $gradientHeader;
        $this->imageUrl = $imageUrl;
        $this->cardImageHeight = $cardImageHeight;
        $this->gradientBackground = $gradientBackground;
        $this->description = $description;
        $this->primaryColor = $primaryColor;
        $this->secondaryColor = $secondaryColor;
        $this->colorIndicatorSize = $colorIndicatorSize;
        $this->colorTooltipPrimary = $colorTooltipPrimary;
        $this->colorTooltipSecondary = $colorTooltipSecondary;
        $this->logoUrl = $logoUrl;
        $this->logoSize = $logoSize;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.carte');
    }
}
