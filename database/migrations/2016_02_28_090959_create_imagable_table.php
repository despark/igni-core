<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagableTable extends Migration
{
    protected $tableName;

    public function __construct()
    {
        $this->tableName = config('ignicms.igniTablesPrefix') ? config('ignicms.igniTablesPrefix').'_imageables' : 'imageables';
    }

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('imageable_id');
            $table->string('imageable_type', 45);
            $table->string('file', 100);
            $table->integer('orientation');
            $table->timestamps();
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
