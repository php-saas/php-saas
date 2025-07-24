<?php

use App\Enums\ProjectRole;
use App\Mail\ProjectInvitation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('user can invite others', function () {
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
});

test('can remove registered user from project', function () {
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
});

test('can remove owner from project', function () {
    /** @var User $user */
    $this->actingAs($user = User::factory()->create());

    $project = $user->currentProject();

    $this
        ->from(route('projects.index'))
        ->delete(route('projects.users.destroy', ['project' => $project, 'email' => $user->email]))
        ->assertSessionHas([
            'error' => __('You cannot remove the project owner.'),
        ]);
});

test('can remove invited user from project', function () {
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
});

test('user can accept invitation', function () {
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
});

test('user cannot join without invitation', function () {
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
});

test('user can leave project', function () {
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
});

test('user can leave project that is not invited', function () {
    /** @var User $owner */
    $owner = User::factory()->create();
    $ownerProject = $owner->currentProject();

    /** @var User $user */
    $this->actingAs($user = User::factory()->create());

    $this
        ->from(route('projects.index'))
        ->delete(route('projects.leave', ['project' => $ownerProject]))
        ->assertNotFound();
});
