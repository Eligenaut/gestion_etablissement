<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parent_Tuteur extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'parent_tuteurs';

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
        'profession',
        'employeur',
        'salaire',
        'niveau_etude',
        'statut',
        'type_parent', // Père, Mère, Tuteur, Grand-parent
        'priorite', // 1 = Principal, 2 = Secondaire
        'photo',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_delivrance_cin' => 'date',
        'salaire' => 'decimal:2',
        'priorite' => 'integer',
    ];
    
    public function eleves()
    {
        return $this->hasMany(Eleve::class);
    }

    /**
     * Accessor pour le nom complet
     */
    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    /**
     * Scope pour les parents actifs
     */
    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }
}
