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
        Schema::create('demandeurs', function (Blueprint $table) {
            $table->id('id_demandeur');
            $table->string('nom');
            $table->string('prenom');
            $table->date('date_naiss');
            $table->string('lieu_naissance')->nullable(); // NOUVEAU
            $table->string('telephone');
            $table->enum('sexe', ['M', 'F']);
            $table->string('groupe_etheroculturel')->nullable();
            $table->string('deca_element')->nullable();
            $table->string('coordonne_gec')->nullable();
            $table->string('coordonnees_geographiques')->nullable(); // NOUVEAU
            $table->string('email')->unique()->nullable();
            $table->text('adresse')->nullable();
            $table->string('profession')->nullable();
            $table->string('localite_exercice')->nullable(); // NOUVEAU
            $table->enum('type_detenteur', ['individu', 'famille', 'communaute', 'autre'])->default('individu'); // NOUVEAU
            $table->string('autre_type_detenteur')->nullable(); // NOUVEAU
            $table->string('nom_structure')->nullable(); // NOUVEAU
            $table->string('type_structure')->nullable(); // NOUVEAU
            $table->string('siege_social')->nullable(); // NOUVEAU
            $table->string('personne_contact')->nullable(); // NOUVEAU
            $table->timestamps();

            // Index
            $table->index('type_detenteur');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandeurs');
    }
};
