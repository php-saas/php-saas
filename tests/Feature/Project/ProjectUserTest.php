<?php

namespace Tests\Feature\Project;

use App\Enums\ProjectRole;
use App\Mail\ProjectInvitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ProjectUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_invite_others(): void
    {
        Mail::fake();

        /** @var User $user */
        $this->actingAs($user = User::factory()->create());

        // make sure the user has default project
        $project = $user->currentProject();

        $this
            ->from(route('projects.index'))
            ->post(route('projects.users.store', ['project' => $project]), [
                'email' => 'new-user@example.com',
                'role' => ProjectRole::ADMIN->value,
            ])
            ->assertRedirect(route('projects.index'))
            ->assertSessionDoesntHaveErrors()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('project_user', [
            'project_id' => $project->id,
            'email' => 'new-user@example.com',
        ]);

        Mail::assertSent(ProjectInvitation::class);
    }

    public function test_can_remove_registered_user_from_project(): void
    {
        /** @var User $user */
        $this->actingAs($user = User::factory()->create());

        $project = $user->currentProject();

        /** @var User $newUser */
        $newUser = User::factory()->create();

        $project->users()->create([
            'project_id' => $project->id,
            'user_id' => $newUser->id,
            'role' => ProjectRole::VIEWER->value,
        ]);

        $this
            ->from(route('projects.index'))
            ->delete(route('projects.users.destroy', ['project' => $project, 'email' => $newUser->email]))
            ->assertRedirect(route('projects.index'))
            ->assertSessionDoesntHaveErrors()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('project_user', [
            'project_id' => $project->id,
            'user_id' => $newUser->id,
        ]);
    }

    public function test_can_remove_owner_from_project(): void
    {
        /** @var User $user */
        $this->actingAs($user = User::factory()->create());

        $project = $user->currentProject();

        $this
            ->from(route('projects.index'))
            ->delete(route('projects.users.destroy', ['project' => $project, 'email' => $user->email]))
            ->assertSessionHas([
                'error' => __('You cannot remove the project owner.'),
            ]);
    }

    public function test_can_remove_invited_user_from_project(): void
    {
        /** @var User $user */
        $this->actingAs($user = User::factory()->create());

        $project = $user->currentProject();

        $project->users()->create([
            'project_id' => $project->id,
            'email' => 'new-user@example.com',
            'role' => ProjectRole::VIEWER->value,
        ]);

        $this
            ->from(route('projects.index'))
            ->delete(route('projects.users.destroy', ['project' => $project, 'email' => 'new-user@example.com']))
            ->assertRedirect(route('projects.index'))
            ->assertSessionDoesntHaveErrors()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('project_user', [
            'project_id' => $project->id,
            'email' => 'new-user@example.com',
        ]);
    }

    public function test_user_can_accept_invitation(): void
    {
        /** @var User $owner */
        $owner = User::factory()->create();
        $ownerProject = $owner->currentProject();

        /** @var User $user */
        $this->actingAs($user = User::factory()->create());

        $ownerProject->users()->create([
            'email' => $user->email,
            'role' => ProjectRole::VIEWER->value,
        ]);

        $this
            ->from(route('projects.index'))
            ->get(route('projects.invitations.accept', ['project' => $ownerProject]))
            ->assertRedirect(route('projects.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('project_user', [
            'project_id' => $ownerProject->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_user_cannot_join_without_invitation(): void
    {
        /** @var User $owner */
        $owner = User::factory()->create();
        $ownerProject = $owner->currentProject();

        /** @var User $user */
        $this->actingAs($user = User::factory()->create());

        $this
            ->from(route('projects.index'))
            ->get(route('projects.invitations.accept', ['project' => $ownerProject]))
            ->assertNotFound();

        $this->assertDatabaseMissing('project_user', [
            'project_id' => $ownerProject->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_user_can_leave_project(): void
    {
        /** @var User $owner */
        $owner = User::factory()->create();
        $ownerProject = $owner->currentProject();

        /** @var User $user */
        $this->actingAs($user = User::factory()->create());

        $ownerProject->users()->create([
            'email' => $user->email,
            'role' => ProjectRole::VIEWER->value,
        ]);

        $this
            ->from(route('projects.index'))
            ->delete(route('projects.leave', ['project' => $ownerProject]))
            ->assertRedirect(route('projects.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('project_user', [
            'project_id' => $ownerProject->id,
            'email' => $user->email,
        ]);
    }

    public function test_user_can_leave_project_that_is_not_invited(): void
    {
        /** @var User $owner */
        $owner = User::factory()->create();
        $ownerProject = $owner->currentProject();

        /** @var User $user */
        $this->actingAs($user = User::factory()->create());

        $this
            ->from(route('projects.index'))
            ->delete(route('projects.leave', ['project' => $ownerProject]))
            ->assertNotFound();
    }
}
