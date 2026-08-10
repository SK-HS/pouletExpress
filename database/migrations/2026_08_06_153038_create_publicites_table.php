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
        Schema::create('publicites', function (Blueprint $table) {
            $table->id();
            $table->string('titre'); // Titre de la pub (ex: Promo -15%)
            $table->text('description'); // Le petit texte explicatif
            $table->text('image'); // Le chemin de l'image stockée
            
            // Colonnes optionnelles (peuvent être vides)
            $table->string('badge')->nullable(); // Ex: "OFFRE LIMITÉE"
            $table->string('lien')->nullable(); // Ex: route vers une page spécifique
            $table->string('bouton_texte')->nullable()->default('En profiter'); // Texte du bouton
            
            // Gestion de l'affichage
            $table->boolean('est_actif')->default(true); // Pour activer/désactiver une pub facilement
            $table->dateTime('date_debut')->nullable(); // Pour programmer une pub à l'avance
            $table->dateTime('date_fin')->nullable(); // Date de fin de la promo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publicites');
    }
};
