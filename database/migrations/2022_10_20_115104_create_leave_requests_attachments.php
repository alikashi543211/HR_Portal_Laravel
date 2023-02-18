<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveRequestsAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_requests_attachments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('file');
            $table->bigInteger('leave_request_id')->unsigned();
            $table->timestamps();
        });
        Schema::table('leave_requests_attachments', function (Blueprint $table) {
            $table->foreign('leave_request_id')->references('id')->on('leave_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_requests_attachments');
    }
}
