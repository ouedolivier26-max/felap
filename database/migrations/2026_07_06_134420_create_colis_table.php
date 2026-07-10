<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ColisStatut; // importer ton enum

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('colis', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('expediteur');
            $table->string('destinataire');
            $table->string('adresse');

            // Utilisation de l'enum pour le champ statut
            $table->enum('statut', [
                ColisStatut::EnPreparation->value,
                ColisStatut::EnRoute->value,
                ColisStatut::Livree->value,
            ])->default(ColisStatut::EnPreparation->value);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colis');
    }
};
