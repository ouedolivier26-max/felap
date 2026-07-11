<?php

namespace App\Http\Controllers;

use App\Enums\ColisStatut;
use App\Models\Colis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CalendrierController extends Controller
{
    public function viewCalendrierPage()
    {
        $livreur = Auth::user()->livreur;
        abort_if(!$livreur, 403, 'Profil livreur introuvable pour cet utilisateur.');

        // Bug corrigé : l'original ne filtrait pas du tout par livreur, donc
        // un livreur voyait le calendrier de TOUS les livreurs.
        $colis = Colis::where('id_livreur', $livreur->id)
            ->whereNull('date_sortie')
            ->with(['commande.client.utilisateur'])
            ->get();

        $colisLivraison = Colis::where('id_livreur', $livreur->id)
            ->whereNotNull('date_sortie')
            ->with(['commande.client.utilisateur'])
            ->get();

        return view('dashboard.livreur.calendrie', compact('colis', 'colisLivraison'));
    }

    public function ajouterRendezVous(Request $request)
    {
        $livreur = Auth::user()->livreur;
        abort_if(!$livreur, 403, 'Profil livreur introuvable pour cet utilisateur.');

        $request->validate([
            'colis_id' => 'required|exists:colis,id',
            'date_sortie' => 'required|date|after_or_equal:today',
            'heure_sortie' => 'required|date_format:H:i',
        ]);

        // Correction IDOR : on vérifie explicitement que le colis appartient
        // au livreur connecté avant de le modifier, au lieu d'un simple
        // findOrFail($request->colis_id).
        $colis = Colis::where('id', $request->colis_id)
            ->where('id_livreur', $livreur->id)
            ->first();

        if (!$colis) {
            return redirect()->back()->with('error', 'Colis introuvable ou non autorisé.');
        }

        if ($colis->statut === ColisStatut::Livree->value) {
            return redirect()->back()
                ->with('error', 'Ce colis a déjà été livré, impossible de planifier un rendez-vous.');
        }

        try {
            $colis->update([
                'date_sortie' => $request->date_sortie,
                'heure_sortie' => $request->heure_sortie,
            ]);

            return redirect()->back()->with('success', 'Rendez-vous de livraison ajouté avec succès.');
        } catch (\Throwable $e) {
            Log::error('Erreur ajout rendez-vous: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', "Une erreur est survenue lors de l'ajout du rendez-vous.")
                ->withInput();
        }
    }
}
