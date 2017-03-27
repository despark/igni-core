<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImagesAddAltTitle extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->string('title')->nullable()->after('order');
            $table->string('alt')->nullable()->after('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropColumn(['title', 'alt']);
        });
    }
}
