<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('password can be updated', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('profile.index'))
        ->put(route('user-password.update'), [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertSessionDoesntHaveErrors()
        ->assertRedirect(route('profile.index'));

    expect(Hash::check('new-password', $user->refresh()->password))->toBeTrue();
});

test('correct password must be provided to update password', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('profile.index'))
        ->put(route('user-password.update'), [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertSessionHasErrors('current_password', errorBag: 'updatePassword')
        ->assertRedirect(route('profile.index'));
});
