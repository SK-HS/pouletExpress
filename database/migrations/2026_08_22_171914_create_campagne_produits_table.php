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
        Schema::create('campagne_produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campagne_promotion_id')->constrained('campagne_promotions')->cascadeOnDelete();
            $table->foreignId('produit_fournisseur_id')->constrained('produit_fournisseurs')->cascadeOnDelete();
            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campagne_produits');
    }
};
