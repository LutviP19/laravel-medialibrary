<?php

namespace App\Livewire\Chats;

use App\Models\Room;
use App\Models\Chat;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

#[On('chat:created')]
class Index extends Component
{
    #[Locked]
    #[Url]
    public ?int $roomId = null;

    #[Computed]
    public function room(): ?Room
    {
        return $this->roomId === null ? null : Room::query()
            ->whereRelation('users', 'users.id', auth()->id())
            ->find($this->roomId);
    }

    #[On('room-selected')]
    public function selectRoom(int $id): void
    {
        $this->roomId = $id;
    }

    public function render(): View
    {
        // add 10 chats to the database with the factory        
        return view('livewire.chats.index', [
            'room' => $this->room,
            'chats' => $this->room !== null ? $this->room->chats()->orderBy('updated_at', 'asc')->get() : [],
        ]);
    }
}
