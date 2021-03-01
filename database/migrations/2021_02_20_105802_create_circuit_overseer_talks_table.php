<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCircuitOverseerTalksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('circuit_overseer_talks', function (Blueprint $table) {
            $table->id();

            $table->foreignid('meeting_id')->nullable();

            $table->dateTime('start_at');
            $table->string('circuitOverseer')->nullable();
            $table->string('topic')->nullable();

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
        Schema::dropIfExists('schedule_item_circuit_overseer_talks');
    }
}
