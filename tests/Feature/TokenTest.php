<?php

namespace Feature;

use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class TokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_api_tokens(): void
    {
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
    }

    public function test_user_can_create_api_token(): void
    {
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
    }

    public function test_user_can_delete_api_token(): void
    {
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
    }
}
