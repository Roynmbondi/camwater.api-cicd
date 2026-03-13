<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="CamWater API",
 *     version="1.0.0",
 *     description="API de gestion de la distribution d'eau - CamWater",
 *     @OA\Contact(
 *         email="admin@camwater.cm"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Serveur de développement"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Entrez votre token JWT au format: Bearer {token}"
 * )
 * 
 * @OA\Tag(
 *     name="Authentification",
 *     description="Endpoints pour l'authentification des opérateurs"
 * )
 * 
 * @OA\Tag(
 *     name="Opérateurs",
 *     description="Gestion des opérateurs"
 * )
 * 
 * @OA\Tag(
 *     name="Abonnés",
 *     description="Gestion des abonnés"
 * )
 * 
 * @OA\Tag(
 *     name="Consommations",
 *     description="Gestion des consommations d'eau"
 * )
 * 
 * @OA\Tag(
 *     name="Factures",
 *     description="Gestion des factures"
 * )
 * 
 * @OA\Tag(
 *     name="Paiements",
 *     description="Gestion des paiements"
 * )
 * 
 * @OA\Tag(
 *     name="Réclamations",
 *     description="Gestion des réclamations clients"
 * )
 * 
 * @OA\Tag(
 *     name="Statistiques",
 *     description="Statistiques et rapports"
 * )
 * 
 * @OA\Tag(
 *     name="Logs",
 *     description="Logs d'activité et traçabilité"
 * )
 */
abstract class Controller
{
    //
}
