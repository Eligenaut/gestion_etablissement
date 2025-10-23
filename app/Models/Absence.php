<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    use HasFactory;

    protected $fillable = [
        'eleve_id',
        'date',
        'motif',
        'justifie',
    ];

    protected $casts = [
        'date' => 'date',
        'justifie' => 'boolean',
    ];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }
}
