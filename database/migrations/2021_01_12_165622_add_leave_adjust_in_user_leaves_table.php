<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeaveAdjustInUserLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('user_leaves', 'leave_adjust')) {
            Schema::table('user_leaves', function (Blueprint $table) {
                $table->integer('leave_adjust')->default(LEAVE_ADJUST); // LEAVE_ADJUST, LEAVE_NOT_ADJUST
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
        Schema::table('user_leaves', function (Blueprint $table) {
            //
        });
    }
}
