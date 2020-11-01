<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserExportedCharts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('UserTrackingSensorList',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->string('sensor_id');
            $table->string('experiment_name');
            $table->longText('sensor_data');
            $table->unsignedBigInteger('user_id')->unsigned();
        });
        // add fk
        Schema::table('UserTrackingSensorList',function( Blueprint $table){
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('UserTrackingSensorList');
    }
}
