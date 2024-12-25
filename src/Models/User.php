<?php

namespace Sokeio\Models;

use Sokeio\Concerns\WithModelHook;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Sokeio\Concerns\WithPermission;
use Illuminate\Support\Facades\Hash;
use Sokeio\Attribute\ModelInfo;
use Illuminate\Support\Str;
use DateTimeInterface;

#[ModelInfo(skipBy: true)]
class User extends Authenticatable
{
    use WithPermission, Notifiable;
    use WithModelHook;
    /**
     * Get the access tokens that belong to model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<TToken, $this>
     */
    public function tokens()
    {
        return $this->morphMany(PersonalAccessToken::class, 'tokenable');
    }
    /**
     * Generate the token string.
     *
     * @return string
     */
    public function generateTokenString()
    {
        return sprintf(
            '%s%s%s',
            config('sokeio.platform.token_prefix', ''),
            $tokenEntropy = Str::random(40),
            hash('crc32b', $tokenEntropy)
        );
    }
    /**
     * Create a new personal access token for the user.
     *
     * @param  string  $name
     * @param  array  $abilities
     * @param  \DateTimeInterface|null  $expiresAt
     * @return \Laravel\Sanctum\NewAccessToken
     */
    public function createToken(string $name, array $abilities = ['*'], ?DateTimeInterface $expiresAt = null)
    {
        $plainTextToken = $this->generateTokenString();

        $token = $this->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken),
            'abilities' => $abilities,
            'expires_at' => $expiresAt,
        ]);

        return  $token->getKey() . '|' . $plainTextToken;
    }

    protected $fillable = ["*"];
    protected $hidden = ["password"];
    public function isActive()
    {
        return $this->status == 1;
    }
    public function isSuperAdmin(): bool
    {
        return $this->hasRole(config('sokeio.model.role')::SupperAdmin());
    }
    public function isBlock()
    {
        return !$this->isActive();
    }
    public function getAllPermission()
    {
        $permissions = $this->permissions()->pluck('slug')->toArray();
        // get permissions in roles of user
        foreach ($this->roles as $role) {
            $permissions = array_merge($permissions, $role->permissions()->pluck('slug')->toArray());
        }
        return collect($permissions)->unique()->toArray();
    }
    public function getAllRole()
    {
        $roles = $this->roles->pluck('slug')->toArray();
        return collect($roles)->unique()->toArray();
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if (Hash::needsRehash($model->password)) {
                $model->password = Hash::make($model->password);
            }
        });
        self::updating(function ($model) {
            if ($model->password && Hash::needsRehash($model->password)) {
                $model->password = Hash::make($model->password);
            }
        });
    }
}
