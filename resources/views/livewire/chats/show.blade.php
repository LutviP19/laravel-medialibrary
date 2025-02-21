<div @class([
'flex items-center space-x-2',
'justify-end' => $isCurrentUser,
])>
    @if (! $isCurrentUser)
        <figure class="flex flex-shrink-0 self-start">
            <img src="{{ $chat->user->profile }}" alt="{{ $chat->user->name }}"
                class="h-8 w-8 object-cover rounded-full">
        </figure>
    @endif
    <div class="flex items-center justify-center space-x-2">
        <div class="block">
            <div class="w-auto rounded-xl px-2 pb-2">
                <div @class([
                    'font-medium text-gray-800 dark:text-gray-100',
                    'text-right' => $isCurrentUser,
                ])>
                    <small class="text-sm sm:text-md">
                        {{ $isCurrentUser ? 'You' : $chat->user->name }}
                    </small>
                </div>
                <div @class([
                    'text-xs sm:text-sm bg-slate-100 dark:bg-gray-700 dark:text-gray-400 p-2',
                    'rounded-tl-3xl rounded-bl-3xl rounded-br-xl' => $isCurrentUser,
                    'rounded-tr-3xl rounded-br-3xl rounded-bl-xl' => !$isCurrentUser,
                ])>
                    <p>
                        {{ $chat->message }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    @if ($isCurrentUser)
        <figure class="flex flex-shrink-0 self-start">
            <img src="{{ $chat->user->profile }}" alt="{{ $chat->user->name }}"
                class="h-8 w-8 object-cover rounded-full">
        </figure>
    @endif
</div>
