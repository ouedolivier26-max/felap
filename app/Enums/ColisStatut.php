<?php

namespace App\Enums;

/**
 * Centralise les statuts de colis utilisés dans AdminController, LivreurController,
 * ColisController et CalendrierController. Avant cette correction, la chaîne
 * 'en_route' / 'livree' / 'en_preparation' était recopiée en dur dans chaque
 * controller : une seule faute de frappe (ex: 'livrée' avec accent) aurait cassé
 * silencieusement les statistiques du dashboard.
 */
enum ColisStatut: string
{
    case EnPreparation = 'en_preparation';
    case EnRoute = 'en_route';
    case Livree = 'livree';

    public function label(): string
    {
        return match ($this) {
            self::EnPreparation => 'En préparation',
            self::EnRoute => 'En route',
            self::Livree => 'Livrée',
        };
    }
}
