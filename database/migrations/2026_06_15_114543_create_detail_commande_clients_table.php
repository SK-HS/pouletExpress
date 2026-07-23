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
        Schema::create('detail_commande_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_client_id')->constrained('commande_clients')->onDelete('cascade');
            $table->foreignId('produit_fournisseur_id')->nullable()->constrained('produit_fournisseurs')->onDelete('cascade');
            $table->foreignId('service_id')->nullable()->constrained('services')->onDelete('cascade');
            $table->float('quantite')->default(0);
            $table->float('prix_unitaire')->default(0);
            $table->string('type')->nullable();
            $table->float('montant')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_commande_clients');
    }
};
