<?php

use App\Models\User;

function user(): User
{
    /** @var User $user */
    $user = auth()->user();

    return $user;
}
