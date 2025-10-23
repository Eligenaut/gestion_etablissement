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
    Schema::create('notes', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('eleve_id');
        $table->unsignedBigInteger('matiere_id');
        $table->unsignedBigInteger('enseignant_id')->nullable();
        $table->float('note');
        $table->string('type')->nullable();
        $table->timestamps();

        $table->foreign('eleve_id')->references('id')->on('eleves')->onDelete('cascade');
        $table->foreign('matiere_id')->references('id')->on('matieres')->onDelete('cascade');
        $table->foreign('enseignant_id')->references('id')->on('enseignants')->nullOnDelete();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
