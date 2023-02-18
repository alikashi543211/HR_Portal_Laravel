<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLeavesQuotaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_leaves_quota', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('used_sick_leaves', 8,1)->default(0);
            $table->float('remaining_sick_leaves', 8,1)->default(0);
            $table->float('used_casual_leaves', 8,1)->default(0);
            $table->float('remaining_casual_leaves', 8,1)->default(0);
            $table->bigInteger('user_id')->unsigned();
            $table->timestamps();
        });

        Schema::table("user_leaves_quota", function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on("users")->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_leaves_quota');
    }
}
