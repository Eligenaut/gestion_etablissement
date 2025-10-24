<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('paiements', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('eleve_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('type_paiement')->nullable();
            $table->decimal('montant', 12, 2);
            $table->decimal('montant_paye', 12, 2)->default(0);
            $table->date('date_paiement')->nullable();
            $table->date('date_echeance')->nullable();
            $table->string('statut')->nullable();
            $table->string('mode_paiement')->nullable();
            $table->string('reference')->nullable();
            $table->text('observations')->nullable();
            $table->unsignedBigInteger('recu_par')->nullable();
            $table->foreign('eleve_id')->references('id')->on('eleves')->restrictOnDelete();
            $table->foreign('parent_id')->references('id')->on('parent_tuteurs')->nullOnDelete();
            $table->foreign('recu_par')->references('id')->on('personnels')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down() {
        Schema::dropIfExists('paiements');
    }
};
