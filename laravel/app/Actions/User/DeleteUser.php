<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DeleteUser
{
    public function delete(User $user, array $input): void
    {
        Validator::make($input, [
            'password' => ['required', 'current_password'],
        ])->validate();

        Auth::logout();
        $user->delete();
    }
}
