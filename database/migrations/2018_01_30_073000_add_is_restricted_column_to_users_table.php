<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsRestrictedColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tablePrefix = config('ignicms.igniTablesPrefix');
        $tableName = $tablePrefix ? $tablePrefix . '_users' : 'users';
        Schema::table($tableName, function (Blueprint $table) {
            $table->boolean('is_restricted')->after('is_admin')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tablePrefix = config('ignicms.igniTablesPrefix');
        $tableName = $tablePrefix ? $tablePrefix . '_users' : 'users';
        Schema::table($tableName, function (Blueprint $table) {
            $table->dropColumn('is_restricted');
        });
    }
}
