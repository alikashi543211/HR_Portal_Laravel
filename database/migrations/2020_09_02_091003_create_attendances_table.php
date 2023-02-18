<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->default(CHECK_IN); // CHECK_IN, CHECK_OUT
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('created_by')->unsigned();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->string('action_time')->nullable();
            $table->timestamps();
        });

        Schema::table("attendances", function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on("users")->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('created_by')->references('id')->on("users")->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('updated_by')->references('id')->on("users")->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
