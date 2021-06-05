<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAwaySpeakersTable extends Migration
{
    public function up()
    {
        Schema::create('away_speakers', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('firstname');
            $table->string('lastname');
            $table->json('dispositions');
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->boolean('may_give_speak_away')->default(false);
            $table->boolean('is_dag')->default(false);
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('away_speakers');
    }
}
