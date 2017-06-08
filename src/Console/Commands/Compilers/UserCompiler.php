<?php

namespace Despark\Cms\Console\Commands\Compilers;

/**
 * Class UserCompiler.
 */
class UserCompiler
{
    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var string
     */
    protected $fullTableName;

    /**
     * @var array
     */
    protected $modelReplacements = [
        ':app_namespace' => '',
        ':table_name' => '',
        ':full_table_name' => '',
    ];

    /**
     * @param Command $command
     * @param         $identifier
     * @param         $options
     *
     * @todo why setting options where we can get it from command? Either remove command or keep options
     */
    public function __construct($tableName, $fullTableName)
    {
        $this->tableName = $tableName;
        $this->fullTableName = $fullTableName;
    }

    /**
     * @param $template
     *
     * @return string
     *
     * @throws \Exception
     */
    public function render_model($template)
    {
        $this->modelReplacements[':app_namespace'] = app()->getNamespace();
        $this->modelReplacements[':table_name'] = $this->tableName;
        $this->modelReplacements[':full_table_name'] = $this->fullTableName;

        $template = strtr($template, $this->modelReplacements);

        return $template;
    }
}
