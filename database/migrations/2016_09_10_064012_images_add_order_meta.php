<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImagesAddOrderMeta extends Migration
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
            $table->json('meta')->nullable()->after('retina_factor');
            $table->smallInteger('order')->default(0)->after('retina_factor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->dropColumn(['meta', 'order']);
        });
    }
}
