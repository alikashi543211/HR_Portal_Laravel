<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaidWorkingDaysToPayRollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('user_pay_rolls', 'paid_working_days')) {
            Schema::table('user_pay_rolls', function (Blueprint $table) {
                $table->float('paid_working_days', 11, 2)->default(0);
            });
        }
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
