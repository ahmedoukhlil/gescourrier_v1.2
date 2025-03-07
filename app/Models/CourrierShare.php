<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CourrierShare extends Model
{
    use HasFactory;

    protected $fillable = [
        'courrier_entrant_id',
        'shared_by',
        'shared_with',
        'annotation_id',
        'is_read'
    ];

    /**
     * Le courrier qui est partagé
     */
    public function courrier()
    {
        return $this->belongsTo(CourriersEntrants::class, 'courrier_entrant_id');
    }

    /**
     * L'utilisateur qui a partagé le courrier
     */
    public function sharer()
    {
        return $this->belongsTo(User::class, 'shared_by');
    }

    /**
     * L'utilisateur avec qui le courrier est partagé
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'shared_with');
    }

    /**
     * L'annotation associée au partage
     */
    public function annotation()
    {
        return $this->belongsTo(CourrierAnnotation::class, 'annotation_id');
    }
    
    /**
     * Scope pour les courriers non lus
     */
    public function scopeUnread(Builder $query)
    {
        return $query->where('is_read', false);
    }
}