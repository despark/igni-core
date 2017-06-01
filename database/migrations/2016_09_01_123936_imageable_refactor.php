<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImageableRefactor extends Migration
{
    protected $oldTableName;

    protected $newTableName;

    public function __construct()
    {
        $this->oldTableName = config('ignicms.databasePrefix') ? config('ignicms.databasePrefix').'_imageables' : 'imageables';
        $this->newTableName = config('ignicms.databasePrefix') ? config('ignicms.databasePrefix').'_images' : 'images';
    }

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::rename($this->oldTableName, $this->newTableName);
        Schema::table($this->newTableName, function (Blueprint $table) {
            /*
             * `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
             * `imageable_id` int(11) NOT NULL,
             * `imageable_type` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
             * `file` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
             * `orientation` int(11) NOT NULL,
             * `created_at` timestamp NULL DEFAULT NULL,
             * `updated_at` timestamp NULL DEFAULT NULL,
             */
            $table->renameColumn('imageable_id', 'resource_id');
            $table->renameColumn('imageable_type', 'resource_model');
            $table->string('image_type', 100)->after('imageable_type');
            $table->renameColumn('file', 'original_image');
            $table->unsignedSmallInteger('retina_factor')->nullable()->after('file');
            $table->dropColumn('orientation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::rename($this->newTableName, $this->oldTableName);
        Schema::table($this->oldTableName, function (Blueprint $table) {
            $table->renameColumn('resource_id', 'imageable_id');
            $table->renameColumn('resource_model', 'imageable_type');
            $table->renameColumn('original_image', 'file');
            $table->dropColumn('retina_factor');
            $table->dropColumn('image_type');
            $table->integer('orientation')->after('original_image');
        });
    }
}
