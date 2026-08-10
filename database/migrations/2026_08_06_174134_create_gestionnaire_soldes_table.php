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
        Schema::create('gestionnaire_soldes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_client_id')->nullable()->constrained('commande_clients')->onDelete('Set Null');
            $table->decimal('montant', 12, 2);
            $table->enum('statut', ['EN_ATTENTE', 'DISPONIBLE', 'PAYE']);
            $table->timestamp('disponible_le')->nullable(); // date de déblocage
            $table->timestamp('paye_le')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gestionnaire_soldes');
    }
};
