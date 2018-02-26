<?php

namespace Despark\Cms\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class UserExported extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    /**
     * @var string
     */
    public $fileFullPath;

    /**
     * @var null|string
     */
    public $userName;

    /**
     * @var string
     */
    public $websiteName;

    /**
     * @var string
     */
    public $websiteUrl;

    /**
     * @var string
     */
    public $view;

    /**
     * @var null|string
     */
    public $subject;

    /**
     * UserExported constructor.
     * @param string $fileFullPath
     * @param null|string $userName
     * @param string $view
     * @param null|string $subject
     */
    public function __construct($fileFullPath, $userName = null, $view = 'ignicms::emails.user.export', $subject = null)
    {
        $this->websiteName = config('app.name');
        $this->websiteUrl = config('app.url');
        $this->fileFullPath = $this->websiteUrl . Storage::url($fileFullPath);
        $this->userName = $userName;
        $this->view = $view;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (!$this->userName) {
            $this->userName = $this->to[0]['name'];
        }

        if (!$this->subject) {
            $this->subject = 'User data export for ' . $this->userName;
        }

        $this->subject($this->subject);

        return $this->view($this->view);
    }
}