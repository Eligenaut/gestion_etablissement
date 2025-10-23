<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Directeur extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'prenom',
        'sexe',
        'date_naissance',
        'lieu_naissance',
        'nationalite',
        'cin',
        'date_delivrance_cin',
        'lieu_delivrance_cin',
        'email',
        'telephone',
        'adresse',
        'photo',
        'matricule',
        'statut',
        'specialite',
        'diplome',
        'date_embauche',
        'salaire',
        'fonction', // Directeur, Directeur Adjoint, etc.
        'niveau_acces',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_delivrance_cin' => 'date',
        'date_embauche' => 'date',
        'salaire' => 'decimal:2',
    ];

    /**
     * Relation avec l'utilisateur système
     */
    public function user()
    {
        return $this->morphOne(User::class, 'profilable');
    }

    /**
     * Relation avec les décisions prises
     */
    public function decisions()
    {
        return $this->hasMany(Decision::class);
    }

    /**
     * Accessor pour le nom complet
     */
    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    /**
     * Scope pour les directeurs actifs
     */
    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }
}
