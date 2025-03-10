<?php

use Illuminate\Support\Facades\Broadcast;
use App\Broadcasting\TestingChannel;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Testing Group
Broadcast::channel('App.Models.UserTeam.{id}', function ($user, $team_id) {
    return (int) $user->current_team_id === (int) $team_id;
});

// Broadcasting Channels
Broadcast::channel('testings', TestingChannel::class);
