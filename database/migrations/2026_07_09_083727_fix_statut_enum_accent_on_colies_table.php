<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Normalise les données existantes avant de changer la contrainte ENUM
        DB::statement("UPDATE colies SET statut = 'livree' WHERE statut = 'livrée'");
        DB::statement("ALTER TABLE colies MODIFY statut ENUM('en_preparation', 'en_route', 'livree') DEFAULT 'en_preparation'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE colies MODIFY statut ENUM('en_preparation', 'en_route', 'livrée') DEFAULT 'en_preparation'");
        DB::statement("UPDATE colies SET statut = 'livrée' WHERE statut = 'livree'");
    }
};