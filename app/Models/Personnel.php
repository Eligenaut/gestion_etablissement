<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personnel extends Model
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
        'fonction', // Secrétaire, Surveillant, Comptable, Infirmier, etc.
        'specialite',
        'diplome',
        'date_embauche',
        'salaire',
        'niveau_acces',
        'responsabilites',
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
     * Relation avec les tâches assignées
     */
    public function taches()
    {
        return $this->hasMany(Tache::class);
    }

    /**
     * Accessor pour le nom complet
     */
    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    /**
     * Scope pour le personnel actif
     */
    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope pour filtrer par fonction
     */
    public function scopeParFonction($query, $fonction)
    {
        return $query->where('fonction', $fonction);
    }
}
