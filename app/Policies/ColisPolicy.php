<?php

namespace App\Policies;

use App\Models\Colis;
use App\Models\Utilisateur;

/**
 * Centralise les règles d'autorisation sur les colis. Avant cette correction,
 * chaque controller (ColieController::updateColieStatus, CalendrieController::
 * ajouteRendezVous, ClientController::downloadColisPdf/rechercherColier)
 * vérifiait (ou, souvent, NE vérifiait PAS) séparément que le colis appartenait
 * bien à l'utilisateur connecté. C'était la source de plusieurs failles IDOR.
 *
 * Penser à enregistrer cette policy dans AuthServiceProvider :
 *   protected $policies = [
 *       \App\Models\Colis::class => \App\Policies\ColisPolicy::class,
 *   ];
 */
class ColisPolicy
{
    /**
     * Un livreur peut gérer (modifier le statut, planifier un RDV) un colis
     * seulement s'il lui est assigné.
     */
    public function manage(Utilisateur $user, Colis $colis): bool
    {
        return $user->livreur && $colis->id_livreur === $user->livreur->id;
    }

    /**
     * Un livreur peut voir un colis qui lui est assigné, un client peut voir
     * un colis rattaché à l'une de ses commandes.
     */
    public function view(Utilisateur $user, Colis $colis): bool
    {
        if ($user->livreur) {
            return $colis->id_livreur === $user->livreur->id;
        }

        if ($user->client) {
            return $colis->commande && $colis->commande->id_client === $user->client->id;
        }

        return false;
    }
}
