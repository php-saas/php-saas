<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('user can delete account', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->delete(route('profile.destroy'), [
        'password' => 'password',
    ])
        ->assertSessionDoesntHaveErrors()
        ->assertRedirect(route('home'));
});

test('user cannot delete account with invalid password', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $this->actingAs($user);

    $this
        ->from(route('profile.index'))
        ->delete(route('profile.destroy'), [
            'password' => 'wrong-password',
        ])
        ->assertSessionHasErrors(['password'])
        ->assertRedirect(route('profile.index'));
});
