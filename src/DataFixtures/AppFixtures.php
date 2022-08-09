<?php

namespace App\DataFixtures;

use App\Entity\Group;
use App\Entity\Media;
use App\Entity\Message;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user_1 = new User();
        $user_1->setName('Paul');
        $user_1->setEmail('jean.paul@gmail.com');
        $user_1->setPassword('jeanjean');
        $user_1->setToken('prout');

        $media_avatar = new Media();
        $media_avatar->setType('image');
        $media_avatar->setFileName('logo.jpeg');

        $user_1->setAvatar($media_avatar);

        $manager->persist($user_1);
        $manager->flush();

        $group_1 = new Group();
        $group_1->setName('grab');

        $manager->persist($group_1);
        $manager->flush();

        $group_2 = new Group();
        $group_2->setName('Old school');

        $manager->persist($group_2);
        $manager->flush();

        $group_3 = new Group();
        $group_3->setName('flips');

        $manager->persist($group_3);
        $manager->flush();

        $media1 = new Media();
        $media1->setType('image');
        $media1->setFileName('symbole_feministe-62ee79856b70a.jpg');

        $media2 = new Media();
        $media2->setType('image');
        $media2->setFileName('logo.jpeg');

        $media3 = new Media();
        $media3->setType('image');
        $media3->setFileName('symbole_feministe-62ee79856b70a.jpg');

        $trick_1 = new Trick();
        $trick_1->setName('indy');
        $trick_1->setDescription('saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière ');
        $trick_1->setGroup($group_1);
        $media1->setTrick($trick_1);
        $media2->setTrick($trick_1);
        $media3->setTrick($trick_1);
        $trick_1->setGallery(new ArrayCollection([$media1, $media2, $media3]));

        $trick_2 = new Trick();
        $trick_2->setName('mute');
        $trick_2->setDescription('saisie de la carre frontside de la planche entre les deux pieds avec la main avant ');
        $trick_2->setGroup($group_1);
        // $trick_2->setMedia('logo-62dec010add64.jpg');

        $trick_3 = new Trick();
        $trick_3->setName('truck driver');
        $trick_3->setDescription('saisie du carre avant et carre arrière avec chaque main (comme tenir un volant de voiture)');
        $trick_3->setGroup($group_1);
        // $trick_3->setMedias('logo-62dec010add64.jpg');

        $trick_4 = new Trick();
        $trick_4->setName('stalefish ');
        $trick_4->setDescription('saisie de la carre backside de la planche entre les deux pieds avec la main arrière');
        $trick_4->setGroup($group_1);
        // $trick_4->setMedias('logo-62dec010add64.jpg');

        $trick_5 = new Trick();
        $trick_5->setName('Backside Air');
        $trick_5->setDescription('saisie du carre avant et carre arrière avec chaque main (comme tenir un volant de voiture)');
        $trick_5->setGroup($group_2);
        //$trick_5->setGallery();

        $trick_6 = new Trick();
        $trick_6->setName('front flip');
        $trick_6->setDescription(' rotations en avant,Néanmoins, en dépit de la difficulté technique relative d une telle figure, le danger de retomber sur la tête ou la nuque est réel et conduit certaines stations de ski à interdire de telles figures dans ses snowparks. ');
        $trick_6->setGroup($group_3);
        // $trick_6->setMedias('logo-62dec010add64.jpg');

        $trick_7 = new Trick();
        $trick_7->setName('japan ou japan air');
        $trick_7->setDescription(' saisie de l avant de la planche, avec la main avant, du côté de la carre frontside.');
        $trick_7->setGroup($group_1);
        // $trick_7->setMedias('logo-62dec010add64.jpg');

        $trick_8 = new Trick();
        $trick_8->setName('seat belt:');
        $trick_8->setDescription(' aisie du carre frontside à l arrière avec la main avant');
        $trick_8->setGroup($group_1);
        // $trick_7->setMedias('logo-62dec010add64.jpg');

        $manager->persist($trick_1);
        $manager->flush();
        $manager->persist($trick_2);
        $manager->flush();
        $manager->persist($trick_3);
        $manager->flush();
        $manager->persist($trick_4);
        $manager->flush();
        $manager->persist($trick_5);
        $manager->flush();
        $manager->persist($trick_6);
        $manager->flush();
        $manager->persist($trick_7);
        $manager->flush();
        $manager->persist($trick_8);
        $manager->flush();

        $message = new Message();
        $message->setContent('Une rotation peut être frontside ou backside : une rotation frontside correspond à une rotation orientée vers la carre backside. Cela peut paraître incohérent mais origine étant que dans un halfpipe ou une rampe de skateboard, une rotation frontside se déclenche naturellement depuis une position frontside');
        $message->setCreationDate(new \DateTime('1970-01-01'));
        $message->setAuthor($user_1);
        $message->setTrick($trick_1);

        $manager->persist($message);
        $manager->flush();





    }
}
