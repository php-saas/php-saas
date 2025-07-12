<?php

namespace App\Providers;

use App\Http\Resources\ProjectResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class ProjectServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Inertia::share('project_provider', function (Request $request) {
            /** @var ?User $user */
            $user = $request->user();

            if (! $user) {
                return [
                    'current' => null,
                    'list' => [],
                ];
            }

            return [
                'current' => ProjectResource::make($user->currentProject()),
                'list' => ProjectResource::collection($user->allProjects()->get()),
            ];
        });
    }
}
