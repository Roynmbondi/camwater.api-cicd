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
        Schema::table('abonnes', function (Blueprint $table) {
            // Ajout du type d'abonné (Domestique ou Professionnel)
            $table->enum('type', ['Domestique', 'Professionnel'])->default('Domestique')->after('numero_compteur');
            
            // Ajout de la ville
            $table->enum('ville', ['Yaoundé', 'Douala', 'Bafoussam', 'Garoua'])->default('Yaoundé')->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('abonnes', function (Blueprint $table) {
            $table->dropColumn(['type', 'ville']);
        });
    }
};
