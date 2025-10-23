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
        Schema::create('niveaux', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // Ex: "3ème", "4ème", "5ème"
            $table->string('code')->unique(); // Ex: "3", "4", "5"
            $table->integer('ordre')->default(0); // Pour ordonner les niveaux
            $table->text('description')->nullable();
            $table->unsignedBigInteger('responsable_id')->nullable(); // Enseignant responsable du niveau
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('responsable_id')->references('id')->on('enseignants')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('niveaux');
    }
};
