<?php

namespace App\Livewire;

use Livewire\Component;

class PreviewLampiran extends Component
{
    public $record;
    
    public function render()
    {
        return view('livewire.preview-lampiran');
    }
}
