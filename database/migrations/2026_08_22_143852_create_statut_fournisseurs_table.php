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
        Schema::create('statut_fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->string('statut'); 
            $table->float('montant')->default(0); 
            $table->text('motif')->nullable(); 
            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->onDelete('Set Null');
            $table->foreignId('user_id')->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statut_fournisseurs');
    }
};
