<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class RenameUsersTable extends Migration
{
    protected $tableName;

    public function __construct()
    {
        $this->tableName = config('ignicms.databasePrefix') ? config('ignicms.databasePrefix').'_users' : 'users';
    }

    /**
     * Run the migrations.
     */
    public function up()
    {
        if ($this->tableName !== 'users') {
            Schema::rename('users', $this->tableName);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if ($this->tableName !== 'users') {
            Schema::rename($this->tableName, 'users');
        }
    }
}
