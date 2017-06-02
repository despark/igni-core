<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsAdminToUsers extends Migration
{
    protected $tableName;

    public function __construct()
    {
        $this->tableName = config('ignicms.igniTablesPrefix') ? config('ignicms.igniTablesPrefix').'_users' : 'users';
    }

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->boolean('is_admin')->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
}
