<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDispositionsTable extends Migration
{
    public function up()
    {
        Schema::create('dispositions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('topic_id');
            $table->string('topic');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dispositions');
    }
}
