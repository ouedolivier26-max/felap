<?php

namespace App\Http\Controllers;

use App\Enums\NotificationStatut;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

/**
 * Fusionne viewNotificationsAdminPage / viewNotificationsLivreurPage /
 * viewNotificationsClientPage (strictement identiques dans l'original) en
 * une seule méthode `index()`. La vue est résolue dynamiquement selon le
 * rôle de l'utilisateur connecté.
 *
 * IMPORTANT : Utilisateur::$role vaut 'administrateur', 'livreur' ou
 * 'client', mais les dossiers de vues s'appellent dashboard.admin.*,
 * dashboard.livreur.*, dashboard.client.* — 'administrateur' ne correspond
 * pas directement à 'admin'. On passe donc par $roleToViewFolder pour
 * mapper explicitement le rôle vers le bon dossier plutôt que de supposer
 * une correspondance 1:1 entre la colonne role et le nom du dossier.
 *
 * Côté routes, cela signifie qu'une seule route (ex: GET /notifications)
 * suffit pour les 3 rôles, à la place des 3 routes séparées existantes.
 */
class NotificationsController extends Controller
{
    /**
     * Mapping entre la valeur de Utilisateur::$role et le nom du dossier
     * de vues correspondant sous resources/views/dashboard/.
     */
    private const ROLE_TO_VIEW_FOLDER = [
        'administrateur' => 'admin',
        'livreur' => 'livreur',
        'client' => 'client',
    ];

    public function index()
    {
        $user = Auth::user();

        $viewFolder = self::ROLE_TO_VIEW_FOLDER[$user->role] ?? abort(404);

        $notifications = Notification::where('id_utilisateur', $user->id)
            ->where('statut', NotificationStatut::NonLue->value)
            ->latest()
            ->paginate(10);

        return view("dashboard.{$viewFolder}.notifications", compact('notifications'));
    }

    public function lireNotification(Notification $notification)
    {
        // Remplace le pattern manuel where('id_utilisateur', ...)->first()
        // par une Policy réutilisable. Comportement identique et sécurisé,
        // mais centralisé dans NotificationPolicy.
        $this->authorize('update', $notification);

        $notification->update(['statut' => NotificationStatut::Lue->value]);

        return redirect()->back()->with('success', 'Notification marquée comme lue.');
    }

    public function lireToutesNotifications()
    {
        $count = Notification::where('id_utilisateur', Auth::id())
            ->where('statut', NotificationStatut::NonLue->value)
            ->update(['statut' => NotificationStatut::Lue->value]);

        return redirect()->back()
            ->with('success', "{$count} notification(s) marquée(s) comme lue(s).");
    }
}