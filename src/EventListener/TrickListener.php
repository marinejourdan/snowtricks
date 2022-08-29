<?php

namespace App\EventListener;

use App\Entity\Trick;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickListener

{
    private SluggerInterface $slug;

    public function __construct(SluggerInterface $slug)
    {
        $this->slug = $slug;
    }

    public function prePersist(Trick $trick, LifecycleEventArgs $event): void
    {
        $name = $trick->getName();
        $slug=$this->slug->slug($name);
        $trick->setSlug($slug);
    }
}
