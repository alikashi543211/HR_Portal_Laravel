<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatedByUpdatedByInTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('users', 'created_by')) {
            Schema::table('users', function (Blueprint $table) {
                $table->bigInteger('created_by')->unsigned()->nullable();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('users', 'updated_by')) {
            Schema::table('users', function (Blueprint $table) {
                $table->bigInteger('updated_by')->unsigned()->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('user_pay_rolls', 'created_by')) {
            Schema::table('user_pay_rolls', function (Blueprint $table) {
                $table->bigInteger('created_by')->unsigned()->nullable();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('user_pay_rolls', 'updated_by')) {
            Schema::table('user_pay_rolls', function (Blueprint $table) {
                $table->bigInteger('updated_by')->unsigned()->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('user_pay_roll_extras', 'created_by')) {
            Schema::table('user_pay_roll_extras', function (Blueprint $table) {
                $table->bigInteger('created_by')->unsigned()->nullable();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('user_pay_roll_extras', 'updated_by')) {
            Schema::table('user_pay_roll_extras', function (Blueprint $table) {
                $table->bigInteger('updated_by')->unsigned()->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('user_pay_roll_allowances', 'created_by')) {
            Schema::table('user_pay_roll_allowances', function (Blueprint $table) {
                $table->bigInteger('created_by')->unsigned()->nullable();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('user_pay_roll_allowances', 'updated_by')) {
            Schema::table('user_pay_roll_allowances', function (Blueprint $table) {
                $table->bigInteger('updated_by')->unsigned()->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('user_leaves_quota', 'created_by')) {
            Schema::table('user_leaves_quota', function (Blueprint $table) {
                $table->bigInteger('created_by')->unsigned()->nullable();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('user_leaves_quota', 'updated_by')) {
            Schema::table('user_leaves_quota', function (Blueprint $table) {
                $table->bigInteger('updated_by')->unsigned()->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('user_leaves', 'created_by')) {
            Schema::table('user_leaves', function (Blueprint $table) {
                $table->bigInteger('created_by')->unsigned()->nullable();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('user_leaves', 'updated_by')) {
            Schema::table('user_leaves', function (Blueprint $table) {
                $table->bigInteger('updated_by')->unsigned()->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('roles', 'created_by')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->bigInteger('created_by')->unsigned()->nullable();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('roles', 'updated_by')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->bigInteger('updated_by')->unsigned()->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('role_permissions', 'created_by')) {
            Schema::table('role_permissions', function (Blueprint $table) {
                $table->bigInteger('created_by')->unsigned()->nullable();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('role_permissions', 'updated_by')) {
            Schema::table('role_permissions', function (Blueprint $table) {
                $table->bigInteger('updated_by')->unsigned()->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('pay_rolls', 'created_by')) {
            Schema::table('pay_rolls', function (Blueprint $table) {
                $table->bigInteger('created_by')->unsigned()->nullable();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('pay_rolls', 'updated_by')) {
            Schema::table('pay_rolls', function (Blueprint $table) {
                $table->bigInteger('updated_by')->unsigned()->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('late_policy_users', 'created_by')) {
            Schema::table('late_policy_users', function (Blueprint $table) {
                $table->bigInteger('created_by')->unsigned()->nullable();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('late_policy_users', 'updated_by')) {
            Schema::table('late_policy_users', function (Blueprint $table) {
                $table->bigInteger('updated_by')->unsigned()->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('late_policy_user_exceptions', 'created_by')) {
            Schema::table('late_policy_user_exceptions', function (Blueprint $table) {
                $table->bigInteger('created_by')->unsigned()->nullable();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('late_policy_user_exceptions', 'updated_by')) {
            Schema::table('late_policy_user_exceptions', function (Blueprint $table) {
                $table->bigInteger('updated_by')->unsigned()->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('late_policy_date_exceptions', 'created_by')) {
            Schema::table('late_policy_date_exceptions', function (Blueprint $table) {
                $table->bigInteger('created_by')->unsigned()->nullable();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('late_policy_date_exceptions', 'updated_by')) {
            Schema::table('late_policy_date_exceptions', function (Blueprint $table) {
                $table->bigInteger('updated_by')->unsigned()->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('late_policy', 'created_by')) {
            Schema::table('late_policy', function (Blueprint $table) {
                $table->bigInteger('created_by')->unsigned()->nullable();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('late_policy', 'updated_by')) {
            Schema::table('late_policy', function (Blueprint $table) {
                $table->bigInteger('updated_by')->unsigned()->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('late_exceptions', 'created_by')) {
            Schema::table('late_exceptions', function (Blueprint $table) {
                $table->bigInteger('created_by')->unsigned()->nullable();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('late_exceptions', 'updated_by')) {
            Schema::table('late_exceptions', function (Blueprint $table) {
                $table->bigInteger('updated_by')->unsigned()->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('increments', 'created_by')) {
            Schema::table('increments', function (Blueprint $table) {
                $table->bigInteger('created_by')->unsigned()->nullable();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('increments', 'updated_by')) {
            Schema::table('increments', function (Blueprint $table) {
                $table->bigInteger('updated_by')->unsigned()->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('holidays', 'created_by')) {
            Schema::table('holidays', function (Blueprint $table) {
                $table->bigInteger('created_by')->unsigned()->nullable();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('holidays', 'updated_by')) {
            Schema::table('holidays', function (Blueprint $table) {
                $table->bigInteger('updated_by')->unsigned()->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('attendances', 'created_by')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->bigInteger('created_by')->unsigned()->nullable();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('attendances', 'updated_by')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->bigInteger('updated_by')->unsigned()->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('allowances', 'created_by')) {
            Schema::table('allowances', function (Blueprint $table) {
                $table->bigInteger('created_by')->unsigned()->nullable();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('allowances', 'updated_by')) {
            Schema::table('allowances', function (Blueprint $table) {
                $table->bigInteger('updated_by')->unsigned()->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });

        Schema::table('user_pay_rolls', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });

        Schema::table('user_pay_roll_extras', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });

        Schema::table('user_pay_roll_allowances', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });

        Schema::table('user_leaves_quota', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });

        Schema::table('user_leaves', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });

        Schema::table('role_permissions', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });

        Schema::table('pay_rolls', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });

        Schema::table('late_policy_users', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });

        Schema::table('late_policy_user_exceptions', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });

        Schema::table('late_policy_date_exceptions', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });

        Schema::table('late_policy', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });

        Schema::table('late_exceptions', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });

        Schema::table('increments', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });

        Schema::table('holidays', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });

        Schema::table('allowances', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });
    }
}
