<?php

namespace App\Http\Controllers\Project;

use App\Actions\Project\CreateProject;
use App\Actions\Project\DeleteProject;
use App\Actions\Project\UpdateProject;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectUserResource;
use App\Models\Project;
use App\Models\ProjectUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', Project::class);

        return Inertia::render('projects/index', [
            'projects' => ProjectResource::collection(
                user()
                    ->allProjects()
                    ->with(['owner', 'users'])
                    ->simplePaginate(20)
            ),
            'invitations' => ProjectUserResource::collection(
                ProjectUser::query()
                    ->where('email', user()->email)
                    ->whereNull('user_id')
                    ->simplePaginate(20)
            ),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Project::class);

        app(CreateProject::class)->create(user(), $request->input());

        return redirect()->route('projects.index')
            ->with('success', __('Project created successfully.'));
    }

    public function update(Project $project, Request $request): RedirectResponse
    {
        $this->authorize('update', $project);

        app(UpdateProject::class)->update($project, $request->input());

        return back()->with('success', __('Project updated successfully.'));
    }

    public function destroy(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);

        app(DeleteProject::class)->delete(user(), $project, $request->input());

        return redirect()->route('projects.index')
            ->with('success', __('Project deleted successfully.'));
    }
}
