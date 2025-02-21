@php
    $messageError = $errors->has('message') ? 'dark:border-2 dark:border-red-500' : 'border-gray-300';
@endphp
<div class="mt-4">
    <form wire:submit="create">
        <div class="flex items-center gap-3">
            <x-text-input wire:model.live="message"
                id="message"
                name="message"
                type="text"
                required
                autofocus
                class="w-full rounded-lg px-4 py-2  {{ $messageError }}"
            />

            <button type="submit"
                x-data=""
                class="text-white font-bold bg-blue-500 py-2 px-4 rounded disabled:cursor-not-allowed"
                :class="$wire.message !== null && $wire.message.length === 0 ? 'disabled:opacity-50' : '' "
                :disabled="$wire.message !== null && $wire.message.length === 0"
            >
                Send
            </button>
        </div>
    </form>
</div>
