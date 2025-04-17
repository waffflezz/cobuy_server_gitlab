<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Domain\Entities\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UserModel extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(GroupModel::class, 'group_user', 'user_id', 'group_id');
    }

    public function ownedGroups(): HasMany
    {
        return $this->hasMany(GroupModel::class, 'owner_id');
    }

    public function toDomain(): User
    {
        return new User(
            $this->id,
            $this->name,
            $this->email,
            $this->email_verified_at ? new \DateTime($this->email_verified_at) : null,
            $this->password,
            $this->remember_token,
            new \DateTime($this->created_at),
            new \DateTime($this->updated_at),
            $this->groups->map(fn(GroupModel $groupModel) => $groupModel->toDomain())->all(),
        );
    }
}
