<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPayRollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_pay_rolls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('gross_salary', 11,2);
            $table->float('base_salary', 11,2);
            $table->float('net_salary', 11,2);
            $table->float('govrt_tax', 11,2)->default(0);
            $table->float('late_deduction', 11,2)->default(0);
            $table->float('leave_deduction', 11,2)->default(0);
            $table->float('house_rent', 11,2)->default(0);
            $table->float('utility', 11,2)->default(0);
            $table->float('allowances', 11,2)->default(0);
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('pay_roll_id')->unsigned();
            $table->timestamps();
        });

        Schema::table("user_pay_rolls", function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on("users")->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::dropIfExists('user_pay_rolls');
    }
}
