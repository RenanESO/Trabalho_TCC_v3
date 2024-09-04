<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public function mount()
    {
        // Reseta a session referente ao caminho da pasta selecionada no Google Drive para vazio.
        session()->put('caminhoPastaGoogleDrive',  '');
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
