<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('salles', function(Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('numero')->nullable();
            $table->string('type')->nullable();
            $table->integer('capacite')->nullable();
            $table->json('equipements')->nullable();
            $table->string('description')->nullable();
            $table->integer('etage')->nullable();
            $table->string('batiment')->nullable();
            $table->string('statut')->nullable();
            $table->unsignedBigInteger('responsable_id')->nullable();
            $table->foreign('responsable_id')->references('id')->on('personnels')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down() {
        Schema::dropIfExists('salles');
    }
};
