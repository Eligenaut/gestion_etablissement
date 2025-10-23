<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Eleve extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eleves';

    protected $fillable = [
        'nom',
        'prenom',
        'sexe',
        'date_naissance',
        'lieu_naissance',
        'nationalite',
        'numero_piece_identite',
        'photo',
        'email',
        'telephone',
        'telephone_parent',
        'email_parent',
        'adresse',
        'ville',
        'code_postal',
        'matricule',
        'classe_id',
        'parent_principal_id',
        'parent_secondaire_id',
        'annee_inscription',
        'date_entree',
        'ecole_precedente',
        'statut',
        'groupe_sanguin',
        'allergies',
        'observations_medicales',
        'absence_statut',
        'nombre_absences',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_entree' => 'date',
        'annee_inscription' => 'integer',
        'nombre_absences' => 'integer',
    ];

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    /**
     * Relation avec le parent principal
     */
    public function parentPrincipal()
    {
        return $this->belongsTo(Parent_Tuteur::class, 'parent_principal_id');
    }

    /**
     * Relation avec le parent secondaire
     */
    public function parentSecondaire()
    {
        return $this->belongsTo(Parent_Tuteur::class, 'parent_secondaire_id');
    }

    /**
     * Relation avec tous les parents (principal + secondaire)
     */
    public function parents()
    {
        return $this->belongsToMany(Parent_Tuteur::class, 'eleve_parent', 'eleve_id', 'parent_id')
                    ->withPivot('type_relation', 'priorite')
                    ->withTimestamps();
    }

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Relation avec les paiements
     */
    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    /**
     * Relation avec les matières via les notes
     */
    public function matieres()
    {
        return $this->hasManyThrough(Matiere::class, Note::class, 'eleve_id', 'id', 'id', 'matiere_id');
    }

    /**
     * Relation avec les enseignants via les notes
     */
    public function enseignants()
    {
        return $this->hasManyThrough(Enseignant::class, Note::class, 'eleve_id', 'id', 'id', 'enseignant_id');
    }

    /**
     * Accessor pour le nom complet
     */
    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    /**
     * Accessor pour l'âge
     */
    public function getAgeAttribute()
    {
        return $this->date_naissance ? $this->date_naissance->age : null;
    }

    /**
     * Scope pour les élèves actifs
     */
    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope pour filtrer par classe
     */
    public function scopeParClasse($query, $classeId)
    {
        return $query->where('classe_id', $classeId);
    }
}
