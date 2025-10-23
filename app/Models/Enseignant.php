<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enseignant extends Model
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
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_delivrance_cin' => 'date',
        'date_embauche' => 'date',
        'salaire' => 'decimal:2',
    ];

    /**
     * Relation many-to-many avec les matières
     * Un enseignant peut enseigner plusieurs matières
     */
    public function matieres()
    {
        return $this->belongsToMany(Matiere::class, 'enseignant_matiere', 'enseignant_id', 'matiere_id')
                    ->withPivot('coefficient', 'date_debut', 'date_fin')
                    ->withTimestamps();
    }

    /**
     * Relation avec les classes dont il est responsable
     */
    public function classesResponsable()
    {
        return $this->hasMany(Classe::class, 'responsable_id');
    }

    /**
     * Relation avec les niveaux dont il est responsable
     */
    public function niveauxResponsable()
    {
        return $this->hasMany(Niveau::class, 'responsable_id');
    }

    /**
     * Relation avec les emplois du temps
     */
    public function emploisDuTemps()
    {
        return $this->hasMany(EmploiDuTemps::class);
    }

    /**
     * Relation avec les notes qu'il a données
     */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Accessor pour le nom complet
     */
    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    /**
     * Scope pour les enseignants actifs
     */
    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope pour filtrer par spécialité
     */
    public function scopeParSpecialite($query, $specialite)
    {
        return $query->where('specialite', $specialite);
    }
}
