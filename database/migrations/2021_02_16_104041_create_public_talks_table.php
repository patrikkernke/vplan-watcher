<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicTalksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_talks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('meeting_id')->nullable();

            $table->dateTime('start_at');
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
        Schema::dropIfExists('public_talks');
    }
}
