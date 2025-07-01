<?php

namespace App\Models;

use App\Enums\ProjectRole;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property int|null $current_project_id
 * @property string $two_factor_recovery_codes
 * @property string $two_factor_secret
 * @property Carbon|null $two_factor_confirmed_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection<int, Project> $ownedProjects
 * @property Collection<int, Project> $invitedProjects
 */
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'current_project_id',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'two_factor_confirmed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function currentProject(): Project
    {
        /** @var ?Project $project */
        $project = Project::query()->find($this->current_project_id);

        if (! $project || (! $this->isOwnerOfProject($project) && ! $this->isUserOfProject($project))) {
            $project = $this->ownedProjects()->first();
        }

        if (! $project) {
            $project = new Project([
                'name' => 'Default Project',
                'owner_id' => $this->id,
            ]);
            $project->save();
        }

        /** @var Project $project */
        if ($this->current_project_id !== $project->id) {
            $this->current_project_id = $project->id;
            $this->save();
        }

        return $project;
    }

    /**
     * @return HasMany<Project, covariant $this>
     */
    public function ownedProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'owner_id');
    }

    /**
     * @return Builder<Project>
     */
    public function allProjects(): Builder
    {
        return Project::query()
            ->where('owner_id', $this->id)
            ->orWhereHas('users', fn (Builder $q) => $q->where('user_id', $this->id));
    }

    public function isUserOfProject(?Project $project): bool
    {
        return $project && $project->users()->where('user_id', $this->id)->exists();
    }

    public function isOwnerOfProject(?Project $project): bool
    {
        return $project?->owner_id === $this->id;
    }

    /**
     * @param  array<int, ProjectRole>  $roles
     */
    public function hasRolesInProject(Project $project, array $roles): bool
    {
        if ($this->isOwnerOfProject($project)) {
            return true;
        }

        return $project->users()
            ->where('user_id', $this->id)
            ->whereIn('role', $roles)
            ->exists();
    }
}
