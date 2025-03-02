<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourrierSortant extends Model
{
    use HasFactory;

    protected $table = 'courriers_sortants';

    protected $fillable = [
        'objet',
        'destinataire',
        'numero',
        'date',
        'courrier_entrant_id',
        'decharge',
        'decharge_manquante'
    ];

    protected $dates = ['date'];
    
    protected static function boot()
    {
        parent::boot();

        // Génération automatique du numéro lors de la création
        static::creating(function ($courrierSortant) {
            $year = date('Y');
            $latestCourrier = self::whereRaw('YEAR(date) = ?', [$year])
                ->orderBy('id', 'desc')
                ->first();
            
            $sequentialNumber = $latestCourrier ? intval(substr($latestCourrier->numero, -4)) + 1 : 1;
            $courrierSortant->numero = 'S-' . $year . '/' . str_pad($sequentialNumber, 4, '0', STR_PAD_LEFT);
        });
    }

    // Relation avec le courrier entrant (si c'est une réponse)
    public function courrierEntrant()
    {
        return $this->belongsTo(CourriersEntrants::class, 'courrier_entrant_id');
    }
    
}