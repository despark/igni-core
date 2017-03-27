<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMetaColumnTypeInImagesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement('ALTER TABLE `images` CHANGE `meta` `meta` TEXT  CHARACTER SET utf8  COLLATE utf8_general_ci  NULL;');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('images', function (Blueprint $table) {
        });
    }
}
