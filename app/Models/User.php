<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Http\Filters\V1\QueryFilter;
use App\Permissions\V1\Abilities;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

#[Fillable(['name', 'email', 'password', 'is_manager'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
            // Returns true/false instead of 1/0
            'is_manager' => 'boolean',
        ];
    }

    /**
     * The shoes written by this user (one user has many shoes).
     */
    public function shoes(): HasMany
    {
        return $this->hasMany(Shoe::class);
    }

    /**
     * The value stored inside the token to identify the user (the user id).
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Extra data stored in the token payload: the user's abilities.
     * They are written into the JWT every time the user logs in.
     */
    public function getJWTCustomClaims()
    {
        return [
            'abilities' => Abilities::getAbilities($this),
        ];
    }

    /**
     * Query scope: User::filter($filters) applies the filters sent in the URL.
     */
    public function scopeFilter(Builder $builder, QueryFilter $filters): Builder
    {
        return $filters->apply($builder);
    }

    /**
     * Checks an ability stored in the current JWT, for example 'shoe:update'.
     * Sanctum's own tokenCan() does not read JWT claims, so we implement it here.
     */
    public function tokenCan(string $ability): bool
    {
        try {
            // Read the "abilities" claim of the token sent with this request
            $abilities = auth('api')->payload()->get('abilities') ?? [];
        } catch (\Throwable $e) {
            // No token, or an unreadable one: no abilities
            return false;
        }

        return in_array($ability, $abilities, true);
    }
}
