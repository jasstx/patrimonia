<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('demandeurs', function (Blueprint $table) {
            // Supprimer l'ancienne colonne
            $table->dropColumn('localite_exercice');
            
            // Ajouter les nouvelles colonnes
            $table->string('province')->nullable()->after('profession');
            $table->string('commune')->nullable()->after('province');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demandeurs', function (Blueprint $table) {
            // Supprimer les nouvelles colonnes
            $table->dropColumn(['province', 'commune']);
            
            // Restaurer l'ancienne colonne
            $table->string('localite_exercice')->nullable()->after('profession');
        });
    }
};
