<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('abonne_id')->constrained('abonnes')->onDelete('cascade');
            $table->foreignId('consommation_id')->constrained('consommations')->onDelete('cascade');
            $table->decimal('montant', 10, 2);
            $table->date('date_generation');
            $table->enum('statut', ['impayee', 'payee'])->default('impayee');
            $table->foreignId('operateur_id')->constrained('operateurs')->onDelete('cascade');
            $table->timestamps();
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
