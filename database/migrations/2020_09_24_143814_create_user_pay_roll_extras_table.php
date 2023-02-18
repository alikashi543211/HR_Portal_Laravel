<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPayRollExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_pay_roll_extras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('amount', 11, 2);
            $table->string('name');
            $table->string('type')->default(EXTRA_CONTRIBUTIONS); // EXTRA_CONTRIBUTIONS, EXTRA_DEDUCTIONS
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('user_pay_roll_id')->unsigned();
            $table->timestamps();
        });
        Schema::table("user_pay_roll_extras", function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on("users")->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('user_pay_roll_id')->references('id')->on("user_pay_rolls")->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_pay_roll_extras');
    }
}
