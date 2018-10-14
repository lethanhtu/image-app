<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Event\UserUploadEvent;

class UserUploadSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            UserUploadEvent::NAME  => 'resizeImage'
        ];
    }

    public function resizeImage(UserUploadEvent $event)
    {
        $uploadedImage = $event->getFile();
        file_put_contents('aaa',1);
    }
}
