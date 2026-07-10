<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Commande;
use App\Models\Livreur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function viewProfileLivreurPage()
    {
        $user = Auth::user();
        $livreur = $user->livreur;

        return view('dashboard.livreur.profile', compact('user', 'livreur'));
    }

    public function viewProfileAdminPage()
    {
        $user = Auth::user();
        $admin = $user->administrateur;

        return view('dashboard.admin.profile', [
            'user' => $user,
            'admin' => $admin,
            'clients_count' => Client::count(),
            'livreurs_count' => Livreur::count(),
            'commandes_count' => Commande::count(),
        ]);
    }

    public function viewProfileClientPage()
    {
        $user = Auth::user();

        return view('dashboard.client.index', compact('user'));
    }

    public function updateClientProfile(Request $request)
    {
        $data = $this->validateBaseInfo($request, [
            'ville' => ['required', 'string', 'max:255'],
        ]);

        try {
            $this->updateUserBaseInfo($data);

            return redirect()->back()->with('success', 'Vos informations ont été mises à jour avec succès.');
        } catch (\Throwable $e) {
            Log::error('Erreur mise à jour profil client: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour de vos informations.');
        }
    }

    public function updateLivreurProfile(Request $request)
    {
        $livreur = Auth::user()->livreur;

        // Bug corrigé : la règle 'ville' avait max:20 pour le client mais
        // max:50 pour le livreur/admin sans raison métier apparente.
        // Harmonisé à max:255 partout via validateBaseInfo().
        $data = $this->validateBaseInfo($request, [
            'nom_entreprise' => ['required', 'string', 'max:255'],
            'statut' => ['required', 'in:disponible,indisponible'],
        ]);

        try {
            // Transaction : évite que $user soit mis à jour mais pas
            // $livreur (ou l'inverse) en cas d'échec partiel.
            DB::transaction(function () use ($data, $livreur) {
                $this->updateUserBaseInfo($data);

                $livreur->update([
                    'nom_entreprise' => $data['nom_entreprise'],
                    'statut' => $data['statut'],
                ]);
            });

            return redirect()->back()->with('success', 'Vos informations ont été mises à jour avec succès.');
        } catch (\Throwable $e) {
            Log::error('Erreur mise à jour profil livreur: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour de vos informations.');
        }
    }

    public function updateAdminProfile(Request $request)
    {
        $admin = Auth::user()->administrateur;

        $data = $this->validateBaseInfo($request, [
            'nom_entreprise' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            DB::transaction(function () use ($data, $admin) {
                $this->updateUserBaseInfo($data);

                $admin->update([
                    'nom_entreprise' => $data['nom_entreprise'],
                    'description' => $data['description'] ?? null,
                ]);
            });

            return redirect()->back()->with('success', 'Vos informations ont été mises à jour avec succès.');
        } catch (\Throwable $e) {
            // Bug corrigé : l'original exposait $e->getMessage() (détails
            // techniques) directement dans le message flash utilisateur.
            Log::error('Erreur mise à jour profil admin: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour de vos informations.');
        }
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'current_password' => ['required'],
            'password' => [
                'required',
                'confirmed',
                // Règle de complexité renforcée (au lieu d'un simple min:8).
                Password::min(8)->mixedCase()->numbers()->symbols(),
                // Comparaison de deux valeurs en clair issues du formulaire
                // (pas de comparaison de hash ici) : garantit que le nouveau
                // mot de passe diffère de ce que l'utilisateur vient de
                // taper comme mot de passe actuel.
                'different:current_password',
            ],
        ]);

        if (!Hash::check($data['current_password'], $user->password)) {
            return redirect()->back()->with('error', 'Le mot de passe actuel est incorrect.');
        }

        try {
            $user->update(['password' => Hash::make($data['password'])]);

            // Sécurité ajoutée : invalide les autres sessions actives après
            // un changement de mot de passe (utile si le compte a pu être
            // compromis).
            Auth::logoutOtherDevices($data['password']);

            return redirect()->back()->with('success', 'Votre mot de passe a été mis à jour avec succès.');
        } catch (\Throwable $e) {
            Log::error('Erreur mise à jour mot de passe: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour de votre mot de passe.');
        }
    }

    public function updatePhoto(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'photo' => ['required', 'image', 'max:2048'],
        ]);

        try {
            $oldPhoto = $user->photo;

            // Bug corrigé : l'original supprimait l'ancienne photo AVANT de
            // confirmer que la nouvelle était bien enregistrée en base. Si
            // update() échouait ensuite, l'utilisateur se retrouvait sans
            // aucune photo. On upload et on met à jour la BDD d'abord, on ne
            // supprime l'ancienne qu'après succès.
            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->update(['photo' => $path]);

            if ($oldPhoto) {
                // Bug corrigé : Storage::delete() sans ->disk('public')
                // utilisait le disk par défaut, différent de celui utilisé
                // pour le store() -> l'ancienne photo n'était en réalité
                // jamais supprimée du disque.
                Storage::disk('public')->delete($oldPhoto);
            }

            return redirect()->back()->with('success', 'Votre photo de profil a été mise à jour avec succès.');
        } catch (\Throwable $e) {
            Log::error('Erreur mise à jour photo profil: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour de votre photo de profil.');
        }
    }

    /**
     * Factorise la validation commune à updateClientProfile,
     * updateLivreurProfile et updateAdminProfile (name/email/phone/ville/
     * adresse étaient dupliqués 3 fois avec une incohérence sur 'ville').
     */
    private function validateBaseInfo(Request $request, array $extraRules): array
    {
        $user = Auth::user();

        return $request->validate(array_merge([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('utilisateurs')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:20'],
            'ville' => ['required', 'string', 'max:255'],
            'adresse' => ['required', 'string', 'max:255'],
        ], $extraRules));
    }

    /**
     * Factorise la mise à jour des champs communs sur Utilisateur, dupliquée
     * dans les 3 méthodes update*Profile de l'original.
     */
    private function updateUserBaseInfo(array $data): void
    {
        Auth::user()->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'ville' => $data['ville'],
            'adresse' => $data['adresse'],
        ]);
    }
}
