<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('logo')->nullable();
            $table->string('authorized_name')->nullable();
            $table->string('authorized_designation')->nullable();
            $table->string('cheque_bank_name')->nullable();
            $table->string('respective_title')->nullable();
            $table->string('respective_first_name')->nullable();
            $table->string('respective_last_name')->nullable();
            $table->string('respective_designation')->nullable();
            $table->string('respective_bank_name')->nullable();
            $table->text('respective_address_1')->nullable();
            $table->text('respective_address_2')->nullable();
            $table->bigInteger('updated_by')->unsigned();
            $table->timestamps();
        });

        Schema::table('company_profiles', function (Blueprint $table) {
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_profiles');
    }
}
