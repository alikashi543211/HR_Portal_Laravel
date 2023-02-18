<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanInstallmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_installments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('amount')->unsigned();
            $table->string('month');
            $table->integer('status')->default(PENDING);
            $table->bigInteger('loan_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('loan_installments', function (Blueprint $table) {
            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_installments');
    }
}
