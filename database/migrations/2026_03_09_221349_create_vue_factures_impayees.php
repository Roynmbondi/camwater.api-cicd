<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("DROP VIEW IF EXISTS vue_factures_impayees");
        
        DB::statement("
            CREATE VIEW vue_factures_impayees AS
            SELECT 
                f.id as facture_id,
                f.montant,
                f.date_generation,
                DATEDIFF(CURDATE(), f.date_generation) as jours_impaye,
                a.id as abonne_id,
                a.nom,
                a.prenom,
                a.telephone,
                a.adresse,
                a.ville,
                a.type,
                a.numero_compteur,
                c.consommation,
                c.date_releve,
                o.nom as operateur_nom,
                o.email as operateur_email
            FROM factures f
            INNER JOIN abonnes a ON f.abonne_id = a.id
            LEFT JOIN consommations c ON f.consommation_id = c.id
            LEFT JOIN operateurs o ON f.operateur_id = o.id
            WHERE f.statut = 'impayee'
            ORDER BY f.date_generation ASC
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS vue_factures_impayees");
    }
};
