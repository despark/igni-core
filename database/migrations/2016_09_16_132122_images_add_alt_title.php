<?php

use Despark\Cms\Models\IgniMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ImagesAddAltTitle extends IgniMigration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table($this->getTableName('images'), function (Blueprint $table) {
            $table->string('title')->nullable()->after('order');
            $table->string('alt')->nullable()->after('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table($this->getTableName('images'), function (Blueprint $table) {
            $table->dropColumn(['title', 'alt']);
        });
    }
}
