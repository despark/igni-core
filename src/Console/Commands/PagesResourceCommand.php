<?php

namespace Despark\Cms\Console\Commands;

use Despark\Cms\Console\Commands\Compilers\PageCompiler;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use File;

/**
 * Class AdminResourceCommand.
 */
class PagesResourceCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'igni:make:pages';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create necessary files for CMS Pages resource.';

    /**
     * Table name.
     *
     * @var string|null
     */
    protected $tablePrefix;

    /**
     * Table name.
     *
     * @var string
     */
    protected $tableName;

    /**
     * Full table name.
     *
     * @var string
     */
    protected $fullTableName;

    /**
     * Compiler.
     *
     * @var
     */
    protected $compiler;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->tablePrefix = config('ignicms.igniTablesPrefix');
        $this->tableName = 'pages';
        $this->fullTableName = $this->tablePrefix ? $this->tablePrefix.'_'.$this->tableName : $this->tableName;
    }

    /**
     * Execute the command.
     */
    public function handle()
    {
        // if (Schema::hasTable($this->fullTableName)) {
        //     $this->tableName = $this->ask('The table name '.$this->fullTableName.' already exists! Please enter a new one without it\'s prefix:');
        //     $this->fullTableName = $this->tablePrefix ? $this->tablePrefix.'_'.$this->tableName : $this->tableName;
        // }

        $this->compiler = new PageCompiler($this->tableName, $this->fullTableName);
        $this->createResource('entities');
        $this->createResource('model');
        $this->createResource('controller');
        $this->createResource('request');
        $this->createResource('migration');
        $this->info('Migrating..'.PHP_EOL);
        $this->call('migrate');
        if ($this->confirm('Do you want to insert dummy data?')) {
            $this->info('Seeding..'.PHP_EOL);
            $this->seedPage();
        }
        $this->info('Fantastic! You are good to go :)'.PHP_EOL);
    }

    /**
     * @param $type
     */
    protected function createResource($type)
    {
        $template = $this->getTemplate($type);
        $template = $this->compiler->{'render_'.$type}($template);
        $path = config('ignicms.paths.'.$type);
        $filename = $this->{$type.'_name'}().'.php';
        $this->saveResult($template, $path, $filename);
    }

    /**
     * @param $type
     *
     * @return string
     */
    public function getTemplate($type)
    {
        return file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'Page'.DIRECTORY_SEPARATOR.$type.'.stub');
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
        $this->info('File "'.$file.'" was created.');
    }

    /**
     * @return string
     *
     * @todo this is not needed in the command we should move it into the compiler
     */
    public function model_name()
    {
        return 'Page';
    }

    /**
     * @return mixed
     */
    public function entities_name()
    {
        return 'pages';
    }

    /**
     * @return string
     */
    public function controller_name()
    {
        return 'PagesController';
    }

    /**
     * @return string
     */
    public function request_name()
    {
        return 'PagesUpdateRequest';
    }

    /**
     * @return string
     */
    public function migration_name()
    {
        return date('Y_m_d_His').'_create_'.str_plural($this->tableName).'_table';
    }

    public function seedPage()
    {
        $page = [
            'title' => 'Home',
            'meta_description' => 'This is the meta description for the Home page.',
            'slug' => 'home',
            'content' => 'This is the content for the Home page.',
            'is_published' => true,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        \DB::table(str_plural($this->fullTableName))->insert($page);
    }
}
