<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AuthLayout extends Component
{
    public int $size;

    /** URL d'une image de couverture : affiche une mise en page en deux colonnes (image / formulaire). */
    public ?string $cover;

    /** Carte du formulaire plus large (utile pour les formulaires multi-colonnes). */
    public bool $wide;

    public function __construct(int $size = 3, ?string $cover = null, bool $wide = false)
    {
        $this->size = $size;
        $this->cover = $cover;
        $this->wide = $wide;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.auth');
    }
}
