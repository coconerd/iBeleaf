<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('admin', function ($user) {
	return $user->role_type == 1; // admin-only
});

// Broadcast::channel('claims', function ($user) {
// 	return $user->role_type == 1; // admin-only
// });