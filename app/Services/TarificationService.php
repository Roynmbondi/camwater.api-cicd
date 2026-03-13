<?php

namespace App\Services;

use App\Models\Abonne;

class TarificationService
{
    /**
     * Calcule le montant de la facture selon le type d'abonné et la consommation
     * 
     * @param Abonne $abonne
     * @param float $consommation en m³
     * @return float montant en FCFA
     */
    public function calculerMontant(Abonne $abonne, float $consommation): float
    {
        if ($abonne->type === 'Professionnel') {
            return $this->calculerTarifProfessionnel($consommation);
        }
        
        return $this->calculerTarifDomestique($consommation);
    }

    /**
     * Calcul pour abonné Domestique (tarification progressive par tranches)
     * - 0-10 m³ : 350 FCFA/m³
     * - 11-20 m³ : 550 FCFA/m³
     * - 21+ m³ : 780 FCFA/m³
     */
    private function calculerTarifDomestique(float $consommation): float
    {
        $montant = 0;

        // Tranche 1 : 0-10 m³ à 350 FCFA/m³
        if ($consommation <= 10) {
            $montant = $consommation * 350;
        } 
        // Tranche 2 : 11-20 m³
        elseif ($consommation <= 20) {
            $montant = (10 * 350) + (($consommation - 10) * 550);
        } 
        // Tranche 3 : 21+ m³
        else {
            $montant = (10 * 350) + (10 * 550) + (($consommation - 20) * 780);
        }

        return round($montant, 2);
    }

    /**
     * Calcul pour abonné Professionnel
     * Forfait de 8500 FCFA + 950 FCFA/m³
     */
    private function calculerTarifProfessionnel(float $consommation): float
    {
        $forfait = 8500;
        $tarifParM3 = 950;
        
        $montant = $forfait + ($consommation * $tarifParM3);
        
        return round($montant, 2);
    }

    /**
     * Retourne le détail du calcul pour affichage
     */
    public function getDetailCalcul(Abonne $abonne, float $consommation): array
    {
        if ($abonne->type === 'Professionnel') {
            return [
                'type' => 'Professionnel',
                'forfait' => 8500,
                'consommation' => $consommation,
                'tarif_par_m3' => 950,
                'montant_consommation' => $consommation * 950,
                'montant_total' => $this->calculerMontant($abonne, $consommation)
            ];
        }

        // Calcul détaillé pour Domestique
        $detail = [
            'type' => 'Domestique',
            'consommation' => $consommation,
            'tranches' => []
        ];

        if ($consommation <= 10) {
            $detail['tranches'][] = [
                'tranche' => '0-10 m³',
                'quantite' => $consommation,
                'tarif' => 350,
                'montant' => $consommation * 350
            ];
        } elseif ($consommation <= 20) {
            $detail['tranches'][] = [
                'tranche' => '0-10 m³',
                'quantite' => 10,
                'tarif' => 350,
                'montant' => 10 * 350
            ];
            $detail['tranches'][] = [
                'tranche' => '11-20 m³',
                'quantite' => $consommation - 10,
                'tarif' => 550,
                'montant' => ($consommation - 10) * 550
            ];
        } else {
            $detail['tranches'][] = [
                'tranche' => '0-10 m³',
                'quantite' => 10,
                'tarif' => 350,
                'montant' => 10 * 350
            ];
            $detail['tranches'][] = [
                'tranche' => '11-20 m³',
                'quantite' => 10,
                'tarif' => 550,
                'montant' => 10 * 550
            ];
            $detail['tranches'][] = [
                'tranche' => '21+ m³',
                'quantite' => $consommation - 20,
                'tarif' => 780,
                'montant' => ($consommation - 20) * 780
            ];
        }

        $detail['montant_total'] = $this->calculerMontant($abonne, $consommation);

        return $detail;
    }
}
