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
        Schema::create('fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable()->unique();
            $table->string('nom');
            $table->string('type')->nullable();
            $table->string('adresse')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('contact')->nullable();
            $table->string('type_produit')->nullable();
            $table->string('nom_ferme')->nullable();
            $table->string('nom_gerant')->nullable();
            $table->string('capacite_ferme')->nullable();
            $table->boolean('etat')->default(1);
            $table->text('image')->nullable();
            $table->text('image_ferme')->nullable();
            $table->text('description')->nullable();
            $table->float('compte')->default(0);
            $table->string('remember_token')->nullable();
            $table->boolean('disponible')->default(0);
            $table->string('password')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->text('piece_fournisseur')->nullable();
            $table->text('certification_sanitaire')->nullable();
             $table->foreignId('quartier_id')->constrained('quartiers')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fournisseurs');
    }
};
