<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('classes', function (Blueprint $table) {
        $table->id();
        $table->string('nom'); // Exemple : 3ème A, 4ème B, Terminale C
        $table->unsignedBigInteger('niveau_id')->nullable(); // clé étrangère vers niveaux
        $table->unsignedBigInteger('responsable_id')->nullable(); // Enseignant responsable
        $table->integer('capacite_max')->nullable();           // capacité maximale
        $table->string('description')->nullable();            // description de la classe
        $table->timestamps();
        $table->softDeletes();
    
        // Clés étrangères
        $table->foreign('niveau_id')->references('id')->on('niveaux')->nullOnDelete();
        $table->foreign('responsable_id')->references('id')->on('enseignants')->nullOnDelete();
    });
    
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
