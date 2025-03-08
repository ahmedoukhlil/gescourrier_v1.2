<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CourriersEntrants extends Model
{
    use HasFactory;
    
    protected $table = 'courriers_entrants';
    
    protected $fillable = [
        'expediteur', 
        'type', 
        'objet', 
        'user_id',
        'statut', 
        'document_path',
        'nom_dechargeur'
    ];

    // Statut par défaut: "en_cours"
    protected $attributes = [
        'statut' => 'en_cours',
    ];

    protected static function boot()
    {
        parent::boot();

        // Génération automatique du numéro d'ordre avant la création
        static::creating(function ($courrier) {
            $year = date('Y');
            $latestCourrier = DB::table('courriers_entrants')
                ->whereRaw('YEAR(created_at) = ?', [$year])
                ->orderBy('id', 'desc')
                ->first();
            
            $sequentialNumber = $latestCourrier ? intval(substr($latestCourrier->numero_ordre, -4)) + 1 : 1;
            $courrier->numero_ordre = $year . '/' . str_pad($sequentialNumber, 4, '0', STR_PAD_LEFT);
        });
    }

    // Relation avec le destinataire principal
    public function destinataireInterne()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    // Relation many-to-many avec les destinataires en copie
    public function destinataires()
    {
        return $this->belongsToMany(User::class, 'courrier_user', 'courrier_entrant_id', 'user_id')
                    ->withTimestamps();
    }
    
    // Relation avec les courriers sortants
    public function courriersSortants()
    {
        return $this->hasMany(CourrierSortant::class, 'courrier_entrant_id');
    }
    
    /**
     * Les annotations pour ce courrier
     */
    public function annotations()
    {
        return $this->hasMany(CourrierAnnotation::class, 'courrier_entrant_id');
    }
    
    /**
     * Les partages de ce courrier
     */
    public function shares()
    {
        return $this->hasMany(CourrierShare::class, 'courrier_entrant_id');
    }
    
    /**
     * Les utilisateurs avec qui ce courrier est partagé
     */
    public function sharedWith()
    {
        return $this->belongsToMany(User::class, 'courrier_shares', 'courrier_entrant_id', 'shared_with')
                    ->withTimestamps();
    }
    
    /**
     * Vérifie si ce courrier est partagé avec un utilisateur spécifique
     */
    public function isSharedWith(User $user)
    {
        return $this->shares()->where('shared_with', $user->id)->exists();
    }
    // Ajouter cette méthode au modèle CourriersEntrants (app/Models/CourriersEntrants.php)

/**
 * Les projets de réponse associés à ce courrier
 */
public function responseDrafts()
{
    return $this->hasMany(LecteurResponseDraft::class, 'courrier_entrant_id');
}
}