<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }

    public function test_can_login_using_social_account(): void
    {
        config()->set('services.github', [
            'client_id' => 'test-client-id',
            'client_secret' => 'test-client',
            'redirect' => 'http://localhost/auth/github/callback',
        ]);

        $this->get(route('auth.redirect', ['provider' => 'github']))
            ->assertRedirectContains('https://github.com/login/oauth/authorize');
    }

    public function test_can_login_using_social_account_with_invalid_provider(): void
    {
        $this->get(route('auth.redirect', ['provider' => 'invalid']))
            ->assertNotFound();
    }

    public function test_social_auth_callback(): void
    {
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
    }

    public function test_social_auth_callback_user_exists_but_not_verified(): void
    {
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

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_social_auth_callback_with_two_factor(): void
    {
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
    }

    public function test_social_callback_wrong_provider(): void
    {
        $this->get(route('auth.callback', ['provider' => 'invalid']))
            ->assertNotFound();
    }
}
