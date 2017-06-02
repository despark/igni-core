<?php

use Despark\Cms\Migrations\IgniMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsAdminToUsers extends IgniMigration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table($this->getTableName('users'), function (Blueprint $table) {
            $table->boolean('is_admin')->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table($this->getTableName('users'), function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
}
