<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     */

    /**
     * Run the migrations.
     */
    public function up()
    {
        if ($prefix = config('ignicms.igniTablesPrefix')) {
            Schema::create($prefix . '_users', function (Blueprint $table) {
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
        if ($prefix = config('ignicms.igniTablesPrefix')) {
            Schema::dropIfExists($prefix . '_users');
        }
    }
}
