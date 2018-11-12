<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Service\ImageProcessor;
use App\Event\UserUploadEvent;

class UserUploadSubscriber implements EventSubscriberInterface
{
    protected $imageProcessor;

    public function __construct(ImageProcessor $imageProcessor)
    {
        $this->imageProcessor = $imageProcessor;
    }

    public function onUserUpload(UserUploadEvent $event)
    {
        $this->imageProcessor->save($event->getFile());
    }

    public static function getSubscribedEvents()
    {
        return [
           'user.upload' => 'onUserUpload',
        ];
    }
}
