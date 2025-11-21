<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patrimoines', function (Blueprint $table) {
            $table->id('id_element');
            $table->string('nom');
            $table->string('initiale')->nullable();
            $table->text('description');
            $table->enum('status', ['propose', 'inscrit', 'classe', 'rejete'])->default('propose');
            $table->date('date_inscription')->nullable();
            $table->string('localisation')->nullable();
            $table->string('region')->nullable();
            $table->foreignId('id_categorie')->constrained('categories', 'id_categorie');

            // NOUVEAUX CHAMPS
            $table->enum('domaine', ['CPNU', 'PSREF', 'ADS', 'SFAT', 'TEO'])->nullable();
            $table->integer('numero_element')->nullable();

            $table->text('historique')->nullable();
            $table->text('caracteristiques')->nullable();
            $table->boolean('est_urgent')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patrimoines');
    }
};
