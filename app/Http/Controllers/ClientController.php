<?php

namespace App\Http\Controllers;

use App\Models\Colis;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::now()->locale('fr_FR');
        $colis = null;
        $colieNumber = '';

        return view('dashboard.client.index', compact('user', 'today', 'colis', 'colieNumber'));
    }

    // Bug corrigé : ne renvoyait que id/name/email, ce qui empêchait l'app
    // mobile d'afficher téléphone, ville, adresse, photo et rôle de
    // l'utilisateur connecté (voir services/api.ts côté app).
    public function getUserProfile()
    {
        $user = Auth::user();

        return response()->json([
            'status' => 'success',
            'message' => 'Profil utilisateur récupéré avec succès',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'ville' => $user->ville,
                'adresse' => $user->adresse,
                'photo' => $user->photo ? asset('storage/' . $user->photo) : null,
                'role' => $user->role,
            ],
        ]);
    }

    public function rechercherColis(Request $request)
    {
        $request->validate([
            'colieNumber' => 'nullable|string|max:50',
        ]);

        $user = Auth::user();
        $client = $user->client;
        $today = Carbon::now()->locale('fr_FR');
        $colieNumber = $request->input('colieNumber', '');

        $colis = null;

        // Correction IDOR : l'original cherchait le colis par numéro SANS
        // vérifier qu'il appartenait au client connecté. N'importe quel
        // client pouvait donc consulter les colis (et les infos client
        // associées) de n'importe qui d'autre en devinant un numéro au
        // format CLS-XXXXX.
        if (!empty($colieNumber) && $client) {
            $colis = Colis::where('colie_number', $colieNumber)
                ->whereHas('commande', fn ($q) => $q->where('id_client', $client->id))
                ->with(['commande.client.utilisateur'])
                ->first();
        }

        return view('dashboard.client.index', compact('colis', 'user', 'today', 'colieNumber'));
    }

    public function downloadColisPdf(Colis $colis)
    {
        // Correction IDOR : l'original faisait un simple findOrFail($id),
        // permettant à n'importe quel client de télécharger le PDF de
        // n'importe quel colis en changeant l'ID dans l'URL.
        $this->authorize('view', $colis);

        try {
            $colis->load(['commande.client.utilisateur', 'commande.livreur.utilisateur']);

            $pdf = Pdf::loadView('dashboard.client.colis-pdf', compact('colis'));

            return $pdf->download('colis-' . $colis->colie_number . '.pdf');
        } catch (\Throwable $e) {
            Log::error('Erreur génération PDF colis: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la génération du PDF.');
        }
    }
}