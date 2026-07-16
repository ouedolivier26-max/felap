<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livreur extends Model
{
    use HasFactory;

    protected $table = 'livreurs';

    // Bug corrigé : sans cette ligne, Eloquent ignorait l'id fourni dans
    // Livreur::create(['id' => $utilisateur->id, ...]) et laissait MySQL
    // auto-incrémenter, cassant la relation utilisateur() (clé partagée
    // avec la table utilisateurs).
    public $incrementing = false;

    // À confirmer selon le type réel de livreurs.id dans la migration
    // (probablement 'int' si unsignedBigInteger sans auto-increment).
    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'nom_entreprise',
        'nom_livreur',
        'statut',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id');
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class, 'id_livreur');
    }

    // Bug corrigé : renommé de colie() à colis() — LivraisonController::
    // deleteLivreur() appelle $livreur->colis(), qui n'existait pas et
    // levait un BadMethodCallException à chaque tentative de suppression.
    public function colis()
    {
        return $this->hasMany(Colis::class, 'id_livreur');
    }
}
