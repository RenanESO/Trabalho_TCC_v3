<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLivewireLayout extends Component
{
    /**
     * Obtenha a visualização/conteúdo que representa o componente.
     */
    public function render(): View
    {
        return view('layouts.applivewire');
    }
}