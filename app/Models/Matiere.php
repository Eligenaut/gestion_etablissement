<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Matiere extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'matieres';
    
    protected $fillable = [
        'nom',
        'code',
        'coefficient',
        'description',
        'couleur',
        'icone',
        'niveau_requis',
        'statut',
    ];

    protected $casts = [
        'coefficient' => 'decimal:2',
        'niveau_requis' => 'integer',
    ];

    /**
     * Relation many-to-many avec les enseignants
     * Une matière peut être enseignée par plusieurs enseignants
     */
    public function enseignants()
    {
        return $this->belongsToMany(Enseignant::class, 'enseignant_matiere', 'matiere_id', 'enseignant_id')
                    ->withPivot('coefficient', 'date_debut', 'date_fin')
                    ->withTimestamps();
    }

    /**
     * Relation avec les classes qui ont cette matière
     */
    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'classe_matiere', 'matiere_id', 'classe_id')
                    ->withPivot('enseignant_id', 'coefficient')
                    ->withTimestamps();
    }

    /**
     * Relation avec les notes
     */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Relation avec les emplois du temps
     */
    public function emploisDuTemps()
    {
        return $this->hasMany(EmploiDuTemps::class);
    }

    /**
     * Scope pour les matières actives
     */
    public function scopeActives($query)
    {
        return $query->where('statut', 'active');
    }

    /**
     * Scope pour filtrer par niveau requis
     */
    public function scopeParNiveau($query, $niveau)
    {
        return $query->where('niveau_requis', $niveau);
    }

    /**
     * Accessor pour le nom avec code
     */
    public function getNomCompletAttribute()
    {
        return $this->code ? $this->code . ' - ' . $this->nom : $this->nom;
    }
}
