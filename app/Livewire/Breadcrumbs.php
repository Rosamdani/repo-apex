<?php

namespace App\Livewire;

use Livewire\Component;

class Breadcrumbs extends Component
{
    public $title;
    public $subTitle;

    public function mount($title, $subTitle)
    {
        $this->title = $title;
        $this->subTitle = $subTitle;
    }
    public function render()
    {
        return view('livewire.breadcrumbs');
    }
}
