<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateTempFiles extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $tablePrefix = config('ignicms.igniTablesPrefix');
        $tableName = $tablePrefix ? $tablePrefix . '_temp_files' : 'temp_files';
        Schema::create($tableName, function (Blueprint $table) {
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
        $tablePrefix = config('ignicms.igniTablesPrefix');
        $tableName = $tablePrefix ? $tablePrefix . '_temp_files' : 'temp_files';
        Schema::drop($tableName);
    }
}
