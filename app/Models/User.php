<?php
namespace App\Models;

use App\Traits\LogsAudit;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, LogsAudit;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->hasRole('admin');
    }

    /**
     * Check if user is contributor
     */
    public function isContributor(): bool
    {
        return $this->role === 'contributor' || $this->hasRole('contributor');
    }

    /**
     * Check if user can manage posts
     */
    public function canManagePosts(): bool
    {
        return $this->isAdmin() || $this->isContributor();
    }

    /**
     * Relationship: User has many posts
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    /**
     * Relationship: User has many contacts
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'user_id');
    }
}

