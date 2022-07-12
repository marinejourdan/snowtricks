<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;




class userController extends AbstractController
{

    #[Route(path: '/connexion', name: 'connexion', methods: ['GET'], schemes: ['https'])]
    function connexion(Request $request)
    {
        $user=new User();

        $form=$this->createFormBuilder($user)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('submit', SubmitType::class)
            ->getForm()
        ;
        return $this->render('connexion.html.twig', [
            'myform'=> $form->createView()
        ]);
    }

    #[Route(path: '/suscribe', name: 'subscribe', methods: ['GET','POST'], schemes: ['https'])]
    function subscribe(Request $request,EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        $user=new User();
        $form=$this->createFormBuilder($user)
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('plainPassword', RepeatedType::class,['type'=>PasswordType::class])
            ->add('submit', SubmitType::class)
            ->getForm()
        ;

        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $user=$form->getData();
            $plainPassword=$user->getPlainPassword();
          //  $hashedPassword=$passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($plainPassword);

            $em-> persist($user);
            $em->flush();
        }
        return $this->render('subscribe.html.twig', [
            'form'=> $form->createView()
        ]);
    }
}
