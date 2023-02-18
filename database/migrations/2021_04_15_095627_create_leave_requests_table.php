<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('type')->default(SICK_LEAVE); // SICK_LEAVE, CASUAL_LEAVE
            $table->integer('period')->default(FULL_DAY_LEAVE); // HALF_DAY_LEAVE, FULL_DAY_LEAVE
            $table->integer('period_type')->default(FULL_DAY); // FULL_DAY, FIRST_HALF, SECOND_HALF
            $table->text('reason')->nullable();
            $table->string('date');
            $table->bigInteger('user_id')->unsigned();
            $table->integer('status')->unsigned()->default(PENDING);
            $table->timestamps();
        });

        Schema::table('leave_requests', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_requests');
    }
}
