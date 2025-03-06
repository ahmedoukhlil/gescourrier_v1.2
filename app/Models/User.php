<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Les rôles de l'utilisateur
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Vérifie si l'utilisateur a un rôle spécifique
     *
     * @param string|array $roles
     * @return bool
     */
    public function hasRole($roles)
    {
        $userRoles = $this->roles->pluck('slug')->toArray();
        
        if (is_array($roles)) {
            return count(array_intersect($userRoles, $roles)) > 0;
        }
        
        return in_array($roles, $userRoles);
    }

    /**
     * Vérifie si l'utilisateur est administrateur
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Vérifie si l'utilisateur peut gérer les courriers
     *
     * @return bool
     */
    public function canManageCourriers()
    {
        return $this->hasRole(['admin', 'gestionnaire', 'agent']);
    }

    /**
     * Vérifie si l'utilisateur peut voir les courriers
     *
     * @return bool
     */
    public function canViewCourriers()
    {
        return $this->hasRole(['admin', 'gestionnaire', 'agent', 'lecteur']);
    }
}