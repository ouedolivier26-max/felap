<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('adresse_colie', function (Blueprint $table) {
            $table->string('ville')->nullable()->after('derniere_adresse');
        });
    }

    public function down(): void
    {
        Schema::table('adresse_colie', function (Blueprint $table) {
            $table->dropColumn('ville');
        });
    }
};