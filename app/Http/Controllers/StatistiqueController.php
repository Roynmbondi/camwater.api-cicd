<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Abonne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class StatistiqueController extends Controller
{

    public function facturesParVille()
    {
        try {
            // Cache pour 1 heure
            $stats = Cache::remember('stats_factures_ville_' . now()->format('Y-m'), 3600, function () {
                $debutMois = now()->startOfMonth();
                $finMois = now()->endOfMonth();

                $factures = DB::table('factures')
                    ->join('abonnes', 'factures.abonne_id', '=', 'abonnes.id')
                    ->select(
                        'abonnes.ville',
                        DB::raw('COUNT(factures.id) as nombre_factures'),
                        DB::raw('SUM(factures.montant) as montant_total'),
                        DB::raw('AVG(factures.montant) as montant_moyen'),
                        DB::raw('SUM(CASE WHEN factures.statut = "payee" THEN factures.montant ELSE 0 END) as montant_paye'),
                        DB::raw('SUM(CASE WHEN factures.statut = "impayee" THEN factures.montant ELSE 0 END) as montant_impaye'),
                        DB::raw('COUNT(CASE WHEN factures.statut = "payee" THEN 1 END) as factures_payees'),
                        DB::raw('COUNT(CASE WHEN factures.statut = "impayee" THEN 1 END) as factures_impayees')
                    )
                    ->whereBetween('factures.date_generation', [$debutMois, $finMois])
                    ->groupBy('abonnes.ville')
                    ->orderBy('montant_total', 'desc')
                    ->get();

                return $factures;
            });

            return response()->json([
                'status' => 'success',
                'periode' => [
                    'mois' => now()->format('F Y'),
                    'debut' => now()->startOfMonth()->format('Y-m-d'),
                    'fin' => now()->endOfMonth()->format('Y-m-d')
                ],
                'data' => $stats,
                'cached' => Cache::has('stats_factures_ville_' . now()->format('Y-m'))
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la récupération des statistiques'
            ], 500);
        }
    }


    public function total12Mois()
    {
        try {
            // Cache pour 6 heures
            $stats = Cache::remember('stats_total_12_mois', 21600, function () {
                $debut12Mois = now()->subMonths(12)->startOfMonth();

                $factures = DB::table('factures')
                    ->select(
                        DB::raw('YEAR(date_generation) as annee'),
                        DB::raw('MONTH(date_generation) as mois'),
                        DB::raw('DATE_FORMAT(date_generation, "%Y-%m") as periode'),
                        DB::raw('COUNT(id) as nombre_factures'),
                        DB::raw('SUM(montant) as montant_total'),
                        DB::raw('AVG(montant) as montant_moyen'),
                        DB::raw('SUM(CASE WHEN statut = "payee" THEN montant ELSE 0 END) as montant_paye'),
                        DB::raw('SUM(CASE WHEN statut = "impayee" THEN montant ELSE 0 END) as montant_impaye')
                    )
                    ->where('date_generation', '>=', $debut12Mois)
                    ->groupBy('annee', 'mois', 'periode')
                    ->orderBy('annee', 'asc')
                    ->orderBy('mois', 'asc')
                    ->get();

                // Calculer les totaux
                $totaux = [
                    'nombre_factures_total' => $factures->sum('nombre_factures'),
                    'montant_total' => $factures->sum('montant_total'),
                    'montant_paye_total' => $factures->sum('montant_paye'),
                    'montant_impaye_total' => $factures->sum('montant_impaye'),
                    'montant_moyen_global' => $factures->avg('montant_moyen')
                ];

                return [
                    'par_mois' => $factures,
                    'totaux' => $totaux
                ];
            });

            return response()->json([
                'status' => 'success',
                'periode' => [
                    'debut' => now()->subMonths(12)->startOfMonth()->format('Y-m-d'),
                    'fin' => now()->format('Y-m-d')
                ],
                'data' => $stats,
                'cached' => Cache::has('stats_total_12_mois')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la récupération des statistiques'
            ], 500);
        }
    }


    public function repartitionAbonnes()
    {
        try {
            // Cache pour 30 minutes
            $stats = Cache::remember('stats_repartition_abonnes', 1800, function () {
                // Répartition par type
                $parType = DB::table('abonnes')
                    ->select(
                        'type',
                        DB::raw('COUNT(id) as nombre'),
                        DB::raw('ROUND(COUNT(id) * 100.0 / (SELECT COUNT(*) FROM abonnes), 2) as pourcentage')
                    )
                    ->groupBy('type')
                    ->get();

                // Répartition par ville
                $parVille = DB::table('abonnes')
                    ->select(
                        'ville',
                        DB::raw('COUNT(id) as nombre'),
                        DB::raw('ROUND(COUNT(id) * 100.0 / (SELECT COUNT(*) FROM abonnes), 2) as pourcentage')
                    )
                    ->groupBy('ville')
                    ->orderBy('nombre', 'desc')
                    ->get();

                // Répartition croisée (type x ville)
                $parTypeEtVille = DB::table('abonnes')
                    ->select(
                        'type',
                        'ville',
                        DB::raw('COUNT(id) as nombre')
                    )
                    ->groupBy('type', 'ville')
                    ->orderBy('type')
                    ->orderBy('nombre', 'desc')
                    ->get();

                // Total des abonnés
                $total = Abonne::count();

                return [
                    'total_abonnes' => $total,
                    'par_type' => $parType,
                    'par_ville' => $parVille,
                    'par_type_et_ville' => $parTypeEtVille
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $stats,
                'cached' => Cache::has('stats_repartition_abonnes')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la récupération des statistiques'
            ], 500);
        }
    }


    public function dashboard()
    {
        try {
            // Cache pour 15 minutes
            $stats = Cache::remember('stats_dashboard', 900, function () {
                $debutMois = now()->startOfMonth();
                $finMois = now()->endOfMonth();

                return [
                    // Statistiques générales
                    'general' => [
                        'total_abonnes' => Abonne::count(),
                        'total_factures' => Facture::count(),
                        'factures_mois_courant' => Facture::whereBetween('date_generation', [$debutMois, $finMois])->count(),
                        'montant_total_facture' => Facture::sum('montant'),
                        'montant_paye' => Facture::where('statut', 'payee')->sum('montant'),
                        'montant_impaye' => Facture::where('statut', 'impayee')->sum('montant'),
                    ],

                    // Factures du mois
                    'mois_courant' => [
                        'nombre_factures' => Facture::whereBetween('date_generation', [$debutMois, $finMois])->count(),
                        'montant_total' => Facture::whereBetween('date_generation', [$debutMois, $finMois])->sum('montant'),
                        'factures_payees' => Facture::whereBetween('date_generation', [$debutMois, $finMois])->where('statut', 'payee')->count(),
                        'factures_impayees' => Facture::whereBetween('date_generation', [$debutMois, $finMois])->where('statut', 'impayee')->count(),
                    ],

                    // Top 5 villes par montant facturé
                    'top_villes' => DB::table('factures')
                        ->join('abonnes', 'factures.abonne_id', '=', 'abonnes.id')
                        ->select('abonnes.ville', DB::raw('SUM(factures.montant) as montant_total'))
                        ->groupBy('abonnes.ville')
                        ->orderBy('montant_total', 'desc')
                        ->limit(5)
                        ->get(),

                    // Répartition par type
                    'repartition_type' => DB::table('abonnes')
                        ->select('type', DB::raw('COUNT(id) as nombre'))
                        ->groupBy('type')
                        ->get(),

                    // Réclamations
                    'reclamations' => [
                        'total' => DB::table('reclamations')->count(),
                        'en_attente' => DB::table('reclamations')->where('statut', 'En attente')->count(),
                        'en_cours' => DB::table('reclamations')->where('statut', 'En cours')->count(),
                        'resolues' => DB::table('reclamations')->where('statut', 'Résolue')->count(),
                    ]
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $stats,
                'cached' => Cache::has('stats_dashboard')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la récupération du dashboard'
            ], 500);
        }
    }

    
    public function clearCache()
    {
        try {
            Cache::forget('stats_factures_ville_' . now()->format('Y-m'));
            Cache::forget('stats_total_12_mois');
            Cache::forget('stats_repartition_abonnes');
            Cache::forget('stats_dashboard');

            return response()->json([
                'status' => 'success',
                'message' => 'Cache des statistiques vidé avec succès'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors du vidage du cache'
            ], 500);
        }
    }
}
