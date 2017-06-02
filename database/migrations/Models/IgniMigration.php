<?php

namespace Despark\Cms\Migrations;

use Illuminate\Database\Migrations\Migration;

class IgniMigration extends Migration
{
    public function getTableName($name)
    {
        return config('ignicms.igniTablesPrefix') ? config('ignicms.igniTablesPrefix').'_'.$name : $name;
    }
}
