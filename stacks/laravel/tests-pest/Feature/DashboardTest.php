<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests are redirected to the login page', function () {
    $this->get(route('dashboard'))->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    /** @var User $user */
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->get(route('dashboard'))->assertOk();
});

test('cannot see dashboard if email is not verified', function () {
    /** @var User $user */
    $user = User::factory()->create(['email_verified_at' => null]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route('verification.notice'));
});
