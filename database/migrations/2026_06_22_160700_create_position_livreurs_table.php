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
        Schema::create('position_livreurs', function (Blueprint $table) {
            $table->id();
            $table->string('longitude');
            $table->string('latitude');
            $table->dateTime('date_position');
            $table->foreignId('livreur_id')->constrained('livreurs')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('position_livreurs');
    }
};
