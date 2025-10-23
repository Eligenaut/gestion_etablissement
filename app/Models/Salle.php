<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'numero',
        'type', // Classe, Laboratoire, Bibliothèque, Bureau, etc.
        'capacite',
        'equipements',
        'description',
        'etage',
        'batiment',
        'statut', // Disponible, Occupée, En maintenance
        'responsable_id', // Personnel responsable
    ];

    protected $casts = [
        'capacite' => 'integer',
        'etage' => 'integer',
        'equipements' => 'array',
    ];

    /**
     * Relation avec le responsable de la salle
     */
    public function responsable()
    {
        return $this->belongsTo(Personnel::class, 'responsable_id');
    }

    /**
     * Relation avec les emplois du temps
     */
    public function emploisDuTemps()
    {
        return $this->hasMany(EmploiDuTemps::class);
    }

    /**
     * Relation avec les réservations
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Scope pour les salles disponibles
     */
    public function scopeDisponibles($query)
    {
        return $query->where('statut', 'disponible');
    }

    /**
     * Scope pour filtrer par type
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Accessor pour le nom complet
     */
    public function getNomCompletAttribute()
    {
        return $this->nom . ' (' . $this->numero . ')';
    }
}
