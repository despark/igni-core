<?php

use Despark\Cms\Migrations\IgniMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMetaColumnTypeInImagesTable extends IgniMigration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement('ALTER TABLE '.$this->getTableName('images').' CHANGE `meta` `meta` TEXT  CHARACTER SET utf8  COLLATE utf8_general_ci  NULL;');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table($this->getTableName('images'), function (Blueprint $table) {
        });
    }
}
