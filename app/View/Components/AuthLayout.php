<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AuthLayout extends Component
{
    public int $size;

    /** URL d'une image de couverture : affiche une mise en page en deux colonnes (image / formulaire). */
    public ?string $cover;

    public function __construct(int $size = 3, ?string $cover = null)
    {
        $this->size = $size;
        $this->cover = $cover;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.auth');
    }
}
