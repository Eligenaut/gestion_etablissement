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
        Schema::table('classes', function (Blueprint $table) {
            // Ajouter la colonne niveau_id
            $table->unsignedBigInteger('niveau_id')->nullable()->after('nom');
            
            // Ajouter d'autres colonnes utiles
            $table->integer('capacite_max')->nullable()->after('niveau_id');
            $table->text('description')->nullable()->after('capacite_max');
            
            // Ajouter la clé étrangère
            $table->foreign('niveau_id')->references('id')->on('niveaux')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropForeign(['niveau_id']);
            $table->dropColumn(['niveau_id', 'capacite_max', 'description']);
        });
    }
};
