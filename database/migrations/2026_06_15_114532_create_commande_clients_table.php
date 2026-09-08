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
        Schema::create('commande_clients', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable()->unique();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->onDelete('cascade');
            $table->foreignId('livreur_id')->nullable()->constrained('livreurs')->onDelete('Set Null');
            $table->float('montant_brut')->nullable()->default(0);
            $table->float('remise')->nullable()->default(0);
            $table->float('montant_hors_taxe')->nullable()->default(0);
            $table->float('tva')->nullable()->default(0);
            $table->float('montant_ttc')->nullable()->default(0);
            $table->float('avance')->nullable()->default(0);
            $table->float('solde')->nullable()->default(0);
            $table->string('statut')->nullable();
            $table->string('type_commande')->nullable();
            $table->string('creneau')->nullable();
            $table->string('telephone_livraison')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->text('lieu_livraison')->nullable();
            $table->boolean('commande_recuperee')->default(0);
            $table->boolean('commande_livree')->default(0);
            $table->boolean('commande_en_route')->default(0);
            $table->boolean('commande_recu')->default(0);
            $table->boolean('cmmd_livre_fournisseur')->default(0);
            $table->dateTime('date_commande');
            $table->dateTime('date_commande_recu')->nullable();
            $table->string('groupe_commande_id')->nullable();
            $table->dateTime('date_cmmd_livre_fournisseur')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('quartier_id')->constrained('quartiers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commande_clients');
    }
};
