<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectUser;
use Illuminate\Http\RedirectResponse;

class LeaveProjectController extends Controller
{
    public function __invoke(Project $project): RedirectResponse
    {
        /** @var ?ProjectUser $user */
        $user = $project->users()
            ->where('user_id', user()->id)
            ->orWhere('email', user()->email)
            ->first();
        if (! $user) {
            abort(404);
        }

        $user->delete();

        return back()->with('success', __('You left the project successfully.'));
    }
}
