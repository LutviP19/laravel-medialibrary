<aside class="bg-white dark:bg-gray-800 w-1/4">
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Chat Rooms</h1>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-2 rounded"
                wire:click="$set('createRoom', true)"
                title="Create Room"
            >
                <x-icons.add class="h-6 w-6" />
            </button>
            <x-dialog-modal wire:model.live="createRoom">
                <x-slot name="title">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
                        {{ __('Create Room') }}
                        </h2>
                        <button wire:click="$set('createRoom', false)"
                            class="text-gray-500 dark:text-gray-400">
                            <x-icons.x class="h-6 w-6" />
                        </button>
                    </div>
                </x-slot>
            
                <x-slot name="content">
                    <div class="p-4">
                        <livewire:rooms.create />
                    </div>
                </x-slot>
                <x-slot name="footer">
                </x-slot>
            </x-dialog-modal>
        </div>

        <div
            class="mt-4 h-[calc(100vh-155px)] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            <div class="flex flex-col gap-4">
                @forelse ($rooms as $room)
                    <div class="bg-white dark:bg-gray-700 shadow-md rounded-lg py-4 cursor-pointer"
                        x-on:click="$dispatch('room-selected', { id: {{ $room->id }} })">
                        <div class="group flex items-center gap-3 px-4">
                            <figure
                                class="rounded h-10 w-10 flex-shrink-0 transition-opacity group-hover:opacity-90 {{ $room->user->profile }}">
                                <img src="{{ $room->user->profile }}" alt="{{ $room->user->name }}"
                                    class="rounded h-10 w-10" />
                            </figure>

                            <div class="overflow-hidden text-sm dark:text-gray-100">
                                <div class="flex items-center gap-2 justify-between">
                                    <p class="truncate font-medium" title="{{ $room->name }}">
                                        {{ $room->name }}
                                    </p>

                                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-auto">
                                        {{ $room->created_at->diffForHumans(short: true) }}
                                    </span>
                                </div>
                                <p class="truncate text-gray-500 dark:text-gray-400">
                                    last message
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-700 shadow-md rounded-lg py-4">
                        <div class="flex items-center gap-3 px-4">
                            <p class="text-gray-500 dark:text-gray-400">No rooms found</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</aside>
