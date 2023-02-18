<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLatePolicyUserHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('late_policy_users', 'no_policy')) {
            Schema::table('late_policy_users', function (Blueprint $table) {
                $table->string('start_time')->default('09:15');
                $table->string('end_time')->default('18:15');
                $table->string('relax_time')->default('09:30');
                $table->string('type')->default('0'); // 0 = per minutes, 1 = half day
                $table->integer('per_minute')->default(5);
                $table->longText('variations')->nullable();
                $table->string('name')->nullable();
                $table->string('start_date')->nullable();
                $table->string('end_date')->nullable();
                $table->boolean('no_policy')->default(false);
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
        Schema::table('late_policy_users', function (Blueprint $table) {
            //
        });
    }
}
