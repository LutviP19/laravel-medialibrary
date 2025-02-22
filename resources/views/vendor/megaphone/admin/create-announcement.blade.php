<div class="mx-auto rounded p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
    @if(session()->has('megaphone_success'))
        <div class="flex bg-green-100 rounded-lg p-4 mb-4 text-sm text-green-700" role="alert">
            <svg class="w-5 h-5 inline mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
            <div>
                {{ session('megaphone_success') }}
            </div>
        </div>
    @endif

    <form wire:submit.prevent="send">
        <div class="mb-6">
            <label for="type" class="mb-2 block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Type') }}*</label>
            <select id="type" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm
                @error('type') border-red-500 @enderror" wire:model.live="type">
                <option>{{ __('Select Type') }}</option>
                @foreach ($notifTypes as $type => $name)
                    <option value="{{ $type }}">
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-6">
            <label for="title" class="mb-2 block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Title') }}*</label>
            <input type="text" id="title" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm @error('title') border-red-500 @enderror" wire:model.blur="title" >
        </div>

        <div class="mb-6">
            <label for="body" class="mb-2 block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Body') }}*</label>
            <input type="text" id="body" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm @error('body') border-red-500 @enderror" wire:model.blur="body" >
        </div>

        <div class="mb-6">
            <label for="link" class="mb-2 block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Link URL') }}</label>
            <input type="text" id="link" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" wire:model.blur="link" >
        </div>

        <div class="mb-6">
            <label for="linkText" class="mb-2 block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Link Text') }}</label>
            <input type="text" id="linkText" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" wire:model.blur="linkText">
        </div>

        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 transition ease-in-out duration-150">{{ __('Send') }}</button>
    </form>
</div>
