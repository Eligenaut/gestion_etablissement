<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Niveau extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'code',
        'ordre',
        'description',
    ];

    protected $casts = [
        'ordre' => 'integer',
    ];

    /**
     * Relation avec les classes
     */
    public function classes()
    {
        return $this->hasMany(Classe::class);
    }

    /**
     * Relation avec le responsable du niveau
     */
    public function responsable()
    {
        return $this->belongsTo(Enseignant::class, 'responsable_id');
    }

    /**
     * Scope pour ordonner les niveaux
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('ordre');
    }
}
