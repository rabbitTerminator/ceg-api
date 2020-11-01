<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserTrackingSensorsList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('UserExportedCharts',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->string('url_id');
            $table->string('chart_data');
            $table->unsignedBigInteger('user_id');
        });
        // add fk
        Schema::table('UserExportedCharts',function(Blueprint $table){
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
        Schema::dropIfExists('UserExportedCharts');
    }
}
