<?php

namespace App\Http\Controllers\Project;

use App\Actions\Project\InviteToProject;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProjectUserController extends Controller
{
    public function store(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        app(InviteToProject::class)->invite($project, $request->all());

        return back()->with('success', __('An invitation has been sent to the email address.'));
    }

    public function destroy(Project $project, string $email): RedirectResponse
    {
        $this->authorize('update', $project);

        /** @var ?User $user */
        $user = User::query()->where('email', $email)->first();

        if ($user && $user->is($project->owner)) {
            return back()->with('error', __('You cannot remove the project owner.'));
        }

        $project->users()
            ->where('user_id', $user?->id)
            ->orWhere('email', $email)
            ->delete();

        return back()->with('success', __('The user has been removed.'));
    }
}
