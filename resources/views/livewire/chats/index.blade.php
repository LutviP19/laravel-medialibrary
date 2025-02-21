<div class="bg-white dark:bg-gray-800 w-3/4">
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                @if ($room !== null)
                    <figure
                        class="rounded h-10 w-10 flex-shrink-0 transition-opacity group-hover:opacity-90 {{ $room->user->profile }}">
                        <img src="{{ $room->user->profile }}" alt="{{ $room->user->name }}" class="rounded h-10 w-10" />
                    </figure>
                    <p class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $room->name }}</p>
                @else
                    <p class="text-xl font-bold text-gray-800 dark:text-gray-100">
                        Please select room.
                    </p>
                @endif
            </div>
        </div>
        <div
            x-data="{ scroll: () => { $el.scrollTo(0, $el.scrollHeight); }}"
            x-intersect="scroll()"
            wire:poll.5s
            class="mt-4 h-[calc(100vh-210px)] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100 flex flex-col-reverse">
            <div class="flex flex-col gap-4">
                @forelse ($chats as $chat)
                    <livewire:chats.show :chat="$chat" :key="$chat->id" />
                @empty
                    <div class="bg-white dark:bg-gray-700 shadow-md rounded-lg py-4">
                        <div class="flex items-center gap-3 px-4">
                            <p class="text-gray-500 dark:text-gray-400">No chats found</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
        <livewire:chats.create :$roomId/>
    </div>
</div>
