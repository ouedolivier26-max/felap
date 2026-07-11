<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('colies', 'date_arrivee')) {
            Schema::table('colies', function (Blueprint $table) {
                $table->timestamp('date_arrivee')->nullable()->after('date_arrivee_estime');
            });
        }
    }

    public function down(): void
    {
        Schema::table('colies', function (Blueprint $table) {
            $table->dropColumn('date_arrivee');
        });
    }
};
