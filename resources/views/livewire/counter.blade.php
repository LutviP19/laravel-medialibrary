<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Rule;

new class extends Component {
    public $count = 0;

    public function zero()
    {
        $this->count = 0;
    }

    public function increment()
    {
        $this->count++;
    }
}; 
?>

<div class="py-12">
    <div class="max-w-7xl mx-auto">
        <h1 class="mb-5 text-2xl font-medium text-gray-900 dark:text-white">Hit: {{ $count }}</h1>

        <button class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 transition ease-in-out duration-150" wire:click="zero">Reset</button>

        <button class="inline-flex ml-3 items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 transition ease-in-out duration-150" wire:click="increment">+</button>                    
    </div>
</div>
