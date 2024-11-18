<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    // Função construtora da pagina no blade "Dashboard".
    public function mount()
    {
        session()->put('caminhoPastaGoogleDrive',  '');
    }

    public function render()
    {
        return view('livewire.dashboard'); 
    }
}
