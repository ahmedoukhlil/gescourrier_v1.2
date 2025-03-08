<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseDraftExchange extends Model
{
    use HasFactory;

    protected $fillable = [
        'draft_id',
        'user_id',
        'comment',
        'file_path',
        'type'
    ];

    /**
     * Le projet de réponse auquel cet échange est associé
     */
    public function draft()
    {
        return $this->belongsTo(LecteurResponseDraft::class, 'draft_id');
    }

    /**
     * L'utilisateur qui a créé cet échange
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}