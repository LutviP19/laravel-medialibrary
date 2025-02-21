<?php

namespace App\Livewire\Pages;

use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Chats')]
class Chats extends Component
{
    public function render(): View
    {
        return view('livewire.pages.chats');
    }
}
