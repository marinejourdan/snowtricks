<?php

namespace App\EventListener;


use App\Entity\Trick;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class TrickListener{

    public function prePersist (Trick $trick, LifecycleEventArgs $event): void
    {
        $name= $trick->getName();
        $slugifier = new Slugify();
        $slug=$slugifier->slugify($name);
        $trick->setSlug($slug);
    }
}