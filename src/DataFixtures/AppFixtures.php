<?php

namespace App\DataFixtures;

use App\Entity\Group;
use App\Entity\Media;
use App\Entity\Message;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user_1 = new User();
        $user_1->setName('Paul');
        $user_1->setEmail('jean.paul@gmail.com');
        $user_1->setPassword('jeanjean');

        $manager->persist($user_1);
        $manager->flush();


        $group_1 = new Group();
        $group_1->setName('grab');

        $manager->persist($group_1);
        $manager->flush();

        $trick_1 = new Trick();
        $trick_1->setName('indy');
        $trick_1->setDescription('saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière ');
        $trick_1->setGroup($group_1);

        $manager->persist($trick_1);
        $manager->flush();

        $message = new Message();
        $message->setContent('Une rotation peut être frontside ou backside : une rotation frontside correspond à une rotation orientée vers la carre backside. Cela peut paraître incohérent mais origine étant que dans un halfpipe ou une rampe de skateboard, une rotation frontside se déclenche naturellement depuis une position frontside');
        $message->setCreationDate(new \DateTime('1970-01-01'));
        $message->setAuthor($user_1);
        $message->setTrick($trick_1);

        $manager->persist($message);
        $manager->flush();

        $media_avatar = new Media();
        $media_avatar->setType('image');
        $media_avatar->setUser($user_1);
        $media_avatar->setUrl('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSTHKPnWNxogFKxX-xMGhvzg1HK4ZAP2apR2CSvMO8VMdXwcvO8J2P9Wlxyy9dHX-AGsgE&usqp=CAU');


        $manager->persist($media_avatar);
        $manager->flush();
    }
}
