<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayRollTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_roll_taxes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('amount')->default(0);
            $table->string('image')->nullable();
            $table->bigInteger('pay_roll_id')->unsigned();
            $table->timestamps();
        });
        Schema::table("pay_roll_taxes", function (Blueprint $table) {
            $table->foreign('pay_roll_id')->references('id')->on("pay_rolls")->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pay_roll_taxes');
    }
}
