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
        Schema::create('communautes', function (Blueprint $table) {
            $table->id('id_communaute');
            $table->string('nom_communaute');
            $table->enum('type_structure', ['association', 'cooperative', 'groupe', 'communauté_villageoise', 'autre']);
            $table->string('contact_principal');
            $table->string('siege_social');
            $table->string('coordonne_gec')->nullable();
            $table->string('telephone');
            $table->string('email')->nullable();
            $table->text('description')->nullable();
            $table->integer('nombre_membres')->nullable();
            $table->string('region');
            $table->string('ville');
            $table->boolean('est_actif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communautes');
    }
};
