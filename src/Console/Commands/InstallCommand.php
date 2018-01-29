<?php

namespace Despark\Cms\Console\Commands;

use Carbon\Carbon;
use Despark\Cms\Console\Commands\Compilers\UserCompiler;
use Illuminate\Console\Command;
use File;

/**
 * Class InstallCommand.
 */
class InstallCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'igni:install {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs the application by setting up all the necessary resources.';

    /**
     * Compiler.
     *
     * @var
     */
    protected $compiler;

    /**
     * @var string
     */
    protected $tableName = 'users';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle($recursed = false, $data = [])
    {
        if (!$recursed) {
            // Check if we didn't installed already
            if ($this->isInstalled() && !$this->option('force')) {
                $this->warn('Installation already ran? If you need to run it again please run with `--force` option');

                return false;
            }
            // Ask for database prefix
            $prefix = null;

            if ($this->confirm('Do you wish to add prefix to the CMS tables?')) {
                $prefix = $this->ask('Enter prefix for the CMS tables:', 'igni');
                $variable = PHP_EOL . PHP_EOL . 'IGNI_TABLES_PREFIX=' . $prefix;
                $env = base_path('.env');
                file_put_contents($env, $variable, FILE_APPEND | LOCK_EX);
                config(['ignicms.igniTablesPrefix' => $prefix]);
                $this->tableName = $prefix . '_users';
            }
            $this->compiler = new UserCompiler($this->tableName);
            $this->createResource('model');

            // Publish the commands
            $this->info('Publishing Igni CMS artifacts..' . PHP_EOL);
            $this->call('vendor:publish', [
                '--provider' => \Despark\Cms\Providers\IgniServiceProvider::class,
                '--tag' => ['migrations', 'configs'],
            ]);

            $this->info(PHP_EOL . 'Dumping autoloader..');
            $this->info(exec('composer dumpautoload'));

            $this->info('Migrating..' . PHP_EOL);
            $this->call('migrate');

            // Publish frontend
            $this->info('Publishing frontend artifacts..' . PHP_EOL);
            $this->call('vendor:publish', [
                '--force' => 1,
                '--tag' => ['igni-frontend', 'resources', 'plugins'],
            ]);

            // Build FE
            $this->info(PHP_EOL . 'Building frontend..' . PHP_EOL);
            exec(__DIR__ . '/../../../scripts/frontend.sh ' . base_path(), $output, $exitCode);
            if ($exitCode > 0) {
                $this->warn('Frontend build failed. Please run manually.');
                $this->info('Reason: ' . implode(PHP_EOL, $output) . PHP_EOL);
            }

            $this->info(PHP_EOL . '--- Admin setup ---');
        }

        if (!isset($data['name'])) {
            $data['name'] = $this->ask('Admin Name');
        }

        if (!isset($data['email'])) {
            $data['email'] = $this->output->ask('Admin email address', null, [$this, 'validateEmail']);
        }

        if (!isset($data['password'])) {
            $data['password'] = $this->secret('Admin Password');
            $data['password_confirmation'] = $this->secret('Confirm Admin Password');
        }
        $validator = \Validator::make($data, [
            'password' => 'required|confirmed',
        ]);
        if ($validator->fails()) {
            unset($data['password']);
            $this->error('Passwords doesn\'t match.');

            return $this->handle(true, $data);
        }

        $this->info('Seeding user..' . PHP_EOL);
        $this->seedUser($data);

        $this->output->success('Installation complete!');
    }

    /**
     * @param $data
     */
    public function seedUser($data)
    {
        $data = array_merge(array_only($data, ['password', 'email', 'name']), [
            'is_admin' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $data['password'] = bcrypt($data['password']);

        \DB::table($this->tableName)->insert($data);
    }

    /**
     * @param $email
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function validateEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new \Exception('Email address is not valid.');
        }

        return $email;
    }

    /**
     * @return bool
     */
    protected function isInstalled()
    {
        if (\Schema::hasTable($this->tableName)) {
            if (\Schema::hasColumn($this->tableName, 'is_admin')) {
                return \DB::table($this->tableName)->where('is_admin', 1)->exists();
            }
        }
    }

    /**
     * @param $type
     */
    protected function createResource($type)
    {
        $template = $this->getTemplate($type);
        $template = $this->compiler->{'render_' . $type}($template);
        $path = config('ignicms.paths.' . $type);
        $filename = $this->{$type . '_name'}() . '.php';
        $this->saveResult($template, $path, $filename);
    }

    /**
     * @param $type
     *
     * @return string
     */
    public function getTemplate($type)
    {
        return file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'User' . DIRECTORY_SEPARATOR . $type . '.stub');
    }

    /**
     * @param $template
     * @param $path
     * @param $filename
     */
    protected function saveResult($template, $path, $filename)
    {
        $file = $path . DIRECTORY_SEPARATOR . $filename;

        if (File::exists($file)) {
            $result = $this->confirm('File "' . $filename . '" already exist. Overwrite?', false);
            if (!$result) {
                return;
            }
        }
        File::put($file, $template);
        $this->info('File "' . $filename . '" was created.');
    }

    /**
     * @return string
     *
     * @todo this is not needed in the command we should move it into the compiler
     */
    public function model_name()
    {
        return 'User';
    }
}
