<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPayRollAllowancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_pay_roll_allowances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('amount', 11,2);
            $table->bigInteger('allowance_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('user_pay_roll_id')->unsigned();
            $table->timestamps();
        });

        Schema::table("user_pay_roll_allowances", function (Blueprint $table) {
            $table->foreign('allowance_id')->references('id')->on("allowances")->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::dropIfExists('user_pay_roll_allowances');
    }
}
