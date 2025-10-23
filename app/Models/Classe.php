<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classe extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',     
        'niveau_id',   
        'responsable_id', 
        'capacite_max',
        'description',
    ];

    protected $casts = [
        'capacite_max' => 'integer',
    ];

    /**
     * Relation avec le niveau
     */
    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    /**
     * Relation avec les élèves
     */
    public function eleves()
    {
        return $this->hasMany(Eleve::class);
    }

    /**
     * Relation avec le responsable de classe
     */
    public function responsable()
    {
        return $this->belongsTo(Enseignant::class, 'responsable_id');
    }

    /**
     * Relation avec les emplois du temps
     */
    public function emploisDuTemps()
    {
        return $this->hasMany(EmploiDuTemps::class);
    }

    /**
     * Relation avec les matières enseignées dans cette classe
     */
    public function matieres()
    {
        return $this->belongsToMany(Matiere::class, 'classe_matiere', 'classe_id', 'matiere_id')
                    ->withPivot('enseignant_id', 'coefficient')
                    ->withTimestamps();
    }

    /**
     * Accessor pour le nom complet de la classe
     */
    public function getNomCompletAttribute()
    {
        return $this->niveau ? $this->niveau->nom . ' ' . $this->nom : $this->nom;
    }

    /**
     * Scope pour filtrer par niveau
     */
    public function scopeParNiveau($query, $niveauId)
    {
        return $query->where('niveau_id', $niveauId);
    }
}
