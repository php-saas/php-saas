<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_account(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->delete(route('profile.destroy'), [
            'password' => 'password',
        ])
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('home'));
    }

    public function test_user_cannot_delete_account_with_invalid_password(): void
    {
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
    }
}
