<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sépare la date de rendez-vous PLANIFIÉE (date_sortie, gérée par
     * CalendrierController) de la date de sortie RÉELLE (renseignée
     * automatiquement quand le statut du colis passe à "en_route").
     * Voir DashboardStatsService pour le contexte complet du bug corrigé.
     */
    public function up(): void
    {
        Schema::table('colies', function (Blueprint $table) {
            $table->timestamp('date_sortie_reelle')->nullable()->after('date_arrivee_estime');
        });
    }

    public function down(): void
    {
        Schema::table('colies', function (Blueprint $table) {
            $table->dropColumn('date_sortie_reelle');
        });
    }
};
