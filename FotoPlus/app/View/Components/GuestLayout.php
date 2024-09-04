<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    /**
     * Obtenha a visualização/conteúdo que representa o componente.
     */
    public function render(): View
    {
        return view('layouts.guest');
    }
}
