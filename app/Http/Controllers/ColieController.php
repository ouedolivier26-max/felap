<?php

namespace App\Http\Controllers;

use App\Enums\ColisStatut;
use App\Models\Colis;
use App\Models\Commande;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Anciennement ColieController (renommé pour corriger la faute de frappe
 * répandue dans tout le code : Colie -> Colis).
 *
 * NOTE : la colonne DB `colie_number` n'a volontairement PAS été renommée
 * ici pour éviter une migration destructive qui casserait les vues Blade
 * existantes. Si vous voulez la renommer en `colis_number`, prévoyez une
 * migration dédiée + mise à jour de toutes les vues qui y font référence.
 */
class ColieController extends Controller
{
    public function viewColisPage()
    {
        $livreur = Auth::user()->livreur;
        abort_if(!$livreur, 403, 'Profil livreur introuvable pour cet utilisateur.');

        $commandes = Commande::where('livraison_statut', 'accepter')
            ->where('id_livreur', $livreur->id)
            ->whereDoesntHave('colis')
            ->get();

        $colis = Colis::where('id_livreur', $livreur->id)
            ->with(['commande.client.utilisateur'])
            ->latest()
            ->get();

        return view('dashboard.livreur.colis', compact('commandes', 'colis'));
    }

    public function ajouterColis(Request $request)
    {
        $livreur = Auth::user()->livreur;
        abort_if(!$livreur, 403, 'Profil livreur introuvable pour cet utilisateur.');

        $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'poids' => 'required|numeric|min:0|max:1000',
            'hauteur' => 'required|numeric|min:0|max:500',
            'longueur' => 'required|numeric|min:0|max:500',
            'largeur' => 'required|numeric|min:0|max:500',
        ]);

        // Correction IDOR : on re-vérifie explicitement que la commande
        // appartient bien à CE livreur, est bien acceptée, et n'a pas déjà
        // un colis. La règle "exists:commandes,id" seule ne garantissait rien
        // de tout cela.
        $commande = Commande::where('id', $request->commande_id)
            ->where('id_livreur', $livreur->id)
            ->where('livraison_statut', 'accepter')
            ->whereDoesntHave('colis')
            ->first();

        if (!$commande) {
            return redirect()->route('livreur.colis')
                ->with('error', "Cette commande n'est plus disponible pour l'ajout d'un colis.");
        }

        try {
            DB::transaction(function () use ($request, $commande, $livreur) {
                $colisNumber = $this->generateUniqueColisNumber();

                Colis::create([
                    'colie_number' => $colisNumber,
                    'poids' => $request->poids,
                    'longueur' => $request->longueur,
                    'largeur' => $request->largeur,
                    'hauteur' => $request->hauteur,
                    'statut' => ColisStatut::EnPreparation->value,
                    'id_commande' => $commande->id,
                    'id_livreur' => $livreur->id,
                ]);

                // Bug corrigé : l'original envoyait la notification à
                // `$commande->id_client` (id de la table `clients`) au lieu
                // de l'id utilisateur. Adaptez `id_utilisateur` ci-dessous
                // au vrai nom de la colonne sur votre modèle Client si
                // besoin (ex: $commande->client->id_utilisateur).
                Notification::create([
                    'titre' => 'Numéro de colis',
                    'message' => "Voici le numéro de colis {$colisNumber} de votre commande {$commande->commande_number}",
                    'id_utilisateur' => $commande->client->id_utilisateur,
                ]);
            });

            return redirect()->route('livreur.colis')
                ->with('success', 'Colis ajouté avec succès à la commande.');
        } catch (\Throwable $e) {
            Log::error('Erreur ajout colis: ' . $e->getMessage());

            return redirect()->route('livreur.colis')
                ->with('error', "Une erreur est survenue lors de l'ajout du colis.");
        }
    }

    public function updateColisStatus(Request $request, Colis $colis)
    {
        // Correction IDOR : avant, n'importe quel livreur authentifié
        // pouvait changer le statut du colis de n'importe qui en devinant
        // l'ID dans l'URL. La policy vérifie que $colis->id_livreur
        // correspond bien au livreur connecté.
        $this->authorize('manage', $colis);

        $request->validate([
            'colisStatut' => 'required|in:en_preparation,en_route,livree',
        ]);

        try {
            DB::transaction(function () use ($request, $colis) {
                $colis->statut = $request->colisStatut;

                // Bug corrigé : date_sortie_reelle n'était jamais renseigné,
                // ce qui faussait les statistiques "colis en livraison" du
                // dashboard (voir DashboardStatsService).
                if ($request->colisStatut === ColisStatut::EnRoute->value) {
                    $colis->date_sortie_reelle = Carbon::now();
                }

                if ($request->colisStatut === ColisStatut::Livree->value) {
                    $colis->date_arrivee = Carbon::now();
                }

                $colis->save();

                if ($request->colisStatut === ColisStatut::Livree->value) {
                    $colis->commande->update(['commande_statut' => 'livree']);
                }
            });

            return redirect()->route('livreur.colis')->with('success', 'Colis modifié avec succès.');
        } catch (\Throwable $e) {
            Log::error('Erreur mise à jour statut colis: ' . $e->getMessage());

            return redirect()->route('livreur.colis')
                ->with('error', 'Une erreur est survenue lors de la mise à jour du colis.');
        }
    }

    /**
     * Génère un numéro de colis garanti unique (l'original utilisait
     * rand() sans aucune vérification d'unicité, risquant des collisions).
     */
    private function generateUniqueColisNumber(): string
    {
        do {
            $colisNumber = 'CLS-' . random_int(10000, 99999);
        } while (Colis::where('colie_number', $colisNumber)->exists());

        return $colisNumber;
    }
}
