<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $tablePrefix = config('ignicms.igniTablesPrefix');
        $tableName = $tablePrefix ? $tablePrefix . '_images' : 'images';
        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('resource_id');
            $table->string('resource_model');
            $table->string('image_type');
            $table->string('original_image');
            $table->unsignedSmallInteger('retina_factor')->nullable();
            $table->smallInteger('order')->default(0);
            $table->string('alt')->nullable();
            $table->string('title')->nullable();
            $table->string('meta')->nullable();
            $table->nullableTimestamps();
        });

        \DB::statement('ALTER TABLE ' . $tableName . '
            MODIFY COLUMN `resource_model`  VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `resource_id`,
            MODIFY COLUMN `image_type`  VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `resource_model`,
            MODIFY COLUMN `original_image`  VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `image_type`,
            MODIFY COLUMN `meta` TEXT  CHARACTER SET utf8  COLLATE utf8_general_ci  NULL;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $tablePrefix = config('ignicms.igniTablesPrefix');
        $tableName = $tablePrefix ? $tablePrefix . '_images' : 'images';
        Schema::dropIfExists($tableName);
    }
}
