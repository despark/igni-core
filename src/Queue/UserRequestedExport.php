<?php

namespace Despark\Cms\Queue;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Storage;
use Despark\Cms\Mail\UserExported;

class UserRequestedExport implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var
     */
    protected $user;

    /**
     * Create a new job instance.
     *
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $jsonData = collect($this->user, $this->user->getRelations())->toJson();
        $filename = $this->generateFilename();
        $fileFullPath = 'user-exports/' . $filename . '.json';
        Storage::disk('public')->put($fileFullPath, $jsonData);
        \Mail::to($this->user)->send(new UserExported($fileFullPath));
    }

    /**
     * @return string
     */
    protected function generateFilename()
    {
        $isUnique = false;
        $path = Storage::disk('public')->getDriver()->getAdapter()->getPathPrefix() . 'user-exports/';
        $filename = '';

        while (!$isUnique) {
            $filename = hash('md5', $this->user->id . date('Y-m-d H:i:s'));
            if (!file_exists($path . $filename . 'json')) {
                $isUnique = true;
            }
        }

        return $filename;
    }
}