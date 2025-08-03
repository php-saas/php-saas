<?php

namespace App\Models;

use App\Traits\HasProjects;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property ?string $two_factor_recovery_codes
 * @property ?string $two_factor_secret
 * @property Carbon|null $two_factor_confirmed_at
 * @property ?int $current_project_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection<int, PersonalAccessToken> $tokens
 *
 * @method Subscription|null subscription($type = 'default')
 */
class User extends Authenticatable implements MustVerifyEmail
{
    // <php-saas:billing>
    use Billable;

    // </php-saas:billing>
    // <php-saas:tokens>
    use HasApiTokens;

    // </php-saas:tokens>
    use HasFactory;

    // <php-saas:projects>
    use HasProjects;

    // </php-saas:projects>
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'two_factor_confirmed_at',
        // <php-saas:projects>
        'current_project_id',
        // </php-saas:projects>
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
