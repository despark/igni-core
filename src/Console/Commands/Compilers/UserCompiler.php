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
     * @var array
     */
    protected $modelReplacements = [
        ':app_namespace' => '',
        ':table_name' => '',
    ];

    /**
     * @param $tableName
     */
    public function __construct($tableName)
    {
        $this->tableName = $tableName;
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

        $template = strtr($template, $this->modelReplacements);

        return $template;
    }
}
