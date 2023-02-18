<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLatePolicyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('late_policy', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('start_time')->default('09:15');
            $table->string('end_time')->default('18:15');
            $table->string('relax_time')->default('09:30');
            $table->string('type')->default('0'); // 0 = per minutes, 1 = half day
            $table->integer('per_minute')->default(5);
            $table->longText('variations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('late_policy');
    }
}
