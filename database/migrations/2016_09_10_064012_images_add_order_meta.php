<?php

use Despark\Cms\Migrations\IgniMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ImagesAddOrderMeta extends IgniMigration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table($this->getTableName('images'), function (Blueprint $table) {
            $table->json('meta')->nullable()->after('retina_factor');
            $table->smallInteger('order')->default(0)->after('retina_factor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table($this->getTableName('images'), function (Blueprint $table) {
            $table->dropColumn(['meta', 'order']);
        });
    }
}
