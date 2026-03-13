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
        Schema::create('logs_activite', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // create, update, delete, login, logout, etc.
            $table->string('module'); // abonnes, factures, consommations, etc.
            $table->string('description');
            $table->foreignId('operateur_id')->nullable()->constrained('operateurs')->onDelete('set null');
            $table->unsignedBigInteger('entity_id')->nullable(); // ID de l'entité concernée
            $table->string('entity_type')->nullable(); // Type de l'entité (Abonne, Facture, etc.)
            $table->json('data_before')->nullable(); // Données avant modification
            $table->json('data_after')->nullable(); // Données après modification
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            
            // Index pour améliorer les performances
            $table->index('action');
            $table->index('module');
            $table->index('operateur_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs_activite');
    }
};
