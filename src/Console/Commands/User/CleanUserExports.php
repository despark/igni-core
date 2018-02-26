<?php

namespace Despark\Cms\Console\Commands\User;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Class CleanUserExports
 */
class CleanUserExports extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'igni:user:exports:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete user exports older than 48 hours.';

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
    public function handle()
    {
        $storageDisk = Storage::disk('public');
        $storagePrefix = $storageDisk->getDriver()->getAdapter()->getPathPrefix();
        $files = $storageDisk->files('user-exports');
        $filesToDelete = [];
        $maxLifetimeInMinutes = 2880;

        foreach ($files as $file) {
            if (file_exists($storagePrefix . $file)) {
                $minutesPassedSinceCreated = date('i', filectime($storagePrefix . $file));
                if ($minutesPassedSinceCreated >= $maxLifetimeInMinutes) {
                    $filesToDelete[] = $file;
                }
            }
        }

        $count = count($filesToDelete);
        $bar = $this->output->createProgressBar($count);

        foreach ($filesToDelete as $file) {
            $storageDisk->delete($file);
            $bar->advance();
        }

        $bar->finish();
        $this->info(PHP_EOL . 'User exports were deleted successfully');
    }
}
