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
        Schema::create('versements', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable()->unique();
            $table->foreignId('commande_client_id')->constrained('commande_clients')->onDelete('cascade');
            // $table->foreignId('caisse_id')->constrained('caisses')->onDelete('set null');
            $table->float('montant')->default(0);
            $table->string('mode_paiement')->nullable();
             $table->dateTime('date_paiement');
            $table->string('detail')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('versements');
    }
};
