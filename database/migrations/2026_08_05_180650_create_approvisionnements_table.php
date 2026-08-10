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
        Schema::create('approvisionnements', function (Blueprint $table) {
            $table->id();
             $table->foreignId('produit_fournisseur_id')->constrained('produit_fournisseurs')->onDelete('cascade');
            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->onDelete('cascade');
            $table->integer('quantite_ajoutee');
            $table->integer('quantite_avant');
            $table->integer('quantite_apres');
            $table->string('motif')->nullable()->default('Réapprovisionnement');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvisionnements');
    }
};
