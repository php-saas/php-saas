<?php

namespace App\Http\Resources;

use App\Models\ProjectUser;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ProjectUser
 */
class ProjectUserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->user_id,
            'project_id' => $this->project_id,
            'project_name' => $this->project->name ?? null,
            'email' => $this->email ?? $this->user->email,
            'role' => $this->role,
            'type' => $this->user_id ? 'user' : 'invitation',
        ];
    }
}
