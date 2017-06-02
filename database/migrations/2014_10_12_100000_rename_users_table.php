<?php

use Despark\Cms\Migrations\IgniMigration;
use Illuminate\Support\Facades\Schema;

class RenameUsersTable extends IgniMigration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if ($this->getTableName('users') !== 'users') {
            Schema::rename('users', $this->getTableName('users'));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if ($this->getTableName('users') !== 'users') {
            Schema::rename($this->getTableName('users'), 'users');
        }
    }
}
