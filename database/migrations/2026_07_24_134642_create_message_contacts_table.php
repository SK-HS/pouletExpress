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
        Schema::create('message_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('sujet')->nullable();
            $table->string('message');
            $table->string('statut')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_contacts');
    }
};
