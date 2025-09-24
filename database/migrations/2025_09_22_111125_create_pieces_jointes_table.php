<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pieces_jointes', function (Blueprint $table) {
            $table->id('id_piece');
            $table->string('type_piece');
            $table->text('description')->nullable(); // NOUVEAU
            $table->string('nom_fichier');
            $table->string('chemin');
            $table->integer('taille')->default(0); // INTEGER au lieu de string
            $table->string('mime_type')->nullable();
            $table->date('date_ajout');
            $table->foreignId('id_demande')->constrained('demandes', 'id_demande')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pieces_jointes');
    }
};
