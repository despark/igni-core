<?php

use Despark\Cms\Models\IgniMigration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminUsersTable extends IgniMigration
{
    /**
     * Run the migrations.
     */

    /**
     * Run the migrations.
     */
    public function up()
    {
        if (config('ignicms.igniTablesPrefix')) {
            Schema::create($this->getTableName('users'), function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->boolean('is_admin');
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (config('ignicms.igniTablesPrefix')) {
            Schema::dropIfExists($this->getTableName('users'));
        }
    }
}
