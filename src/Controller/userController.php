<?php

namespace App\Controller;

use App\Form\UserFormType;
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
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;



class userController extends AbstractController
{

    #[Route(path: '/connexion', name: 'connexion', methods: ['GET','POST'], schemes: ['https'])]
    public function connexion(AuthenticationUtils $authenticationUtils)
    {

        $error=$authenticationUtils->getLastAuthenticationError();
        $username=$authenticationUtils->getLastUsername();
        $user=new User();

        return $this->render('connexion.html.twig', [
            'error'=>$error,
            'username'=>$username
        ]);
    }

    #[Route(path: '/suscribe', name: 'suscribe', methods: ['GET','POST'], schemes: ['https'])]
    function suscribe(Request $request,EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        $user=new User();
        $form=$this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){
            $user=$form->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
            $em-> persist($user);
            $em->flush();
            return $this->redirectToRoute('connexion');
        }
        return $this->render('suscribe.html.twig', [
            'form'=> $form->createView()

        ]);
    }
}
