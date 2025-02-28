<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destinataire extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'service', 'email'];

    // Relation many-to-many avec les courriers (pour les destinataires en copie)
    public function courriers()
    {
        return $this->belongsToMany(CourriersEntrants::class, 'courrier_destinataire', 'destinataire_id', 'courrier_id')
                    ->withTimestamps();
    }

    // Relation one-to-many avec les courriers (pour le destinataire principal)
    public function courriersDestines()
    {
        return $this->hasMany(CourriersEntrants::class, 'destinataire_id');
    }
}