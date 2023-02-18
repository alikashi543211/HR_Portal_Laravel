<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->enum('gender', array('Male', 'Female'))->default('Male');
            $table->string('email', 200)->unique();
            $table->string('password');
            $table->bigInteger('role_id')->unsigned();


            $table->string('phone_number')->nullable();
            $table->string('cnic')->nullable();
            $table->string('dob')->nullable();
            $table->string('doj')->nullable();
            $table->string('dop')->nullable();
            $table->string('status')->nullable();
            $table->string('designation')->nullable();
            $table->string('employee_id')->nullable();
            $table->string('finger_print_id')->nullable();
            $table->string('nationality')->nullable();
            $table->string('base_salary')->nullable();
            $table->string('picture')->nullable();
            $table->string('personal_email', 200)->unique()->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_relation')->nullable();
            $table->string('emergency_contact_number')->nullable();
            $table->timestamp('email_verified_at')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table("users", function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on("roles")->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
