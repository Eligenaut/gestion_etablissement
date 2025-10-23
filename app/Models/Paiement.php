<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paiement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'eleve_id',
        'parent_id',
        'type_paiement', // Scolarité, Transport, Cantine, Activités, etc.
        'montant',
        'montant_paye',
        'date_paiement',
        'date_echeance',
        'statut', // En attente, Payé, En retard, Annulé
        'mode_paiement', // Espèces, Chèque, Virement, Carte
        'reference',
        'observations',
        'recu_par', // Personnel qui a reçu le paiement
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'montant_paye' => 'decimal:2',
        'date_paiement' => 'date',
        'date_echeance' => 'date',
    ];

    /**
     * Relation avec l'élève
     */
    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    /**
     * Relation avec le parent
     */
    public function parent()
    {
        return $this->belongsTo(Parent_Tuteur::class, 'parent_id');
    }

    /**
     * Relation avec le personnel qui a reçu le paiement
     */
    public function recuPar()
    {
        return $this->belongsTo(Personnel::class, 'recu_par');
    }

    /**
     * Scope pour les paiements en retard
     */
    public function scopeEnRetard($query)
    {
        return $query->where('statut', 'en_retard')
                    ->where('date_echeance', '<', now());
    }

    /**
     * Scope pour les paiements payés
     */
    public function scopePayes($query)
    {
        return $query->where('statut', 'paye');
    }

    /**
     * Accessor pour le montant restant
     */
    public function getMontantRestantAttribute()
    {
        return $this->montant - $this->montant_paye;
    }
}
