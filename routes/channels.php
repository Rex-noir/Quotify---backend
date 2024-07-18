<?php

use App\Models\Post;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('posts', function () {
    return true;
});
Broadcast::channel('comments', function () {
    return true;
});
