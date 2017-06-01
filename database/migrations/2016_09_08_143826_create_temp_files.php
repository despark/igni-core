<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempFiles extends Migration
{
    protected $tableName;

    public function __construct()
    {
        $this->tableName = config('ignicms.databasePrefix') ? config('ignicms.databasePrefix').'_temp_files' : 'temp_files';
    }

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
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
        Schema::drop($this->tableName);
    }
}
