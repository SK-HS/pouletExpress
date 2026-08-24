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
        Schema::create('campagne_promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->cascadeOnDelete();
            $table->string('type'); // ex: "Soldes de Noël", "Liquidation"
            $table->string('titre'); // ex: "Soldes de Noël", "Liquidation"
            $table->decimal('taux_remise', 5, 2); // Le pourcentage (ex: 15.00)
            $table->integer('seuil_quantite')->nullable(); // ex: 15 (s'il faut acheter X articles pour l'avoir)
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->boolean('est_active')->default(true);
            $table->boolean('supprimer')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campagne_promotions');
    }
};
