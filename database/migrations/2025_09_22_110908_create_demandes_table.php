<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demandes', function (Blueprint $table) {
            $table->id('id_demande');
            $table->string('type_demande');
            $table->date('date_creation');
            $table->string('status')->default('en_attente');
            $table->timestamp('validee_le')->nullable();
            $table->timestamp('rejetee_le')->nullable();
            $table->foreignId('valider_par')->nullable()->constrained('users');
            $table->foreignId('rejeter_par')->nullable()->constrained('users');
            $table->foreignId('id_demandeur')->constrained('demandeurs', 'id_demandeur');
            $table->text('motif_rejet')->nullable();

            // NOUVEAUX CHAMPS
            $table->boolean('declaration_honneur')->default(false);
            $table->timestamp('date_declaration')->nullable();
            $table->string('signature')->nullable();
            $table->string('photo_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demandes');
    }
};
