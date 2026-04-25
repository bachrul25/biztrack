<?php

namespace App\Livewire;

use Livewire\Component;

class HomePageComponent extends Component
{
    public function render()
    {
        return view('livewire.home-page-component')
            ->layout('layouts.app', ['title' => 'Home - PT BOBA']);
    }
}
