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
        Schema::create('commande_livreurs', function (Blueprint $table) {
            $table->id();
            $table->string('distance')->nullable();
            $table->string('statut')->nullable();
            $table->dateTime('date_affectation')->nullable();
            $table->dateTime('date_depart')->nullable();
            $table->dateTime('date_arrivee')->nullable();
            $table->foreignId('quartier_id')->constrained('quartiers')->onDelete('cascade');
            $table->foreignId('commande_client_id')->nullable()->constrained('commande_clients')->onDelete('Set Null');
            $table->foreignId('livreur_id')->nullable()->constrained('livreurs')->onDelete('Set Null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commande_livreurs');
    }
};
