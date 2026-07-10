<?php

namespace App\Http\Controllers;

use App\Enums\NotificationStatut;
use App\Models\Commande;
use App\Models\Notification;
use App\Models\Paiement;
use App\Services\DashboardStatsService;
use Illuminate\Support\Facades\Auth;

class LivreurController extends Controller
{
    public function __construct(private readonly DashboardStatsService $statsService)
    {
    }

    public function index()
    {
        $user = Auth::user();
        $livreur = $user->livreur;
        abort_if(!$livreur, 403, 'Profil livreur introuvable pour cet utilisateur.');

        $notifications = Notification::where('id_utilisateur', $user->id)
            ->where('statut', NotificationStatut::NonLue->value)
            ->latest()
            ->take(4)
            ->get();

        $stats = $this->statsService->getStats($livreur->id);

        // Bug corrigé : l'original ne filtrait pas par livreur, exposant les
        // 4 dernières commandes de TOUS les livreurs (avec les infos client
        // associées) sur le dashboard de chaque livreur.
        $commandes = Commande::where('id_livreur', $livreur->id)
            ->with('client.utilisateur')
            ->latest()
            ->take(4)
            ->get();

        // Bug corrigé : même souci pour les paiements, sans filtre ET sans
        // tri (take(4) seul ne garantit pas les 4 plus récents).
        $paiements = Paiement::whereHas('colis', fn ($q) => $q->where('id_livreur', $livreur->id))
            ->with('colis.commande.client.utilisateur')
            ->latest()
            ->take(4)
            ->get();

        // NOTE : la liste des autres livreurs disponibles (`$livreurs`) a été
        // retirée. Dans l'original, un livreur voyait l'identité et les
        // coordonnées de TOUS les autres livreurs disponibles sans qu'aucune
        // fonctionnalité connue de ce controller ne l'exploite. Si c'est
        // voulu (ex: réassignation de commande entre livreurs), réintroduisez
        // la requête en ne sélectionnant que les colonnes strictement
        // nécessaires à l'affichage.

        return view('dashboard.livreur.index', [
            'user' => $user,
            'notifications' => $notifications,
            'commandes' => $commandes,
            'paiements' => $paiements,
            ...$stats,
        ]);
    }
}
