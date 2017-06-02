<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMetaColumnTypeInImagesTable extends Migration
{
    protected $tableName;

    public function __construct()
    {
        $this->tableName = config('ignicms.igniTablesPrefix') ? config('ignicms.igniTablesPrefix').'_images' : 'images';
    }

    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement('ALTER TABLE '.$this->tableName.' CHANGE `meta` `meta` TEXT  CHARACTER SET utf8  COLLATE utf8_general_ci  NULL;');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
        });
    }
}
