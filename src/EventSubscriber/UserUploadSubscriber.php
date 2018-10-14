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

    public static function getSubscribedEvents()
    {
        return [
            UserUploadEvent::NAME  => [['resizeImage', 10]]
        ];
    }

    public function resizeImage(UserUploadEvent $event)
    {
        $this->imageProcessor->save($event->getFile());
    }
}
