<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('colies', function (Blueprint $table) {
            $table->unsignedBigInteger('id_livreur')->nullable()->after('id_commande');
            $table->foreign('id_livreur')->references('id')->on('livreurs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('colies', function (Blueprint $table) {
            $table->dropForeign(['id_livreur']);
            $table->dropColumn('id_livreur');
        });
    }
};