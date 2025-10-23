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
        $table->string('niveau'); // Exemple : 3ème, 4ème, Terminale
        $table->unsignedBigInteger('responsable_id')->nullable(); // Enseignant responsable
        $table->timestamps();
        $table->softDeletes();

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
