<?php

use Despark\Cms\Models\IgniMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends IgniMigration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create($this->getTableName('images'), function (Blueprint $table) {
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

        \DB::statement('ALTER TABLE '.$this->getTableName('images').'
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
        Schema::dropIfExists($this->getTableName('images'));
    }
}
