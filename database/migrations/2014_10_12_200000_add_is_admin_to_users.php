<?php

use Despark\Cms\Models\IgniMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsAdminToUsers extends IgniMigration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (! config('ignicms.igniTablesPrefix')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_admin')->after('password');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (! config('ignicms.igniTablesPrefix')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_admin');
            });
        }
    }
}
