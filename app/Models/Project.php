<?php

namespace App\Models;

use App\Enums\ProjectRole;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $owner_id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $owner
 * @property Collection<int, User> $users
 */
class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function registeredUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user')
            ->using(ProjectUser::class)
            ->withPivot(['role', 'email'])
            ->withTimestamps();
    }

    public function users(): HasMany
    {
        return $this->hasMany(ProjectUser::class, 'project_id');
    }

    public function role(User $user): ?ProjectRole
    {
        if ($user->id === $this->owner_id) {
            return ProjectRole::OWNER;
        }

        $role = $this->users()->where('user_id', $user->id)->value('role');

        return $role ? ProjectRole::from($role) : null;
    }
}
