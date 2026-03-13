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
        Schema::create('token_blacklist', function (Blueprint $table) {
            $table->id();
            $table->text('token');
            $table->string('jti')->unique(); // JWT ID
            $table->timestamp('expires_at');
            $table->foreignId('operateur_id')->nullable()->constrained('operateurs')->onDelete('set null');
            $table->timestamps();
            
            // Index pour améliorer les performances de recherche
            $table->index('jti');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token_blacklist');
    }
};
