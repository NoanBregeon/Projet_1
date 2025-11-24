<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Carte extends Component
{
    public int $id;

    public string $name;

    public string $gradientHeader;

    public ?string $imageUrl;

    public string $cardImageHeight;

    public string $gradientBackground;

    public string $description;

    public string $primaryColor;

    public string $secondaryColor;

    public string $colorIndicatorSize;

    public string $colorTooltipPrimary;

    public string $colorTooltipSecondary;

    public ?string $logoUrl;

    public string $logoSize;

    /**
     * Create a new component instance.
     */
    public function __construct(
        int $id,
        string $name,
        string $gradientHeader,
        ?string $imageUrl,
        string $cardImageHeight,
        string $gradientBackground,
        string $description,
        string $primaryColor,
        string $secondaryColor,
        string $colorIndicatorSize,
        string $colorTooltipPrimary,
        string $colorTooltipSecondary,
        ?string $logoUrl,
        string $logoSize
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
