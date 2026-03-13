<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('consommations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('abonne_id')->constrained('abonnes')->onDelete('cascade');
            $table->integer('index_precedent');
            $table->integer('index_actuel');
            $table->integer('consommation')->default(0);
            $table->date('date_releve');
            $table->foreignId('operateur_id')->constrained('operateurs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('consommations');
    }
};
