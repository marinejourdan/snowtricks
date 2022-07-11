<?php

namespace App\Controller;

use App\Entity\Trick;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    #[Route(path: '/suscribe', name: 'suscribe', methods: ['GET'], schemes: ['https'])]
    function suscribe(Request $request)
    {
        $user=new User();

        $form=$this->createFormBuilder($user)
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('submit', SubmitType::class)
            ->getForm()
        ;
        return $this->render('suscribe.html.twig', [
            'myform'=> $form->createView()
        ]);
    }
}
