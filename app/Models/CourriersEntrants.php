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
        'destinataire_id', 
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
        return $this->belongsTo(Destinataire::class, 'destinataire_id');
    }
    
    // Relation many-to-many avec les destinataires en copie
    public function destinataires()
    {
        return $this->belongsToMany(Destinataire::class, 'courrier_destinataire', 'courrier_id', 'destinataire_id')
                    ->withTimestamps();
    }
    
    // Relation avec les courriers sortants
    public function courriersSortants()
    {
        return $this->hasMany(CourriersSortants::class, 'courrier_entrant_id');
    }
}