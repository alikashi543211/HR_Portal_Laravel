<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUserPayRollsTableForLoanDeduction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_pay_rolls', function (Blueprint $table) {
            $table->double("loan_deduction")->default(0.00)->after("leave_deduction");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_pay_rolls', function (Blueprint $table) {
            //
        });
    }
}
