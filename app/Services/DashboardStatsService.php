<?php

namespace App\Services;

use App\Enums\ColisStatut;
use App\Models\Colis;
use App\Models\Commande;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * AdminController::index() et LivreurController::index() faisaient exactement
 * les mêmes calculs, avec pour seule différence un filtre optionnel sur
 * id_livreur. Ce service centralise cette logique :
 *   - une seule correction de bug profite aux deux dashboards,
 *   - le bug "todayOrdersInDelivery sans filtre de date" (AdminController)
 *     est corrigé une fois pour toutes ici.
 *
 * NOTE IMPORTANTE sur date_sortie / date_sortie_reelle :
 * Dans le code original, `date_sortie` sert à la fois de "date de rendez-vous
 * planifiée" (CalendrierController) et de "date réelle de sortie" (stats).
 * Ces deux usages sont incompatibles : un colis avec un RDV programmé dans 3
 * jours (statut encore en_preparation) polluait déjà les statistiques du mois
 * en cours. Ce service utilise donc une nouvelle colonne `date_sortie_reelle`,
 * renseignée uniquement quand le statut passe réellement à `en_route`
 * (voir ColisController::updateColisStatus). Une migration est fournie
 * (add_date_sortie_reelle_to_colis_table) pour ajouter cette colonne.
 *
 * NOTE sur revenusParVille :
 * Il n'existe pas de colonne "ville" structurée sur les colis ou les
 * commandes. La seule source fiable est `utilisateurs.ville`, renseignée à
 * la création du compte client (voir CommandesController::ajouteCommande).
 * On rejoint donc commandes -> clients -> utilisateurs pour regrouper les
 * revenus par ville. Les commandes dont le client n'a pas de ville
 * renseignée (comptes créés avant l'ajout de ce champ, ou via un autre flux)
 * sont exclues via whereNotNull plutôt que comptées sous une ville vide.
 */
class DashboardStatsService
{
    public function getStats(?int $livreurId = null): array
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $now = Carbon::now();

        $baseCommandeQuery = fn () => Commande::query()
            ->when($livreurId, fn ($q) => $q->where('id_livreur', $livreurId));

        $baseColisQuery = fn () => Colis::query()
            ->when($livreurId, fn ($q) => $q->where('id_livreur', $livreurId));

        return [
            'todayRevenue' => $baseCommandeQuery()
                ->whereDate('date_commande', $today)
                ->where('paiement_status', 1)
                ->sum('total_a_payer'),

            'monthlyRevenue' => $baseCommandeQuery()
                ->whereBetween('date_commande', [$startOfMonth, $now])
                ->where('paiement_status', 1)
                ->sum('total_a_payer'),

            'todayTotalOrders' => $baseCommandeQuery()
                ->whereDate('date_commande', $today)
                ->count(),

            'monthlyTotalOrders' => $baseCommandeQuery()
                ->whereBetween('date_commande', [$startOfMonth, $now])
                ->count(),

            'todayDeliveredColis' => $baseColisQuery()
                ->where('statut', ColisStatut::Livree->value)
                ->whereDate('date_arrivee', $today)
                ->count(),

            'monthlyDeliveredColis' => $baseColisQuery()
                ->where('statut', ColisStatut::Livree->value)
                ->whereBetween('date_arrivee', [$startOfMonth, $now])
                ->count(),

            // Bug corrigé : l'original ne filtrait pas par date du tout ici,
            // il comptait TOUS les colis en_route depuis toujours.
            'todayColisInDelivery' => $baseColisQuery()
                ->where('statut', ColisStatut::EnRoute->value)
                ->whereDate('date_sortie_reelle', $today)
                ->count(),

            'monthlyColisInDelivery' => $baseColisQuery()
                ->where('statut', ColisStatut::EnRoute->value)
                ->whereBetween('date_sortie_reelle', [$startOfMonth, $now])
                ->count(),

            'revenusParVille' => $baseCommandeQuery()
                ->join('clients', 'clients.id', '=', 'commandes.id_client')
                ->join('utilisateurs', 'utilisateurs.id', '=', 'clients.id')
                ->where('commandes.paiement_status', 1)
                ->whereNotNull('utilisateurs.ville')
                ->select('utilisateurs.ville', DB::raw('SUM(commandes.total_a_payer) as total'))
                ->groupBy('utilisateurs.ville')
                ->orderByDesc('total')
                ->limit(6)
                ->get(),
        ];
    }
}