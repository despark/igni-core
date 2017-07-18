<?php

namespace Despark\Cms\Console\Commands\Compilers;

use Despark\Cms\Admin\Interfaces\UploadImageInterface;
use Despark\Cms\Admin\Traits\AdminImage;
use Despark\Cms\Console\Commands\Admin\ResourceCommand;
use Despark\LaravelDbLocalization\Contracts\Translatable;
use Despark\LaravelDbLocalization\Traits\HasTranslation;
use Illuminate\Console\Command;

/**
 * Class ResourceCompiler.
 */
class ResourceCompiler
{
    /**
     * @var Command|ResourceCommand
     */
    protected $command;

    /**
     * @var
     */
    protected $identifier;

    /**
     * @var
     */
    protected $configIdentifier;

    /**
     * @var
     */
    protected $options;

    /**
     * @var array
     */
    protected $modelReplacements = [
        ':identifier' => '',
        ':model_name' => '',
        ':app_namespace' => '',
        ':uses' => [],
        ':traits' => [],
        ':table_name' => '',
        ':implementations' => [],
        ':translatable' => '',
    ];

    /**
     * @var array
     */
    protected $entitiesReplacements = [
        ':image_fields' => '',
        ':identifier' => '',
        ':model_name' => '',
        ':model_config_name' => '',
        ':app_namespace' => '',
        ':controller_name' => '',
        ':index_route' => '',
        ':identifier' => '',
        ':actions' => '',
    ];

    /**
     * @var array
     */
    protected $controllerReplacements = [
        ':controller_name' => '',
        ':app_namespace' => '',
    ];

    /**
     * @var array
     */
    protected $migrationReplacements = [
        ':app_namespace' => '',
        ':table_name' => '',
        ':parent_table_name' => '',
        ':migration_class' => '',
    ];

    /**
     * @param Command $command
     * @param         $identifier
     * @param         $options
     *
     * @todo why setting options where we can get it from command? Either remove command or keep options
     */
    public function __construct(Command $command, $identifier, $configIdentifier, $options)
    {
        $this->command = $command;
        $this->identifier = $identifier;
        $this->configIdentifier = $configIdentifier;
        $this->options = $options;
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
        if ($this->options['image_uploads']) {
            $this->modelReplacements[':uses'][] = UploadImageInterface::class;
            $this->modelReplacements[':implementations'][] = class_basename(UploadImageInterface::class);
            $this->modelReplacements[':uses'][] = AdminImage::class;
            $this->modelReplacements[':traits'][] = class_basename(AdminImage::class);
        }

        if ($this->options['translations']) {
            $this->modelReplacements[':uses'][] = Translatable::class;
            $this->modelReplacements[':uses'][] = HasTranslation::class;
            $this->modelReplacements[':implementations'][] = class_basename(Translatable::class);
            $this->modelReplacements[':traits'][] = class_basename(HasTranslation::class);
            $this->modelReplacements[':translatable'] = 'protected $translatable = [];';
        }

        $this->modelReplacements[':app_namespace'] = app()->getNamespace();
        $this->modelReplacements[':table_name'] = str_plural($this->identifier);
        $this->modelReplacements[':model_name'] = $this->command->model_name($this->identifier);
        $this->modelReplacements[':identifier'] = $this->configIdentifier;

        $this->prepareReplacements();

        $template = strtr($template, $this->modelReplacements);

        return $template;
    }

    /**
     * Prepare Replacements.
     */
    private function prepareReplacements()
    {
        $usesString = '';
        if (! empty($this->modelReplacements[':uses'])) {
            foreach ($this->modelReplacements[':uses'] as $use) {
                $usesString .= 'use '.$use.';'.PHP_EOL;
            }
        }

        $this->modelReplacements[':uses'] = $usesString;

        $this->modelReplacements[':implementations'] = ! empty($this->modelReplacements[':implementations']) ?
            'implements '.implode(', ', $this->modelReplacements[':implementations']) : '';

        $this->modelReplacements[':traits'] = ! empty($this->modelReplacements[':traits']) ?
            'use '.implode(', ', $this->modelReplacements[':traits']).';' : '';
    }

    /**
     * @param $template
     *
     * @return string
     */
    public function render_entities($template)
    {
        $this->entitiesReplacements[':app_namespace'] = app()->getNamespace();
        $this->entitiesReplacements[':table_name'] = str_plural($this->identifier);
        $this->entitiesReplacements[':model_name'] = $this->command->model_name($this->identifier);
        $this->entitiesReplacements[':controller_name'] = $this->command->controller_name($this->identifier);
        $this->entitiesReplacements[':identifier'] = str_plural($this->configIdentifier);
        $this->entitiesReplacements[':model_config_name'] = $this->getConfigModelName();
        $this->entitiesReplacements[':index_route'] = $this->getIndexRoute();

        if ($this->options['edit']) {
            $actions[] = "'edit'";
        }
        if ($this->options['create']) {
            $actions[] = "'create'";
        }
        if ($this->options['destroy']) {
            $actions[] = "'destroy'";
        }

        $this->entitiesReplacements[':actions'] = implode(', ', $actions);

        if ($this->options['image_uploads']) {
            $this->entitiesReplacements[':image_fields'] = "'image_fields' => [
        'image' => [
            'thumbnails' => [
                'admin' => [
                    'width' => 150,
                    'height' => null,
                    'type' => 'resize',
                ],
                'normal' => [
                    'width' => 960,
                    'height' => null,
                    'type' => 'resize',
                ],
            ],
        ],
    ],";
        }

        $template = strtr($template, $this->entitiesReplacements);

        return $template;
    }

    /**
     * @param $template
     *
     * @return string
     */
    public function render_controller($template)
    {
        $this->controllerReplacements[':app_namespace'] = app()->getNamespace();
        $this->controllerReplacements[':controller_name'] = $this->command->controller_name($this->identifier);

        $template = strtr($template, $this->controllerReplacements);

        return $template;
    }

    /**
     * @param $template
     *
     * @return string
     */
    public function render_migration($template)
    {
        $this->migrationReplacements[':app_namespace'] = app()->getNamespace();
        $this->migrationReplacements[':migration_class'] = 'Create'.str_plural(studly_case($this->identifier)).'Table';
        $this->migrationReplacements[':table_name'] = str_plural($this->identifier);

        $template = strtr($template, $this->migrationReplacements);

        return $template;
    }

    /**
     * @param $template
     *
     * @return string
     */
    public function render_migration_i18n($template)
    {
        $this->migrationReplacements[':app_namespace'] = app()->getNamespace();
        $this->migrationReplacements[':migration_class'] = 'Create'.str_plural(studly_case($this->identifier)).'I18nTable';
        $this->migrationReplacements[':table_name'] = str_plural($this->identifier).'_i18n';
        $this->migrationReplacements[':parent_table_name'] = str_plural($this->identifier);

        $template = strtr($template, $this->migrationReplacements);

        return $template;
    }

    /**
     * @return string
     */
    public function getConfigModelName()
    {
        $modelNameSplitted = preg_split('/(?=[A-Z])/', $this->entitiesReplacements[':model_name']);
        $string = '';

        foreach ($modelNameSplitted as $key => $value) {
            if ($key != 0) {
                if (end($modelNameSplitted) === $value) {
                    $string .= $value;
                } else {
                    $string .= $value.' ';
                }
            }
        }

        return $string;
    }

    /**
     * @return string
     */
    public function getIndexRoute()
    {
        return strtolower(str_replace(' ', '', $this->entitiesReplacements[':model_config_name']).'.index');
    }
}
