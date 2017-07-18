<?php

namespace Despark\Cms\Console\Commands;

use Carbon\Carbon;
use Despark\Cms\Console\Commands\Compilers\ResourceCompiler;
use File;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class AdminResourceCommand.
 */
class ResourceCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'igni:make:resource';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create necessary files for CMS resource.';
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
    protected $compiler;
    /**
     * @var array
     */
    protected $resourceOptions = [
        'image_uploads' => false,
        'translations' => false,
        'migration' => false,
        'create' => false,
        'edit' => false,
        'destroy' => false,
    ];

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the command.
     */
    public function handle()
    {
        $this->identifier = self::normalize($this->argument('identifier'));
        $this->configIdentifier = $this->generateConfigIdentifier();

        $this->askImageUploads();
        $this->askTranslations();
        $this->askMigration();
        $this->askActions();
        $this->compiler = new ResourceCompiler($this, $this->identifier, $this->configIdentifier, $this->resourceOptions);
        $this->createResource('entities');
        $this->info('Don’t forget to add all the fields you want to manage in your new resource in the entity before running it.'.PHP_EOL);
        $this->createResource('model');
        $this->info('Don’t forget to add all the fields you want to manage in your new resource in the model before running it.'.PHP_EOL);
        $this->createResource('controller');
        if ($this->resourceOptions['migration']) {
            $this->createResource('migration');
            if ($this->resourceOptions['translations']) {
                $this->createResource('migration_i18n');
            }
            $this->info('Don’t forget to add all the fields you want to manage in your new resource in the migration before running it.'.PHP_EOL);
        }
    }

    /**
     * @param $type
     */
    protected function createResource($type)
    {
        $template = $this->getTemplate($type);
        $template = $this->compiler->{'render_'.$type}($template);
        if ($type !== 'migration_i18n') {
            $path = config('ignicms.paths.'.$type);
        } else {
            $path = config('ignicms.paths.migration');
        }

        $filename = $this->{$type.'_name'}().'.php';
        $this->saveResult($template, $path, $filename);
    }

    /**
     * @param $str
     *
     * @return mixed|string
     */
    public static function normalize($str)
    {
        return snake_case($str);
        $str[0] = strtolower($str[0]);
        $func = create_function('$c', 'return "_".strtolower($c[1]);');
        $snake = preg_replace_callback('/([A-Z])/', $func, $str);

        return str_replace(' ', '', $snake);
    }

    protected function askImageUploads()
    {
        $answer = $this->confirm('Do you need image uploads?');
        $this->resourceOptions['image_uploads'] = $answer;
    }

    protected function askTranslations()
    {
        $answer = $this->confirm('Do you need translations?');
        $this->resourceOptions['translations'] = $answer;
    }

    protected function askMigration()
    {
        $answer = $this->confirm('Do you need migration?');
        $this->resourceOptions['migration'] = $answer;
    }

    protected function askActions()
    {
        $options = ['create', 'edit', 'destroy'];
        $answer = $this->ask('Which actions do you need? ['.implode(', ', $options).', all]', 'all');
        $answer = str_replace(' ', '', $answer);
        if ($answer == 'all') {
            foreach ($options as $action) {
                $this->resourceOptions[$action] = true;
            }
        } else {
            $actions = explode(',', $answer);
            $actions = array_map('strtolower', $actions);
            foreach ($actions as $action) {
                if (in_array($action, $options)) {
                    $this->resourceOptions[$action] = true;
                }
            }
        }
    }

    /**
     * @param $type
     *
     * @return string
     */
    public function getTemplate($type)
    {
        return file_get_contents(__DIR__.DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.$type.'.stub');
    }

    /**
     * @param $template
     * @param $path
     * @param $filename
     */
    protected function saveResult($template, $path, $filename)
    {
        $file = $path.DIRECTORY_SEPARATOR.$filename;

        if (File::exists($file)) {
            $result = $this->confirm('File "'.$filename.'" already exist. Overwrite?', false);
            if (! $result) {
                return;
            }
        }
        File::put($file, $template);
        $this->info('File "'.$filename.'" was created.');
    }

    /**
     * @return string
     *
     * @todo this is not needed in the command we should move it into the compiler
     */
    public function model_name()
    {
        return studly_case($this->identifier);
    }

    /**
     * @return mixed
     */
    public function entities_name()
    {
        return $this->configIdentifier;
    }

    /**
     * @return string
     */
    public function controller_name()
    {
        return str_plural(studly_case($this->identifier)).'Controller';
    }

    /**
     * @return string
     */
    public function migration_name()
    {
        return date('Y_m_d_His').'_create_'.str_plural($this->identifier).'_table';
    }

    /**
     * @return string
     */
    public function migration_i18n_name()
    {
        return Carbon::now()->addSeconds(1)->format('Y_m_d_His').'_create_'.str_plural($this->identifier).'_i18n_table';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            [
                'identifier',
                InputArgument::REQUIRED,
                'Identifier',
            ],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
        ];
    }

    /**
     * @return string
     */
    public function generateConfigIdentifier()
    {
        $identifier = explode('_', $this->identifier);
        $string = '';

        foreach ($identifier as $key => $value) {
            if ($key != 0) {
                $string .= ucfirst($value);
            } else {
                $string = $value;
            }
        }

        return $string;
    }
}
