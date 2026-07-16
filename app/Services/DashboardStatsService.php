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
 *
 * AJOUT (variations % + graphique mensuel) :
 * - Les 4 cartes KPI du dashboard affichaient un badge "+2.5%" codé en dur,
 *   trompeur pour l'admin. Chaque métrique "today" est maintenant comparée à
 *   la même métrique "hier", et chaque métrique "monthly" à la même période
 *   du mois précédent (du 1er au même jour relatif), pour une comparaison
 *   équitable plutôt que mois précédent complet vs mois en cours partiel.
 * - Le line chart du dashboard utilisait un tableau de valeurs statiques
 *   ([200, 300, 250, ...]). `ordersByMonth` fournit désormais le vrai nombre
 *   de commandes par mois sur les 12 derniers mois glissants, mois manquants
 *   inclus avec 0 (pas de "trous" dans le graphique).
 */
class DashboardStatsService
{
    public function getStats(?int $livreurId = null): array
    {
        $today = Carbon::today();
        $yesterday = $today->copy()->subDay();
        $startOfMonth = Carbon::now()->startOfMonth();
        $now = Carbon::now();

        // Même période relative sur le mois précédent (ex: on est le 16 ->
        // on compare au 1-16 du mois dernier), pour une variation % qui
        // compare des périodes de durée équivalente.
        $startOfLastMonth = $startOfMonth->copy()->subMonthNoOverflow();
        $sameDayLastMonth = $now->copy()->subMonthNoOverflow();

        $baseCommandeQuery = fn () => Commande::query()
            ->when($livreurId, fn ($q) => $q->where('id_livreur', $livreurId));

        $baseColisQuery = fn () => Colis::query()
            ->when($livreurId, fn ($q) => $q->where('id_livreur', $livreurId));

        // --- Valeurs "aujourd'hui" / "ce mois-ci" (inchangé) ---

        $todayRevenue = $baseCommandeQuery()
            ->whereDate('date_commande', $today)
            ->where('paiement_status', 1)
            ->sum('total_a_payer');

        $monthlyRevenue = $baseCommandeQuery()
            ->whereBetween('date_commande', [$startOfMonth, $now])
            ->where('paiement_status', 1)
            ->sum('total_a_payer');

        $todayTotalOrders = $baseCommandeQuery()
            ->whereDate('date_commande', $today)
            ->count();

        $monthlyTotalOrders = $baseCommandeQuery()
            ->whereBetween('date_commande', [$startOfMonth, $now])
            ->count();

        $todayDeliveredColis = $baseColisQuery()
            ->where('statut', ColisStatut::Livree->value)
            ->whereDate('date_arrivee', $today)
            ->count();

        $monthlyDeliveredColis = $baseColisQuery()
            ->where('statut', ColisStatut::Livree->value)
            ->whereBetween('date_arrivee', [$startOfMonth, $now])
            ->count();

        $todayColisInDelivery = $baseColisQuery()
            ->where('statut', ColisStatut::EnRoute->value)
            ->whereDate('date_sortie_reelle', $today)
            ->count();

        $monthlyColisInDelivery = $baseColisQuery()
            ->where('statut', ColisStatut::EnRoute->value)
            ->whereBetween('date_sortie_reelle', [$startOfMonth, $now])
            ->count();

        // --- Valeurs "hier" / "même période le mois dernier" (comparaison) ---

        $yesterdayRevenue = $baseCommandeQuery()
            ->whereDate('date_commande', $yesterday)
            ->where('paiement_status', 1)
            ->sum('total_a_payer');

        $lastMonthRevenue = $baseCommandeQuery()
            ->whereBetween('date_commande', [$startOfLastMonth, $sameDayLastMonth])
            ->where('paiement_status', 1)
            ->sum('total_a_payer');

        $yesterdayTotalOrders = $baseCommandeQuery()
            ->whereDate('date_commande', $yesterday)
            ->count();

        $lastMonthTotalOrders = $baseCommandeQuery()
            ->whereBetween('date_commande', [$startOfLastMonth, $sameDayLastMonth])
            ->count();

        $yesterdayDeliveredColis = $baseColisQuery()
            ->where('statut', ColisStatut::Livree->value)
            ->whereDate('date_arrivee', $yesterday)
            ->count();

        $lastMonthDeliveredColis = $baseColisQuery()
            ->where('statut', ColisStatut::Livree->value)
            ->whereBetween('date_arrivee', [$startOfLastMonth, $sameDayLastMonth])
            ->count();

        $yesterdayColisInDelivery = $baseColisQuery()
            ->where('statut', ColisStatut::EnRoute->value)
            ->whereDate('date_sortie_reelle', $yesterday)
            ->count();

        $lastMonthColisInDelivery = $baseColisQuery()
            ->where('statut', ColisStatut::EnRoute->value)
            ->whereBetween('date_sortie_reelle', [$startOfLastMonth, $sameDayLastMonth])
            ->count();

        return [
            'todayRevenue' => $todayRevenue,
            'monthlyRevenue' => $monthlyRevenue,
            'todayTotalOrders' => $todayTotalOrders,
            'monthlyTotalOrders' => $monthlyTotalOrders,
            'todayDeliveredColis' => $todayDeliveredColis,
            'monthlyDeliveredColis' => $monthlyDeliveredColis,
            'todayColisInDelivery' => $todayColisInDelivery,
            'monthlyColisInDelivery' => $monthlyColisInDelivery,

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

            // Variations % : null signifie "pas de donnée comparable"
            // (période précédente à zéro) plutôt qu'un faux "+100%".
            'revenueChange' => $this->percentChange($todayRevenue, $yesterdayRevenue),
            'monthlyRevenueChange' => $this->percentChange($monthlyRevenue, $lastMonthRevenue),
            'ordersChange' => $this->percentChange($todayTotalOrders, $yesterdayTotalOrders),
            'monthlyOrdersChange' => $this->percentChange($monthlyTotalOrders, $lastMonthTotalOrders),
            'deliveredChange' => $this->percentChange($todayDeliveredColis, $yesterdayDeliveredColis),
            'monthlyDeliveredChange' => $this->percentChange($monthlyDeliveredColis, $lastMonthDeliveredColis),
            'inDeliveryChange' => $this->percentChange($todayColisInDelivery, $yesterdayColisInDelivery),
            'monthlyInDeliveryChange' => $this->percentChange($monthlyColisInDelivery, $lastMonthColisInDelivery),

            'ordersByMonth' => $this->getOrdersByMonth($livreurId),
        ];
    }

    /**
     * Variation en % entre deux valeurs. Retourne null si la valeur
     * précédente est nulle ou nulle-zéro (division impossible / non
     * significative), plutôt que de renvoyer un +100% ou +Infini trompeur.
     */
    private function percentChange(int|float $current, int|float $previous): ?float
    {
        if ($previous == 0) {
            return null;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    /**
     * Nombre de commandes par mois sur les 12 derniers mois glissants
     * (mois courant inclus). Les mois sans commande apparaissent avec 0
     * plutôt que d'être absents, pour ne pas casser l'alignement des labels
     * du line chart.
     */
    private function getOrdersByMonth(?int $livreurId = null): array
    {
        $start = Carbon::now()->startOfMonth()->subMonths(11);

        $raw = Commande::query()
            ->when($livreurId, fn ($q) => $q->where('id_livreur', $livreurId))
            ->where('date_commande', '>=', $start)
            ->select(
                DB::raw("DATE_FORMAT(date_commande, '%Y-%m') as ym"),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('ym')
            ->pluck('total', 'ym');

        $labels = [];
        $data = [];
        $cursor = $start->copy();

        for ($i = 0; $i < 12; $i++) {
            $key = $cursor->format('Y-m');
            $labels[] = ucfirst($cursor->translatedFormat('M'));
            $data[] = (int) ($raw[$key] ?? 0);
            $cursor->addMonth();
        }

        return ['labels' => $labels, 'data' => $data];
    }
}
