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
        Schema::create('demande_retraits', function (Blueprint $table) {
            $table->id();
            $table->morphs('beneficiaire');
            $table->decimal('montant', 12, 2);
            $table->string('mode_paiement')->nullable(); // espece/mobile/wave
            $table->string('numero_paiement')->nullable(); // numéro Mobile Money
            $table->dateTime('date_traitee')->nullable(); // numéro Mobile Money
            $table->enum('statut', ['EN_ATTENTE', 'TRAITEE', 'REJETEE']);
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_retraits');
    }
};
