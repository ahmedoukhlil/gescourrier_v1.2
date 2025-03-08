<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LecteurResponseDraft extends Model
{
    use HasFactory;

    protected $fillable = [
        'courrier_entrant_id',
        'user_id',
        'comment',
        'file_path',
        'is_reviewed',
        'feedback',
        'reviewed_by',
        'reviewed_at'
    ];

    protected $casts = [
        'is_reviewed' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Le courrier entrant auquel ce projet de réponse est associé
     */
    public function courrier()
    {
        return $this->belongsTo(CourriersEntrants::class, 'courrier_entrant_id');
    }

    /**
     * L'utilisateur (lecteur) qui a créé ce projet de réponse
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * L'utilisateur (gestionnaire/admin) qui a examiné ce projet de réponse
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}