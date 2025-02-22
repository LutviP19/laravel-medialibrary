<?php

use Illuminate\Support\Facades\Broadcast;
use App\Broadcasting\TestingChannel;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


// Broadcasting Channels
Broadcast::channel('testings', TestingChannel::class);
