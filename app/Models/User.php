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
        'service', // Ajout du champ service
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
     * Relation many-to-many avec les courriers (pour les destinataires en copie)
     */
    public function courriers()
    {
        return $this->belongsToMany(CourriersEntrants::class, 'courrier_user', 'user_id', 'courrier_entrant_id')
                ->withTimestamps();
    }

    /**
     * Relation one-to-many avec les courriers (pour le destinataire principal)
     */
    public function courriersDestines()
    {
        return $this->hasMany(CourriersEntrants::class, 'user_id');
    }
    
    /**
     * Les annotations faites par l'utilisateur
     */
    public function annotations()
    {
        return $this->hasMany(CourrierAnnotation::class, 'annotated_by');
    }
    
    /**
     * Les courriers partagés par l'utilisateur
     */
    public function courrierShares()
    {
        return $this->hasMany(CourrierShare::class, 'shared_by');
    }
    
    /**
     * Les courriers partagés avec l'utilisateur
     */
    public function sharedCourriers()
    {
        return $this->hasMany(CourrierShare::class, 'shared_with');
    }
    
    /**
     * Les projets de réponse créés par l'utilisateur
     */
    public function responseDrafts()
    {
        return $this->hasMany(LecteurResponseDraft::class);
    }

    /**
     * Les projets de réponse examinés par l'utilisateur
     */
    public function reviewedDrafts()
    {
        return $this->hasMany(LecteurResponseDraft::class, 'reviewed_by');
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
     * Vérifie si l'utilisateur est gestionnaire
     *
     * @return bool
     */
    public function isGestionnaire()
    {
        return $this->hasRole('gestionnaire');
    }
    
    /**
     * Vérifie si l'utilisateur est agent
     *
     * @return bool
     */
    public function isAgent()
    {
        return $this->hasRole('agent');
    }
    
    /**
     * Vérifie si l'utilisateur est lecteur
     *
     * @return bool
     */
    public function isLecteur()
    {
        return $this->hasRole('lecteur');
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
     * Vérifie si l'utilisateur peut annoter les courriers
     *
     * @return bool
     */
    public function canAnnotateCourriers()
    {
        return $this->hasRole(['admin', 'gestionnaire']);
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