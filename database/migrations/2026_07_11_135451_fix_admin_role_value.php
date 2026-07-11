<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('utilisateurs')
            ->where('role', 'admin')
            ->update(['role' => 'administrateur']);
    }

    public function down(): void
    {
        DB::table('utilisateurs')
            ->where('role', 'administrateur')
            ->update(['role' => 'admin']);
    }
};