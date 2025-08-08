<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('profile page is displayed', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get(route('profile.index'));

    $response->assertOk();
});

test('profile information can be updated', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('profile.index'))
        ->put(route('user-profile-information.update'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionDoesntHaveErrors()
        ->assertRedirect(route('profile.index'));

    $user->refresh();

    expect($user->name)->toBe('Test User');
    expect($user->email)->toBe('test@example.com');
    expect($user->email_verified_at)->toBeNull();
});

test('email verification status is unchanged when the email address is unchanged', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('profile.index'))
        ->put(route('user-profile-information.update'), [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionDoesntHaveErrors()
        ->assertRedirect(route('profile.index'));

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

test('user can delete their account', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete(route('profile.destroy'), [
            'password' => 'password',
        ]);

    $response
        ->assertSessionDoesntHaveErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    expect($user->fresh())->toBeNull();
});

test('correct password must be provided to delete account', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('profile.index'))
        ->delete(route('profile.destroy'), [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrors('password')
        ->assertRedirect(route('profile.index'));

    expect($user->fresh())->not->toBeNull();
});
