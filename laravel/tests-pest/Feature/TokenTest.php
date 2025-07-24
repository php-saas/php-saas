<?php

use App\Models\PersonalAccessToken;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('user can see api tokens', function () {
    /** @var User $user */
    $user = User::factory()->create();
    $this->actingAs($user);

    $user->createToken('test', ['read', 'write']);

    /** @var PersonalAccessToken $token */
    $token = $user->tokens()->first();

    $this->actingAs($user)
        ->get(route('tokens.index'))
        ->assertOk();

    $this->get(route('tokens.index'))
        ->assertSuccessful()
        ->assertInertia(fn (AssertableInertia $page) => $page->component('tokens/index')
            ->where('tokens.data.0.id', $token->id)
            ->where('tokens.data.0.abilities', ['read', 'write'])
        );
});

test('user can create api token', function () {
    /** @var User $user */
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this
        ->from(route('tokens.index'))
        ->post(route('tokens.store'), [
            'name' => 'Test Token',
            'ability' => 'write',
        ]);

    $response->assertRedirect(route('tokens.index'));
    $response->assertSessionHas('success', 'Api key created.');
    $response->assertSessionHas('data');

    $this->assertDatabaseHas('personal_access_tokens', [
        'name' => 'Test Token',
        'abilities' => json_encode(['read', 'write']),
        'tokenable_id' => $user->id,
        'tokenable_type' => User::class,
    ]);
});

test('user can delete api token', function () {
    /** @var User $user */
    $user = User::factory()->create();
    $this->actingAs($user);

    $user->createToken('Test Token', ['read', 'write']);

    /** @var PersonalAccessToken $token */
    $token = $user->tokens()->first();

    $response = $this
        ->from(route('tokens.index'))
        ->delete(route('tokens.destroy', $token));

    $response->assertRedirect(route('tokens.index'));
    $response->assertSessionHas('success', 'Token deleted.');

    $this->assertDatabaseMissing('personal_access_tokens', [
        'id' => $token->id,
        'name' => 'Test Token',
    ]);
});
