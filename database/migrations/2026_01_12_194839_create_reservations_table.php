<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
              $table->id();
              $table->foreignId('user_id')->constrained();
              $table->foreignId('resource_id')->constrained();
             $table->dateTime('date_start');
             $table->integer('quantity');
             $table->dateTime('date_end');
              $table->string('status')->default('pending');
             $table->text('justification');
              $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
