<?php

namespace App\Livewire\Rooms;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    public $createRoom = false;

    #[Validate('required')]
    #[Validate('min:2')]
    #[Validate('max:80')]
    public ?string $name = null;

    /**
     * @var array<array-key, int>
     */
    #[Validate([
        'members' => ['array', 'min:1'],
        'members.*' => [
            'required',
            'exists:users,id',
        ],
    ])]
    public ?array $members = [];

    public function store(): void
    {
        if (auth()->user() === null) {
            $this->redirectRoute('login', navigate: true);

            return;
        }

        /** @var array{members: array<int>, name: string} $validated */
        $validated = $this->validate();

        $room = auth()->user()->rooms()->create([
            'name' => $validated['name'],
        ]);

        $validated['members'][] = auth()->id();

        $room->users()->attach($validated['members']);

        $this->dispatch('room-created');
        $this->dispatch('room-selected', id: $room->id);

        $this->reset();
    }

    public function render(): View
    {
        return view('livewire.rooms.create', [
            'users' => User::query()
                ->where('id', '!=', auth()->id())
                ->pluck('name', 'id'),
        ]);
    }
}
