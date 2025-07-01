<?php

namespace Tests\Feature\Project;

use App\Enums\ProjectRole;
use App\Events\ProjectDeleted;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_projects_and_roles(): void
    {
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
    }

    public function test_user_has_current_project(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->get(route('dashboard'))->assertSuccessful();

        $this->assertDatabaseHas('projects', [
            'owner_id' => $user->id,
            'name' => 'Default Project',
        ]);
    }

    public function test_make_invited_project_as_current_if_user_doesnt_have_any(): void
    {
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

        $this->assertEquals($project->id, $user->refresh()->current_project_id);
    }

    public function test_user_can_create_project(): void
    {
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
    }

    public function test_user_can_view_projects(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->get(route('projects.index'))
            ->assertSuccessful();
    }

    public function test_user_can_switch_project(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->actingAs($user);

        /** @var Project $project */
        $project = $user->ownedProjects()->create(['name' => 'Test Project']);

        $this->put(route('projects.switch', ['project' => $project->id]))
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('dashboard'));

        $this->assertEquals($project->id, $user->refresh()->current_project_id);
    }

    public function test_user_can_update_project(): void
    {
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
    }

    public function test_project_update_name_must_be_unique(): void
    {
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
    }

    public function test_user_can_delete_project(): void
    {
        Event::fake();

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

        Event::assertDispatched(ProjectDeleted::class);
    }

    public function test_user_cannot_delete_default_project(): void
    {
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
    }

    public function test_user_cannot_delete_last_project(): void
    {
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
    }

    public function test_project_deletion_validation_fails(): void
    {
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
    }

    public function test_cannot_delete_not_owned_project(): void
    {
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
    }

    public function test_can_edit_project_as_admin_role(): void
    {
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
    }

    public function test_cannot_edit_project_as_viewer_role(): void
    {
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
    }
}
