<?php

use Illuminate\Support\Facades\Route;
use App\Jobs\ProcessRabbitMQMessage;
use App\Livewire\Pages\Chats;

Route::get('/', function () {
    return view('welcome');
});

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
});
