<?php

namespace App\Http\Controllers;

use App\Enums\NotificationStatut;
use App\Models\Client;
use App\Models\Notification;
use App\Services\DashboardStatsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class AdminController extends Controller
{
    public function __construct(private readonly DashboardStatsService $statsService)
    {
    }

    public function index()
    {
        $user = Auth::user();
        $notifications = Notification::where('id_utilisateur', $user->id)
            ->where('statut', NotificationStatut::NonLue->value)
            ->latest()
            ->take(4)
            ->get();

        // Toute la logique de calcul (revenus, commandes, colis livrés/en
        // livraison) vient maintenant du même service que LivreurController,
        // ce qui garantit que le bug de filtre de date corrigé ici profite
        // aussi bien à l'admin qu'au livreur.
        $stats = $this->statsService->getStats();

        return view('dashboard.admin.index', [
            'user' => $user,
            'notifications' => $notifications,
            ...$stats,
        ]);
    }

    public function viewClientPage()
    {
        // Paginé plutôt que ->get() : évite de charger toute la table en
        // mémoire si la base clients grossit.
        $clients = Client::with('utilisateur')->paginate(20);

        return view('dashboard.admin.clients', compact('clients'));
    }

    /**
     * Endpoint AJAX appelé par les boutons jours/mois/années du dashboard
     * (dashboard.admin.index). Retourne les données du line chart pour la
     * granularité demandée.
     */
    public function ordersChartData(string $granularity): JsonResponse
    {
        try {
            $data = $this->statsService->getOrdersChart($granularity);
        } catch (InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json($data);
    }
}
