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
    Schema::create('enseignants', function (Blueprint $table) {
        $table->id();
        $table->string('nom');
        $table->string('prenom');
        $table->string('email')->nullable();
        $table->string('telephone')->nullable();
        $table->string('cin')->nullable();
        $table->date('date_delivrance_cin')->nullable();
        $table->string('adresse')->nullable();
        $table->unsignedBigInteger('classe_responsable_id')->nullable(); // Classe dont il est responsable
        $table->timestamps();
        $table->softDeletes();

        $table->foreign('classe_responsable_id')->references('id')->on('classes')->nullOnDelete();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enseignants');
    }
};
