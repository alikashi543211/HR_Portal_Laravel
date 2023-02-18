<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayRollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_rolls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('date');
            $table->bigInteger('created_by')->unsigned();
            $table->timestamps();
        });

        Schema::table("pay_rolls", function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on("users")->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pay_rolls');
    }
}
