<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourrierAnnotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'courrier_entrant_id',
        'annotated_by',
        'annotation'
    ];

    /**
     * Le courrier associé à cette annotation
     */
    public function courrier()
    {
        return $this->belongsTo(CourriersEntrants::class, 'courrier_entrant_id');
    }

    /**
     * L'utilisateur qui a créé l'annotation
     */
    public function annotator()
    {
        return $this->belongsTo(User::class, 'annotated_by');
    }

    /**
     * Les partages qui incluent cette annotation
     */
    public function shares()
    {
        return $this->hasMany(CourrierShare::class, 'annotation_id');
    }
}