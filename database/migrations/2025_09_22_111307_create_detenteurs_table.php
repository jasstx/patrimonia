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
        Schema::create('detenteurs', function (Blueprint $table) {
            $table->id('id_detenteur');
            $table->enum('type_detenteur', ['individuel', 'communautaire']);
            $table->string('photo')->nullable();
            $table->text('biographie')->nullable();
            $table->string('specialite')->nullable();

            // AJOUTER LES CHAMPS DIRECTEMENT ICI
            $table->string('localisation')->nullable();
            $table->string('contact')->nullable();
            $table->text('notes')->nullable();

            $table->integer('annees_experience')->nullable();
            $table->boolean('est_verifie')->default(false);
            $table->date('date_verification')->nullable();
            $table->foreignId('verifie_par')->nullable()->constrained('users');

            // Liens selon le type de détenteur
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('communaute_id')->nullable()->constrained('communautes', 'id_communaute')->onDelete('cascade');
            $table->foreignId('demandeur_id')->nullable()->constrained('demandeurs', 'id_demandeur')->onDelete('cascade');

            $table->timestamps();

            // Index
            $table->index('type_detenteur');
            $table->index('est_verifie');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detenteurs');
    }
};
