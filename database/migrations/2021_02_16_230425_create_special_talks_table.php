<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialTalksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_talks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('meeting_id');

            $table->dateTime('startAt');
            $table->string('speaker')->nullable();
            $table->string('congregation')->nullable();
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
        Schema::dropIfExists('special_talks');
    }
}
