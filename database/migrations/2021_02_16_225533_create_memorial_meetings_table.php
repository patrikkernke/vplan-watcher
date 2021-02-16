<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemorialMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memorial_meetings', function (Blueprint $table) {
            $table->id();

            $table->dateTime('startAt');
            $table->string('chairman')->nullable();
            $table->string('speaker')->nullable();
            $table->string('disposition')->nullable();
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
        Schema::dropIfExists('memorial_meetings');
    }
}
