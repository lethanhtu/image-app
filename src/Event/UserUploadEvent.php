<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;

class UserUploadEvent extends Event
{
    protected $file;

    const NAME = 'user.upload';

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function getFile()
    {
        return $this->file;
    }
}
