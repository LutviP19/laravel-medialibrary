<?php

use Illuminate\Support\Facades\Route;
use App\Jobs\ProcessRabbitMQMessage;
use App\Livewire\Pages\Chats;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/send-message', function () {
    ProcessRabbitMQMessage::dispatch('Test message');
    
    return 'Message sent to RabbitMQ!';
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/chats', function () {
        return view('livewire.pages.chats');
    })->name('chats');

    Route::get('/admin-notification', function () {
        return view('livewire.pages.admin-notification');
    })->name('admin-notification');

    // Volt::route('/counter', 'counter');
});
