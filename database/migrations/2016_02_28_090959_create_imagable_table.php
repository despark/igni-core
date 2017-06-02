<?php

use Despark\Cms\Models\IgniMigration;
use Illuminate\Database\Schema\Blueprint;

class CreateImagableTable extends IgniMigration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create($this->getTableName('imageables'), function (Blueprint $table) {
            $table->increments('id');
            $table->integer('imageable_id');
            $table->string('imageable_type', 45);
            $table->string('file', 100);
            $table->integer('orientation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop($this->getTableName('imageables'));
    }
}
