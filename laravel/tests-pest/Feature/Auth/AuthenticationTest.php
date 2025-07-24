<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;

uses(RefreshDatabase::class);

test('login screen can be rendered', function () {
    $response = $this->get(route('login'));

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users can not authenticate with invalid password', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $this->assertGuest();
    $response->assertRedirect(route('login'));
});

test('can login using social account', function () {
    config()->set('services.github', [
        'client_id' => 'test-client-id',
        'client_secret' => 'test-client',
        'redirect' => 'http://localhost/auth/github/callback',
    ]);

    $this->get(route('auth.redirect', ['provider' => 'github']))
        ->assertRedirectContains('https://github.com/login/oauth/authorize');
});

test('can login using social account with invalid provider', function () {
    $this->get(route('auth.redirect', ['provider' => 'invalid']))
        ->assertNotFound();
});

test('social auth callback', function () {
    config()->set('services.github', [
        'client_id' => 'test-client-id',
        'client_secret' => 'test-client',
        'redirect' => 'http://localhost/auth/github/callback',
    ]);

    $abstractUser = Mockery::mock('Laravel\Socialite\Contracts\User');
    $abstractUser->shouldReceive('getId')->andReturn('123456');
    $abstractUser->shouldReceive('getName')->andReturn('John Doe');
    $abstractUser->shouldReceive('getEmail')->andReturn('john@example.com');

    // Mock Socialite to return the fake user
    Socialite::shouldReceive('driver->user')
        ->once()
        ->andReturn($abstractUser);

    $response = $this->get(route('auth.callback', ['provider' => 'github']));

    $response->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();

    $this->assertDatabaseHas('users', [
        'email' => 'john@example.com',
        'name' => 'John Doe',
    ]);
});

test('social auth callback user exists but not verified', function () {
    /** @var User $user */
    $user = User::factory()->create([
        'email' => 'john@example.com',
        'email_verified_at' => null,
    ]);

    config()->set('services.github', [
        'client_id' => 'test-client-id',
        'client_secret' => 'test-client',
        'redirect' => 'http://localhost/auth/github/callback',
    ]);

    $abstractUser = Mockery::mock('Laravel\Socialite\Contracts\User');
    $abstractUser->shouldReceive('getId')->andReturn('123456');
    $abstractUser->shouldReceive('getName')->andReturn('John Doe');
    $abstractUser->shouldReceive('getEmail')->andReturn('john@example.com');

    // Mock Socialite to return the fake user
    Socialite::shouldReceive('driver->user')
        ->once()
        ->andReturn($abstractUser);

    $response = $this->get(route('auth.callback', ['provider' => 'github']));

    $response->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();

    $this->assertDatabaseHas('users', [
        'email' => 'john@example.com',
        'name' => $user->name,
    ]);

    expect($user->fresh()->email_verified_at)->not->toBeNull();
});

test('social auth callback with two factor', function () {
    User::factory()->create([
        'email' => 'john@example.com',
        'two_factor_secret' => 'secret',
        'two_factor_confirmed_at' => now(),
    ]);

    config()->set('services.github', [
        'client_id' => 'test-client-id',
        'client_secret' => 'test-client',
        'redirect' => 'http://localhost/auth/github/callback',
    ]);

    $abstractUser = Mockery::mock('Laravel\Socialite\Contracts\User');
    $abstractUser->shouldReceive('getId')->andReturn('123456');
    $abstractUser->shouldReceive('getName')->andReturn('John Doe');
    $abstractUser->shouldReceive('getEmail')->andReturn('john@example.com');

    Socialite::shouldReceive('driver->user')
        ->once()
        ->andReturn($abstractUser);

    $response = $this->get(route('auth.callback', ['provider' => 'github']));

    $response->assertRedirect(route('two-factor.login'));
});

test('social callback wrong provider', function () {
    $this->get(route('auth.callback', ['provider' => 'invalid']))
        ->assertNotFound();
});
