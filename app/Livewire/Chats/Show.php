<?php

declare(strict_types=1);
namespace App\Livewire\Chats;

use App\Models\User;
use App\Models\Chat;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    public Chat $chat;

    public function render(): View
    {
        //dd($this->chat->append('user')->orderBy('updated_at', 'desc')->first());
        // dd($this->chat->user->profile);
        // dd($this->chat->user);
        //dd(Chat::orderBy('updated_at', 'asc')->first());

        return view('livewire.chats.show', [
            'chat' => Chat::orderBy('updated_at', 'asc')->get(),
            'isCurrentUser' => $this->chat->user->id === auth()->id(),
        ]);
    }
}
