<?php

declare(strict_types=1);

namespace App\Livewire\Rooms;

use App\Models\Room;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

#[On('room-created')]
class Index extends Component
{
    public $createRoom = false;

    public function render(): View
    {
        return view('livewire.rooms.index', [
            'rooms' => Room::query()
                ->whereRelation('users', 'users.id', auth()->id())
                ->latest()
                ->get(),
        ]);
    }
}
