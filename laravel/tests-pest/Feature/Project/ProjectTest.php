<?php

use App\Enums\ProjectRole;
use App\Models\Project;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('user can see projects and roles', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $this->actingAs($user);

    $project = $user->currentProject();

    /** @var User $otherUser */
    $otherUser = User::factory()->create();
    $otherProject = $otherUser->currentProject();
    $otherProject->users()->create([
        'user_id' => $user->id,
        'role' => ProjectRole::ADMIN->value,
    ]);

    $this->get(route('projects.index'))
        ->assertSuccessful()
        ->assertInertia(fn (AssertableInertia $page) => $page->component('projects/index')
            ->where('projects.data.0.id', $project->id)
            ->where('projects.data.0.role', ProjectRole::OWNER->value)
            ->where('projects.data.1.id', $otherProject->id)
            ->where('projects.data.1.role', ProjectRole::ADMIN->value)
        );
});

test('user has current project', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get(route('dashboard'))->assertSuccessful();

    $this->assertDatabaseHas('projects', [
        'owner_id' => $user->id,
        'name' => 'Default Project',
    ]);
});

test('make invited project as current if user doesnt have any', function () {
    /** @var User $user */
    $user = User::factory()->create();

    // Ensure the user a current project
    $project = $user->currentProject();

    $this->actingAs($user);

    /** @var User $otherUser */
    $otherUser = User::factory()->create();
    $otherProject = $otherUser->currentProject();

    $user->current_project_id = $otherProject->id;
    $user->save();

    $this->get(route('dashboard'))->assertSuccessful();

    expect($user->refresh()->current_project_id)->toEqual($project->id);
});

test('user can create project', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post(route('projects.store'), [
        'name' => 'New Project',
    ])
        ->assertSessionDoesntHaveErrors()
        ->assertRedirect(route('projects.index'));

    $this->assertDatabaseHas('projects', [
        'owner_id' => $user->id,
        'name' => 'New Project',
    ]);
});

test('user can view projects', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get(route('projects.index'))
        ->assertSuccessful();
});

test('user can switch project', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $this->actingAs($user);

    /** @var Project $project */
    $project = $user->ownedProjects()->create(['name' => 'Test Project']);

    $this->put(route('projects.switch', ['project' => $project->id]))
        ->assertSessionDoesntHaveErrors()
        ->assertRedirect(route('dashboard'));

    expect($user->refresh()->current_project_id)->toEqual($project->id);
});

test('user can update project', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $this->actingAs($user);

    /** @var Project $project */
    $project = $user->ownedProjects()->create(['name' => 'Old Project Name']);

    $this
        ->from(route('projects.index'))
        ->put(route('projects.update', ['project' => $project->id]), [
            'name' => 'Updated Project Name',
        ])
        ->assertSessionDoesntHaveErrors()
        ->assertRedirect(route('projects.index'));

    $this->assertDatabaseHas('projects', [
        'id' => $project->id,
        'name' => 'Updated Project Name',
    ]);
});

test('project update name must be unique', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $this->actingAs($user);

    $user->currentProject()->update(['name' => 'Default Project']);

    /** @var Project $project */
    $project = $user->ownedProjects()->create(['name' => 'Old Project Name']);

    $this
        ->from(route('projects.index'))
        ->put(route('projects.update', ['project' => $project->id]), [
            'name' => 'Default Project',
        ])
        ->assertSessionHasErrors('name');
});

test('user can delete project', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $this->actingAs($user);

    // make sure the user has a current project
    $user->currentProject();

    /** @var Project $project */
    $project = $user->ownedProjects()->create(['name' => 'Project to Delete']);

    $this->delete(route('projects.destroy', ['project' => $project->id]), [
        'name' => 'Project to Delete',
    ])
        ->assertSessionDoesntHaveErrors()
        ->assertRedirect(route('projects.index'));

    $this->assertDatabaseMissing('projects', [
        'id' => $project->id,
    ]);
});

test('user cannot delete default project', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $this->actingAs($user);

    $project = $user->currentProject();

    $user->ownedProjects()->create(['name' => 'last project']);

    $this->delete(route('projects.destroy', ['project' => $project]), [
        'name' => $project->name,
    ])
        ->assertSessionHasErrors([
            'name' => 'Cannot delete your current project.',
        ]);

    $this->assertDatabaseHas('projects', [
        'id' => $project->id,
    ]);
});

test('user cannot delete last project', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $this->actingAs($user);

    /** @var Project $project */
    $project = $user->ownedProjects()->create(['name' => 'Project to Delete']);

    $this->delete(route('projects.destroy', ['project' => $project]))
        ->assertSessionHasErrors('name');

    $this->assertDatabaseHas('projects', [
        'id' => $user->currentProject()->id,
    ]);
});

test('project deletion validation fails', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $this->actingAs($user);

    /** @var Project $project */
    $project = $user->ownedProjects()->create(['name' => 'Project to Delete']);

    $this->delete(route('projects.destroy', ['project' => $project->id]), [
        'name' => 'wrong-name',
    ])
        ->assertSessionHasErrors('name');

    $this->assertDatabaseHas('projects', [
        'id' => $project->id,
    ]);
});

test('cannot delete not owned project', function () {
    /** @var User $owner */
    $owner = User::factory()->create();
    $ownerProject = $owner->currentProject();

    /** @var User $user */
    $this->actingAs($user = User::factory()->create());

    $ownerProject->users()->create([
        'user_id' => $user->id,
        'role' => ProjectRole::ADMIN->value,
    ]);

    $this->delete(route('projects.destroy', ['project' => $ownerProject]), [
        'name' => $ownerProject->name,
    ])
        ->assertForbidden();
});

test('can edit project as admin role', function () {
    /** @var User $owner */
    $owner = User::factory()->create();
    $ownerProject = $owner->currentProject();

    /** @var User $user */
    $this->actingAs($user = User::factory()->create());

    $ownerProject->users()->create([
        'user_id' => $user->id,
        'role' => ProjectRole::ADMIN->value,
    ]);

    $this
        ->from(route('projects.index'))
        ->put(route('projects.update', ['project' => $ownerProject]), [
            'name' => 'new-name',
        ])
        ->assertSessionDoesntHaveErrors()
        ->assertRedirect(route('projects.index'));

    $this->assertDatabaseHas('projects', [
        'id' => $ownerProject->id,
        'name' => 'new-name',
    ]);
});

test('cannot edit project as viewer role', function () {
    /** @var User $owner */
    $owner = User::factory()->create();
    $ownerProject = $owner->currentProject();

    /** @var User $user */
    $this->actingAs($user = User::factory()->create());

    $ownerProject->users()->create([
        'user_id' => $user->id,
        'role' => ProjectRole::VIEWER->value,
    ]);

    $this
        ->from(route('projects.index'))
        ->put(route('projects.update', ['project' => $ownerProject]), [
            'name' => 'new-name',
        ])
        ->assertForbidden();
});
