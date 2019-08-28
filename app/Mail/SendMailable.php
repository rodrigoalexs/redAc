<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailable extends Mailable
{
    use Queueable, SerializesModels;
    public  $content;
    public $view;
    public $files;

    /**
     * Create a new message instance.
     *
     * @param string $content
     * @param string $view
     * @param array $files
     */
    public function __construct(string $content, string $view, array $files)
    {
        $this->html = $content;
        $this->view = $view;
        $this->files = $files;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = $this->view("emails.{$this->view}");

        foreach ($this->files as $fileInfo){
            if(file_exists($fileInfo['path'])){
                $view->attach($fileInfo['path'],[
                    'as' => $fileInfo['name'],
                    'mime' => $fileInfo['mime']
                ]);
            }
        }
        return $view;
    }
}
