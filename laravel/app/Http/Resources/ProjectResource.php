<?php

namespace App\Http\Resources;

use App\Enums\ProjectRole;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Project */
class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var ?User $user */
        $user = $request->user();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'role' => $user ? $this->role($user)?->value : null,
            'owner' => ProjectUserResource::make(new ProjectUser([
                'project_id' => $this->id,
                'user_id' => $this->owner->id,
                'email' => $this->owner->email,
                'role' => ProjectRole::OWNER,
                'type' => 'user',
            ])),
            'users' => ProjectUserResource::collection($this->whenLoaded('users')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
