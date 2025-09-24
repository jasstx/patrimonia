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
        Schema::create('detenteur_patrimoine', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_detenteur')->constrained('detenteurs', 'id_detenteur')->onDelete('cascade');
            $table->foreignId('id_patrimoine')->constrained('patrimoines', 'id_element')->onDelete('cascade');
            $table->date('date_debut_detention')->nullable();
            $table->enum('type_detention', ['proprietaire', 'gardien', 'depositaire', 'heritier']);
            $table->text('preuves')->nullable();
            $table->boolean('est_actif')->default(true);
            $table->timestamps();

            $table->unique(['id_detenteur', 'id_patrimoine']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detenteur_patrimoine');
    }
};
