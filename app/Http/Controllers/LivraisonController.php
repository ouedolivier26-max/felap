<?php

namespace App\Http\Controllers;

use App\Mail\SendEmailLivreur;
use App\Models\Livreur;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * NOTE : la classe Mail d'origine s'appelait `sendEmailLivreur` (minuscule),
 * ce qui viole la convention PascalCase des classes PHP. Pensez à renommer
 * le fichier app/Mail/sendEmailLivreur.php en SendEmailLivreur.php et sa
 * classe en conséquence.
 */
class LivraisonController extends Controller
{
    public function viewLivraisonPage()
    {
        $livreurs = Livreur::with('utilisateur')->get();

        return view('dashboard.admin.livraison', compact('livreurs'));
    }

    public function ajouterLivreur(Request $request)
    {
        $request->validate([
            'nom_livreur' => 'required|string|max:255',
            'nom_entreprise' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'phone' => 'required|string|max:20',
            'ville' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
        ]);

        // Mot de passe fort et non prévisible (l'original utilisait rand()
        // et un format fixe "XX!@" facilement devinable).
        $password = Str::password(12);

        try {
            // Transaction : si la création du Livreur échoue après celle de
            // l'Utilisateur, l'original laissait un compte utilisateur
            // "fantôme" sans profil livreur associé.
            DB::transaction(function () use ($request, $password) {
                $utilisateur = Utilisateur::create([
                    'name' => $request->nom_livreur,
                    'email' => $request->email,
                    'password' => Hash::make($password),
                    'role' => 'livreur',
                    'phone' => $request->phone,
                    'ville' => $request->ville,
                    'adresse' => $request->adresse,
                    'must_change_password' => true,
                ]);

                Livreur::create([
                    'id' => $utilisateur->id,
                    'nom_livreur' => $request->nom_livreur,
                    'nom_entreprise' => $request->nom_entreprise,
                    'statut' => 'disponible',
                ]);
            });
        } catch (\Throwable $e) {
            Log::error('Erreur création livreur: ' . $e->getMessage());

            // Bug corrigé : la redirection pointait vers 'admin.commandes'
            // au lieu de 'admin.livraison' (probable copier-coller).
            return redirect()->route('admin.livraison')
                ->with('error', "Une erreur est survenue lors de l'ajout du livreur.");
        }

        // L'envoi d'email est isolé dans son propre bloc : si le compte a
        // bien été créé mais que le mail échoue (SMTP down...), on ne veut
        // pas afficher un message d'erreur trompeur qui pousserait l'admin
        // à recréer un doublon.
        try {
            Mail::to($request->email)->queue(new SendEmailLivreur(
                $request->nom_livreur,
                $request->email,
                $password,
            ));
        } catch (\Throwable $e) {
            Log::error('Erreur envoi email livreur: ' . $e->getMessage());

            return redirect()->route('admin.livraison')
                ->with('success', "Livreur ajouté avec succès, mais l'email de bienvenue n'a pas pu être envoyé.");
        }

        return redirect()->route('admin.livraison')
            ->with('success', 'Livreur ajouté avec succès.');
    }

    public function deleteLivreur(Livreur $livreur)
    {
        try {
            $hasCommandes = $livreur->commandes()->count() > 0;

            // Ajout : on bloque aussi la suppression si des colis actifs
            // (en_preparation / en_route) sont en cours, pas seulement s'il
            // existe des commandes.
            $hasActiveColis = $livreur->colis()
                ->whereIn('statut', ['en_preparation', 'en_route'])
                ->count() > 0;

            if ($hasCommandes || $hasActiveColis) {
                $livreur->update(['statut' => 'indisponible']);

                return redirect()->route('admin.livraison')
                    ->with('warning', 'Le livreur a des commandes ou colis associés : il a été désactivé au lieu d\'être supprimé.');
            }

            DB::transaction(function () use ($livreur) {
                $utilisateur = $livreur->utilisateur;
                $livreur->delete();
                $utilisateur?->delete();
            });

            return redirect()->route('admin.livraison')
                ->with('success', 'Livreur supprimé avec succès.');
        } catch (\Throwable $e) {
            Log::error('Erreur suppression livreur: ' . $e->getMessage());

            // Bug corrigé : le message d'erreur original exposait
            // $e->getMessage() (détails techniques/SQL) directement à
            // l'utilisateur admin dans l'interface.
            return redirect()->route('admin.livraison')
                ->with('error', 'Une erreur est survenue lors de la suppression du livreur.');
        }
    }
}
