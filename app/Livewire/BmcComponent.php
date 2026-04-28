<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class BmcComponent extends Component
{
    public function render()
    {
        return view('livewire.bmc-component');
    }
}
