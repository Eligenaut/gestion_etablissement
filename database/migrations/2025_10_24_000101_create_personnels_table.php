<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('personnels', function(Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('sexe');
            $table->date('date_naissance')->nullable();
            $table->string('lieu_naissance')->nullable();
            $table->string('nationalite')->nullable();
            $table->string('cin')->unique();
            $table->date('date_delivrance_cin')->nullable();
            $table->string('lieu_delivrance_cin')->nullable();
            $table->string('email')->unique();
            $table->string('telephone')->nullable();
            $table->string('adresse')->nullable();
            $table->string('photo')->nullable();
            $table->string('matricule')->unique();
            $table->string('statut')->nullable();
            $table->string('fonction')->nullable();
            $table->string('specialite')->nullable();
            $table->string('diplome')->nullable();
            $table->date('date_embauche')->nullable();
            $table->decimal('salaire', 12, 2)->nullable();
            $table->string('niveau_acces')->nullable();
            $table->text('responsabilites')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down() {
        Schema::dropIfExists('personnels');
    }
};
