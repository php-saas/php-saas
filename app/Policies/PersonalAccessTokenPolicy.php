<?php

namespace App\Policies;

use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PersonalAccessTokenPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function delete(User $user, PersonalAccessToken $personalAccessToken): bool
    {
        return $user->id === $personalAccessToken->tokenable_id && $personalAccessToken->tokenable_type === User::class;
    }
}
