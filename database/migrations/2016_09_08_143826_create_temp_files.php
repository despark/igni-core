<?php

use Despark\Cms\Migrations\IgniMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempFiles extends IgniMigration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create($this->getTableName('temp_files'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('filename');
            $table->string('temp_filename');
            $table->string('file_type');
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop($this->getTableName('temp_files'));
    }
}
