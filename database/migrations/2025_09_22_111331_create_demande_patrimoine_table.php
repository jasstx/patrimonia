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
        Schema::create('demande_patrimoine', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_demande')->constrained('demandes', 'id_demande')->onDelete('cascade');
            $table->foreignId('id_patrimoine')->constrained('patrimoines', 'id_element')->onDelete('cascade');
            $table->text('relation_detenteur')->nullable(); // Comment le détenteur est lié à l'élément
            $table->integer('anciennete')->nullable(); // Nombre d'années de détention
            $table->text('preuves_detention')->nullable();
            $table->timestamps();

            $table->unique(['id_demande', 'id_patrimoine']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_patrimoine');
    }
};
