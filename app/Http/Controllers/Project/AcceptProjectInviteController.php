<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectUser;
use Illuminate\Http\RedirectResponse;

class AcceptProjectInviteController extends Controller
{
    public function __invoke(Project $project): RedirectResponse
    {
        /** @var ?ProjectUser $user */
        $user = $project->users()->where('email', user()->email)->first();
        if (! $user) {
            abort(404);
        }

        $user->email = null;
        $user->user_id = user()->id;
        $user->save();

        return redirect()->route('projects.index')->with('success', __('You joined the project successfully.'));
    }
}
