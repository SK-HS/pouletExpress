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
        Schema::create('produit_fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->integer('quantite')->nullable();
            $table->float('prix')->nullable();
            $table->boolean('statuts')->default(1);
            $table->text('description')->nullable();
            $table->text('images')->nullable();
            $table->boolean('etat')->default(1);
            $table->string('commande_min')->nullable();
            $table->foreignId('categorie_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->foreignId('taille_id')->nullable()->constrained('tailles')->onDelete('cascade');
            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produit_fournisseurs');
    }
};
