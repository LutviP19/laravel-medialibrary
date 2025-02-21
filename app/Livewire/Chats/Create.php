<?php

namespace App\Livewire\Chats;

use App\Models\Room;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    #[Locked]
    #[Reactive]
    public ?int $roomId = null;

    #[Validate('required|string')]
    public string $message;

    public function create(): void
    {
        $this->validate();

        $room = Room::query()->findOrFail($this->roomId);

        $memberExists = $room->users()
            ->where('user_id', auth()->id())
            ->exists();

        Gate::denyIf(! $memberExists, 'You are not a member of this room.');

        $room->chats()->create([
            'user_id' => auth()->id(),
            'message' => $this->pull('message'),
        ]);

        $this->dispatch('chat:created');
    }

    public function render(): View
    {
        return view('livewire.chats.create');
    }
}
