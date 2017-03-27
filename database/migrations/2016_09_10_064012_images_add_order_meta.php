<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImagesAddOrderMeta extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->json('meta')->nullable()->after('retina_factor');
            $table->smallInteger('order')->default(0)->after('retina_factor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropColumn(['meta', 'order']);
        });
    }
}
