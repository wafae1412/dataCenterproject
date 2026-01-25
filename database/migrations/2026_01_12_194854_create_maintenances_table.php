<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenancesTable extends Migration
{
    /**
     * Exécute les migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id(); // Identifiant unique

            // Clé étrangère vers la table resources
            $table->foreignId('resource_id')
                  ->constrained()
                  ->onDelete('cascade'); // Suppression en cascade

            // Titre de la maintenance
            $table->string('title');

            // Description détaillée
            $table->text('description');

            // Type de maintenance avec valeurs prédéfinies
            $table->enum('type', ['preventive', 'corrective', 'emergency', 'upgrade'])
                  ->default('preventive'); // Valeur par défaut : maintenance préventive

            // Date et heure de début
            $table->dateTime('start_date');

            // Date et heure de fin
            $table->dateTime('end_date')->nullable();

            // Durée estimée en heures 
            $table->integer('estimated_duration')->nullable();

            // Notes supplémentaires
            $table->text('notes')->nullable();

            // Statut de la maintenance
            $table->string('status')->default('scheduled');

            // Timestamps Laravel (created_at, updated_at)
            $table->timestamps();
        });
    }

    /**
     * Annule les migrations.
     *
     * @return void
     */
    public function down()
    {
        // Supprime la table si elle existe
        Schema::dropIfExists('maintenances');
    }
}
