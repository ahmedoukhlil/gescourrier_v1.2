<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestinataireSortant extends Model
{
    use HasFactory;

    protected $table = 'destinataires_sortants';

    protected $fillable = [
        'nom',
        'organisation',
        'adresse',
        'email',
        'telephone'
    ];

    /**
     * Relation avec les courriers sortants
     */
    public function courriersSortants()
    {
        return $this->hasMany(CourrierSortant::class, 'destinataire_sortant_id');
    }
}