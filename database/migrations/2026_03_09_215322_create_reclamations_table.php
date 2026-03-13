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
        Schema::create('reclamations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('abonne_id')->constrained('abonnes')->onDelete('cascade');
            $table->foreignId('facture_id')->nullable()->constrained('factures')->onDelete('set null');
            $table->text('description');
            $table->enum('statut', ['En attente', 'En cours', 'Résolue'])->default('En attente');
            $table->date('date_creation');
            $table->date('date_resolution')->nullable();
            $table->foreignId('operateur_id')->nullable()->constrained('operateurs')->onDelete('set null');
            $table->text('commentaire_resolution')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reclamations');
    }
};
