<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImagesAddAltTitle extends Migration
{
    protected $tableName;

    public function __construct()
    {
        $this->tableName = config('ignicms.databasePrefix') ? config('ignicms.databasePrefix').'_images' : 'images';
    }

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->string('title')->nullable()->after('order');
            $table->string('alt')->nullable()->after('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->dropColumn(['title', 'alt']);
        });
    }
}
