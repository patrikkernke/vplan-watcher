<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_meetings', function (Blueprint $table) {
            $table->id();

            $table->dateTime('startAt');
            $table->string('type');
            $table->string('leader')->nullable();
            $table->boolean('is_visit_circuit_overseer')->default(FALSE);
            $table->foreignId('field_service_group_id')->nullable();

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
        Schema::dropIfExists('service_meetings');
    }
}
