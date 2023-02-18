<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CerateAllowancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allowances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('type')->default(1); // 1 = ALLOWANCES_FIXED, 2 = ALLOWANCES_PERCENTAGE
            $table->integer('value');
            $table->boolean('for_all')->default(true);
            $table->bigInteger('created_by')->unsigned();
            $table->timestamps();
        });

        Schema::table("allowances", function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on("users")->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('allowances');
    }
}
