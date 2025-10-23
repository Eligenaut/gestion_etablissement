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
    Schema::create('eleves', function (Blueprint $table) {
        $table->id();

        // Informations personnelles
        $table->string('nom');
        $table->string('prenom');
        $table->enum('sexe', ['M', 'F', 'Autre'])->nullable();
        $table->date('date_naissance')->nullable();
        $table->string('lieu_naissance')->nullable();
        $table->string('nationalite')->nullable();
        $table->string('numero_piece_identite')->nullable();
        $table->string('photo')->nullable();
        $table->string('email')->nullable();
        $table->string('telephone')->nullable();
        $table->string('telephone_parent')->nullable();
        $table->string('email_parent')->nullable();
        $table->string('adresse')->nullable();
        $table->string('ville')->nullable();
        $table->string('code_postal')->nullable();

        // Informations scolaires
        $table->string('matricule')->unique();
        $table->unsignedBigInteger('classe_id')->nullable();
        $table->unsignedBigInteger('parent_id')->nullable();
        $table->integer('annee_inscription')->nullable();
        $table->date('date_entree')->nullable();
        $table->string('ecole_precedente')->nullable();
        $table->string('statut')->default('actif');
        $table->string('groupe_sanguin')->nullable();
        $table->string('allergies')->nullable();
        $table->text('observations_medicales')->nullable();
        $table->boolean('absence_statut')->default(false);
        $table->integer('nombre_absences')->default(0);

        $table->timestamps();
        $table->softDeletes();

        // Clés étrangères
        $table->foreign('classe_id')->references('id')->on('classes')->onDelete('set null');
        $table->foreign('parent_id')->references('id')->on('parent_models')->onDelete('set null');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eleves');
    }
};
