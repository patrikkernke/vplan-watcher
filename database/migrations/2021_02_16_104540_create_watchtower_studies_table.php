<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWatchtowerStudiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('watchtower_studies', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('meeting_id');

            $table->dateTime('startAt');
            $table->string('conductor')->nullable();
            $table->string('reader')->nullable();

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
        Schema::dropIfExists('watchtower_studies');
    }
}
